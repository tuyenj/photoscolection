<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditPhoto;
use App\Http\Requests\StorePhoto;
use App\Photo;
use Auth;
use DB;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Storage;
use Str;

class PhotoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index','show']);
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        $photo = Photo::with(['owner'])->orderByDesc('created_at');
        return \response()->json($photo->paginate());
    }

    /**
     * @param StorePhoto $request
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function create(StorePhoto $request)
    {
        $extension = $request->file('image')->extension();

        $photo = new Photo();
        $image = $request->file('image');
        $photo->filename = Str::uuid()->toString() . '.' . $extension;

        DB::beginTransaction();
        try {
            Auth::user()->photos()->save($photo);
            $image->storeAs('/', $photo->filename, ['disk' => 'azure']);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            Storage::cloud()->delete($photo->filename);
            throw $exception;
        }
        return response($photo, 201);
    }

    public function update(EditPhoto $request, $id)
    {
        $photo = Photo::findOrFail($id);

        $image = $request->file('image');

        DB::beginTransaction();
        try {
            if (!is_null($image)) {
                $photo->filename = Str::uuid()->toString() . '.' . $image->extension();
                $photo->save();

                // save image
                $image->storeAs('/', $photo->filename, ['disk' => 'azure']);
                DB::commit();
            }
        } catch (Exception|_Error $e) {
            DB::rollBack();
            $storage = Storage::cloud();
            if ($storage->exists($photo->filename)) {
                $storage->delete($photo->filename);
            }
            throw $e;
        }
        return \response()->json($photo, 200);
    }

    public function show($id)
    {
        $photo = Photo::findOrFail($id);
        return \response()->json([
            'image_path'=>$photo->image_path,
            'image_name'=>$photo->filename
        ]);
    }
}
