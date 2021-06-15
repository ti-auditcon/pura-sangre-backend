<?php

namespace App\Models\Invoicing;

use GuzzleHttp\Client;
use App\Models\Invoicing\DTE;

class DTERequests extends DTE
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

    // /**
    //  *  Fill url and apis for requests
    //  *
    //  *  @return  void
    //  */
    // protected function fillProperties(): void
    // {
    //     $this->urlDev = config('invoicing.haulmer.sandbox.base_uri');
    //     $this->apiKeyDev = config('invoicing.haulmer.sandbox.api_key');

    //     $this->apiKeyProduction = config('invoicing.haulmer.production.api_key');
    //     $this->urlProduction = config('invoicing.haulmer.production.base_uri');
    // }

    public function get($urlPath, $params)
    {
        try {
            $client = new Client(['base_uri' => $this->urlProduction]);

            $response = $client->post("/v2/{$urlPath}", [
                'headers'  => [
                    "apikey" => $this->apiKeyProduction,
                    'Accept' => 'application/json',
                ],
                'json' => [
                    "Page" => $params['page'] ?? 1
                ]
            ]);

            $body = $response->getBody();
            $content = $body->getContents();
            $response = json_decode($content);

            $json_data = array(
                // "draw"            => intval($request->input('draw')),
                "recordsFiltered" => intval(count($response->data)),
                "recordsTotal"    => intval($response->total),
                "data"            => $response->data,
                "current_page"    => $response->current_page,
                "last_page"       => $response->last_page
            );

            echo json_encode($json_data);
        } catch (\GuzzleHttp\Exception\ClientException $error) {
            $response = json_decode($error->getResponse()->getBody()->getContents(), true);

            return response()->json([
                'status' => 'Request failed', 'message' => $response['message']
            ], $response['statusCode']);
        }
    }
}
