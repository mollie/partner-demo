<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     * @return Renderable
     */
    public function __invoke()
    {
        return view('home.index');
    }
}
