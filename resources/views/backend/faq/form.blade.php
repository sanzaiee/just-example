@extends('backend.form.master')
@section('form-content')

<div class="row g-3">
      @foreach ([
                  ['question','Question'],
                  ['answer','Answer']

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

