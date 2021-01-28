<?php

namespace App\Models\Flow\Services;

use App\Models\Flow\Resources\BasicResource;
use App\Models\Flow\Responses\BasicResponse;

class Payment extends BaseService
{
    use Concerns\HasCrudOperations;

    /**
     * Change the default endpoint for the method for another
     *
     * @var array
     */
    protected $verbsMap = [
        'get' => 'getStatus',
    ];

    /**
     * Permitted actions of the Service Resources
     *
     * @var array
     */
    protected $permittedActions = [
        'get'    => true,
        'commit' => true,
        'create' => false,
        'update' => false,
        'delete' => false,
    ];

    /*
    |--------------------------------------------------------------------------
    | Defaults
    |--------------------------------------------------------------------------
    */

    /**
     * @inheritdoc
     */
    protected function getDefaultsForResource(BasicResource $resource)
    {
        $array = [];

        if ($urlConfirmation = $this->flow->getWebhookUrls('payment.urlConfirmation')) {
            $array['urlConfirmation'] = $urlConfirmation;
        }

        if ($urlReturn = $this->flow->getReturnUrls('payment.urlReturn')) {
            $array['urlReturn'] = $urlReturn;
        }

        return empty($array) ? null : $array;
    }


    /*
    |--------------------------------------------------------------------------
    | Additional Operations
    |--------------------------------------------------------------------------
    */

    /**
     * Retrieves a Resource from the Service using its Commerce Order
     *
     * @param string $id
     * @return \DarkGhostHunter\FlowSdk\Resources\BasicResource
     * @throws \Exception
     */
    public function getByCommerceOrder(string $id)
    {
        // Log Debug
        $this->flow->getLogger()->debug("Retrieving Payment by CommerceOrder $id");

        return $this->make(
            $this->performGet('commerceId', $id, ['method' => 'getStatusByCommerceId'])
        );
    }

    /**
     * Alias for getByCommerceOrder()
     *
     * @param string $id
     * @return \DarkGhostHunter\FlowSdk\Resources\BasicResource
     * @throws \Exception
     */
    public function getByCommerceId(string $id)
    {
        return $this->getByCommerceOrder($id);
    }

    /**
     * Retrieves a Resource from the Service using its Commerce Order
     *
     * @param array $attributes
     * @return BasicResponse
     * @throws \Exception
     */
    public function commitByEmail(array $attributes)
    {
        // Log Debug
        $this->flow->getLogger()->debug('Creating by Email: ' . json_encode($attributes));

        return new BasicResponse(
            $this->performCreate($attributes, ['method' => 'createEmail'])
        );
    }
}