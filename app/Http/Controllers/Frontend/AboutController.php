<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class AboutController extends Controller
{
    /**
     * Halaman Tentang
     * File: resources/views/frontend/about/index.blade.php
     */
    public function index()
    {
        return view('frontend.about.index');
    }
}
