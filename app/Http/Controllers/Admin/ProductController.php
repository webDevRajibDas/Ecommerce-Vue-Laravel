<?php

namespace App\Http\Controllers\Admin;

use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use App\Models\Product;


class ProductController extends Controller
{
    use ImageUploadTrait;

    public function index()
    {

        $products = Product::all();
        return view('admin.product.index',compact('products'));
    }

    public function create()
    {
        return view('admin.product.create');
    }


    // Store a newly created product in the database
    public function store(Request $request)
    {
        //dd($request->all());
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'nullable|string',
            'category_id' => 'required|exists:product_categories,id',
            'brand_id' => 'required',
            'vendor_id' => 'required',
            'price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
        ]);


        if ($request->hasFile('image')) {
            $validatedData['image'] = $this->uploadImage($request->file('image'), 'products');
        }

        // Decode attributes JSON
        //$attributes = json_decode($request->input('atts'), true);


        // Save product to the database
        $product = Product::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'content' => $validatedData['content'],
            'product_categorie_id' => $validatedData['category_id'],
            'brand_id' => $validatedData['brand_id'],
            'vendor_id' => $validatedData['vendor_id'],
            'price' => $validatedData['price'],
            'sale_price' => $validatedData['sale_price'],
            'image' => $validatedData['image'],
        ]);

        // Handle additional images upload
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $this->uploadImage($image, 'products');
                $product->images()->create(['image_path' => $imagePath]);
            }
        }

        // Return success response
        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product,
        ], 201);



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
