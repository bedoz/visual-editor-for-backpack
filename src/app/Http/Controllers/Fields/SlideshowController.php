<?php
namespace App\Http\Controllers\Fields;

use Illuminate\Routing\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class SlideshowController extends Controller {
    
    function gmapsRegion(Request $request) {
        Validator::extend('geo_nazione', function ($attribute, $value, $parameters, $validator) {
            return !is_null(\App\Models\World::getNazioneByISO2($value));
        });
        $validator = Validator::make($request->all(), [
            'nazione' => 'required|geo_nazione'
        ]);
        if ($validator->fails()) {
            return response($validator->errors()->get('nazione'), 400);
        }
        
        $regioni = \App\Models\World::getRegioni(Input::get('nazione'));
        
        return [
            'regioni' => $regioni
        ];
    }
    
    function gmapsProvince(Request $request) {
        Validator::extend('geo_regione', function ($attribute, $value, $parameters, $validator) {
            return !is_null(\App\Models\World::getRegioneByCode($value));
        });
        $validator = Validator::make($request->all(), [
            'regione' => 'required|geo_regione'
        ]);
        if ($validator->fails()) {
            return response($validator->errors()->get('regione'), 400);
        }
        
        $province = \App\Models\World::getProvince(Input::get('regione'));
        
        return [
            'province' => $province
        ];
    }
    
    function order(Request $request) {
        $validator = Validator::make($request->all(), [
            'model' => 'required|string',
            'id' => 'required|numeric',
            'field' => 'required|string',
            'value' => 'required|array',
        ]);
        
        if ($validator->fails()) {
            $errors = $validator->errors();
            return Response::json([
                'error' => 'wrong values',
                'error_description' => 'One or more fields has wrong values, please check it before send request.',
                'fields' => $errors->all()
            ], 400);
        }
        
        $model = $request->input("model");
        if (!class_exists($model)) {
            return Response::json([
                'error' => 'wrong values',
                'error_description' => 'Model doesn\'t exists',
            ], 400);
        }
        $model = $model::find($request->input("id"));
        if (is_null($model)) {
            return Response::json([
                'error' => 'wrong values',
                'error_description' => 'Content doesn\'t exists',
            ], 400);
        }
        if ($model->getAttributeValue($request->input("field")) == null) {
            return Response::json([
                'error' => 'wrong values',
                'error_description' => 'Property doesn\'t exists',
            ], 400);
        }
        $field = $request->input("field");
        $model->{$field} = $request->input("value");
        $model->save();
        return $model->{$field};
    }
    
    function crop(Request $request) {
        return $this->galleryOrder($request);
    }
    
    function saveImage(Request $request) {
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
        $disk = "uploads";
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
    
    function deleteImage(Request $request) {
        $validator = Validator::make($request->all(), [
            'model' => 'required|string',
            'id' => 'required|numeric',
            'field' => 'required|string',
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
        
        $model = $request->input("model");
        if (!class_exists($model)) {
            return Response::json([
                'error' => 'wrong values',
                'error_description' => 'Model doesn\'t exists',
            ], 400);
        }
        $model = $model::find($request->input("id"));
        if (is_null($model)) {
            return Response::json([
                'error' => 'wrong values',
                'error_description' => 'Content doesn\'t exists',
            ], 400);
        }
        if ($model->getAttributeValue($request->input("field")) == null) {
            return Response::json([
                'error' => 'wrong values',
                'error_description' => 'Property doesn\'t exists',
            ], 400);
        }
        
        $field = $request->input("field");
        $image = $request->input("image");
        $delete = $request->input("delete");
        $currentValue = $model->{$field};
        $value = array_where($model->{$field}, function($value, $key) use ($image){
            return $value['image'] == $image;
        });
        foreach ($value as $k => $v) {
            if (isset($currentValue[$k]['sizes'][$delete])) {
                unset($currentValue[$k]['sizes'][$delete]);
                unset($value[$k]['sizes'][$delete]);
            }
            if (count($currentValue[$k]['sizes']) == 0) {
                unset($currentValue[$k]['sizes']);
                unset($value[$k]['sizes']);
            }
        }
        $model->{$field} = $currentValue;
        $model->save();
        return json_encode(reset($value));
    }
}