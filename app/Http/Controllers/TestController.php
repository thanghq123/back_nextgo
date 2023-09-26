<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\MailRegister;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
    public function test()
    {
        $url = Storage::disk('google')->getVisibility('test1.txt');

//        dd($url);
        return view('test',compact('url'));
    }
    public function index()
    {
        $name = 'John Doe';
        $phone = '081234567890';
        $email = 'binhvt12003@gmail.com';
        Mail::to($email)->send(new MailRegister($email,$name,$phone,rand(100000,999999)));
        dd('Mail Send Successfully');
    }

    public function create()
    {
        $googleDisk = Storage::disk('google')->put('test2.txt', 'Hello World');
        dd($googleDisk,'test');
    }
    public function list()
    {
        $googleDisk = collect(Storage::disk('google')->listContents('/', true));
        return $googleDisk;
    }

    public function upload(Request $request)
    {
        $dir = '/';
        $file = $request->file('image');
        $filename = $file->getClientOriginalName();
        $googleDisk = Storage::disk('google')->putFileAs($dir, $file, $filename);
        dd($googleDisk);
    }
}
