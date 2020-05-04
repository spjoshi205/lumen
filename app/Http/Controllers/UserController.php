<?php

namespace App\Http\Controllers;

use App\Users;

class UserController extends Controller
{
    /**
     * Retrieve the user for the given ID.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
		return $user = Users::find($id);
		if(Hash::check($request->input('password'), $user->password)){
			$apikey = base64_encode(str_random(40));
			Users::where('email', $request->input('email'))->update(['api_key' => "$apikey"]);;
			return response()->json(['status' => 'success','api_key' => $apikey]);
		}else{
			return response()->json(['status' => 'fail'],401);
		}
    }
    
}
