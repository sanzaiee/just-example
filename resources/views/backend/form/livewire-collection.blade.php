
@switch($type)
    @case('textarea')
        <div class="{{ $div ?? 'col-md-12' }}">
            <label class="form-label" for="{{ $data['name'] }}">{{ $data['label'] }} @if($required) * @endif</label>
            <textarea wire:model.live="{{ $data['name'] }}" id="{{ $data['name'] }}" class="tinymce">
                {!! old($data['name'], $model?->{$data['name']} ?? '') !!}
            </textarea>
            @error($data['name'])
            <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
    @break

    @case('normal-textarea')
        <div class="{{ $div ?? 'col-md-12' }}">
            <label class="form-label" for="{{ $data['name'] }}">{{ $data['label'] }} @if($required) * @endif</label>
            <textarea wire:model.live="{{ $data['name'] }}" id="{{ $data['name'] }}" class="form-control">
                {!! old($data['name'], $model?->{$data['name']} ?? '') !!}
            </textarea>
            @error($data['name'])
            <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
    @break

    @case('text')
        <div class="{{ $div ?? 'col-md-6' }}">
            <label class="form-label" for="{{ $data['name'] }}">{{ $data['label'] }} @if($required) * @endif</label>
            <input type="text" wire:model.live="{{ $data['name'] }}" id="{{ $data['name'] }}" class="form-control"  value="{{ old('name',$model->{$data['name']} ?? '')  }}">
            @error($data['name'])
            <div class="text-danger small">{{ $message }}</div>
            @enderror

        </div>
        @break

    @case('number')
        <div class="{{ $div ?? 'col-md-6' }}">
            <label class="form-label" for="{{ $data['name'] }}">{{ $data['label'] }} @if($required) * @endif</label>
            <input type="number" wire:model.live="{{ $data['name'] }}" id="{{ $data['name'] }}" class="form-control"  value="{{ old('name',$model->{$data['name']} ?? 1)  }}">
            @error($data['name'])
            <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        @break

    @case('file')
        <div class="col-md-12">
            <label class="form-label" for="{{ $data['name'] }}">{{ $data['label'] }} @if($required) * @endif</label>
            <input type="file" wire:model.live="{{ $data['name'] }}" id="{{ $data['name'] }}" class="dropify" data-default-file="{{ ($model) ? asset($model) : asset('dummy.png') }}"/>
            @error($data['name'])
            <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        @break


    @case('files')
        <div class="col-md-12">
            <label class="form-label" for="{{ $data['name'] }}">{{ $data['label'] }} @if($required) * @endif</label>
            <input type="file" wire:model.live="{{ $data['name'] }}[]" id="{{ $data['name'] }}" class="dropify" data-default-file="{{ ($model && $model->{$data['name']}) ? asset($model->{$data['name']}) : asset('dummy.png') }}" multiple/>
            @error($data['name'])
            <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        @break

    @case('select-status')
        @if ($model)
            <div class="{{ $div ?? 'col-md-6' }}">
                <label class="form-label" for="{{ $data['name'] }}">{{ $data['label'] }} @if($required) * @endif</label>
                <select wire:model.live="{{ $data['name'] }}" id="{{ $data['name'] }}" class="form-control">
                    @if ($model->{$data['name']} == 1)
                        <option value="1" selected> Active</option>
                        <option value="0">In Active</option>
                    @elseif ($model->{$data['name']} == 0)
                        <option value="1"> Active</option>
                        <option value="0" selected>In Active</option>
                    @endif
                </select>
                @error($data['name'])
                <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
        @endif
    @break

    @case('status')
        <div class="{{ $div ?? 'col-md-6' }}">
            <label class="form-label" for="{{ $data['name'] }}">{{ $data['label'] }} @if($required) * @endif</label>
            <select wire:model.live="{{ $data['name'] }}" id="{{ $data['name'] }}" class="form-control">
                <option>-- Please Select --</option>
                <option value="1"> Active</option>
                <option value="0">In Active</option>
            </select>
            @error($data['name'])
            <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
    @break

    @case('dynamic-select')
        <div class="{{ $div ?? 'col-md-6' }}">
            <label class="form-label" for="{{ $data['name'] }}">{{ $data['label'] }} @if($required) * @endif</label>
            <select wire:model.live="{{ $data['name'] }}" id="{{ $data['name'] }}" class="form-control">
                <option value="0">-- Please Select --</option>
                @foreach($arrayData as $index => $item)
                    <option value="{{$item}}"> {{ucfirst($index)}}</option>
                @endforeach
            </select>
            @error($data['name'])
            <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
    @break

    @case('normal-multiple-select')
        <div class="{{ $div ?? 'col-md-6' }}">
            <label for="{{ $data['name'] }}" class="form-label">{{ $data['label'] }}</label>
            <div class="input-group mb-3">
                <select
                    id="{{ $data['name'] }}"
                    class="form-control"
                    wire:model="{{ $data['name'] }}"
                    multiple
                >
                   @foreach($secondaryModel as $row => $id)
                        <option value="{{ $id }}">{{ ucwords($row) }}</option>
                    @endforeach
                </select>
            </div>
            @error('tag')
            <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
    @break

    @case('multiple-select')
        <div class="{{ $div ?? 'col-md-6' }}">
            <label for="{{ $data['name'] }}" class="form-label">{{ $data['label'] }}</label>
            <div class="input-group mb-3">
                <select class="js-example-basic-multiple form-control m-input select2_demo_1" id="{{ $data['name'] }}" wire:model.live="{{ $data['name'] }}[]" multiple="multiple">
                    @foreach($secondaryModel as $row)
                        <option <?php if (isset($selected)) {
                            foreach ($selected as $check) {
                                if ($row->name == $check) {
                                    echo 'selected="SELECTED"';
                                }
                            }
                        } ?>value="{{ $row->id }}">{{ ucwords($row->name) }}</option>
                    @endforeach
                </select>
            </div>
            @error($data['name'])
            <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
    @default

@endswitch


