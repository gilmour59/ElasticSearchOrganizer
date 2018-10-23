@extends('layouts.app')

@section('content')
<div class="card mx-auto" style="width: 1150px;">
    <div class="card-header font-weight-bold">
        Add Division
    </div>
    <div class="card-body">    
        <form action="{{ route('divisions.store') }}" method="post">
            @csrf
            <div class="form-group">
                <label class="control-label" for="div_name">Name: </label>
                <input class="form-control" type="text" name="div_name" id="div_name" value="{{ old('div_name') }}" autofocus>
            </div>
            <input type="submit" onclick="return confirm('Are you sure you want to do this?');" class="btn btn-primary float-right" value="Submit"> 
        </form>
    </div>
</div>
@endsection