<?php

namespace App\Livewire;

use App\Models\Brand;
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
        if ($this->updateModelId != null) {
            $brand = Brand::findOrFail($this->updateModelId);
            $brand->update($data);
        } else {
            $brand = Brand::create($data);
        }
        if ($this->image && $this->image->isValid()) {
            $brand->clearMediaCollection('image');
            $brand->addMedia($this->image->getRealPath())
                ->usingFileName($this->image->getClientOriginalName())
                ->toMediaCollection('image');
        }

        $this->resetData();

    }

    public function update($id)
    {
        $this->updateModelId = $id;
        $brand = Brand::findOrFail($this->updateModelId);
        $this->fill($brand->toArray());

    }

    public function delete($id)
    {
        $this->updateModelId = $id;
        $brand = Brand::findOrFail($this->updateModelId)->delete();

        $this->resetData();
    }
}
