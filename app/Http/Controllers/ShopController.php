<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Video;
use http\Env\Response;
use Illuminate\Http\Request;
use Cloudder;

class ShopController extends Controller
{
    public function index(Request $request) {
        $request->session()->put('menu-active-dashboard', '');
        $request->session()->put('menu-active-article', '');
        $request->session()->put('menu-active-video', '');
        $request->session()->put('menu-active-shop', 'active');
        $request->session()->put('menu-active-band', '');
        $request->session()->put('menu-active-member', '');

        $categories = $this->fetchCategoriesForDropdown();
        $data = array(
            'categories' => $categories
        );

        return view ('shop')->with($data);
    }

    public function fetch(Request $request) {
        $keyword = $_GET['search']['value'];
        $status = isset($keyword)?strtolower($keyword)=='ready'?1:0:'';
        $recordsTotal = Product::count();
        $recordsFiltered = Product::count();
        $raw = Product::whereHas('category', function($q) use($keyword){
            return $q->where('name', 'like', '%' .  $keyword . '%');
            })
            ->orWhere('name', 'like', '%' .  $keyword . '%')
            ->orWhere('status', 'like', '%' .  $status . '%')
            ->with('category')->orderBy('id', 'desc')->get();
        //return response()->json($raw); die();
	//echo $raw[0]->id; die();
        $data=array();
        foreach ($raw as $val) {
	    $dataTemp = array(
                'id' => $val->id,
                'name' => $val->name,
                'status' => $val->status,
                'categoryId' => $val->category[0]->id,
                'categoryName' => $val->category[0]->name,
                'shopeeLink' => $val->shopee_url,
                'tokopediaLink' => $val->tokopedia_url,
                'publicId' => $val->public_id,
                'secureUrl' => $val->secure_url,
                'createdAt' => $val->created_at,
            );
            array_push($data, $dataTemp);
        }

	//die();
        $output = array(
            "draw" => $request->draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );

        return $output;
    }

    public function get(Request $request) {
        $data = Product::where('id', $request->id)->get();

        return response()->json($data);
    }

    public function save(Request $request) {
        //echo json_encode($request->all()); die();
        Cloudder::upload($request->file('productImage'), null ,
            array(
                'folder' => 'product-image/',
                'transformation' =>
                    array(
                        'width'=>195,
                        'height'=>244,
                        'crop'=>'scale',
                    )
            )
        );
        $response = Cloudder::getResult();

        $product = new Product();
        $product->name = $request->productName;
        $product->status = $request->status??0;
        $product->price = $request->price;
        $product->category_id = $request->categoryId;
        $product->tokopedia_url = $request->tokopediaLink??'#';
        $product->shopee_url = $request->shopeeLink??'#';
        $product->public_id = $response['public_id'];
        $product->secure_url = $response['secure_url'];
        $product->save();
	    $product->category()->attach($request->categoryId);

        if($product->id > 0) {
            return response()->json(array(
                'status' => 'success',
                'message' => 'Product Uploaded'
            ));
        } else {
            return response()->json(array(
                'status' => 'fail',
                'message' => 'Cannot add product, please contact System Admin.'
            ));
        }
    }

    public function update(Request $request) {
        //echo json_encode($request->all()); die();
        $product = Product::find($request->id);
        $product->name = $request->productName;
        $product->status = $request->status;
        $product->price = $request->price;
        $product->category_id = $request->categoryId;
        $product->tokopedia_url = $request->tokopediaLink;
        $product->shopee_url = $request->shopeeLink;
        if($request->file('productImage')) {
            Cloudder::destroy($product->public_id, $options=array());
            Cloudder::upload($request->file('productImage'), null ,
                array(
                    'folder' => 'product-image/',
                    'transformation' =>
                        array(
                            'width'=>195,
                            'height'=>244,
                            'crop'=>'scale',
                        )
                )
            );
            $response = Cloudder::getResult();
            $product->public_id = $response['public_id'];
            $product->secure_url = $response['secure_url'];
        }
        $product->category()->sync($request->categoryId);
        $doUpdate = $product->save();

        if($doUpdate) {
            return response()->json(array(
                'status' => 'success',
                'message' => 'Product Updated'
            ));
        } else {
            return response()->json(array(
                'status' => 'fail',
                'message' => 'Cannot update product, please contact System Admin.'
            ));
        }

    }

    public function destroy(Request $request) {
        $product = Product::find($request->id);
        Cloudder::destroy($product->public_id, $options=array());
        $product->category()->detach();
	    $product->delete();
	    //$destroy = Product::where('id', $request->id)->delete();

        if($product) {
            return response()->json(array(
                'status' => 'success',
                'message' => 'Product Deleted'
            ));
        } else {
            return response()->json(array(
                'status' => 'fail',
                'message' => 'Cannot delete product, please contact System Admin.'
            ));
        }
    }

    public function fetchCategoriesForDropdown() {
        $data = Category::where('related_table','products')->get();
        return $data;
    }

    public function fetchCategories(Request $request) {
        $keyword = $_GET['search']['value'];
        $recordsTotal = Category::count();
        $recordsFiltered = Category::count();
        $data = Category::where('related_table','products')
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

    public function addShopCategory(Request $request) {
        $category = new Category();
        $category->related_table = 'products';
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

    public function destroyShopCategory(Request $request) {
        $categoryId = $request->id;
        $related = Product::whereHas('category', function($q) use($categoryId){
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
