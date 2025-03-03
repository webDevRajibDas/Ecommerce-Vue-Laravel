<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use App\Models\Product;


class ProductController extends Controller
{

    public function index()
    {
        return view('admin.product.index');
    }

    public function create()
    {
        return view('admin.product.create');
    }


    // Store a newly created product in the database
    public function store(Request $request)
    {
        dd($request->all());

        $validatedData = $request->validate([
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'status' => 'required|string|in:published,draft',
            'images' => 'nullable|array',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
            'stock_status' => 'required|string|in:in_stock,out_of_stock',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $this->uploadImage($image, 'products');
            }
        }
        $validatedData['images'] = json_encode($imagePaths);


        if ($request->hasFile('image')) {
            $validatedData['image'] = $this->uploadImage($request->file('image'), 'products');
        }

        Product::create($validatedData);
        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }



    public function dropzone_upload(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:3000', // 3MB max
        ]);

        if ($validator->fails()) {
            return Response::make($validator->errors()->first(), 400);
        }
        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $directory = public_path('uploads');
        $filename = sha1(time() . time()) . ".{$extension}";

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0777, true);
        }
        $file->move($directory, $filename);
        return Response::json('success', 200);
    }
}
