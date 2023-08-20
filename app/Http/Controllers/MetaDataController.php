<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MetaDataController extends Controller
{

    public function index() {
        return inertia("MetaData/Index", [
            'meta_data' => auth()->user()->meta_data
        ]);
    }
}
