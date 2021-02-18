<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Video;
use Illuminate\Http\Request;
use Cloudder;

class VideoController extends Controller
{
    public function index(Request $request){
        $request->session()->put('menu-active-dashboard', '');
        $request->session()->put('menu-active-article', '');
        $request->session()->put('menu-active-video', 'active');
        $request->session()->put('menu-active-shop', '');
        $request->session()->put('menu-active-band', '');
        $request->session()->put('menu-active-member', '');

        $videos = $this->fetchVideos($request->page, $request->startDate, $request->endDate);
        $data = array(
            'raw' => $videos['raw'],
            'row' => $videos['row'],
            'page' => $videos['page'],
            'categories' => $this->fetchCategoriesForDropdown(),
        );

        return view ('video')->with($data);
    }

    public function fetchVideos($page, $startDate, $endDate){
        $page = $page;
        $rows = 10;
        $offset = ($page-1)*$rows;
        $total = Video::whereBetween('date', [$startDate!=0?$startDate:'1970-12-12', $endDate!=0?$endDate:date('Y-m-d')])->count();
        $row = Video::
            whereBetween('date', [$startDate!=0?$startDate:'1970-12-12', $endDate!=0?$endDate:date('Y-m-d')])
            ->skip($offset)->take($rows)->count();
        $raw = Video::
            whereBetween('date', [$startDate!=0?$startDate:'1970-12-12', $endDate!=0?$endDate:date('Y-m-d')])
            ->skip($offset)->take($rows)->with('category')->orderBy('id', 'desc')->get();
        $page = ceil($total/$rows);
        return array(
            'row' => $row,
            'raw' => $raw,
            'page' => $page
        );
    }

    public function save(Request $request) {
        //echo json_encode($request->all()); die();
        Cloudder::upload($request->file('videoThumb'), null ,
            array(
                'folder' => 'video-thumbnail/',
                'transformation' =>
                    array(
                        'width'=>390,
                        'height'=>220,
                        'crop'=>'scale',
                    )
            )
        );
        $response = Cloudder::getResult();

        $video = new Video();
        $video->title = $request->videoTitle;
        $video->youtube_embeded = $request->youtubeEmbed;
        //$video->is_active = isset($request->videoPublish)?$request->videoPublish:0;
        $video->is_active = $request->videoPublish=='true'?1:0;
        $video->public_id = $response['public_id'];
        $video->secure_url = $response['secure_url'];
        $video->thumb_path = $response['secure_url'];
        $video->date = date('Y-m-d');
        $video->save();
        $video->category()->attach($request->categoryId);

        if($video->id > 0) {
            return response()->json(array(
                'status' => 'success',
                'message' => 'Video Uploaded'
            ));
        } else {
            return response()->json(array(
                'status' => 'fail',
                'message' => 'Cannot upload video, please contact System Admin.'
            ));
        }
    }

    public function getVideo(Request $request) {
        $raw = Video::where('id', $request->id)->with('category')->first();
        return response()->json($raw);
    }

    public function destroy(Request $request) {
        $video = Video::find($request->id);
        Cloudder::destroy($video->public_id, $options=array());
        $video->category()->detach();
        $video->delete();

        if($video) {
            return response()->json(array(
                'status' => 'success',
                'message' => 'Video Deleted'
            ));
        } else {
            return response()->json(array(
                'status' => 'fail',
                'message' => 'Cannot delete video, please contact System Admin.'
            ));
        }
    }

    public function update(Request $request) {
        //echo json_encode($request->all()); die();
        $video = Video::find($request->id);
        $video->title = $request->videoTitle;
        $video->youtube_embeded = $request->youtubeEmbed;
        $video->is_active = $request->videoPublish=='true'?1:0;
        if($request->file('videoThumb')) {
            Cloudder::destroy($video->public_id, $options=array());
            Cloudder::upload($request->file('videoThumb'), null ,
                array(
                    'folder' => 'video-thumbnail/',
                    'transformation' =>
                        array(
                            'width'=>390,
                            'height'=>220,
                            'crop'=>'scale',
                        )
                )
            );
            $response = Cloudder::getResult();
            $video->public_id = $response['public_id'];
            $video->secure_url = $response['secure_url'];
            $video->thumb_path = $response['secure_url'];
        }
        $doUpdate = $video->save();

        if($doUpdate) {
            return response()->json(array(
                'status' => 'success',
                'message' => 'Video Updated'
            ));
        } else {
            return response()->json(array(
                'status' => 'fail',
                'message' => 'Cannot update video, please contact System Admin.'
            ));
        }
    }

    public function fetchCategoriesForDropdown() {
        $data = Category::where('related_table','videos')->get();
        return $data;
    }

    public function fetchCategories(Request $request) {
        $keyword = $_GET['search']['value'];
        $recordsTotal = Category::count();
        $recordsFiltered = Category::count();
        $data = Category::where('related_table','videos')
            ->where(function($q) use ($keyword) {
                $q->where('name', 'like', '%' .  $keyword . '%')
                    ->orWhere('id', 'like', '%' .  $keyword . '%');
            })->get();


        $output = array(
            "draw" => $request->draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );

        if($request->responseWish == 'datatables') {
            $output = $output;
        } else if($request->responseWish == 'dropdown') {
            $output = $data;
        }

        return response()->json($output);
    }

    public function addVideoCategory(Request $request) {
        if(Category::where('related_table', 'videos')->count() >= 4) {
            return response()->json(array(
                'status' => 'fail',
                'message' => 'Cannot add category, you only allowed to add maximum of 4 categories'
            ));
        }

        $category = new Category();
        $category->related_table = 'videos';
        $category->name = $request->categoryName;
        $category->save();

        if($category->id > 0) {
            return response()->json(array(
                'status' => 'success',
                'message' => 'Category Added'
            ));
        } else {
            return response()->json(array(
                'status' => 'fail',
                'message' => 'Cannot add category, please contact System Admin.'
            ));
        }
    }

    public function destroyVideoCategory(Request $request) {
        $categoryId = $request->id;
        $related = Video::whereHas('category', function($q) use($categoryId){
            return $q->where('category_id', $categoryId);
        })->count();

        //echo json_encode(array($categoryId, $related)); die();
        if($related >= 1)
            return response()->json(array(
                'status' => 'fail',
                'message' => 'Cannot remove category, There are products that have a relationship with this category'
            ));

        $doDestroy = Category::where('id', $request->id)->delete();

        if($doDestroy > 0) {
            return response()->json(array(
                'status' => 'success',
                'message' => 'Category Deleted'
            ));
        } else {
            return response()->json(array(
                'status' => 'fail',
                'message' => 'Cannot delete category, please contact System Admin.'
            ));
        }
    }

}
