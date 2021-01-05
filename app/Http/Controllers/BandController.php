<?php

namespace App\Http\Controllers;

use App\Models\Band;
use Illuminate\Http\Request;

class BandController extends Controller
{
    public function index(){
        /*$key = $this->fetchBands();
        echo $key[0]->filesUploadeds[1]->file_name;
        die();*/
        $data = array (
            'raw' => $this->fetchBands(),
        );
        return view('band')->with($data);
    }

    public function fetchBands(){
        return Band::with('filesUploadeds','city')->get();
    }
}
