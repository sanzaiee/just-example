<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Tag;
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
    public $name = '', $lname='';

    // #[Validate('nullable|min:3|max:20')]
    public $password = '', $confirm = '';

    #[Validate('required|regex:/^\+[0-9]+$/')]
    public $mobile = '';

    #[Validate('required|email|min:5|max:200')]
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
            ->orderBy('id', 'desc')
            ->paginate($this->rowPerPage);
//            ->withQueryString();

        return view('livewire.user-management',[
        'records' => $records
        ]);

    }

    public $tags;
    #[Validate('required|array|min:1')]
    public array $selectedTags = [];

    public function mount(){
        $this->tags = Tag::all();
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
        $this->lname = '';
        $this->email = '';
        $this->mobile = '';
        $this->status = '';
        $this->selectedTags = [];
        $this->updateModelId = 0;
    }

    public function save(){
        $id = $this->updateModelId ?? 'NULL';

        $rules = [
            'name'          => 'required|min:3|max:20',
            'lname'         => 'required|min:3|max:20',
            'email'         => 'required|email|min:5|max:200|unique:users,email,' . $id,
            'mobile'        => 'required|regex:/^\+[0-9]+$/|unique:users,mobile,' . $id,
            'status'        => 'required',
            'selectedTags'  => 'required|array|min:1',
        ];
        $data = $this->validate($rules);

        $data['email'] = strtolower($data['email']);
        unset($data['confirm']);
        if($this->updateModelId != NULL){
            $user = User::findOrFail($this->updateModelId);
            $user->update($data);
        }else{
            $data['password'] = '';
            $user = User::create($data);
        }

        // Attach tags
        $user->tags()->sync($this->selectedTags);

        $this->resetData();
        $this->dispatch('toastMagic',
            status: 'success',
            title: 'Action Success.',
            message: 'Saved Successfully.'
        );
    }

    public function update($id){
        $this->updateModelId = $id;

        $user = User::with('tags')->findOrFail($this->updateModelId);

        //$this->fill($user->toArray()); fill($user->toArray()) overwrites $this->tags
        $this->name = $user->name;
        $this->lname = $user->lname;
        $this->email = $user->email;
        $this->mobile = $user->mobile;
        $this->status = $user->status;

        // Fill selected tags (array of tag IDs)
        $this->selectedTags = $user->tags->pluck('id')->toArray();

        // Always reload all tags for the UI
        $this->tags = Tag::all();
    }

    public function delete($id){
        $this->updateModelId = $id;
        $user = User::find($this->updateModelId);

        if ($user) {
            $user->delete();

            $this->dispatch('toastMagic',
                status: 'success',
                title: 'Action Success.',
                message: 'Deleted Successfully.'
            );
        } else {
            // User not found
            $this->dispatch('toastMagic',
                status: 'error',
                title: 'Not Found.',
                message: 'User does not exist.'
            );
        }
    }


    public function makeAdmin($id)
    {
        if(!auth()->user()->is_admin){
            $user = User::findOrFail($id);
            $user->update([
                'is_admin' => !$user['is_admin']
            ]);
        }else{
            $this->dispatch('toastMagic',
                status: 'error',
                title: 'Unauthorize access.',
                message: 'You are not an admin.'
            );
        }
    }
}
