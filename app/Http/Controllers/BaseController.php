<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;


class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
    	$response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $code = null, $errorMessages = [])
    {
    	$response = [
            'success' => false,
            'message' => $error,
        ];
        try{
            $code = $code != null ? $response['code'] = $code : null;
            if(count($errorMessages) > 0){
                $response['data'] = $errorMessages;
            }
        }catch(Exception $e){
            return response()->json($e);
        }
        return response()->json($response);
    }
}