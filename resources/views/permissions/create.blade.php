@extends('layouts.app')

@section('content')
<div class="card mx-auto" style="width: 1150px;">
    <div class="card-header font-weight-bold">
        Add Permissions
    </div>
    <div class="card-body">    
        <form action="{{ route('permissions.store') }}" method="post">
            @csrf
            <div class="form-group">
                <label class="control-label" for="addPermissionName">Name: </label>
                <input class="form-control" type="text" name="addPermissionName" id="addPermissionName" value="{{ old('addPermissionName') }}" autofocus>
            </div>
            @if(!$roles->isEmpty())
            <h4>Assign Permission to Roles</h4>
            <div class="form-group">
                @foreach($roles as $role)
                <input class="form-check-input" type="check" value="{{ $role->id }}" name="addPermissionRole[]">
                    <label class="form-check-label">
                        {{$role->name}} 
                    </label> 
                    <br>
                @endforeach
            </div>
            @endif
            <input type="submit" class="btn btn-primary" name="submit" value="Submit"> 
        </form>
    </div>
</div>
@endsection