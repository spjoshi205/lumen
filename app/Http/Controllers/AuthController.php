<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Users;

class AuthController extends Controller
{
    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login($id)
    {
		$user = Users::find($id);
		if($user){
			$apikey = base64_encode(str_random(40));
			$user->api_key = $apikey;
			$user->save();
			return response()->json(['status' => 'success','api_key' => $apikey]);
		}else{
			return response()->json(['status' => 'fail'],401);
		}
    }


}
