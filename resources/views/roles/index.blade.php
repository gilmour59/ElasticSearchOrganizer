@extends('layouts.app')

@section('content')

{{-- send this to nav after everything --}}
<h1>
    <i class="fa fa-users"></i>
    <a href="{{ route('permissions.index') }}" class="btn btn-default pull-right">Permissions</a>
    <a href="{{ route('users.index') }}" class="btn btn-default pull-right">Users</a>
</h1>  

<div class="card mx-auto" style="width: 1150px;">
    <div class="card-header font-weight-bold">
        Roles
    </div>
    <div class="card-body">    
        <a href="{{ route('permissions.create') }}" class="btn btn-success">Add Permissions</a>
        <div class="row">
            <div class="table-responsive" style="font-size:14px">
                <table class="table table-striped table-bordered text-center">
                    <thead>
                        <tr>
                            <th>Role: </th>
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
                            <td>{{ str_replace(array('[',']','"'),'', $role->permissions()->pluck('name')) }}</td>
                            <td>
                                <a href="{{ route('permissions.edit', $user->id) }}" class="btn btn-info" style="margin-right: 3px;">Edit</a>
                            </td>
                            <td>
                                <form action="{{ route('permissions.destroy', $user->id) }}" method="post">
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