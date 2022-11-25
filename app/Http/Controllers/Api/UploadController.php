<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class UploadController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param String $fileName
     * @return \Illuminate\Http\Response
     */
    public function index($fileName)
    {
        if (!(Storage::exists('image/' . $fileName))) {
            return $this->errorNotFound("image not found");
        }

        $image = Storage::get('image/' . $fileName);
        $path = Storage::path('image/' . $fileName);
        $mimeType = File::mimeType($path);
        return response($image)->header('content-type', $mimeType);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            'file' => ['required', 'file', 'mimes:jpg,png'],
            'note' => ['string'],
        ]);
        if ($validator->fails()) {
            return $this->errorValidationResponse("validation failed", $validator->errors());
        }

        try {
            $path = $request->file('file')->store('image');
        } catch (Exception $e) {
            return $this->errorValidationResponse("store file error", $e);
        }

        $response = [
            "image-path" => '/api/' . $path,
        ];

        return $this->successResponse("file uploaded", $response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
        //
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
