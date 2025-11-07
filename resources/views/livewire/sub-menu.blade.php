<div>
    @inject('menu','App\Helpers\MenuHelper')
    <!-- Start Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-title">SubMenu List
                    <a href="{{ route('submenu.index') }}"><button class="btn btn-info">Clear</button></a>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12 col-lg-4">
                            <div class="form-group">
                                <label class="form-label">Categories </label>
                                <select data-live-search="true" wire:model="selectedCategory">
                                    <option value=""> -- Please Select Category --</option>
                                    @forelse ($categories as $index => $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @empty

                                    @endforelse
                                </select>
                            </div>
                        </div>

                        @if(!is_null($selectedCategory))
                            <div class="col-md-12 col-lg-4">
                                <div class="form-group">
                                    <label class="form-label">Products</label>
                                    <select data-live-search="true" wire:model="selectedProduct">
                                        <option value=""> -- Please Select Product --</option>
                                        @forelse ($products as $index => $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- End Row -->

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-title ">
                <div class="card-title">
                    @if($submenu_id == '')
                        Create Sub Menu
                    @else
                        Update Sub Menu
                        <a href="{{ route('submenu.index') }}"><button class="btn btn-info">Add New</button></a>
                    @endif
                </div>
                </div>
                <div class="panel-body">
                    <form wire:submit.prevent="submit">
                        <input type="hidden" wire:model="submenu_id">
                        <div class="row">
                            <div class="col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" class="form-control" wire:model="title" value="{{ old('title') ?? '' }}" id="title">
                                </div>
                                @error('title') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label for="position" class="form-label">Position</label>
                                    <input type="text" class="form-control" wire:model="position" wire:model="{{ old('position') ?? '' }}" id="position">
                                </div>
                                @error('position') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">Store</label>
                                    <select class="form-control" wire:model="store_id" id="store_id">
                                        <option value=""> -- Please Select Store --</option>
                                        @forelse ($stores as $index => $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @error('store_id') <span class="error" style="color:red">{{ $message }}</span> @enderror

                                </div>
                            </div>

                            {{-- <div class="col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label for="input1" class="form-label">ToolTip</label>
                                    <input type="text" class="form-control" wire:model="tooltip" value="{{ old('tooltip') ?? '' }}" id="input1">
                                </div>
                                @error('tooltip') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div> --}}

                            <div class="col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">Parent ID</label>
                                        <select data-live-search="true" wire:model="parent_id" id="parent_id">
                                            <option value=""> -- Please Select Parent --</option>
                                            @forelse ($submenu as $index => $item)
                                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                                                {{-- @forelse($item->child as $child)
                                                    <option value="{{ $child->id }}">----{{ $child->title }}</option>
                                                @empty
                                                @endforelse --}}
                                            @empty
                                            @endforelse
                                        </select>
                                    @error('parent_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            @if($categorySlug == null)
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="form-label">Route</label><br>
                                        <select data-live-search="true" wire:model="action" id="route">
                                            {{-- <select @if(is_null($selectedCategory)) wire:model="action" @endif id="route"> --}}
                                            <option value=""> -- Please Select Route --</option>
                                            @forelse ($menu->routeList()  as $index => $item)
                                                <option value="{{ $item }}">{{ $index }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                        @error('action') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @endif

                            @if($categorySlug != null)
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <label for="cat-action" class="form-label">Category Action</label>
                                        <input type="text" class="form-control" wire:model="action" id="cat-action" readonly>
                                    </div>
                                    @error('action') <span class="error" style="color: red">{{ $message }}</span> @enderror

                                </div>

                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <label for="cat-attribute" class="form-label">Category Attribute</label>
                                        <input type="text" class="form-control" wire:model="attribute" id="cat-attribute" readonly>
                                    </div>
                                    @error('attribute') <span class="error" style="color: red">{{ $message }}</span> @enderror
                                </div>
                            @endif



                            @if($attribute != null)
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <label for="prod-action" class="form-label">Product Action</label>
                                        <input type="text" class="form-control" wire:model="prod_action" id="prod-action" readonly>
                                    </div>
                                    @error('prod_action') <span class="error" style="color: red">{{ $message }}</span> @enderror

                                </div>

                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <label for="prod-attribute" class="form-label">Product Attribute</label>
                                        <input type="text" class="form-control" wire:model="prod_attribute" id="prod-attribute" readonly>
                                    </div>
                                    @error('prod_attribute') <span class="error" style="color: red">{{ $message }}</span> @enderror

                                </div>

                            @endif

                            {{-- <div class="col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label class="col-sm-4 col-lg-12 control-label form-label" style="width:100%; padding:0">Target</label>
                                    <div class="col-sm-8 col-lg-12" style="width:100%; padding:0; margin-bottom:10px">
                                        <select data-live-search="true" wire:model="target" id="target_id">
                                            <option value=""> -- Please Select Target --</option>
                                            <option value="self">Self</option>
                                            <option value="blank">Blank</option>
                                            <option value="top">Top</option>
                                        </select>
                                    </div>
                                </div>
                                @error('target') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div> --}}
                            {{-- <div class="col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label for="input1" class="form-label">Icon</label>
                                    <input type="text" class="form-control" wire:model="icon" wire:model="{{ old('icon') ?? '' }}" id="input1">
                                </div>
                                @error('icon') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div> --}}
                            {{-- <div class="col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label for="input1" class="form-label">Enabled</label>
                                    <input type="checkbox" class="form-control" checked data-toggle="toggle" wire:model="enabled" data-onstyle="success">
                                </div>
                                @error('enabled') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div> --}}

                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <button type="submit" class="btn btn-default">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
     <!-- END card -->

            <!-- Start Row -->
        <div class="col-md-6">
            <div class="panel panel-default submenu-list" style="max-height:500px; overflow:scroll;">
                <div class="panel-title">SubMenu List</div>
                    <div class="panel-body">
                        <ul class="list-group">
                            @forelse ($submenu as $item)
                            <li class="list-group-item">{{ $item->title }}
                                <div class="action" style="float: right">
                                    <button type="button" class="btn btn-info" wire:click="editMenu({{ $item }})"><i class="fa fa-edit">Edit</i></button>
                                    <button type="button" wire:click="deleteId({{ $item->id }})" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal">Delete</button>
                                </div>
                                <ul class="list-group" style="padding-left: 30px">
                                    @forelse ($item->child as $child)
                                        <li class="list-group-item">
                                            {{ $child->title }}
                                            <div class="action" style="float: right">
                                                <button type="button" class="btn btn-info" wire:click="editMenu({{ $child }})"><i class="fa fa-edit">Edit</i></button>
                                                <button type="button" wire:click="deleteId({{ $child->id }})" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal">Delete</button>
                                            </div>
                                        </li>
                                    @empty
                                    @endforelse
                                </ul>
                            </li>

                            @empty
                                <li class="list-group-item">Please Add Submenus</li>
                            @endforelse
                        </ul>
                    </div>

                <!-- Modal -->
                <div wire:ignore.self class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Delete Confirm</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true close-btn">×</span>
                                </button>
                            </div>
                        <div class="modal-body">
                                <p>Are you sure want to delete?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
                                <button type="button" wire:click.prevent="deleteMenu()" class="btn btn-danger close-modal" data-dismiss="modal">Yes, Delete</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- End Row -->
    </div>
</div>
