<?php

namespace App\Livewire;

use App\Models\Tag;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('backend.master')]
class TagSetup extends Component
{
    use WithPagination;

//    public $records;

    #[Validate('required|min:3|max:30')]
    public $name = '';

    #[Validate('nullable|min:5|max:200')]
    public $description = '';
    public $updateModelId = NULL;

    #[Url]
    public ?string $query = null;
    #[Url]
    public int $rowPerPage = 3;

    public function render()
    {
        $records = Tag::query()
            ->when($this->query, function ($query, $search) {
                $term = '%'.$search.'%';
                $query->where('name', 'like', $term);
            })
            ->latest()
            ->paginate($this->rowPerPage);


        return view('livewire.tag-setup',[
            'records' => $records
        ]);
    }

    public function mount(){
    }

    public function resetData(){
        $this->name = '';
        $this->description = '';
    }

    public function save(){
        $data = $this->validate();
        if($this->updateModelId != NULL){
            \App\Models\Tag::findOrFail($this->updateModelId)->update($data);
        }else{
            \App\Models\Tag::create($data);
        }

        $this->resetData();
    }

    public function update($id){
        $this->updateModelId = $id;
        $tag = \App\Models\Tag::findOrFail($this->updateModelId);
        $this->fill($tag->toArray());

    }

    public function delete($id){
        $this->updateModelId = $id;
        \App\Models\Tag::findOrFail($this->updateModelId)->delete();

        $this->resetData();
    }

}
