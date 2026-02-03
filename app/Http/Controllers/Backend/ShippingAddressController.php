<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShippingAddressRequest;
use App\Models\ShippingAddress;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ShippingAddressController extends Controller
{
    public function __construct(
        protected ShippingAddress $shippingAddress
    ) {}

    public function index(): View
    {
        $records = $this->shippingAddress
            ->with('user')
            ->latest()
            ->paginate(15);

        return view('backend.shipping-address.index', [
            'records' => $records,
            'modelName' => 'ShippingAddress',
        ] + getRoutes('shipping-address'));
    }

    public function create(): View
    {
        $model = null;

        return view('backend.shipping-address.form', [
            'model' => $model,
            'modelName' => 'ShippingAddress',
        ] + getRoutes('shipping-address'));
    }

    public function store(ShippingAddressRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['user_id'] = auth()->id();

        $this->shippingAddress->create($data);

        return redirect()
            ->route('shipping-address.index')
            ->with('success', 'Shipping address created successfully.');
    }

    public function edit(int $id): View
    {
        $model = $this->shippingAddress->with('user')->findOrFail($id);

        return view('backend.shipping-address.form', [
            'model' => $model,
            'modelName' => 'ShippingAddress',
        ] + getRoutes('shipping-address'));
    }

    public function update(ShippingAddressRequest $request, int $id): RedirectResponse
    {
        $model = $this->shippingAddress->findOrFail($id);

        $data = $request->validated();

        $model->update($data);

        return redirect()
            ->route('shipping-address.index')
            ->with('success', 'Shipping address updated successfully.');
    }

    public function setActive(int $id): RedirectResponse
    {
        $address = $this->shippingAddress->findOrFail($id);

        if ($address->user_id !== null) {
            $this->shippingAddress
                ->where('user_id', $address->user_id)
                ->update(['active' => false]);
        } else {
            $this->shippingAddress
                ->whereKeyNot($address->getKey())
                ->update(['active' => false]);
        }

        $address->active = true;
        $address->save();

        return redirect()
            ->route('shipping-address.index')
            ->with('success', 'Shipping address status updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $model = $this->shippingAddress->findOrFail($id);
        $model->delete();

        return redirect()
            ->route('shipping-address.index')
            ->with('success', 'Shipping address deleted successfully.');
    }
}
