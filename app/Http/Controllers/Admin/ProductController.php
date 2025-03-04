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
        return view('admin.product.index');
    }

    public function create()
    {
        return view('admin.product.create');
    }


    // Store a newly created product in the database
    public function store(Request $request)
    {
        //dd($request->all());

//        $validatedData = $request->validate([
//            'productName' => 'required|string|max:255',
//            'productDescription' => 'required|string',
//            'category_id' => 'required|exists:categories,id',
//            'brand_id' => 'required|exists:brands,id',
//            'regularPrice' => 'required|numeric',
//            'salePrice' => 'nullable|numeric',
//            'sku' => 'required|string|unique:products,sku',
//            'stock_status' => 'required|in:in_stock,out_of_stock',
//            'weight' => 'nullable|numeric',
//            'dimensionsLength' => 'nullable|numeric',
//            'dimensionsWidth' => 'nullable|numeric',
//            'dimensionsHeight' => 'nullable|numeric',
//            'purchaseNote' => 'nullable|string',
//            'menuOrder' => 'nullable|integer',
//            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
//            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate multiple images
//            'atts' => 'nullable|json',
//        ]);

        $validatedData=[];

        // Handle main image upload
        if ($request->hasFile('image')) {
            $validatedData['image'] = $this->uploadImage($request->file('image'), 'products');
        }
        // Handle multiple images upload
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $this->uploadImage($image, 'products');
            }
        }
        $validatedData['images'] = json_encode($imagePaths); // Store image paths as JSON

        // Decode attributes JSON
        $attributes = json_decode($request->input('atts'), true);

        // Save product to the database
        $product = Product::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'category_id' => $validatedData['category_id'],
            'brand_id' => $validatedData['brand_id'],
            'price' => $validatedData['price'],
            'sale_price' => $validatedData['sale_price'],
            'sku' => $validatedData['sku'],
            'stock_status' => $validatedData['stock_status'],
            'weight' => $validatedData['weight'],
            'dimensions' => [
                'length' => $validatedData['dimensionsLength'],
                'width' => $validatedData['dimensionsWidth'],
                'height' => $validatedData['dimensionsHeight'],
            ],
            'purchase_note' => $validatedData['purchaseNote'],
            'menu_order' => $validatedData['menuOrder'],
            'image' => $validatedData['image'],
            'images' => $validatedData['images'],
            'attributes' => $attributes, // Save attributes as JSON
        ]);

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
