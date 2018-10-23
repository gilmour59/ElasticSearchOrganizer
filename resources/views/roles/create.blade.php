@extends('layouts.app')

@section('content')
<div class="card mx-auto" style="width: 1150px;">
    <div class="card-header font-weight-bold">
        Add Roles
    </div>
    <div class="card-body">    
        <form action="{{ route('roles.store') }}" method="post">
            @csrf
            <div class="form-group">
                <label class="control-label" for="name">Name: </label>
                <input class="form-control" type="text" name="name" id="name" value="{{ old('name') }}" autofocus>
            </div>
            <label><b>Select Permissions: </b></label>
            <div class="form-check">
                @foreach($permissions as $permission)
                <input class="form-check-input" type="checkbox" value="{{ $permission->id }}" name="permissions[]">
                    <label class="form-check-label">
                        {{$permission->name}} 
                    </label> 
                    <br>
                @endforeach
            </div>
            <br>
            <input type="submit" onclick="return confirm('Are you sure you want to do that?');" class="btn btn-primary float-right" value="Submit"> 
        </form>
    </div>
</div>
@endsection