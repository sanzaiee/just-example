<div>
    {{-- Stop trying to control. --}}
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Tags Management</span></h4>
        <div class="row g-3">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        {{-- <h5 class="mb-0">Category</h5> --}}
                        <small class="text-muted float-end">
                            List
                        </small>
                    </div>

                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6 m-3">
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Search ..."
                                    wire:model.live.debounce.500ms="query"
                                >
                            </div>

                            <div class="col-md-3 m-3">
                                <div class="col-md-auto">
                                    <a href="{{route('tag.index')}}"
                                       class="btn btn-light waves-effect waves-light shadow-none">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                </div>
                            </div>
                        </div>


                        <div class="table-responsive">
                            <table class="table card-table">
                                <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse ($records as $index=>$item)
                                        <tr>
                                            <td>{{ ++$index }}</td>

                                            <td>{{ $item->name }}</td>

                                            <td>
                                                <button class="btn btn-sm btn-primary" wire:click="update({{$item->id}})"><i class="fa fa-edit"></i> </button>
                                                <button class="btn btn-sm btn-danger" wire:click="delete({{$item->id}})"><i class="fa fa-trash-alt"></i> </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td>Please add some content...</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                             <span class="m-3">
{{--                                 {{$records->links()}}--}}
                                 {{$records->withQueryString()->links()}}
                            </span>

                        </div>
                    </div>

                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <small class="text-muted float-end">
                            {{($this->updateModelId == NULL) ? "Create" : "Update ". ucfirst($this->name)}}
                        </small>
                        <button class="btn btn-sm btn-info" wire:click="resetData"><i class="fa fa-refresh"></i> </button>
                    </div>

                    <form wire:submit="save">
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach ([['name','Name']] as $item)
                                    @include('backend.form.livewire-collection', [
                                        'data' => [
                                            'name' => $item[0],
                                            'label' => $item[1],
                                        ],
                                        'required' => true,
                                        'model' => $model ?? null,
                                        'type' => 'text',
                                        'div' => 'col-md-12' //optional
                                    ])
                                @endforeach

                                @foreach ([['description','Description']] as $item)
                                    @include('backend.form.livewire-collection', [
                                        'data' => [
                                            'name' => $item[0],
                                            'label' => $item[1],
                                        ],
                                        'required' => false,
                                        'model' => $model ?? null,
                                        'type' => 'normal-textarea'
                                    ])
                                @endforeach
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="d-flex justify-content-end gap-4">
                                <button class="btn btn-primary" type="submit" name="submit">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
