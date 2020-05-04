<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Product;

class ProductsController extends Controller
{
    /**
     * Store a new product.
     *
     * @param  Request  $request
     * @return Response
     */
    public function add(Request $request)
    {
		// get login user detail
		$loginuser = $request->get('loginuser');
        if($loginuser->role !=1){
			return response()->json(['success' => false,'message' => 'unauthorize request'], 201);
		} 
        try {
			// set request id value in variable 
			$id = $request->input('id','');
			// check id pass in request
			if($id != '') {
				// fidn record by id
				$product = Product::find($id);
			} else {
				// init product class
				$product = new Product;
			}
			// if data found or init success fully
			if($product){
				// set field value
				$product->InStock = $request->input('InStock');
				$product->Name = $request->input('Name');
				$product->Description = $request->input('Description');
				$product->Quantity = $request->input('Quantity');
				// save record
				$product->save();
				
				//return successful response
				return response()->json(['success' => true,'product' => $product, 'message' => 'CREATED'], 201);
			} else {
				//return error message
				return response()->json(['success' => false,'message' => 'Provide valid data'], 409);
			}
        } catch (\Exception $e) {
            //return error message
            return response()->json(['success' => false,'message' => 'Product Save Failed!'], 409);
        }
    }
    
    /**
     * Store a new product.
     *
     * @param  Request  $request
     * @return Response
     */
    public function delete(Request $request)
    {
        try {
			// get login user detail
			$loginuser = $request->get('loginuser');
			if($loginuser->role !=1){
				return response()->json(['success' => false,'message' => 'unauthorize request'], 201);
			}
			// set request id value in variable 
			$id = $request->input('id','');
			// check id pass in request
			if($id != '') {
				// fidn record by id
				$product = Product::find($id);
			}
			// if data found or init success fully
			if($product){
				// delete record
				$product->delete();
				
				//return successful response
				return response()->json(['success' => true,'message' => 'Product deleted successfully'], 201);
			} else {
				//return error message
				return response()->json(['success' => false,'message' => 'Provide valid data'], 409);
			}
        } catch (\Exception $e) {
            //return error message
            return response()->json(['success' => false,'message' => 'Product Save Failed!'], 409);
        }
    }
    public function fetchproducts()
    {
		return Product::all();
	}
}
