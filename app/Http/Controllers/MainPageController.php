<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MainPage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use GrahamCampbell\Flysystem\Facades\Flysystem;

class MainPageController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MainPage::orderBy('order')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (!$request->hasFile('photo')) {
            return response()
                    ->json([
                        'success' => false,
                        'error' => ['message' => 'File does not exist.']
                    ], 404);
        }

        $path = url('/').'/img/';
        $img_name = $request->photo->getClientOriginalName();
        $img_size = $request->photo->getClientSize();
        $order = MainPage::count() + 1;
        $title = $request->title;
        $describe = $request->describe;

        /* ---存檔--- */
        Flysystem::put(
            $img_name,
            file_get_contents($request->photo)
        );

        /* ---資料庫建檔--- */
        MainPage::create([
            'filename' => $img_name,
            'filesize' => $img_size,
            'path' => $path,
            'order' => $order,
            'title' => $title,
            'describe' => $describe
        ]);

        return response()->json(['success' => true]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        try {
            $MainPage = MainPage::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false], 500);
        }

        return $MainPage;

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        /* ---檢查圖片Info有沒有送來--- */
        if (!$request->has(['filename','title', 'describe'])) {
            return response()
                    ->json([
                        'success' => false,
                        'error' => ['message' => 'Column keys do not exist.']
                    ], 404);
        }

        $img_name = $request->filename;
        $title = $request->title;
        $describe = $request->describe;

        MainPage::where('id', $id)
                ->update([
                    'filename' => $img_name,
                    'title' => $title,
                    'describe' => $describe
                ]);

        return response()->json(['success' => true]);

    }

    /* ---換圖片--- */
    public function switch(Request $request) {

        if ($request->hasFile('photo')) {   //檢查有沒有上傳新照片
            $img_name = $request->photo->getClientOriginalName();
            $img_size = $request->photo->getClientSize();
        } else {
            // failed
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
