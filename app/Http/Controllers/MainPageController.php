<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MainPage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use GrahamCampbell\Flysystem\Facades\Flysystem;
use Intervention\Image\Facades\Image;


class MainPageController extends Controller
{

    private $img_url, $img_src;

    function __construct() {

        $this->img_url = url('/').'/api/mainpages/src/';
        $this->img_src = 'img/MainPage/';

    }
    
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
            return response()->json(['success' => false, 'message' => 'File does not exist.'], 404);
        }

        $img_name = $request->photo->getClientOriginalName();
        $img_size = $request->photo->getClientSize();
        $order = $this->getOrderNumber();
        $newID = MainPage::withTrashed()->count() + 1;
        $title = $request->title;
        $describe = $request->describe;

        /* ---資料庫建檔--- */
        MainPage::create([
            'filename' => $img_name,
            'filesize' => $img_size,
            'path' => $this->img_url.$newID,
            'order' => $order,
            'title' => $title,
            'describe' => $describe
        ]);

        /* ---存檔--- */
        Flysystem::put(
            $this->img_src.$img_name,
            file_get_contents($request->photo)
        );

        return response()->json(['success' => true]);

    }

    /**
     * 取得最新的排序.
     */
    private function getOrderNumber() {
        $query = MainPage::select('order')->orderBy('order', 'desc')->first();
        if (!is_object($query)) {
            return 1;
        }
        return $query->order + 1;
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
            return response()
                ->json(['success' => false, 'message' => 'Column keys do not exist.'], 404);
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
        if (!$request->has(['filename','title', 'describe', 'visible'])) {
            return response()->json(['success' => false,'message' => 'Column keys do not exist.'], 404);
        }

        $img_name = $request->filename;
        $title = $request->title;
        $describe = $request->describe;
        $visible = $request->visible;

        MainPage::where('id', $id)
                ->update([
                    'filename' => $img_name,
                    'title' => $title,
                    'describe' => $describe,
                    'visible' => $visible
                ]);

        return response()->json(['success' => true]);

    }

    /**
     * 換圖片
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function switch(Request $request, $id) {

        if (!$request->hasFile('photo')) {
            return response()->json(['success' => false,'message' => 'File does not exist.'], 404);
        }

        $img_name = $request->photo->getClientOriginalName();
        $img_size = $request->photo->getClientSize();

        /* ---存檔--- */
        Flysystem::put(
            $this->img_src.$img_name,
            file_get_contents($request->photo)
        );

        /* ---更新資料--- */
        MainPage::where('id', $id)
                ->update([
                    'filename' => $img_name,
                    'filesize' => $img_size
                ]);

        return response()->json(['success' => true]);

    }

    /**
     * 取得圖片資源
     *
     * @param  int  $id
     */
    public function getImage($id) {
        
        $query = MainPage::select('filename')->where('id', $id)->first();

        if (is_object($query)) {
            $path = storage_path('files/'.$this->img_src.$query->filename);
            if (file_exists($path)) {
                return Image::make($path)->response();
            }
        }

        return response()->json(['success' => false, 'message' => 'Image not exist.'], 404);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /* ---取得原始檔名--- */
        $query = MainPage::select('filename')->where('id', $id)->first();

        if (!is_object($query)) {
            return response()->json(['success' => false, 'message' => 'Non object.'], 404);
        } else if (!Flysystem::has($this->img_src.$query->filename)) {
            return response()->json(['success' => false, 'message' => 'file not exists.'], 404);
        } else {
            /* ---刪除資料--- */
            MainPage::where('id', $id)->delete();

            /* ---丟進垃圾桶--- */
            Flysystem::rename(
                $this->img_src.$query->filename, 
                'trash/'.$query->filename
            );
        }

        return response()->json(['success' => true]);

    }

}
