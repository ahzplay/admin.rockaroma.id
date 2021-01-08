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

    public function save(Request $request) {
        /*$images = array(
            array(
               'file' => $request->file('thumbFile'),
               'name' => md5(time() . rand(1000,9999)) . '.' . $request->file('thumbFile')->getClientOriginalExtension()
            ),
            array(
                'file' => $request->file('bannerFile'),
                'name' => md5(time() . rand(1000,9999)) . '.' . $request->file('bannerFile')->getClientOriginalExtension()
            ),
            array(
                'file' => $request->file('gallery1File'),
                'name' => md5(time() . rand(1000,9999)) . '.' . $request->file('gallery1File')->getClientOriginalExtension()
            ),
            array(
                'file' => $request->file('gallery2File'),
                'name' => md5(time() . rand(1000,9999)) . '.' . $request->file('gallery2File')->getClientOriginalExtension()
            ),
            array(
                'file' => $request->file('gallery3File'),
                'name' => md5(time() . rand(1000,9999)) . '.' . $request->file('gallery3File')->getClientOriginalExtension()
            ),
            array(
                'file' => $request->file('gallery4File'),
                'name' => md5(time() . rand(1000,9999)) . '.' . $request->file('gallery4File')->getClientOriginalExtension()
            ),
            array(
                'file' => $request->file('gallery5File'),
                'name' => md5(time() . rand(1000,9999)) . '.' . $request->file('gallery5File')->getClientOriginalExtension()
            ),
        );

        foreach($images as $val) {
            $val['file']->storeAs('articles', $val['name'], 'public');
            //$val['file']->move('storage/articles', $val['name']);

        }

        die();*/
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
        echo Article::where('id', $request->id)->get();
        die();
        if(File::exists('storage/articles/0e230f7cc2c8c0cdb0821f7062c3b1da.jpg')) {
            File::delete('storage/articles/0e230f7cc2c8c0cdb0821f7062c3b1da.jpg');
        }

    }
}
