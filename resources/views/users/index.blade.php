@extends('layouts.app')

@section('content')

{{-- send this to nav after everything --}}
<h1>
    <i class="fa fa-users"></i>
    <a href="{{ route('roles.index') }}" class="btn btn-default pull-right">Roles</a>
    <a href="{{ route('permissions.index') }}" class="btn btn-default pull-right">Permissions</a>
</h1>  

<div class="card mx-auto" style="width: 1150px;">
    <div class="card-header font-weight-bold">
        User Administration
    </div>
    <div class="card-body">    
        <a href="{{ route('users.create') }}" class="btn btn-success">Add User</a>
        <div class="row">
            <div class="table-responsive" style="font-size:14px">
                <table class="table table-striped table-bordered text-center">
                    <thead>
                        <tr>
                            <th>Name:</th>
                            <th>Email:</th>
                            <th>Date/Time Added:</th>
                            <th>User Roles</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at->format('F d, Y h:ia') }}</td>
                            {{-- Retrieve array of roles associated to a user and convert to string --}}
                            <td>{{ $user->roles()->pluck('name')->implode(' ') }}</td>
                            <td>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-info" style="margin-right: 3px;">Edit</a>
                            </td>
                            <td>
                                <form action="{{ route('users.destroy', $user->id) }}" method="post">
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