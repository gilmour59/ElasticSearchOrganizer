@extends('layouts.app')

@section('content')
<div class="card mx-auto" style="width: 1150px;">
    <div class="card-header font-weight-bold">
        Edit Role: {{$role->name}}
    </div>
    <div class="card-body">    
        <form action="{{ route('roles.update', $role->id) }}" method="post">
            @csrf
            <div class="form-group">
                <label class="control-label" for="name">Name: </label>
                <input class="form-control" type="text" name="name" id="name" value="{{ $role->name }}" autofocus>
            </div>

            <h4>Assign Permissions </h4>
            <div class="form-check">
                @foreach($permissions as $permission)
                <input class="form-check-input" type="checkbox" value="{{ $permission->id }}" name="permissions[]" <?php foreach($role->permissions as $role_p){if(($role_p->id) == ($permission->id)){ echo "checked"; }} ?>>
                    <label class="form-check-label">
                        {{$permission->name}} 
                    </label> 
                    <br>
                @endforeach
            </div>
            <input type="hidden" name="_method" value="PUT">
            <input type="submit" class="btn btn-primary" value="Submit"> 
        </form>
    </div>
</div>
@endsection