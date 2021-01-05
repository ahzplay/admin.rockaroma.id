<?php

namespace App\Http\Controllers;

use App\Models\DashboardSlider;
use Illuminate\Http\Request;
use Cloudder;

class DashboardController extends Controller
{
    public function index() {
        $data = array (
            'raw' => $this->fetchSliders(),
        );
        return view('dashboard')->with($data);
    }

    public function fetchSliders() {
        return DashboardSlider::get();
    }

    public function updateStatus(Request $request) {
        $slider = DashboardSlider::find($request->id);
        $slider->is_active = $request->status;
        $doUpdate = $slider->save();

        if($doUpdate) {
            return response()->json(array(
                'status' => 'success',
                'message' => 'Video Slider'
            ));
        } else {
            return response()->json(array(
                'status' => 'fail',
                'message' => 'Cannot update slider, please contact System Admin.'
            ));
        }
    }

    public function getSlider(Request $request) {
        $raw = DashboardSlider::where('id', $request->id)->get();
        return response()->json($raw);
    }

    public function update(Request $request) {
        //echo json_encode($request->all()); die();
        $slider = DashboardSlider::find($request->id);
        $slider->title = $request->sliderTitle;
        if($request->file('imageFile')) {
            Cloudder::destroy($slider->public_id, $options=array());
            Cloudder::upload($request->file('imageFile'), null ,
                array(
                    'folder' => 'dashboard-slider/',
                    'transformation' =>
                        array(
                            'width'=>1280,
                            'height'=>720,
                            'crop'=>'scale',
                        )
                )
            );
            $response = Cloudder::getResult();
            $slider->public_id = $response['public_id'];
            $slider->secure_url = $response['secure_url'];
        }
        $doUpdate = $slider->save();

        if($doUpdate) {
            return response()->json(array(
                'status' => 'success',
                'message' => 'Slider Updated'
            ));
        } else {
            return response()->json(array(
                'status' => 'fail',
                'message' => 'Cannot update slider, please contact System Admin.'
            ));
        }

    }
}
