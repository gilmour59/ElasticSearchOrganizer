@extends('layouts.app')

@section('content')
<div class="card mx-auto" style="width: 1150px;">
    <div class="card-header font-weight-bold">
        User Administration
    </div>
    <div class="card-body">    
        <a href="{{ route('users.create') }}" class="btn btn-success mb-2">Add User</a>
        <div class="row">
            <div class="table-responsive" style="font-size:14px">
                <table class="table table-striped table-bordered text-center">
                    <thead>
                        <tr>
                            <th>Name:</th>
                            <th>Email:</th>
                            <th>Date/Time Added:</th>
                            <th>User Role:</th>
                            <th></th>
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
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary" style="margin-right: 3px;">Edit</a>
                            </td>
                            <td>
                                <a href="{{ route('users.password', $user->id) }}" class="btn btn-info" style="margin-right: 3px;">Password</a>
                            </td>
                            <td>
                                @if($user->id !== 1)
                                <form action="{{ route('users.destroy', $user->id) }}" method="post">
                                    @csrf
                                    <input name="_method" type="hidden" value="DELETE">
                                    <input type="submit" onclick="return confirm('Are you sure you want to Permanently DELETE this?');" class="btn btn-danger" value="Delete">
                                </form>
                                @endif
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