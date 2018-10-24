<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;
use Validator;
use Illuminate\Http\Request;
use App\ArchiveFile;
use App\Division;
use Smalot\PdfParser\Parser;
use App\Rules\checkForUndetectedTextContent;

class PostsController extends Controller
{
    public function __construct()
    {
        //To prioritize admin name in the nav
        $this->middleware(['auth', 'clearance']); //The important guards are in the web routes; 
    }

    public function index(Request $request)
    {
        $request->session()->put('division', $request
                ->has('division') ? $request->get('division') : ($request->session()
                ->has('division') ? $request->session()->get('division') : 0));

        $request->session()->put('search', $request
                ->has('search') ? $request->get('search') : ($request->session()
                ->has('search') ? $request->session()->get('search') : ''));

        $request->session()->put('field', $request
                ->has('field') ? $request->get('field') : ($request->session()
                ->has('field') ? $request->session()->get('field') : 'id'));

        $request->session()->put('sort', $request
                ->has('sort') ? $request->get('sort') : ($request->session()
                ->has('sort') ? $request->session()->get('sort') : 'desc'));

        $archiveFiles = new ArchiveFile();

        if(empty($request->session()->get('search'))){
            $isShowAll = true;
        }else{
            $isShowAll = false;
        }

        if($request->session()->get('division') == 0){
            if($isShowAll){
                $archiveFiles = $archiveFiles
                    ->join('divisions', 'archive_files.division_id', '=', 'divisions.id')
                    ->select('archive_files.*', 'divisions.div_name')
                    ->orderBy($request->session()->get('field'), $request->session()->get('sort'))
                    ->paginate(10);
            }else{
                $archiveFiles = $archiveFiles
                    ->search($request->session()->get('search'))
                    ->orderBy($request->session()->get('field'), $request->session()->get('sort'))
                    ->paginate(10);
            }
        }else{
            if($isShowAll){
                $archiveFiles = $archiveFiles
                    ->join('divisions', 'archive_files.division_id', '=', 'divisions.id')
                    ->select('archive_files.*', 'divisions.div_name')
                    ->where('archive_files.division_id', '=', $request->session()->get('division'))
                    ->orderBy($request->session()->get('field'), $request->session()->get('sort'))
                    ->paginate(10);
            }else{
                $archiveFiles = $archiveFiles
                    ->search($request->session()->get('search'))
                    ->where('division_id', $request->session()->get('division'))
                    ->orderBy($request->session()->get('field'), $request->session()->get('sort'))
                    ->paginate(10);
            }  
        }

        $division = $request->session()->get('division');
        $division_name = Division::get()->toArray();

        if($request->ajax()){
            return view('index')->with('archiveFiles', $archiveFiles)->with('division_name', $division_name);
        }
        return view('ajax')->with('archiveFiles', $archiveFiles)->with('division', $division)->with('division_name', $division_name);
    }

    public function store(Request $request)
    {
        $passedData = $request->session()->get('passData');
        
        $rule = array();
        $ruleDateFileNameOnly = array();
        foreach($passedData as $key => $value){
            $rule['saveDivision' . $key] = ['required', new checkForUndetectedTextContent];
            $rule['saveDate' . $key] = ['required'];
            $rule['saveFileName' . $key] = ['required'];
            $ruleDateFileNameOnly['saveDate' . $key] = ['required'];
            $ruleDateFileNameOnly['saveFileName' . $key] = ['required'];
        }

        $request->validate($ruleDateFileNameOnly);

        if(($request->input('saveAllDivision')) == 0){
            $request->validate($rule);
        }

        //Loop Create new Data
        foreach($passedData as $key => $value){

            $archiveFiles = new ArchiveFile();
            //sleep(1);

            if(($request->input('saveAllDivision')) == 0){
                $div_key = $request->input('saveDivision' . $key);
            }else{
                $div_key = $request->input('saveAllDivision');
            }
            $archiveFiles->division_id = $div_key;

            $date = $request->input('saveDate' . $key);            
            $archiveFiles->date = $date;

            $year = date('Y', strtotime($date));

            $archiveFiles->content = $request->input('saveContent' . $key);

            $division = Division::find($div_key);

            $archiveFiles->file_name = $request->input('saveFileName' . $key);

            $FileSys = new Filesystem();
            //Add Year of the files
            if($FileSys->exists(storage_path('app/public/temp/') . $value['file'])){

                $extension = explode(".", $value['file']);
                $extension = end($extension);

                if(!$FileSys->exists(config('organizer.storage_path') . $division->div_name . '\\' . $year . '\\')){
                    $FileSys->makeDirectory(config('organizer.storage_path') . $division->div_name . '\\' . $year . '\\', 0777, true);
                }

                //$newName = $this->incrementFileName(storage_path('app/public/' . $division->div_name . '/' . $year . '/'),  ($request->input('saveFileName' . $key) . '.' . $extension));
                $newName = $this->incrementFileName(config('organizer.storage_path') . $division->div_name . '\\' . $year . '\\',  ($request->input('saveFileName' . $key) . '.' . $extension));

                //Storage::move('public/temp/' .  $value['file'], 'public/' . $division->div_name . '/' . $year . '/' . $newName);
                $FileSys->move(storage_path('app\public\\') . 'temp\\' . $value['file'], config('organizer.storage_path') . $division->div_name . '\\' . $year . '\\' . $newName);

                $archiveFiles->file = $newName;

                $archiveFiles->save(); 
            }else{
                return redirect()->route('index')->with('error', 'File Not Found!');
            }
        }
        return redirect()->route('index')->with('success', 'Saved!');
    }

