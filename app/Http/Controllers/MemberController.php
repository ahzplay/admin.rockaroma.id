<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request) {
        $request->session()->put('menu-active-dashboard', '');
        $request->session()->put('menu-active-article', '');
        $request->session()->put('menu-active-video', '');
        $request->session()->put('menu-active-shop', '');
        $request->session()->put('menu-active-band', '');
        $request->session()->put('menu-active-member', 'active');
        return View('member');
    }

    public function show(Request $request) {
        $keyword = $_GET['search']['value'];
        $recordsTotal = User::count();
        $recordsFiltered = User::with('profile', 'profile.province', 'profile.city')
            ->whereHas('role', function ($q) {
                $q->where('role_id',2);})
            ->whereHas('profile', function ($q) use ($keyword){
                $q->where('full_name', 'like', '%' .  $keyword . '%');
                $q->orWhere('phone_number', 'like', '%' .  $keyword . '%');
            })
            ->count();
        $raw = User::with('profile', 'profile.province', 'profile.city')
            ->whereHas('role', function ($q) {
                $q->where('role_id',2);})
            ->whereHas('profile', function ($q) use ($keyword){
                $q->where('full_name', 'like', '%' .  $keyword . '%');
                $q->orWhere('phone_number', 'like', '%' .  $keyword . '%');
            })
            ->skip($_GET['start'])->take($_GET['length'])->get();

        $data = array();
        foreach ($raw as $val) {
            $dataTemp['id'] = $val->id;
            $dataTemp['email'] = $val->email;
            $dataTemp['full_name'] = $val->profile->full_name;
            $dataTemp['phone_number'] = $val->profile->phone_number;
            $dataTemp['gender'] = $val->profile->gender;
            $dataTemp['gender_label'] = $val->profile->gender=='m'?'Male':'Female';
            $dataTemp['is_smoker'] = $val->profile->is_smoker;
            $dataTemp['is_smoker_label'] = $val->profile->is_smoker==1?'Smoker':'Non-Smoker';
            $dataTemp['city_name'] = $val->profile->city->name;
            $dataTemp['province_name'] = $val->profile->province->name;
            array_push($data, $dataTemp);
        }

        $output = array(
            "draw" => $request->draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );

        return $output;
    }
}
