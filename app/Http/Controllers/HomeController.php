<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $products = Product::with('brand','category');
        $products = $products->when(request('query'),function($q){
            $q->where('name','like','%'.request('query').'%')
            ->orWhere('slug','like','%'.request('query').'%');
        });

        $products = $products->paginate(15);
        return view('home',['products' => $products]);
    }


     /*Tiny MCE image Upload*/
     public function uploadImage(Request $request)
     {
         $fileName = $request->file('file')->getClientOriginalName();
         $path = $request->file('file')->storeAs('uploads', $fileName, 'public');
         return response()->json(['location' => "/storage/$path"]);
     }

     public function checkout()
     {
        return view('backend.checkout.index');
     }
}
