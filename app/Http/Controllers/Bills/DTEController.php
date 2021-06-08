<?php

namespace App\Http\Controllers\Bills;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Freshwork\ChileanBundle\Rut;

class DTEController extends Controller
{
        /**
     *  url for developing and testing
     *
     *  @var  string
     */
    protected $urlDev = 'https://dev-api.haulmer.com/v2';

    protected $urlProduction = 'https://api.haulmer.com/v2';

    protected $apiKeyDev = '928e15a2d14d4a6292345f04960f4bd3';

    protected $apiKeyProduction = 'bab4ce50d3c9406b86ae536d44d6b172';

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
     *  @param  Request
     *  @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        // dd($request->all());
        if ($request->rut) {
            $vn = Rut::set($request->rut)->calculateVerificationNumber();
            $rut = "{$request->rut}-{$vn}";
        }

        try {
            $client = new Client(['base_uri' => $this->urlDev]);

            $response = $client->get("/v2/dte/document/{$rut}/{$request->type}/{$request->document_number}/pdf", [
                'headers'  => [
                    "apikey" => $this->apiKeyDev
                ]
                // 'https://dev-api.haulmer.com/v2/dte/document/76423895-8/34/396/json
            ]);
            $body = $response->getBody();
            $content = $body->getContents();
            $decoded_json = json_decode($content);

            if ($decoded_json->pdf) {
                return response()->json([
                    'status' => 'Ok - Successful',
                    'data'   => $decoded_json->pdf
                ]);
            }
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['status' => 'Error'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
