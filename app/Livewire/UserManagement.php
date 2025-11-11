<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;

#[Layout('backend.master')]
class UserManagement extends Component
{
    use WithPagination;

    #[Validate('required|min:3|max:20')]
    public $name = '';

    #[Validate('nullable|min:3|max:20')]
    public $password = '', $confirm = '';

    #[Validate('nullable|email|min:5|max:200')]
    public $email = '';


    #[Validate('required')]
    public $status = 1;

    public $updateModelId = NULL;

     #[Url]
    public ?string $query = null;

    #[Url]
    public int $rowPerPage = 10;
    public $parents;

    public function render()
    {
        $records = User::when($this->query, function ($query, $search) {
                $term = '%'.$search.'%';
                $query->where('name', 'like', $term);
            })
            ->orderBy('id')
            ->paginate($this->rowPerPage);
//            ->withQueryString();

        return view('livewire.user-management',[
        'records' => $records
        ]);

    }
    public function mount(){

    }

    public $showPasswordRequired = false, $showInvalidPassword = false;

    public function updatedConfirm()
    {
        $this->checkPassword();
    }

    public function updatedPassword()
    {
        $this->checkPassword();
    }

    public function checkPassword()
    {
        $this->showPasswordRequired = ($this->password == '');
        $this->showInvalidPassword = ($this->password != $this->confirm);
    }

    public function resetData(){
        $this->name = '';
        $this->email = '';
        $this->status = 1;
    }

    public function save(){
        $data = $this->validate();
        unset($data['confirm']);
        if($this->updateModelId != NULL){
            User::findOrFail($this->updateModelId)->update($data);
        }else{
            User::create($data);
        }

        $this->resetData();

    }

      public function update($id){
        $this->updateModelId = $id;
        $user = User::findOrFail($this->updateModelId);
        $this->fill($user->toArray());

    }

    public function delete($id){
        $this->updateModelId = $id;
        User::findOrFail($this->updateModelId)->delete();

        $this->resetData();
    }
}
