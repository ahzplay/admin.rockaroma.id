<?php

namespace App\Http\Controllers;

use App\Models\Band;
use Illuminate\Http\Request;

class BandController extends Controller
{
    public function index(Request $request){
        $request->session()->put('menu-active-dashboard', '');
        $request->session()->put('menu-active-article', '');
        $request->session()->put('menu-active-video', '');
        $request->session()->put('menu-active-shop', '');
        $request->session()->put('menu-active-band', 'active');
        $request->session()->put('menu-active-member', '');

        $data = array (
            'raw' => $this->fetchBands(),
        );
        return view('band')->with($data);
    }

    public function fetchBands(){
        return Band::with('filesUploadeds','city')->get();
    }
}
