@extends('layouts.app')

@section('content')
<div class="card mx-auto" style="width: 1150px;">
    <div class="card-header font-weight-bold">
        Add Roles
    </div>
    <div class="card-body">    
        <form action="{{ route('permissions.store') }}" method="post">
            @csrf
            <div class="form-group">
                <label class="control-label" for="addRoleName">Name: </label>
                <input class="form-control" type="text" name="addRoleName" id="addRoleName" value="{{ old('addRoleName') }}" autofocus>
            </div>

            <h4>Assign Permissions </h4>
            <div class="form-group">
                @foreach($permissions as $permission)
                <input class="form-check-input" type="check" value="{{ $permission->id }}" name="addRolePermission[]">
                    <label class="form-check-label">
                        {{$permission->name}} 
                    </label> 
                    <br>
                @endforeach
            </div>
            
            <input type="submit" class="btn btn-primary" name="submit" value="Submit"> 
        </form>
    </div>
</div>
@endsection