@extends('layouts.app')

@section('content')
<div class="card mx-auto" style="width: 1150px;">
    <div class="card-header font-weight-bold">
        Divisions
    </div>
    <div class="card-body">    
        <a href="{{ route('divisions.create') }}" class="btn btn-success mb-2">Add Divisions</a>
        <div class="row">
            <div class="table-responsive" style="font-size:14px">
                <table class="table table-striped table-bordered text-center">
                    <thead>
                        <tr>
                            <th>Divisions: </th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($divisions as $division)
                        <tr>
                            <td>{{ $division->div_name }}</td>
                            <td>
                                <a href="{{ route('divisions.edit', $division->id) }}" class="btn btn-info" style="margin-right: 3px;">Edit</a>
                            </td>
                            <td>
                                <form action="{{ route('divisions.destroy', $division->id) }}" method="post">
                                    @csrf
                                    <input name="_method" type="hidden" value="DELETE">
                                    <input type="submit" class="btn btn-danger" value="Delete">
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>  
            </div>
        </div>
    </div>
</div>
@endsection