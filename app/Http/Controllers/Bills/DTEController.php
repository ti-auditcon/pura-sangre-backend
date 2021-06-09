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
     * Display the specified resource.
     *
     *  @param  Request
     *  @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $rut = "{$request->rut}-{$request->vn}";

        try {
            $client = new Client(['base_uri' => $this->urlDev]);

            $response = $client->get("/v2/dte/document/{$rut}/{$request->type}/{$request->document_number}/pdf", [
                'headers'  => [
                    "apikey" => $this->apiKeyDev
                ]
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
            return response()->json([
                'status' => 'Error',
                'message' => 'No se ha podido traer el PDF correctamente, Inténtalo de nuevo más tarde.'
            ], 500);
        }
    }
}
