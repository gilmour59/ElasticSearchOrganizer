<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;
use Validator;
use Illuminate\Http\Request;
use App\ArchiveFile;
use App\Division;
use App\Category;
use Smalot\PdfParser\Parser;

class ViewForSavingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'clearance']); //$this->middleware('auth');
    }

    public function viewFiles(Request $request){
        
        $url = config('scout_elastic.client.hosts.0');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch);
        $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if (200 !== $retcode) {
            abort('500');
        } 

        $validator = Validator::make($request->all(), [
            'addFileUpload' => 'required',
            'addFileUpload.*' => 'file|required|mimes:pdf',
            'addDate' => 'required',
        ]); 

        if ($validator->fails())
        {
            $isAdd = true;
            $errors = $validator->errors();
            return redirect('/')->with('isAdd', $isAdd)->with('errors', $errors)->withInput();
        }
        $date = $request->input('addDate');

        $fileSys = new Filesystem();

        if(!$fileSys->exists(storage_path('app\public\temp\\'))){
            $fileSys->makeDirectory(storage_path('app\public\temp\\'));
        }

        if($request->hasFile('addFileUpload')){

            $path = storage_path('app/public/temp/');
            if ($handle = opendir($path)) {

                while (false !== ($file = readdir($handle))) { 
                    $fileLastModified = filemtime($path . $file);
                    time() - $fileLastModified;
                    //24 hours in a day * 3600 seconds per hour
                    if((time() - $fileLastModified) > 600 && $file != '.' && $file != '..')
                    {
                    unlink($path . $file);
                    }
                }
                closedir($handle); 
            }

            $passData = [];
            foreach($request->file('addFileUpload') as $file)
            {
                $file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                $isDuplicate = false;
                
                //For Dupes
                if(ArchiveFile::where('file_name', '=', $file_name)->count() > 0) {
                    $isDuplicate = true;
                }
                
                $fileNameToStore = $file->getClientOriginalName();
                
                $newName = $this->incrementFileName(storage_path('app/public/temp/'), $fileNameToStore);
                
                $path = $file->storeAs('public/temp', $newName); 

                $parser = new Parser();
                if($pdf = $parser->parseFile(storage_path('/app/') . $path)){
                    //IF FAIL - 'content cannot be parsed'
                    $text = $pdf->getText();

                    $key_div = $this->checkKeywords($text);
                }else{
                    alert('Parsing Error');
                    return view('index');
                }

                $data = array('isDuplicate' => $isDuplicate, 'file' => $newName, 'file_name' => $file_name, 'date' => $date, 'content' => $text, 'key_div' => $key_div);  

                if($isDuplicate || ($key_div == 0)){
                    array_unshift($passData, $data);
                }else{
                    array_push($passData, $data);
                }
            }

            $request->session()->put('passData', $passData);
        }
        return view('view_files')->with('passData', $passData);
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

    public function checkKeywords($text){

        $divisions = Division::select('div_name')->get();
        $Keywords = array();
        
        foreach($divisions->toArray() as $key => $value){
            $Keywords[$key + 1] = $value['div_name'];
        }
        //dd($Keywords);
        $textWithKeyword = array();

        //key is integer for the division
        foreach($Keywords as $key => $value){
            $posKeyword = stripos($text, $value);
            if($posKeyword !== false){
                $textWithKeyword[$key] = $posKeyword;
            }else{
                unset($textWithKeyword[$key]);
            }
        }    
        //dd($textWithKeyword);
        if(empty($textWithKeyword)){
            return 0;
        }else{
            //This returns the key of the division containing the keyword
            $wordDivision = array_search(min($textWithKeyword), $textWithKeyword);

            //Returns the key of the division
            //dd($wordDivision);
            return $wordDivision;
        }
    }

    public function deleteViewFile(Request $request){

        $url = config('scout_elastic.client.hosts.0');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch);
        $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if (200 !== $retcode) {
            abort('500');
        } 

        $passData = $request->session()->get('passData');
        $id = $request->get('delete');
        unset($passData[$id]);
        //reindex the array
        //$passData = array_values($passData); 
        $request->session()->put('passData', $passData);
        //dd($passData);
        return view('view_files')->with('passData', $passData);
    }

}
