<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct() {
        //isAdmin middleware lets only users with a specific permission permission to access these resources
        $this->middleware(['auth', 'isAdmin']); 
    }

    public function index() {
        $permissions = Permission::all(); //Get all permissions

        return view('permissions.index')->with('permissions', $permissions);
    }

    public function create() {
        $roles = Role::get(); //Get all roles

        return view('permissions.create')->with('roles', $roles);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'addPermissionName'=>'required|max:40',
        ]);

        $name = $request['addPermissionName'];
        $permission = new Permission();
        $permission->name = $name;

        $roles = $request['addPermissionRole'];

        $permission->save();

        if (!empty($request['addPermissionRole'])) { //If one or more role is selected
            foreach ($roles as $role) {
                $r = Role::where('id', '=', $role)->firstOrFail(); //Match input role to db record

                $permission = Permission::where('name', '=', $name)->first(); //Match input //permission to db record
                $r->givePermissionTo($permission);
            }
        }

        return redirect()->route('permissions.index')
            ->with('success', 'Permission'. $permission->name.' added!');
    }

    public function show($id) {
        return redirect('permissions');
    }

    public function edit($id) {
        $permission = Permission::findOrFail($id);

        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, $id) {
        $permission = Permission::findOrFail($id);
        $this->validate($request, [
            'editPermissionRole'=>'required|max:40',
        ]);
        $input = $request->all();
        $permission->fill($input)->save();

        return redirect()->route('permissions.index')
            ->with('success', 'Permission'. $permission->name.' updated!');
    }

    public function destroy($id) {
        $permission = Permission::findOrFail($id);

    //Make it impossible to delete this specific permission    
    if ($permission->name == "Administer roles & permissions") {
            return redirect()->route('permissions.index')
            ->with('error', 'Cannot delete this Permission!');
        }

        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('success', 'Permission deleted!');
    }
}
