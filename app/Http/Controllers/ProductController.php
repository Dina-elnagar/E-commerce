<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Option;
use App\Models\Variant;
use Illuminate\Support\Facades\Validator;
use App\Events\ProductOutOfStock;

class ProductController extends Controller
{


        public function index(Request $request)
        {
            $products = Product::get();
            return response()->json($products);
            return $products;
        }


            public function store(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'average_rating' => 'required',
                'options' => 'required|array', // Assuming 'options' is an array of JSON objects
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }

            // Create a new variant
            $variant = Variant::create([
                'title' => $request->input('title'),
                'option1' => $request->input('option1'),
                'option2' => $request->input('option2'),
                'price' => $request->input('price'),
                'stock' => $request->input('stock'),
                'is_in_stock' => $request->input('is_in_stock'),
            ]);

            // Save the variant
            $variant->save();

             // Create the Option with JSON data
             $options = json_encode($request->input('options'));


            // Create a new option
            $option = Option::create([
                'name' => $request->input('title'),
                'value' =>  $options, // Assuming 'options' is an array
            ]);

            // Save the option
            $option->save();

            // Find the variant with the minimum price
            $minPrice = Variant::min('price');
            $variantWithMinPrice = Variant::where('price', $minPrice)->first();

            if ($variantWithMinPrice) {
                $minPriceId = $variantWithMinPrice->id;

                // Create a new product
                $product = Product::create([
                    'title' => $request->input('title'),
                    'average_rating' => $request->input('average_rating'),
                    'default_variant' => $minPriceId,
                    'is_in_stock' => $request->input('is_in_stock'),
                    'options' =>  $options, // Assuming 'options' is an array
                ]);

                // Save the product
                $product->save();
            } else {
                return response()->json(['error' => 'No variant available'], 420);
            }

            return response()->json(['success' => $product, 'variant' => $variant, 'option' => $option], 200);
        }


        public function FilterProducts(Request $request)
        {
            // Create a query for products
            $query = Product::query();

            // Check for filtering by average_rating
            if ($request->has('average_rating')) {
                $averageRating = $request->input('average_rating');
                $query->where('average_rating', $averageRating);
                return $this->filterProductsAndRespond($query);
            }

            // Check for filtering by options
            if ($request->has('options')) {
                $options = $this->parseOptionsString($request->input('options'));
                $query->whereJsonContains('options', $options);
                return $this->filterProductsAndRespond($query);
            }

            // Check for filtering by max_price
            if ($request->has('max_price')) {
                $maxPrice = $request->input('max_price');
                $variantSubquery = Variant::where('price', '<=', $maxPrice)->select('id');
                $query->whereIn('default_variant', $variantSubquery);
                return $this->filterProductsAndRespond($query);
            }

            // No valid filter parameters
            return response()->json(['error' => 'No products found'], 401);
        }

        // Helper function to parse options string into an array
        private function parseOptionsString($optionsString)
        {
            $options = [];
            $optionPairs = explode(',', $optionsString);

            foreach ($optionPairs as $optionPair) {
                list($key, $value) = array_map('trim', explode(':', $optionPair));
                $options[$key] = $value;
            }

            return $options;
        }

        // Helper function to filter products and return a JSON response
        private function filterProductsAndRespond($query)
        {
            $filteredProducts = $query->get();
            return response()->json($filteredProducts);
        }

        public function getOutOfStock($productId) {
            // Find the product by ID
            $product = Product::find($productId);

            // Check if the product exists
            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            // Check if the product is out of stock
            if ($product->is_in_stock === 0) {
                // Send an email to notify about the out-of-stock product
                event(new ProductOutOfStock($product));

                // Find the default variant for the product
                $variant = Variant::find($product->default_variant);

                return response('Email sent successfully');
            } else {
                return response()->json(['success' => 'Product is in stock', 'product' => $product], 200);
            }
        }



}




