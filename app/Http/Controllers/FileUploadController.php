<?php
/**
 * Created by PhpStorm.
 * User: Adre
 * Date: 9/20/15
 * Time: 9:52 AM
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class FileUploadController extends Controller
{

    public function uploadImage(Request $request){
        if($request->hasFile('file')){

            $file = $request->file('file');
            $destinationPath =public_path()."/uploads/blogs/images/original/";
            $fileName = rand(1, 100000).strtotime(date('Y-m-d H:i:s')).$request->user()->id.".".$file->getClientOriginalExtension();
            $file->move($destinationPath, $fileName);

            //$fileName = $destinationPath.$fileName;
            $fileName = "/uploads/blogs/images/original/".$fileName;

            return response()->json(array('status'=>200, 'file_name' => $fileName), 200);
        }
        else{
            return response()->json(array('status'=>400, 'message' => 'Failed to upload file'), 400);
        }
    }
}