<div>
    @inject('menu','App\Helpers\MenuHelper')
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <form wire:submit.prevent="submit">
                    <input type="hidden" wire:model="menu_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input1" class="form-label">Title</label>
                                <input type="text" class="form-control" wire:model="title" value="{{ old('title') ?? '' }}" id="input1">
                            </div>
                            @error('title') <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input1" class="form-label">Position</label>
                                <input type="number" class="form-control" wire:model="position" value="{{ old('position') ?? '' }}" id="input1">
                            </div>
                            @error('position') <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div>



                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">SubMenu</label>
                                <select class="selectpicker form-control" wire:model="selectedSubmenu"  multiple="multiple">
                                    @foreach($submenu as $row)
                                    <option  value="{{ $row->id }}">{{ ucwords($row->title) }}</option>
                                    @endforeach
                                </select>
                                @error('selectedSubmenu') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Route</label>
                                <select class="form-control" wire:model="route" id="route_id">
                                    <option value=""> -- Please Select Target --</option>
                                    @forelse ($menu->routeList() as $index => $item)
                                        <option value="{{ $item }}">{{ $index }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input1" class="form-label">Is Mega Menu??</label>
                                <select wire:model="mega_menu" class="form-control">
                                    <option value="0" class="form-control">No</option>
                                    <option value="1" class="form-control">Yes</option>
                                </select>
                            </div>
                            @error('mega_menu') <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input1" class="form-label">Is Footer Menu??</label>
                                <select wire:model="footer_menu" class="form-control">
                                    <option value="0" class="form-control">No</option>
                                    <option value="1" class="form-control">Yes</option>
                                </select>
                            </div>
                            @error('footer_menu') <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div>

                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label for="input1" class="form-label">ToolTip</label>
                                <input type="text" class="form-control" wire:model="tooltip" value="{{ old('tooltip') ?? '' }}" id="input1">
                            </div>
                            @error('tooltip') <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div> --}}

                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label for="input1" class="form-label">Icon</label>
                                <input type="text" class="form-control" wire:model="icon" value="{{ old('icon') ?? '' }}" id="input1">
                            </div>
                        </div> --}}

                        {{-- <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-4 col-lg-12 control-label form-label" style="width:100%; padding:0">Target</label>
                            <div class="col-sm-8 col-lg-12" style="width:100%; padding:0; margin-bottom:10px">
                                <select  data-live-search="true" wire:model="target" id="target_id">
                                    <option value=""> -- Please Select Target --</option>
                                    <option value="self">Self</option>
                                    <option value="blank">Blank</option>
                                    <option value="top">Top</option>
                                </select>
                            </div>
                        </div>
                        @error('target') <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div> --}}

                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label for="input1" class="form-label">Enabled</label><br>
                                <input type="checkbox" class="form-control" checked data-toggle="toggle" wire:model="enabled" data-onstyle="success">
                            </div>
                            @error('enabled') <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div> --}}

                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <button type="submit" class="btn btn-default">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        <!-- END card -->

        <!-- Start Row -->

            <div class="col-md-6">
                <div class="panel panel-default submenu-list" style="max-height:500px; overflow:scroll;">
                <div class="panel-title">Menu List</div>
                    <div class="panel-body">
                        <ul class="list-group menu-ul">
                            @forelse ($menus as $item)
                                <li draggable="true" class="list-group-item menu">{{ $item->title }}
                                    <div class="action" style="float: right">
                                        <button type="button" class="btn btn-info" wire:click="editMenu({{ $item }})"><i class="fa fa-edit"></i></button>
                                        <button type="button" wire:click="deleteId({{ $item->id }})" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal">Delete</button>
                                    </div>
                                    {{-- <ul class="list-group sub-menu-ul" style="padding-left: 70px;">
                                        @forelse ($item->submenus as $sub)
                                            <li class="list-group-item sub-menu">{{ $sub->title }}</li>
                                        @empty
                                        @endforelse

                                    </ul> --}}
                                </li>
                            @empty
                                <li class="list-group-item">Please Add Menus</li>
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
        </div>
        <!-- End Row -->
    </div>
</div>
