<form action="{{ route($route,$params ?? '') }}" method="get">
    <div class="row mb-3">

        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label" for="query">Search</label>
                <input type="search" class="form-control" id="query" name="query" value="{{ request()->input('query') ?? '' }}" placeholder="Search...">
            </div>
        </div>
        <div class="col-md-3 mt-4">
            <button class="btn btn-info btn-m" type="submit"><i class="fa fa-search"></i></button>
            <a href="{{ route($route,$params ?? '') }}" class="btn btn-danger btn-m"> <i class="fa fa-refresh"></i> </a>
        </div>
    </div>
</form>
