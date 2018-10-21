<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Division;

class DivisionController extends Controller
{
    public function __construct() {
        //isAdmin middleware lets only users with a specific permission permission to access these resources
        $this->middleware(['auth', 'isAdmin']); 
    }

    public function index() {
        $divisions = Division::all(); //Get all permissions
        return view('divisions.index')->with('divisions', $divisions);
    }

    public function create() {
        return view('divisions.create');
    }

    public function store(Request $request) {
        $this->validate($request, [
            'div_name'=>'required|max:40|unique:divisions,div_name',
        ]);

        $name = $request['div_name'];

        $division = new Division();
        $division->div_name = $name;
        $division->save();

        return redirect()->route('divisions.index')
            ->with('success', 'Division: '. $division->div_name.' added!');
    }

    public function show($id) {
        return redirect('divisions');
    }

    public function edit($id) {
        $division = Division::findOrFail($id);
        return view('divisions.edit', compact('division'));
    }

    public function update(Request $request, $id) {
        $division = Division::findOrFail($id);
        $this->validate($request, [
            'div_name'=>'required|max:40|unique:divisions,div_name,'.$id,
        ]);

        $name = $request['div_name'];
        $division->div_name = $name;
        $division->save();

        return redirect()->route('divisions.index')
            ->with('success', 'Division: '. $division->div_name.' updated!');
    }

    public function destroy($id) {
        $division = Division::findOrFail($id);

        $division->delete();

        return redirect()->route('divisions.index')
            ->with('success', 'Division deleted!');
    }
}
