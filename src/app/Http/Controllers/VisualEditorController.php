<?php
namespace Bedoz\VisualEditorForBackpack\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class VisualEditorController extends Controller {
    function preview(Request $request) {
        $request->validate([
            'data' => 'required|json'
        ]);
        $data = json_decode($request->input('data'), true);
        foreach ($data as $id => $value) {
            $class = explode("_", $id);
            $id = array_pop($class);
            $class = implode("\\", $class);
            //dd($id, $class);
            echo $class::renderFrontend($value)->render();
        }
    }
}