@extends('layouts.app')

@section('content')
<div class="card mx-auto" style="width: 1150px;">
    <div class="card-header font-weight-bold">
        Edit Permission: {{$permission->name}}
    </div>
    <div class="card-body">    
        <form action="{{ route('permissions.update', $permission->id) }}" method="post">
            @csrf
            <div class="form-group">
                <label class="control-label" for="name">Name: </label>
                <input class="form-control" type="text" name="name" id="name" value="{{ $permission->name }}" autofocus>
            </div>
            <input type="hidden" name="_method" value="PUT">
            <input type="submit" onclick="return confirm('Are you sure you want to do that?');" class="btn btn-primary" value="Submit"> 
        </form>
    </div>
</div>
@endsection