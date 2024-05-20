<?php

namespace App\Http\Controllers;

use App\Models\product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(request()->ajax())
        {
            $query = product::query();
            return DataTables::of($query)
            ->addColumn('action', function ($item) {
                return '
                <a href="'.route('dashboard.product.gallery.index', $item->id).'" class="btn btn-sm btn-primary">Gallery</a>
                <a href="'.route('dashboard.product.edit', $item->id).'" class="btn btn-sm btn-primary">Edit</a>
                <form class="inline-block" action="'. route('dashboard.product.destroy', $item->id).'" method="POST">
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    '. method_field('delete') . csrf_field() .'
                </form>
                ';
            })            
            ->editColumn('price', function ($item){
                return number_format($item->price);
            })
            ->rawColumns(['action'])
            ->make();
        }
        return view('pages.dashboard.product.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.dashboard.product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        Product::create($data);

        return redirect()->route('dashboard.product.index');
       
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(product $product)
    {
        return view('pages.dashboard.product.edit', [
            "item" => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, product $product)
    {
        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $product->update($data);
        return redirect()->route('dashboard.product.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(product $product)
    {
        $product->delete();
        return redirect()->route('dashboard.product.index');
    }
}
