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
     */
    private string $urlDev;

    private string $urlProduction;

    private  string $apiKeyDev;

    private  string $apiKeyProduction;

    public function __construct()
    {
        $this->fillProperties();
    }

    /**
     *  Fill url and apis for requests
     *
     *  @return  void
     */
    public function fillProperties(): void
    {
        $this->urlDev = config('invoicing.haulmer.dev.base_uri');
        $this->apiKeyDev = config('invoicing.haulmer.dev.api_key');

        $this->apiKeyProduction = config('invoicing.haulmer.production.api_key');
        $this->urlProduction = config('invoicing.haulmer.production.base_uri');
    }
    /**
     * Display the specified resource.
     *
     *  @param  Request
     *  @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $rut = "{$request->rut}-{$request->dv}";

        try {
            $client = new Client(['base_uri' => $this->urlProduction]);

            $response = $client->get("/v2/dte/document/{$rut}/{$request->type}/{$request->document_number}/pdf", [
                'headers'  => [
                    "apikey" => $this->apiKeyProduction
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
        } catch (\Throwable $error) {
            dd($error);
            return response()->json([
                'status' => 'Error',
                'message' => 'No se ha podido traer el PDF correctamente, Inténtalo de nuevo más tarde.'
            ], 500);
        }
    }
}
