<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $url = 'https://api.telepro.me/api/v1/jobs/1149/calls?&search=&call_converted=1&conversation_status=dongytraodoi&error=&keywords=&limit=30&package=&page=1&period=today&search=&status=success';
        $token = 'XyBIK37CF8zUvXR9rVzu';
        $ch = curl_init( $url );
		$authorization = "Authorization: Bearer ".$token;
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

		$response = curl_exec($ch);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$res=json_decode(curl_exec($ch),true);
		// return json_encode($response)->calls;
		// return $response;
		return $res['calls']['data'];
		// dd(json_decode($response));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
