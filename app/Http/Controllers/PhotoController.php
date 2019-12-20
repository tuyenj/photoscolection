<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhoto;
use App\Photo;
use Auth;
use DB;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Storage;

class PhotoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
        $photo->filename = \Str::uuid()->toString() . '.' . $extension;

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
}
