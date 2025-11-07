@extends('backend.form.master')
@section('form-content')
        <div class="row g-3">
                @include('backend.form.collection', [
                    'data' => [
                        'name' => 'image',
                        'label' => 'Please select your image',
                    ],
                    'required' => true,
                    'model' => $banner ?? null,
                    'type' => 'file'
                ])


                @foreach ([
                            ['name','Name'],
                            ['short_description','Short Description'],
                            ['description','Description'],
                            ['button','Button Name'],
                            ['link','Link'],

                        ] as $item)
                    @include('backend.form.collection', [
                        'data' => [
                            'name' => $item[0],
                            'label' => $item[1],
                        ],
                        'required' => false,
                        'model' => $banner ?? null,
                        'type' => 'text'
                    ])
                @endforeach


                @include('backend.form.collection', [
                    'data' => [
                        'name' => 'position',
                        'label' => 'Position',
                    ],
                    'required' => true,
                    'model' => $banner ?? null,
                    'type' => 'number'
                ])


                @include('backend.form.collection', [
                    'data' => [
                        'name' => 'status',
                        'label' => 'Feature Status',
                    ],
                    'required' => true,
                    'model' => $banner ?? null,
                    'type' => 'select-status'
                ])


                {{-- @foreach ([
                            ['short_description','Short Description'],
                            ['description','Description']

                        ] as $item)
                    @include('backend.form.collection', [
                        'data' => [
                            'name' => $item[0],
                            'label' => $item[1],
                        ],
                        'required' => true,
                        'model' => $banner ?? null,
                        'type' => 'textarea'
                    ])
                @endforeach --}}
        </div>

    @endsection

