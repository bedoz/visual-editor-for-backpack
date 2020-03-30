<?php
namespace Bedoz\VisualEditorForBackpack\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class VisualEditorController extends Controller {
    function preview(Request $request) {
        $request->validate([
            'data' => 'required|json'
        ]);
        dd($request->input('data'));
    }
}