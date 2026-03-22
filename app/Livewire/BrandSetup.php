<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('backend.master')]
class BrandSetup extends Component
{
    use WithFileUploads, WithPagination;

    #[Validate('required|min:3|max:20')]
    public $name = '';

    #[Validate('nullable|min:5|max:200')]
    public $description = '';

    #[Rule('nullable|numeric')]
    public $position = 1;

    #[Rule('required')]
    public $status = 1;

    #[Rule('nullable|file|mimes:png,jpg,jpeg')]
    public $image;

    public $menu = 0;

    public $updateModelId = null;

    #[Url]
    public ?string $query = null;

    #[Url]
    public int $rowPerPage = 10;

    public function render()
    {
        $records = Brand::query()
            ->when($this->query, function ($query, $search) {
                $term = '%'.$search.'%';
                $query->where('name', 'like', $term);
            })
            ->orderBy('position')
            ->paginate($this->rowPerPage)
            ->withQueryString();

        return view('livewire.brand-setup', [
            'records' => $records,
        ]);
    }

    public function resetData()
    {
        $this->name = '';
        $this->description = '';
        $this->menu = 0;
        $this->status = 1;
        $this->position = Brand::count() + 1;
        $this->image = null;
    }

    public function save()
    {
        $data = $this->validate();
        unset($data['image']);
        $msg='';
        if ($this->updateModelId != null) {
            $brand = Brand::findOrFail($this->updateModelId);
            $brand->update($data);
            $msg='Brand information updated successful.';
        } else {
            $brand = Brand::create($data);
            $msg='Brand information created successful.';
        }
        if ($this->image && $this->image->isValid()) {
            $brand->clearMediaCollection('image');
            $brand->addMedia($this->image->getRealPath())
                ->usingFileName($this->image->getClientOriginalName())
                ->toMediaCollection('image');
        }

        $this->dispatch('alert', [
            'type' => 'success',
            'message' => $msg,
        ]);
        $this->resetData();
        $this->updateModelId=null;
    }

    public function update($id)
    {
        $this->updateModelId = $id;
        $brand = Brand::findOrFail($this->updateModelId);
        $this->fill($brand->toArray());
    }

    public function delete($id)
    {
        $brand = Brand::find($id);
        if (! $brand) {
            logger()->warning("Brand with ID {$id} not found.");
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Brand information not found.',
            ]);
            return;
        }

        // Check if any product is using this brand
        $isUsed = Product::where('brand_id', $brand->id)->exists();
        if ($isUsed) {
            $this->dispatch('alert', [
                'type' => 'warning',
                'message' => 'Brand is currently used in product.',
            ]);
            return;
        }

        // Safe to delete
        $brand->delete();
        // $this->updateModelId = $id;
        $this->resetData();
    }
}
