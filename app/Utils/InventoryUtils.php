<?php

namespace App\Utils;

use Illuminate\Support\Facades\DB;

class InventoryUtils
{
    public static function updateVariationQuantities($request)
    {
    // Verify that 'products' exists and is an array
        if (!$request->has('products') || !is_array($request->input('products'))) {
            throw new \InvalidArgumentException('Invalid products data');
        }
        $dict = [];
        foreach ($request->input('products') as $product) {
            // Retrieve product_id dynamically
            $product_id = $product['product_id'];
            // Get the recipe for the product from mfg_recipes
            $product_recipe = DB::table('mfg_recipes')->where('product_id', $product_id)->first();

            if ($product_recipe) {
                // Get the recipe ingredients using the mfg_recipe_id
                $recipe_ingredients = DB::table('mfg_recipe_ingredients')->where('mfg_recipe_id', $product_recipe->id)->get();

                // Create an empty dictionary (array) to store variation_id, product_id, and quantity
                
                // Loop through each recipe ingredient
                foreach ($recipe_ingredients as $ingredient) {
                    $variation=DB::table('variations')->where('id',$ingredient->variation_id)->first();
                    
                    // Get the variation_id and quantity from the request (assuming you need this from 'products' array)
                    $variation_id = $ingredient->variation_id;
                    $quantity = $ingredient->quantity;

                    // Store the data in the dictionary
                    $dict[] = [
                        'variation_id' => $variation_id,
                        'product_id'=>$variation->product_id,
                        'quantity' => $quantity,
                    ];
                }
                
            }
        }

        // Loop through each dictionary entry in $dict
        foreach ($dict as $dic) {
            // Get the variation_id, product_id, and quantity from the current dictionary entry
            $variation_id = $dic['variation_id'];
            $product_id = $dic['product_id'];
            $quantity = $dic['quantity'];

            // Retrieve the current qty_available for the variation from the variation_location_details table
            $variation_details = DB::table('variation_location_details')
                                    ->where('variation_id', $variation_id)
                                    ->where('product_id', $product_id)
                                    ->first();

            if ($variation_details) {
                // Calculate the new quantity available by subtracting the ordered quantity
                $new_qty_available = $variation_details->qty_available - $quantity;

                // Ensure the new quantity is not negative
                if ($new_qty_available < 0) {
                    $new_qty_available = 0; // Set to 0 if the result is negative, or handle this case as needed
                }

                // Update the qty_available in the variation_location_details table
                DB::table('variation_location_details')
                    ->where('variation_id', $variation_id)
                    ->where('product_id', $product_id)
                    ->update(['qty_available' => $new_qty_available]);
            }
        }
    }
}
