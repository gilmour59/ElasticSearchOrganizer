@extends('layouts.app')

@section('content')
<div class="card mx-auto" style="width: 1150px;">
    <div class="card-header font-weight-bold">
        Edit User {{$user->name}}'s Password:
    </div>
    <div class="card-body">    
        <form action="{{ route('users.password', $user->id) }}" method="post">
            @csrf
            <div class="form-group">
                <label class="control-label" for="password">Password: </label>
                <input class="form-control" type="password" name="password" id="password">
            </div>
            <div class="form-group">
                <label class="control-label" for="password_confirmation">Confirm Password: </label>
                <input class="form-control" type="password" name="password_confirmation" id="password_confirmation">
            </div>
            <input type="hidden" name="_method" value="PUT">
            <input type="submit" onclick="return confirm('Are you sure you want to do that?');" class="btn btn-primary" value="Submit"> 
        </form>
    </div>
</div>
@endsection