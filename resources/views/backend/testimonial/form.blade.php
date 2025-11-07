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


      @foreach ([['name','Name'],['post','Post'],
      ] as $item)
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
            'name' => 'position',
            'label' => 'Position',
        ],
        'required' => true,
        'model' => $banner ?? null,
        'type' => 'number'
    ])


        @foreach ([
            ['facebook','Facebook'],
            ['instagram','Instagram'],
            ['youtube','Youtube'],
            ['linkedin','Linkedin'],
            ['twitter','Twitter'],
        ] as $item)
            @include('backend.form.collection', [
                'data' => [
                    'name' => $item[0],
                    'label' => $item[1],
                ],
                'required' => false,
                'model' => $model ?? null,
                'type' => 'text'
            ])
        @endforeach



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
            ['description','Description'],

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

