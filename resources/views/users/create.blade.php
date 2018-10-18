@extends('layouts.app')

@section('content')
<div class="card mx-auto" style="width: 1150px;">
    <div class="card-header font-weight-bold">
        Add User
    </div>
    <div class="card-body">    
        <form action="{{ route('users.store') }}" method="post">
            @csrf
            <div class="form-group">
                <label class="control-label" for="addUserName">Name: </label>
                <input class="form-control" type="text" name="addUserName" id="addUserName" value="{{ old('addUserName') }}" autofocus>
            </div>
            <div class="form-group">
                <label class="control-label" for="addUserEmail">Email: </label>
                <input class="form-control" type="email" name="addUserEmail" id="addUserEmail" value="{{ old('addUserEmail') }}">
            </div>
            <div class="form-group">
                @foreach($roles as $role)
                <input class="form-check-input" type="radio" value="{{ $role->id }}" name="addUserRole[]">
                    <label class="form-check-label">
                        {{$role->name}} 
                    </label> 
                    <br>
                @endforeach
            </div>
            <div class="form-group">
                <label class="control-label" for="addUserPassword">Password: </label>
                <input class="form-control" type="password" name="addUserPassword" id="addUserPassword">
            </div>
            <div class="form-group">
                <label class="control-label" for="addUserPassword_confirmation">Password: </label>
                <input class="form-control" type="password" name="addUserPassword_confirmation" id="addUserPassword_confirmation">
            </div>

            <input type="submit" class="btn btn-primary" name="submit" value="Submit"> 
        </form>
    </div>
</div>
@endsection