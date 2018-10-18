@extends('layouts.app')

@section('content')
<div class="card mx-auto" style="width: 1150px;">
    <div class="card-header font-weight-bold">
        Edit User: {{$user->name}}
    </div>
    <div class="card-body">    
        <form action="{{ route('users.update') }}" method="post">
            @csrf
            <div class="form-group">
                <label class="control-label" for="editUserName">Name: </label>
                <input class="form-control" type="text" name="editUserName" id="editUserName" value="{{ $user->name }}" autofocus>
            </div>
            <div class="form-group">
                <label class="control-label" for="editUserEmail">Email: </label>
                <input class="form-control" type="email" name="editUserEmail" id="editUserEmail" value="{{ $user->email }}">
            </div>
            <div class="form-group">
                @foreach($roles as $role)
                <input class="form-check-input" type="radio" value="{{ $role->id }}" name="editUserRole[]" <?php if(($user->roles) == ($role->id)){ echo checked } ?>>
                    <label class="form-check-label">
                        {{$role->name}} 
                    </label> 
                    <br>
                @endforeach
            </div>
            <input type="submit" class="btn btn-primary" name="submit" value="Submit"> 
        </form>
    </div>
</div>
@endsection