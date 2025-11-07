<a href="{{ route($routeEdit,['slug'=>$params ?? '','id'=>$item->id]) }}" class="btn btn-rounded btn-sm btn-info m-r-5" data-toggle="tooltip" data-original-title="Edit">
    <i class="fa fa-pencil"></i>
</a>
<a href="" class="btn btn-rounded btn-sm btn-danger m-r-5" data-toggle="tooltip"
data-original-title="Delete"
onclick="event.preventDefault(); if(confirm('Are You Sure ?')) document.getElementById('delete-form-{{ $item->id }}').submit();">
<i class="fa fa-trash"></i>
</a>
<form id="delete-form-{{ $item->id }}" action="{{route($routeDelete,['slug'=>$params ?? '','id'=>$item->id])}}" method="post">
@csrf
@method('delete')
</form>
