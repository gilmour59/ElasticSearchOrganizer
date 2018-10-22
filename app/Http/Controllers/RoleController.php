<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct() {
        //isAdmin middleware lets only users with a specific permission permission to access these resources
        $this->middleware('isAdmin');
    }

    public function index() {
        $roles = Role::all();//Get all roles
        return view('roles.index')->with('roles', $roles);
    }

    public function create() {
        $permissions = Permission::all();//Get all permissions
        return view('roles.create', ['permissions'=>$permissions]);
    }

    public function store(Request $request) {
        //Validate name and permissions field
        $this->validate($request, [
            'name'=>'required|unique:roles,name|max:40',
            'permissions' =>'required',
            ]
        );

        $name = $request['name'];
        $role = new Role();
        $role->name = $name;
        $permissions = $request['permissions'];
        $role->save();

        //Looping thru selected permissions
        foreach ($permissions as $permission) {
            $p = Permission::where('id', '=', $permission)->firstOrFail(); 
            //Fetch the newly created role and assign permission
            $role = Role::where('name', '=', $name)->first(); 
            $role->givePermissionTo($p);
        }
        return redirect()->route('roles.index')
            ->with('success', 'Role: '. $role->name.' added!'); 
    }

    public function show($id) {
        return redirect('roles');
    }

    public function edit($id) {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        return view('roles.edit', compact('role', 'permissions'));
    }
    
    public function update(Request $request, $id) {

        //Get role with the given id
        $role = Role::findOrFail($id);
        
        //Validate name and permission fields
        $this->validate($request, [
            'name'=>'required|max:40|unique:roles,name,'.$id, //PROBLEM HERE!
            'permissions' =>'required',
        ]);

        $input = $request->except(['permissions']);
        $permissions = $request['permissions'];
        $role->fill($input)->save();
        $p_all = Permission::all();//Get all permissions

        foreach ($p_all as $p) {
            $role->revokePermissionTo($p); //Remove all permissions associated with role
        }

        foreach ($permissions as $permission) {
            $p = Permission::where('id', '=', $permission)->firstOrFail(); //Get corresponding form //permission in db
            $role->givePermissionTo($p);  //Assign permission to role
        }

        return redirect()->route('roles.index')
            ->with('success', 'Role: '. $role->name.' updated!');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role deleted!');
    }
}
