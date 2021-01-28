<?php

namespace App\Models\Flow\Services;

use App\Models\Flow\Contracts\ResourceInterface;
use App\Models\Flow\Resources\BasicResource;

class Plan extends BaseService
{
    use Concerns\HasCrudOperations,
        Concerns\HasPagination;

    /**
     * Change the default endpoint for the method for another
     *
     * @var array
     */
    protected $verbsMap = [];

    /**
     * Main identifier
     *
     * @var string
     */
    protected $id = 'planId';

    /**
     * Endpoint name
     *
     * @var string
     */
    protected $endpoint = 'plans';

    /**
     * Permitted actions of the Service Resources
     *
     * @var array
     */
    protected $permittedActions = [
        'get'    => true,
        'commit' => false,
        'create' => true,
        'update' => true,
        'delete' => true,
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
        if ($urlCallback = $this->flow->getWebhookUrls('plan.urlCallback')) {
            return ['urlCallback' => $urlCallback ];
        }

        return;
    }

    /**
     * Calculates the Resource existence based its attributes (or presence)
     *
     * @param \DarkGhostHunter\Fluid\Fluid&ResourceInterface $resource
     * @return bool
     */
    protected function calcResourceExistence(ResourceInterface $resource)
    {
        // By default, the resource exists if its not null or false.
        return $resource->getAttribute('status') !== 0;
    }
}