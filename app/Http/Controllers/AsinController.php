<?php

namespace App\Http\Controllers;

use App\Models\AsinDetails;
use App\Models\AsinFile;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AsinController extends Controller
{
    //
    public function uploadAsin(Request $request)
    {
        $asins_storage_path = storage_path('public/asins');
        $user = auth()->user();
        if(!is_dir($asins_storage_path)){
            mkdir($asins_storage_path,0777, true);
        }

        $headers = array(
            'Content-Type' => 'text/csv'
        );
        $file = $request->file('asin_file');
        if($file){
            $filename = 'asin_'.date('dmYHiss');
            $extension = $file->getClientOriginalExtension(); //Get Extension of uploaded file
            $tempPath = $file->getRealPath();
            $fileSize = $file->getSize(); //Get Size of uploaded file in bytes

            $csv_data = file_get_contents($tempPath);
            if(preg_match('/\x00\x/',$csv_data)){
                return back()->with('error', 'Your File is corrupted. Kindly Upload Valid File.');
            }

            $file->move($asins_storage_path,$filename.'.'.$extension);

            $asinFileArr = [
                'user_id' => $user->id,
                'filename' => $filename.'.'.$extension,
                'added_date' => date('Y-m-d'),
                'last_checked_date' => date('Y-m-d'),
                'attempt_report' => 0
            ];
            AsinFile::create($asinFileArr);
        }

        return back()->with('succes', 'Csv File is Updated Successfully!');
    }

    public function getAsinDetails()
    {
        $asinDetails = AsinDetails::where('user_id',auth()->user()->id)->orderBy('id','ASC')->get();
        return view('pages.asins',compact('asinDetails'));
    }
}
