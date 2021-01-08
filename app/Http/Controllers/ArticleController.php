<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Support\Facades\File;
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
        $request->session()->put('menu-active-article', 'article');
        $request->session()->put('menu-active-video', '');
        $request->session()->put('menu-active-shop', '');
        $request->session()->put('menu-active-band', '');

        //$categories = $this->fetchCategoriesForDropdown();
        $data = array(
            'categories' => '$categories'
        );

        return view('articleAdd');
    }

    public function articleEdit(Request $request) {
        $request->session()->put('menu-active-dashboard', '');
        $request->session()->put('menu-active-article', 'article');
        $request->session()->put('menu-active-video', '');
        $request->session()->put('menu-active-shop', '');
        $request->session()->put('menu-active-band', '');

        $content = $this->get($request->articleId);
        $data = array(
            'content' => $content
        );

        return view('articleEdit')->with($data);
    }

    public function update(Request $request) {
        $article = Article::find($request->articleId);
        $article->title = $request->title;
        $article->content = $request->contentOut;

        /*File::delete('storage/articles/'.$image_path);
        File::delete('storage/articles/'.$thumb_path);
        File::delete('storage/articles/'.$gallery_1_path);
        File::delete('storage/articles/'.$gallery_2_path);
        File::delete('storage/articles/'.$gallery_3_path);
        File::delete('storage/articles/'.$gallery_4_path);
        File::delete('storage/articles/'.$gallery_5_path);*/

        if($request->file('thumbFile')) {
            $thumb_path = explode('articles/', $article->thumb_path);
            $thumb_path = $thumb_path[1];
            File::delete('storage/articles/'.$thumb_path);

            $thumbFile      = $request->file('thumbFile');
            $thumbFileName   = md5(time() . rand(100, 999) ) . '.' . $thumbFile->getClientOriginalExtension();
            $request->file('thumbFile')->move('storage/articles', $thumbFileName);
            $article->thumb_path = env('APP_URL').'storage/articles/'.$thumbFileName;
        }

        if($request->file('bannerFile')) {
            $image_path = explode('articles/', $article->image_path);
            $image_path = $image_path[1];
            File::delete('storage/articles/'.$image_path);

            $bannerFile      = $request->file('bannerFile');
            $bannerFileName   = md5(time() . rand(100, 999) ) . '.' . $bannerFile->getClientOriginalExtension();
            $request->file('bannerFile')->move('storage/articles', $bannerFileName);
            $article->image_path = env('APP_URL').'storage/articles/'.$bannerFileName;
        }

        if($request->file('gallery1File')) {
            $gallery1Path = explode('articles/', $article->gallery_1_path);
            $gallery1Path = $gallery1Path[1];
            File::delete('storage/articles/'.$gallery1Path);

            $gallery1File      = $request->file('gallery1File');
            $gallery1FileName   = md5(time() . rand(100, 999) ) . '.' . $gallery1File->getClientOriginalExtension();
            $request->file('gallery1File')->move('storage/articles', $gallery1FileName);
            $article->gallery_1_path = env('APP_URL').'storage/articles/'.$gallery1FileName;
        }

        if($request->file('gallery2File')) {
            $gallery2Path = explode('articles/', $article->gallery_2_path);
            $gallery2Path = $gallery2Path[1];
            File::delete('storage/articles/'.$gallery2Path);

            $gallery2File      = $request->file('gallery2File');
            $gallery2FileName   = md5(time() . rand(100, 999) ) . '.' . $gallery2File->getClientOriginalExtension();
            $request->file('gallery2File')->move('storage/articles', $gallery2FileName);
            $article->gallery_2_path = env('APP_URL').'storage/articles/'.$gallery2FileName;
        }

        if($request->file('gallery3File')) {
            $gallery3Path = explode('articles/', $article->gallery_3_path);
            $gallery3Path = $gallery3Path[1];
            File::delete('storage/articles/'.$gallery3Path);

            $gallery3File      = $request->file('gallery3File');
            $gallery3FileName   = md5(time() . rand(100, 999) ) . '.' . $gallery3File->getClientOriginalExtension();
            $request->file('gallery3File')->move('storage/articles', $gallery3FileName);
            $article->gallery_3_path = env('APP_URL').'storage/articles/'.$gallery3FileName;
        }

        if($request->file('gallery4File')) {
            $gallery4Path = explode('articles/', $article->gallery_4_path);
            $gallery4Path = $gallery4Path[1];
            File::delete('storage/articles/'.$gallery4Path);

            $gallery4File      = $request->file('gallery4File');
            $gallery4FileName   = md5(time() . rand(100, 999) ) . '.' . $gallery4File->getClientOriginalExtension();
            $request->file('gallery4File')->move('storage/articles', $gallery4FileName);
            $article->gallery_4_path = env('APP_URL').'storage/articles/'.$gallery4FileName;
        }

        if($request->file('gallery5File')) {
            $gallery5Path = explode('articles/', $article->gallery_5_path);
            $gallery5Path = $gallery5Path[1];
            File::delete('storage/articles/'.$gallery5Path);

            $gallery5File      = $request->file('gallery5File');
            $gallery5FileName   = md5(time() . rand(100, 999) ) . '.' . $gallery5File->getClientOriginalExtension();
            $request->file('gallery5File')->move('storage/articles', $gallery5FileName);
            $article->gallery_5_path = env('APP_URL').'storage/articles/'.$gallery5FileName;
        }

        $doUpdate = $article->save();

        if($doUpdate) {
            return response()->json(array(
                'status' => 'success',
                'message' => 'Article Updated'
            ));
        } else {
            return response()->json(array(
                'status' => 'fail',
                'message' => 'Cannot update article, please contact System Admin.'
            ));
        }
    }

    public function fetch(Request $request) {
        $keyword = $_GET['search']['value'];
        $recordsTotal = Article::count();
        $recordsFiltered = Article::where('title', 'like', '%' .  $keyword . '%')->count();
        $raw = Article::where('title', 'like', '%' .  $keyword . '%')->orderBy('created_at', 'DESC')->skip($_GET['start'])->take($_GET['length'])->get();
        //return response()->json($raw); die();
        $data=array();
        foreach ($raw as $val) {
            $dataTemp = array(
                'id' => $val->id,
                'title' => $val->title,
                'createdAt' => date('j F Y', strtotime($val->created_at)),
            );
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

    public function get($id) {
        $raw = Article::where('id', $id)->first();
        return $raw;
    }

    public function save(Request $request) {
        $thumbFile      = $request->file('thumbFile');
        $thumbFileName   = md5(time() . rand(100, 999) ) . '.' . $thumbFile->getClientOriginalExtension();
        $thumbFiledoStore = $request->file('thumbFile')->move('storage/articles', $thumbFileName);

        $bannerFile      = $request->file('bannerFile');
        $bannerFileName   = md5(time() . rand(100, 999) ) . '.' . $bannerFile->getClientOriginalExtension();
        $bannerFiledoStore = $request->file('bannerFile')->move('storage/articles', $bannerFileName);

        $gallery1File      = $request->file('gallery1File');
        $gallery1FileName   = md5(time() . rand(100, 999) ) . '.' . $gallery1File->getClientOriginalExtension();
        $gallery1FiledoStore = $request->file('gallery1File')->move('storage/articles', $gallery1FileName);

        $gallery2File      = $request->file('gallery2File');
        $gallery2FileName   = md5(time() . rand(100, 999) ) . '.' . $gallery2File->getClientOriginalExtension();
        $gallery2FiledoStore = $request->file('gallery2File')->move('storage/articles', $gallery2FileName);

        $gallery3File      = $request->file('gallery3File');
        $gallery3FileName   = md5(time() . rand(100, 999) ) . '.' . $gallery3File->getClientOriginalExtension();
        $gallery3FiledoStore = $request->file('gallery3File')->move('storage/articles', $gallery3FileName);

        $gallery4File    = $request->file('gallery4File');
        $gallery4FileName   = md5(time() . rand(100, 999) ) . '.' . $gallery4File->getClientOriginalExtension();
        $gallery4FiledoStore = $request->file('gallery4File')->move('storage/articles', $gallery4FileName);

        $gallery5File      = $request->file('gallery5File');
        $gallery5FileName   = md5(time() . rand(100, 999) ) . '.' . $gallery5File->getClientOriginalExtension();
        $gallery5FiledoStore = $request->file('gallery5File')->move('storage/articles', $gallery5FileName);

        $article = new Article();
        $article->is_active = 1;
        $article->image_path = env('APP_URL').'storage/articles/'.$bannerFileName;
        $article->thumb_path = env('APP_URL').'storage/articles/'.$thumbFileName;
        $article->gallery_1_path = env('APP_URL').'storage/articles/'.$gallery1FileName;
        $article->gallery_2_path = env('APP_URL').'storage/articles/'.$gallery2FileName;
        $article->gallery_3_path = env('APP_URL').'storage/articles/'.$gallery3FileName;
        $article->gallery_4_path = env('APP_URL').'storage/articles/'.$gallery4FileName;
        $article->gallery_5_path = env('APP_URL').'storage/articles/'.$gallery5FileName;
        $article->title = $request->title;
        $article->content = $request->contentOut;
        $article->updated_at = date('Y-m-d H:i:s');
        $article->save();

        if($article->id > 0) {
            return response()->json(array(
                'status' => 'success',
                'message' => 'Article Published'
            ));
        } else {
            return response()->json(array(
                'status' => 'fail',
                'message' => 'Cannot publish article, please contact System Admin.'
            ));
        }
    }

    public function destroy(Request $request) {
        $data = Article::where('id', $request->id)->first();
        $image_path = explode('articles/', $data->image_path);
        $image_path = $image_path[1];
        $thumb_path = explode('articles/', $data->thumb_path);
        $thumb_path = $thumb_path[1];
        $gallery_1_path = explode('articles/', $data->gallery_1_path);
        $gallery_1_path = $gallery_1_path[1];
        $gallery_2_path = explode('articles/', $data->gallery_2_path);
        $gallery_2_path = $gallery_2_path[1];
        $gallery_3_path = explode('articles/', $data->gallery_3_path);
        $gallery_3_path = $gallery_3_path[1];
        $gallery_4_path = explode('articles/', $data->gallery_4_path);
        $gallery_4_path = $gallery_4_path[1];
        $gallery_5_path = explode('articles/', $data->gallery_5_path);
        $gallery_5_path = $gallery_5_path[1];

        File::delete('storage/articles/'.$image_path);
        File::delete('storage/articles/'.$thumb_path);
        File::delete('storage/articles/'.$gallery_1_path);
        File::delete('storage/articles/'.$gallery_2_path);
        File::delete('storage/articles/'.$gallery_3_path);
        File::delete('storage/articles/'.$gallery_4_path);
        File::delete('storage/articles/'.$gallery_5_path);

        $doDestroy = Article::where('id', $request->id)->delete();

        if($doDestroy) {
            return response()->json(array(
                'status' => 'success',
                'message' => 'Article Deleted'
            ));
        } else {
            return response()->json(array(
                'status' => 'fail',
                'message' => 'Cannot delete article, please contact System Admin.'
            ));
        }

        /*if(File::exists('storage/articles/'.$image_path)) {
            File::delete('storage/articles/0e230f7cc2c8c0cdb0821f7062c3b1da.jpg');
        } else {
            File::delete('storage/articles/0e230f7cc2c8c0cdb0821f7062c3b1da.jpg');
        }*/

    }
}
