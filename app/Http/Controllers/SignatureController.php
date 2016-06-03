<?php
/**
 * Created by PhpStorm.
 * User: aqib
 * Date: 5/10/16
 * Time: 1:07 PM
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class SignatureController extends Controller
{
    public function getSignature()
    {
        if(auth()->user()->signature) return redirect('/');
        return $this->createView('auth.signature', []);
    }

    public function save()
    {
        if(auth()->user()->signature) return redirect('/');
        $signature_url = null;
        $request = Request::capture();
        $data = [];

        if($request->has('signature') && $request->input('signature')){
            try {
                $base64_content = $request->input('signature');
                if (preg_match('/data:image\/(gif|jpeg|png);base64,(.*)/i', $base64_content, $matches)) {
                    $imageType = $matches[1];
                    $imageData = base64_decode($matches[2]);

                    $base_path = public_path('uploads/signatures/');
                    $file_path = $this->auth->id() . '_' . time() . '.png';
                    $signature_url = 'uploads/signatures/'.$file_path;
                    file_put_contents($base_path.$file_path, $imageData);
                    $data['signature'] = $signature_url;
                    $this->auth->user()->update($data);
                } else {
                    throw new \Exception('Invalid data URL.');
                }
            }catch (\Exception $e){
                return redirect()->back()->with('error', 'Failed to save signature. Please try again.');
            }
        }
        else {
            return redirect()->back()->with('error', 'Failed to save signature. Please try again.');
        }
        return redirect('/');
    }
}