<?php

namespace App\Livewire;

use App\Models\Category as ModelsCategory;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
#[Layout('backend.master')]

class Category extends Component
{
    use WithPagination;

    #[Validate('required|min:3|max:20')]
    public $name = '';

    #[Validate('nullable|min:5|max:200')]
    public $description = '';

    #[Rule('nullable|numeric')]
    public $parent_id = 0 ,$position = 1;

    #[Rule('required')]
    public $status = 1,$menu = 0;

    public $updateModelId = NULL;
    #[Url]
    public ?string $query = null;

    #[Url]
    public int $rowPerPage = 10;

    public $parents;

    public function render()
    {
        $records = ModelsCategory::with('parent')
            ->when($this->query, function ($query, $search) {
                $term = '%'.$search.'%';
                $query->where('name', 'like', $term);
            })
            ->orderBy('position')
//            ->get();
            ->paginate($this->rowPerPage);
//            ->withQueryString();


        return view('livewire.category',[
        'records' => $records
        ]);
    }

    public function mount(){
        $this->parents = ModelsCategory::where("parent_id",0)->pluck('id','name');
    }

    public function resetData(){
        $this->parents = ModelsCategory::where("parent_id",0)->pluck('id','name');
        $this->name = '';
        $this->description = '';
        $this->menu = 0;
        $this->status = 1;
        $this->parent_id = 0;
        $this->position = $this->records->count() + 1;
    }

    public function save(){
        $data = $this->validate();
        if($this->updateModelId != NULL){
            \App\Models\Category::findOrFail($this->updateModelId)->update($data);
        }else{
            \App\Models\Category::create($data);
        }

        $this->resetData();

    }

    public function update($id){
        $this->updateModelId = $id;
        $category = \App\Models\Category::findOrFail($this->updateModelId);
        $this->fill($category->toArray());

    }

    public function delete($id){
        $this->updateModelId = $id;
        $category = \App\Models\Category::findOrFail($this->updateModelId)->delete();

        $this->resetData();
    }
}
