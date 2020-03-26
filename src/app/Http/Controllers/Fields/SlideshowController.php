<?php
namespace Bedoz\VisualEditorForBackpack\app\Http\Controllers\Fields;

use Illuminate\Routing\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class SlideshowController extends Controller {

    function saveImage(Request $request) {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return Response::json([
                'error' => 'wrong values',
                'error_description' => 'One or more fields has wrong values, please check it before send request.',
                'fields' => $errors->all()
            ], 400);
        }

        if ($request->file("file")->isValid()) {
            $file = $request->file("file");

            $filename = $file->getClientOriginalName();
            $disk = "public";
            $destination_path = "VisualEditor";

            $mime = $file->getClientMimeType();
            switch ($mime) {
                case "image/png": $extension = "png"; break;
                case "image/jpg": $extension = "jpg"; break;
                case "image/jpeg": $extension = "jpg"; break;
                case "image/svg+xml": $extension = "svg"; break;
            }

            $filename = md5($filename.time()).'.'.$extension;

            $file_path = $file->storeAs($destination_path, $filename, $disk);

            return \response()->json([
                'image' => $file_path,
                'sizes' => new \stdClass()
            ]);
        }

        return false;
    }

    function saveCrop(Request $request) {
        $validator = Validator::make($request->all(), [
            'croppedImage' => 'required|image',
            'filename' => 'required|string',
            'size' => 'required|string',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return Response::json([
                'error' => 'wrong values',
                'error_description' => 'One or more fields has wrong values, please check it before send request.',
                'fields' => $errors->all()
            ], 400);
        }
        $attribute_name = "croppedImage";
        $disk = "public";
        $filename = basename($request->input("filename"));
        $destination_path = dirname($request->input("filename"));
        $size = $request->input("size");
        $filename = $size."_".$filename;
        if (\Storage::disk($disk)->exists($destination_path."/".$filename)) {
            \Storage::disk($disk)->delete($destination_path."/".$filename);
        }

        if ($request->file($attribute_name)->isValid()) {
            $file = $request->file($attribute_name);
            return $file->storeAs($destination_path, $filename, $disk);
        }
        return false;
    }

    function deleteCrop(Request $request) {
        $validator = Validator::make($request->all(), [
            'image' => 'required|string',
            'delete' => 'required|string',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return Response::json([
                'error' => 'wrong values',
                'error_description' => 'One or more fields has wrong values, please check it before send request.',
                'fields' => $errors->all()
            ], 400);
        }

        $disk = "public";
        $filename = basename($request->input("image"));
        $destination_path = dirname($request->input("image"));
        $size = $request->input("delete");
        $filename = $size."_".$filename;
        if (\Storage::disk($disk)->exists($destination_path."/".$filename)) {
            \Storage::disk($disk)->delete($destination_path."/".$filename);
            return true;
        }
        return false;
    }

    function deleteImage(Request $request) {
        $validator = Validator::make($request->all(), [
            'image' => 'required|string',
            'delete' => 'array',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return Response::json([
                'error' => 'wrong values',
                'error_description' => 'One or more fields has wrong values, please check it before send request.',
                'fields' => $errors->all()
            ], 400);
        }

        $disk = "public";
        $filename = basename($request->input("image"));
        $destination_path = dirname($request->input("image"));
        $sizes = $request->input("delete");
        if ($sizes) {
            foreach ($sizes as $size) {
                $cropFile = $size."_".$filename;
                if (\Storage::disk($disk)->exists($destination_path."/".$cropFile)) {
                    \Storage::disk($disk)->delete($destination_path."/".$cropFile);
                }
            }
        }
        if (\Storage::disk($disk)->exists($destination_path."/".$filename)) {
            \Storage::disk($disk)->delete($destination_path."/".$filename);
        }
    }
}