<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Imports\QasirDataGyrImport;
use App\Exports\BerkasExport;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = DB::table('products')->paginate(10);

		return view('image.show',['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $path1 = request()->file('shopee_media')->store('temp'); 
        $path = storage_path('app').'/'.$path1;  
        $arrayMedia = Excel::toArray(new QasirDataGyrImport,$path);

        foreach($arrayMedia[0] as $singleMedia){
            if(str_contains($singleMedia[0], 'et_title_product_id') || str_contains($singleMedia[0], 'media_info') || str_contains($singleMedia[0], 'Kode Produk') || str_contains($singleMedia[0], 'Tidak Dapat Diubah') || str_contains($singleMedia[4], 'Mohon masukkan link foto.') || is_null($singleMedia[2])){
                continue;
            }
            $product = new Product;

            $product->name = $singleMedia[2];
            $product->shopee_image = $singleMedia[4];
            $product->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }

    public function cari(Request $request)
	{
		// menangkap data pencarian
		$cari = $request->cari;
 
    		// mengambil data dari table pegawai sesuai pencarian data
		$products = DB::table('products')
		->where('name','like',"%".$cari."%")
		->paginate();
 
    		// mengirim data pegawai ke view index
		return view('image.show',['products' => $products]);
 
	}
}
