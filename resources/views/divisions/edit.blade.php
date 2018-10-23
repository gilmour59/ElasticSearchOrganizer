@extends('layouts.app')

@section('content')
<div class="card mx-auto" style="width: 1150px;">
    <div class="card-header font-weight-bold">
        Edit Division: {{$division->div_name}}
    </div>
    <div class="card-body">    
        <form action="{{ route('divisions.update', $division->id) }}" method="post">
            @csrf
            <div class="form-group">
                <label class="control-label" for="div_name">Name: </label>
                <input class="form-control" type="text" name="div_name" id="div_name" value="{{ $division->div_name }}" autofocus>
            </div>
            <input type="hidden" name="_method" value="PUT">
            <input type="submit" onclick="return confirm('Are you sure you want to do this?');" class="btn btn-primary" value="Submit"> 
        </form>
    </div>
</div>
@endsection