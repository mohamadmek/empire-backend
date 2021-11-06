<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\BaseController as BaseController;

class FavoriteController extends BaseController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Unit is required', 500, $validator->errors());
        }

        $city = $request->city;
        $user = Auth::user();

        if(Favorite::where('city', '=', $city)
        ->where('user_id', '=', $user->id)->doesntExist()) {
            $favCreated = Favorite::create([
                "user_id" => $user->id,
                "city" => $city
            ]);
            return $this->sendResponse($favCreated, 'Successfully registered'); 
        } else {
            return $this->sendError('already Exist', 500);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Favorite::destroy($request->id);
    }
}
