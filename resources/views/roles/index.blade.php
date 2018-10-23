@extends('layouts.app')

@section('content')
<div class="card mx-auto" style="width: 1150px;">
    <div class="card-header font-weight-bold">
        Roles
    </div>
    <div class="card-body">    
        <a href="{{ route('roles.create') }}" class="btn btn-success mb-2">Add Roles</a>
        <div class="row">
            <div class="table-responsive" style="font-size:14px">
                <table class="table table-striped table-bordered text-center">
                    <thead>
                        <tr>
                            <th>Roles: </th>
                            <th>Permissions: </th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            {{-- Retrieve array of permissions associated to a role and convert to string --}}
                            <td>{{ str_replace(array('[',']','"',','),'|', $role->permissions()->pluck('name')) }}</td>
                            <td>
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-info" style="margin-right: 3px;">Edit</a>
                            </td>
                            <td>
                                <form action="{{ route('roles.destroy', $role->id) }}" method="post">
                                    @csrf
                                    <input name="_method" type="hidden" value="DELETE">
                                    <input type="submit" onclick="return confirm('Are you sure you want to Permanently DELETE this?');" class="btn btn-danger" value="Delete">
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