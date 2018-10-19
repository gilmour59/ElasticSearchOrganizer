@extends('layouts.app')

@section('content')
<div class="card mx-auto" style="width: 1150px;">
    <div class="card-header font-weight-bold">
        Edit User: {{$user->name}}
    </div>
    <div class="card-body">    
        <form action="{{ route('users.update', $user->id) }}" method="post">
            @csrf
            <div class="form-group">
                <label class="control-label" for="name">Name: </label>
                <input class="form-control" type="text" name="name" id="name" value="{{ $user->name }}" autofocus>
            </div>
            <div class="form-group">
                <label class="control-label" for="email">Email: </label>
                <input class="form-control" type="email" name="email" id="email" value="{{ $user->email }}">
            </div>
            <label><b>Role: </b></label>
            <div class="form-check">
                @foreach($roles as $role)
                <input class="form-check-input" type="radio" value="{{ $role->id }}" name="role[]" <?php if(isset($user->roles[0]->id)){if($user->roles[0]->id == $role->id){ echo "checked"; }} ?>>
                    <label class="form-check-label">
                        {{$role->name}} 
                    </label> 
                    <br>
                @endforeach
            </div>
            <br>
            <input type="hidden" name="_method" value="PUT">
            <input type="submit" class="btn btn-primary" value="Submit"> 
        </form>
    </div>
</div>
@endsection