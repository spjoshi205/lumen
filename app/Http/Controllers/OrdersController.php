<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Orders;
use App\Product;
use App\Users;

class OrdersController extends Controller
{
    /**
     * Store a new order.
     *
     * @param  Request  $request
     * @return Response
     */
    public function add(Request $request)
    {
        try {
			// get login user detail
			$loginuser = $request->get('loginuser');
			// set OrderDetail request value in variable
			$OrderDetail = $request->input('OrderDetail','');
			// convert string to array 
			$OrderDetailarr = json_decode($OrderDetail,true);
			// check data exist
			if(!empty($OrderDetailarr)){
				// create variable for set OrderDetail request value as key value pair
				$proddata = [];
				// OrderDetail foreach for set key val pair of product id and qty
				foreach($OrderDetailarr as $Order){
					// set product id as key and qty as value
					$proddata[$Order['productId']] = $Order['qty'];
				}
				// find all order products 
				$existingProducts = Product::whereIn("id",array_keys($proddata))->get();
				$qtymatch = 1;
				// foreach for match qty 
				foreach($existingProducts as $existingProduct){
					// check qty is exist or not
					if($existingProduct->Quantity < $proddata[$existingProduct->id]){
						$qtymatch = 0;
					}
				}
				// check qty status
				if($qtymatch == 1){
					// init orders class
					$orders = new Orders;
					// set field value
					$orders->UserID = $loginuser->id;
					$orders->OrderStatus = $request->input('OrderStatus');
					$orders->Address = $request->input('Address');
					$orders->City = $request->input('City');
					$orders->Postcode = $request->input('Postcode');
					$orders->Street = $request->input('Street');
					$orders->Province = $request->input('Province');
					$orders->Country = $request->input('Country');
					$orders->OrderDetail = $request->input('OrderDetail');
					// save record
					$orders->save();
					// foreach for update product qty
					foreach($proddata as $pid => $pqty){
						// find product detail
						$updateproduct = Product::find($pid);
						// decrease used qty
						$Quantity = ($updateproduct->Quantity-$pqty);
						// set qty in product table
						$updateproduct->Quantity = $Quantity;
						// check qty is exist or not after use
						if($Quantity <= 0){
							// update instock status 
							$updateproduct->InStock = 0;
						}
						// save product
						$updateproduct->save();
					}
					//return successful response
					return response()->json(['success' => true, 'message' => 'CREATED'], 201);
				} else {
					return response()->json(['success' => false, 'message' => 'kindly provide valid data'], 409);
				}
			} else {
				return response()->json(['success' => false, 'message' => 'kindly provide data'], 409);
			}
        } catch (\Exception $e) {
			return $e;
            //return error message
            return response()->json(['success' => false,'message' => 'Failed!'], 409);
        }
    }
    
    /**
     * order filter.
     *
     * @param  Request  $id,$orderstatus
     * @return Response
     */
    public function filter($orderstatus,Request $request)
    {
		// get login user detail
		$loginuser = $request->get('loginuser');
        try {
			$where = ["UserID"=>$loginuser->id];
			if($orderstatus != 'all'){
				$where['OrderStatus'] = $orderstatus;
			}
			return Orders::where($where)->get();
        } catch (\Exception $e) {
            //return error message
            return response()->json(['success' => false,'message' => $e], 409);
        }
    }
    
    /**
     * Store a change old product status
     *
     */
    public function changestatus()
    {
        try {
			Orders::where('created_at',"<",date('Y-m-d'))->update(['OrderStatus' => 'processed']);
			return response()->json(['success' => true,'message' => 'success'], 201);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['success' => false,'message' => $e], 409);
        }
    }
    
    /**
     * accept order 
     *
     * @param  Request  $id
     * @return Response
     */
    public function accept($id,Request $request)
    {
		// get login user detail
		$loginuser = $request->get('loginuser');
        if($loginuser->role !=1){
			return response()->json(['success' => false,'message' => 'unauthorize request'], 201);
		}
        try {
			$order = Orders::find($id);
			$userid = $order->UserID;
			$order->OrderStatus = 'accepted';
			$order->save();
			$user = Users::find($userid);
			Mail::raw('order accepted', function($msg) {
            $msg->to([$user->email]); 
            $msg->from(['spjoshi@gmail.com']); });
			return response()->json(['success' => true,'message' => 'accepted successfully'], 201);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['success' => false,'message' => $e], 409);
        }
    }
    
}
