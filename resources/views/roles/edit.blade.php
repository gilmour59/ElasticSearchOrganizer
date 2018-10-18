@extends('layouts.app')

@section('content')
<div class="card mx-auto" style="width: 1150px;">
    <div class="card-header font-weight-bold">
        Edit Role: {{$role->name}}
    </div>
    <div class="card-body">    
        <form action="{{ route('roles.update') }}" method="post">
            @csrf
            <div class="form-group">
                <label class="control-label" for="editRoleName">Name: </label>
                <input class="form-control" type="text" name="editRoleName" id="editRoleName" value="{{ $role->name }}" autofocus>
            </div>

            <h4>Assign Permissions </h4>
            <div class="form-group">
                @foreach($permissions as $permission)
                <input class="form-check-input" type="check" value="{{ $permission->id }}" name="editRolePermission[]" <?php if(($role->permissions) == ($permission->id)){ echo checked } ?>>
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