    function incrementFileName($file_path,$filename){

        if(count(glob($file_path. '' .$filename))>0){

            $file_ext = explode(".", $filename);
            $file_ext = end($file_ext);
            $file_name = str_replace(('.'.$file_ext),"",$filename);
            $newfilename = $file_name.'_'.(count(glob($file_path."$file_name*.$file_ext")) + 1).'.'.$file_ext;

            return $newfilename;
        }else{

            return $filename;
        }
    }

    //$newName = incrementFileName( "uploads/", $_FILES["my_file"]["name"] );
    //move_uploaded_file($_FILES["my_file"]["tmp_name"],"uploads/".$newName);

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'editFileUpload' => 'file|mimes:pdf',
            'editDivision' => 'required',
            'editDate' => 'required',
            'editFileName' => 'required'
        ]);

        if ($validator->fails())
            return response()->json([
                'fail' =>true,
                'errors' => $validator->errors()
            ]);

        //Find Data 
        $archiveFiles = ArchiveFile::find($id);

        $FileSys = new FileSystem();
        if($archiveFiles->date != $request->input('editDate')){ //done
            
            $division = Division::find($archiveFiles->division_id);
            
            $dateOld = $archiveFiles->date;
            $dateNew = $request->input('editDate');
            
            $yearOld = date('Y', strtotime($dateOld));
            $yearNew = date('Y', strtotime($dateNew));  

            /* if(!$FileSys->exists(config('organizer.storage_path') . $division->div_name . '\\' . $yearOld . '\\')){
                $FileSys->makeDirectory(config('organizer.storage_path') . $division->div_name . '\\' . $yearOld . '\\', 0777, true);
            } */

            if(!$FileSys->exists(config('organizer.storage_path') . $division->div_name . '\\' . $yearNew . '\\')){
                $FileSys->makeDirectory(config('organizer.storage_path') . $division->div_name . '\\' . $yearNew . '\\', 0777, true);
            }

            //$newName = $this->incrementFileName(storage_path('app/public/' . $division->div_name . '/' . $yearNew . '/'),  $archiveFiles->file);
            $newName = $this->incrementFileName(config('organizer.storage_path') . $division->div_name . '\\' . $yearNew . '\\',  $archiveFiles->file);

            //Storage::move('public/' . $division->div_name . '/' . $yearOld . '/' . $archiveFiles->file, 'public/' . $division->div_name . '/' . $yearNew . '/' . $newName);
            $FileSys->move(config('organizer.storage_path') . $division->div_name . '\\' . $yearOld . '\\' . $archiveFiles->file, config('organizer.storage_path') . $division->div_name . '\\' . $yearNew . '\\' . $newName);

            $archiveFiles->date = $dateNew;
            $archiveFiles->file = $newName;
        }

        if($archiveFiles->file_name != $request->input('editFileName')){ //done

            $division = Division::find($archiveFiles->division_id);

            $extension = explode(".", $archiveFiles->file);
            $extension = end($extension);

            $newFileName = $request->input('editFileName') . '.' . $extension;

            $year = date('Y', strtotime($archiveFiles->date));

            //$newName = $this->incrementFileName(storage_path('app/public/' . $division->div_name . '/' . $year . '/'),  $newFileName);
            $newName = $this->incrementFileName(config('organizer.storage_path') . $division->div_name . '\\' . $year . '\\',  $newFileName);

            //Storage::move('public/' . $division->div_name . '/' . $year . '/' . $archiveFiles->file, 'public/' . $division->div_name . '/' . $year . '/' . $newName);
            rename(config('organizer.storage_path') . $division->div_name . '\\' . $year . '\\' . $archiveFiles->file, config('organizer.storage_path') . $division->div_name . '\\' . $year . '\\' . $newName);

            $archiveFiles->file_name = $request->input('editFileName');
            $archiveFiles->file = $newName;
        }

        if($archiveFiles->division_id != $request->input('editDivision')){ //done

            $divisionOld = Division::find($archiveFiles->division_id);
            $divisionNew = Division::find($request->input('editDivision'));

            $year = date('Y', strtotime($archiveFiles->date));            

            if(!$FileSys->exists(config('organizer.storage_path') . $divisionNew->div_name . '\\' . $year . '\\')){
                $FileSys->makeDirectory(config('organizer.storage_path') . $divisionNew->div_name . '\\' . $year . '\\', 0777, true);
            }

            //$newName = $this->incrementFileName(storage_path('app/public/' . $divisionNew->div_name . '/' . $year . '/'),  $archiveFiles->file);
            $newName = $this->incrementFileName(config('organizer.storage_path') . $divisionNew->div_name . '\\' . $year . '\\',  $archiveFiles->file);

            //Storage::move('public/' . $divisionOld->div_name . '/' . $year . '/' . $archiveFiles->file, 'public/' . $divisionNew->div_name . '/' . $year . '/' . $newName);
            $FileSys->move(config('organizer.storage_path') . $divisionOld->div_name . '\\' . $year . '\\' . $archiveFiles->file, config('organizer.storage_path') . $divisionNew->div_name . '\\' . $year . '\\' . $newName);
            
            $archiveFiles->division_id = $request->input('editDivision');
            $archiveFiles->file = $newName;
        }

        //Handle File Upload
        if ($request->hasFile('editFileUpload')) { //done?

            //get File Name
            //$fileNameWithExtension = $request->file('editFileUpload')->getClientOriginalName();
            $extension = $request->file('editFileUpload')->getClientOriginalExtension();
            $fileNameToStore = $request->input('editFileName') . '.' . $extension;

            $division = Division::find($archiveFiles->division_id);
            $year = date('Y', strtotime($archiveFiles->date));

            //Delete and Replace
            //Storage::delete('public/' . $division->div_name . '/' . $year . '/' . $archiveFiles->file);
            $FileSys->delete(config('organizer.storage_path') . $division->div_name . '\\' . $year . '\\' . $archiveFiles->file);
            
            //$newName = $this->incrementFileName(storage_path('app/public/' . $division->div_name . '/' . $year . '/'),  $fileNameToStore);
            $newName = $this->incrementFileName(config('organizer.storage_path') . $division->div_name . '\\' . $year . '\\',  $fileNameToStore);

            //$path = $request->file('editFileUpload')->storeAs('public/' . $division->div_name . '/' . $year . '/', $newName);
            $path = $request->file('editFileUpload')->move(config('organizer.storage_path') . $division->div_name . '/' . $year . '/', $newName);

            //Parse pdf
            $parser = new Parser();

            //change this
            if($pdf = $parser->parseFile($path)){
                //IF FAIL - 'content cannot be parsed'
                $text = $pdf->getText();
                $archiveFiles->content = $text; 
            }else{
                return response()->json([
                    'fail' => true,
                    'errorParse' => 'File Parse Error!'
                ]);
            }
            $archiveFiles->file_name = $request->input('editFileName');
            $archiveFiles->file = $newName;
        }
        $archiveFiles->save();

        return response()->json([
            'fail' => false,
            'redirect_url' => route('index')
        ]);
    }

    public function edit($id)
    {
        $archiveFiles = ArchiveFile::find($id);
        $division = Division::find($archiveFiles->division_id);

        return response()->json([
            'file' => $archiveFiles,
            'division' => $division->id
        ]);
    }

    public function destroy($id)
    {
        $archiveFiles = ArchiveFile::findOrFail($id);
        $division = Division::find($archiveFiles->division_id);

        $year = date('Y', strtotime($archiveFiles->date));

        $FileSys = new FileSystem();
        //Storage::delete('public/' . $division->div_name . '/' . $year . '/' . $archiveFiles->file);
        $FileSys->delete(config('organizer.storage_path') . $division->div_name . '\\' . $year . '\\' . $archiveFiles->file);
        
        $archiveFiles->delete();
    }

    public function division()
    {
        $divisions = Division::get();

        return response()->json([
            'divisions' => $divisions,
        ]);
    }
    
    public function view($id)
    {
        $archiveFiles = ArchiveFile::find($id);
        $division = Division::find($archiveFiles->division_id);

        $year = date('Y', strtotime($archiveFiles->date));

        return response()->file(config('organizer.storage_path') . $division->div_name . '/' . $year . '/' . $archiveFiles->file);
    }

    public function download($id)
    {
        $archiveFiles = ArchiveFile::find($id);
        $division = Division::find($archiveFiles->division_id);

        $year = date('Y', strtotime($archiveFiles->date));

        return response()->download(config('organizer.storage_path') . $division->div_name . '/' . $year . '/' . $archiveFiles->file);
    }
}
