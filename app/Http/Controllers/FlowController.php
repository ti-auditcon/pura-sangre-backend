<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FlowController extends Controller
{
        public function showReturn()
    {
        return view('web.flow.return');
    }

    public function showError()
    {
        return view('web.flow.error');
    }
}
