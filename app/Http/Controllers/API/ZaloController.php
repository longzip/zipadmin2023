<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Zalo\Zalo;

class ZaloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $config = array(
            'app_id' => '4455423608092684560',
            'app_secret' => 'TzcRIBSadQrPBRS7mZAQ',
            'callback_url' => 'https://www.callback.com'
        );
        $zalo = new Zalo($config);
        $helper = $zalo->getRedirectLoginHelper();
        $loginUrl = $helper->getLoginUrl("https://www.callbackack.com");
        $callBackUrl = "https://admin.noithatzip.com/api/test1";
        $oauthCode = isset($request->code) ? $request->code : "THIS NOT CALLBACK PAGE !!!"; // get oauthoauth code from url params
        $accessToken = $helper->getAccessToken($callBackUrl); // get access token
        if ($accessToken != null) {
            $expires = $accessToken->getExpiresAt(); // get expires time
        }
        dd($oauthCode);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $config = array(
            'app_id' => '4455423608092684560',
            'app_secret' => 'TzcRIBSadQrPBRS7mZAQ',
            'callback_url' => 'https://www.callback.com'
        );
        $zalo = new Zalo($config);
        $helper = $zalo->getRedirectLoginHelper();
        $loginUrl = $helper->getLoginUrl("https://www.callbackack.com");
        $callBackUrl = "https://admin.noithatzip.com/api/test1";
        $oauthCode = isset($request->code) ? $request->code : "THIS NOT CALLBACK PAGE !!!"; // get oauthoauth code from url params
        $accessToken = $helper->getAccessToken($callBackUrl); // get access token
        if ($accessToken != null) {
            $expires = $accessToken->getExpiresAt(); // get expires time
        }
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
