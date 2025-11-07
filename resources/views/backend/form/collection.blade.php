
@switch($type)
    @case('textarea')
        <div class="col-md-12">
            <label class="form-label" for="{{ $data['name'] }}">{{ $data['label'] }} @if($required) * @endif</label>
            <textarea name="{{ $data['name'] }}" id="{{ $data['name'] }}" class="tinymce">
                {!! old($data['name'], $model?->{$data['name']} ?? '') !!}
            </textarea>
            @error($data['name'])
                <div class="text-danger small">{{ $message }}</div>
        @enderror
        </div>
    @break
    @case('text')
        <div class="col-md-6">
            <label class="form-label" for="{{ $data['name'] }}">{{ $data['label'] }} @if($required) * @endif</label>
            <input type="text" name="{{ $data['name'] }}" id="{{ $data['name'] }}" class="form-control"  value="{{ old('name',$model->{$data['name']} ?? '')  }}">
            @error($data['name'])
                 <div class="text-danger small">{{ $message }}</div>
            @enderror

        </div>
    @break

    @case('number')
        <div class="col-md-6">
            <label class="form-label" for="{{ $data['name'] }}">{{ $data['label'] }} @if($required) * @endif</label>
            <input type="number" name="{{ $data['name'] }}" id="{{ $data['name'] }}" class="form-control"  value="{{ old('name',$model->{$data['name']} ?? 1)  }}">
            @error($data['name'])
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
    @break

    @case('file')
        <div class="col-md-12">
            <label class="form-label" for="{{ $data['name'] }}">{{ $data['label'] }} @if($required) * @endif</label>
            <input type="file" name="{{ $data['name'] }}" id="{{ $data['name'] }}" class="dropify" data-default-file="{{ ($model && $model->{$data['name']}) ? asset($model->{$data['name']}) : asset('dummy.png') }}"/>
            @error($data['name'])
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
    @break


    @case('files')
        <div class="col-md-12">
            <label class="form-label" for="{{ $data['name'] }}">{{ $data['label'] }} @if($required) * @endif</label>
            <input type="file" name="{{ $data['name'] }}[]" id="{{ $data['name'] }}" class="dropify" data-default-file="{{ ($model && $model->{$data['name']}) ? asset($model->{$data['name']}) : asset('dummy.png') }}" multiple/>
            @error($data['name'])
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
    @break

    @case('select-status')
        @if ($model)
            <div class="col-md-6">
                <label class="form-label" for="{{ $data['name'] }}">{{ $data['label'] }} @if($required) * @endif</label>
                <select name="{{ $data['name'] }}" id="{{ $data['name'] }}" class="form-control">
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

    @case('select')
    @if ($model)
        <div class="col-md-6">
            <label class="form-label" for="{{ $data['name'] }}">{{ $data['label'] }} @if($required) * @endif</label>
            <select name="{{ $data['name'] }}" id="{{ $data['name'] }}" class="form-control">
                @foreach ($secondayModel as $item)
                    <option value="{{ $item->id }}" @selected($selected == $item->id)>{{ $item->title }}</option>
                @endforeach
            </select>
            @error($data['name'])
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
    @endif
@break

    @case('multiple-select')
        <div class="col-md-6">
            <label for="{{ $data['name'] }}" class="form-label">{{ $data['label'] }}</label>
            <div class="input-group mb-3">
                <select class="js-example-basic-multiple form-control m-input select2_demo_1" id="{{ $data['name'] }}" name="{{ $data['name'] }}[]" multiple="multiple">
                    @foreach($secondayModel as $row)
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


@push('custom-scripts')
    <script src="{{ asset('') }}select2/4.0.11/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2({
                placeholder: 'Select an option'
            });
        });
    </script>
@endpush
