<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Cloudder;

class VideoController extends Controller
{
    public function index(Request $request){
        $videos = $this->fetchVideos($request->page, $request->startDate, $request->endDate);
        $data = array(
            'raw' => $videos['raw'],
            'row' => $videos['row'],
            'page' => $videos['page']
        );

        return view ('video')->with($data);
    }

    public function fetchVideos($page, $startDate, $endDate){
        $page = intval($page);
        $rows = 10;//isset($param['rows']) ? intval($param['rows']) : 9;
        $offset = ($page-1)*10;
        $total = Video::where('is_active',1)->count();
        $row = Video::
            whereBetween('date', [$startDate!=0?$startDate:'1970-12-12', $endDate!=0?$endDate:date('Y-m-d')])
            ->skip($offset)->take($rows)->count();
        $raw = Video::
            whereBetween('date', [$startDate!=0?$startDate:'1970-12-12', $endDate!=0?$endDate:date('Y-m-d')])
            ->skip($offset)->take($rows)->get();
        $page = ceil($total/$rows);
        return array(
            'row' => $row,
            'raw' => $raw,
            'page' => $page
        );
    }

    public function save(Request $request) {
        /*dd($request->file('videoThumb'));
        die();*/
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
        $video->is_active = isset($request->videoPublish)?$request->videoPublish:0;
        $video->public_id = $response['public_id'];
        $video->secure_url = $response['secure_url'];
        $video->thumb_path = $response['secure_url'];
        $video->date = date('Y-m-d');
        $video->save();

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
        $raw = Video::where('id', $request->id)->get();
        return response()->json($raw);
    }

    public function destroy(Request $request) {
        Cloudder::destroy($request->publicId, $options=array());
        $destroy = Video::where('id', $request->id)->delete();

        if($destroy) {
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
        $video->is_active = isset($request->videoPublish)?$request->videoPublish:0;
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

}
