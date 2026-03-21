<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class PwaController extends Controller
{
    public function app()
    {
        return response()->file(public_path('pwa/index.html'));
    }
}
