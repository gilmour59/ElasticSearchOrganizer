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
                <label class="control-label" for="name">Name: </label>
                <input class="form-control" type="text" name="name" id="name" value="{{ old('name') }}" autofocus>
            </div>
            @if(!$roles->isEmpty())
            <label><b>Select Role: </b></label>
            <div class="form-check">
                @foreach($roles as $role)
                <input class="form-check-input" type="checkbox" value="{{ $role->id }}" name="role[]">
                    <label class="form-check-label">
                        {{$role->name}} 
                    </label> 
                    <br>
                @endforeach
            </div>
            @endif
            <br>
            <input type="submit" class="btn btn-primary float-right" value="Submit"> 
        </form>
    </div>
</div>
@endsection