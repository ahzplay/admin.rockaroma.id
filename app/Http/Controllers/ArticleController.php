<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request) {
        $request->session()->put('menu-active-dashboard', '');
        $request->session()->put('menu-active-article', 'active');
        $request->session()->put('menu-active-video', '');
        $request->session()->put('menu-active-shop', '');
        $request->session()->put('menu-active-band', '');

        //$categories = $this->fetchCategoriesForDropdown();
        $data = array(
            'categories' => '$categories'
        );

        return view ('article')->with($data);
    }

    public function articleAdd(Request $request)
    {
        $request->session()->put('menu-active-dashboard', '');
        $request->session()->put('menu-active-article', '');
        $request->session()->put('menu-active-video', '');
        $request->session()->put('menu-active-shop', 'active');
        $request->session()->put('menu-active-band', '');

        //$categories = $this->fetchCategoriesForDropdown();
        $data = array(
            'categories' => '$categories'
        );

        return view('articleAdd');
    }
}
