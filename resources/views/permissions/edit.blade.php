@extends('layouts.app')

@section('content')
<div class="card mx-auto" style="width: 1150px;">
    <div class="card-header font-weight-bold">
        Edit Permission: {{$permission->name}}
    </div>
    <div class="card-body">    
        <form action="{{ route('permissions.update') }}" method="post">
            @csrf
            <div class="form-group">
                <label class="control-label" for="editPermissionName">Name: </label>
                <input class="form-control" type="text" name="editPermissionName" id="editPermissionName" value="{{ $permission->name }}" autofocus>
            </div>

            <input type="submit" class="btn btn-primary" name="submit" value="Submit"> 
        </form>
    </div>
</div>
@endsection