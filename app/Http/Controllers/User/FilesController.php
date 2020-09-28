<?php

namespace App\Http\Controllers\User;

use App\Models\File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;



class FilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $files = File::whereUserId(Auth::id())->latest()->get();
        return view('user.files.index', compact('files'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { 
        $file = File::whereCodeName($id)->firstOrFail();
        $user_id = Auth::id();

        if ($file->user_id == $user_id){
            return redirect('/storage' . '/' . $user_id . '/' . $file->code_name);

        }else {
            
            abort(403);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $max_size = (int)ini_get('upload_max_filesize') * 10240;

        $files = $request->file('files');
        $user_id = Auth::id();


        if ($request->hasFile('files')) {
            foreach ($files as $file) {
                $fileName = encrypt($file->getClientOriginalName())
                  . '.' . $file->getClientOriginalExtension();
                if (Storage::putFileAs('/public/' . $user_id . '/', $file, $fileName)) {
                    File::create([
                  'name' => $file->getClientOriginalName(),
                  'code_name' => $fileName,
                  'user_id' => $user_id
            ]);
                }
            }
            Alert::success('Dosya Yüklendi', 'Yükleme Başarılı');
            return back();
        } else {
            Alert::error('Dosya Yüklenemedi', 'Herhangi Bir Dosya Seçilmedi');
            return back();
        }
    }

    public function destroy(Request $request, $id){
       $file = File::whereCodeName($id)->firstOrFail();
       unlink(public_path('storage' . '/' . Auth::id() . '/' . $file->code_name));

       $file->delete();
       Alert::info('Dikkat Silindi !', 'Dosyadan Kaldırıldı');
       return back();
    }
}