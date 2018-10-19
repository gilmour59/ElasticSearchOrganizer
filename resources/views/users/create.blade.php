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
                <label class="control-label" for="name">Name: </label>
                <input class="form-control" type="text" name="name" id="name" value="{{ old('name') }}" autofocus>
            </div>
            <div class="form-group">
                <label class="control-label" for="email">Email: </label>
                <input class="form-control" type="email" name="email" id="email" value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <label class="control-label" for="password">Password: </label>
                <input class="form-control" type="password" name="password" id="password">
            </div>
            <div class="form-group">
                <label class="control-label" for="password_confirmation">Confirm Password: </label>
                <input class="form-control" type="password" name="password_confirmation" id="password_confirmation">
            </div>
            <label><b>Select Role: </b></label>
            <div class="form-check">
                @foreach($roles as $role)
                <input class="form-check-input" type="radio" value="{{ $role->id }}" name="role[]">
                    <label class="form-check-label">
                        {{$role->name}} 
                    </label> 
                    <br>
                @endforeach
                <br>
            </div>
            <input type="submit" class="btn btn-primary float-right" value="Submit"> 
        </form>
    </div>
</div>
@endsection