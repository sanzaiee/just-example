          @extends('backend.form.master')
          @section('form-content')


        <div class="row g-3">
                @include('backend.form.collection', [
                    'data' => [
                        'name' => 'image',
                        'label' => 'Please select your image',
                    ],
                    'required' => true,
                    'model' => $model ?? null,
                    'type' => 'file'
                ])


                @foreach ([['title','Title'],['sub_title','Sub Title']] as $item)
                    @include('backend.form.collection', [
                        'data' => [
                            'name' => $item[0],
                            'label' => $item[1],
                        ],
                        'required' => true,
                        'model' => $model ?? null,
                        'type' => 'text'
                    ])
                @endforeach

                @include('backend.form.collection', [
                    'data' => [
                        'name' => 'page_id',
                        'label' => 'Page',
                    ],
                    'required' => true,
                    'model' => $model ?? [],
                    'secondayModel' => $pages ?? [],
                    'selected' => $model->page_id ?? [],
                    'type' => 'select'
                ])

                @include('backend.form.collection', [
                    'data' => [
                        'name' => 'position',
                        'label' => 'Position',
                    ],
                    'required' => true,
                    'model' => $model ?? null,
                    'type' => 'number'
                ])

                @include('backend.form.collection', [
                    'data' => [
                        'name' => 'status',
                        'label' => 'Feature Status',
                    ],
                    'required' => true,
                    'model' => $model ?? null,
                    'type' => 'select-status'
                ])


                @foreach ([
                            ['short_description','Short Description'],
                            ['description','Description']

                        ] as $item)
                    @include('backend.form.collection', [
                        'data' => [
                            'name' => $item[0],
                            'label' => $item[1],
                        ],
                        'required' => true,
                        'model' => $model ?? null,
                        'type' => 'textarea'
                    ])
                @endforeach

        </div>
            @endsection

