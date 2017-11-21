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
        return MainPage::all();
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
                        'error' => ['code' => 500, 'message' => 'File does not exist.']
                    ]);
        }

        $dir = url('/').'/img';
        $img_name = $request->photo->getClientOriginalName();
        $img_size = $request->photo->getClientSize();
        $img_path = $dir.'/'.$img_name;
        $order = MainPage::count() + 1;

        Flysystem::put(
            $img_name,
            file_get_contents($request->photo)
        );

        MainPage::create([
            'filename' => $img_name,
            'filesize' => $img_size,
            'path' => $img_path,
            'order' => $order
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

        MainPage::where('id', $id)
                ->update([
                    'filename' => $request->x
                ]);

        return response()->json(['success' => true]);

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
