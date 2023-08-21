<?php

namespace App\Http\Controllers;

use App\Http\Resources\LlmFunctionResource;
use App\Http\Resources\MetaDataResource;
use App\Models\LlmFunction;
use Illuminate\Http\Request;

class LlmFunctionController extends Controller
{

    public function index() {
        return inertia('LlmFunctions/Index', [
            'llm_functions' => LlmFunctionResource::collection(LlmFunction::orderBy('label')->get()),
        ]);
    }
}
