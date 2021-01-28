<?php

namespace App\Models\Flow\Services;

use App\Models\Flow\Flow;
use App\Models\Flow\Contracts\ResourceInterface;
use App\Models\Flow\Contracts\ServiceInterface;
use App\Models\Flow\Resources\BasicResource;

abstract class BaseService implements ServiceInterface
{
    /**
     * Flow Gateway Instance
     *
     * @var Flow
     */
    protected $flow;

    /**
     * Main identifier
     *
     * @var string
     */
    protected $id = 'token';

    /**
     * Endpoint name
     *
     * @var string
     */
    protected $endpoint;

    /**
     * Change the default endpoint for the method for another
     *
     * @var array
     */
    protected $verbsMap = [];

    /**
     * Update-able attributes. If null, no attributes will be filtered
     *
     * @var array|null
     */
    protected $updateableAttributes;

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

    /**
     * Resource Class to instantiate
     *
     * @var ResourceInterface|BasicResource
     */
    protected $resourceClass = BasicResource::class;

    /*
    |--------------------------------------------------------------------------
    | Construction
    |--------------------------------------------------------------------------
    */

    /**
     * @inheritdoc
     */
    public function __construct(Flow $flow)
    {
        $this->flow = $flow;

        $this->endpoint = $this->endpoint ??
            lcfirst(substr(strrchr(get_class($this), '\\'), 1));
    }

    /*
    |--------------------------------------------------------------------------
    | Getters and Setters
    |--------------------------------------------------------------------------
    */

    /**
     * Get the ID key name for this service
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the ID key name for this service
     *
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * @inheritdoc
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @inheritdoc
     */
    public function setEndpoint(string $endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * @inheritdoc
     */
    public function getVerbsMap()
    {
        return $this->verbsMap;
    }

    /**
     * @inheritdoc
     */
    public function setVerbsMap(array $map)
    {
        $this->verbsMap = $map;
    }

    /**
     * @inheritdoc
     */
    public function getUpdateableAttributes()
    {
        return $this->updateableAttributes;
    }

    /**
     * @inheritdoc
     */
    public function setUpdateableAttributes(array $updateableAttributes)
    {
        $this->updateableAttributes = $updateableAttributes;
    }

    /**
     * @inheritdoc
     */
    public function can(string $method)
    {
        return $this->permittedActions[$method] ?? false;
    }

    /*
    |--------------------------------------------------------------------------
    | Resource Maker
    |--------------------------------------------------------------------------
    */

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function make(array $attributes)
    {
        /** @var ResourceInterface|BasicResource $resource */
        $resource = new $this->resourceClass($attributes);

        // Add this Service so it can be called from within the Resource
        $resource->setService($this);

        // Set the Type of Resource so it can be easily identified
        $resource->setType(lcfirst(substr(strrchr(get_class($this), '\\'), 1)));

        // Set the existence flag depending on its attributes
        $resource->setExists($this->calcResourceExistence($resource));

        // Set the Defaults (Webhooks, Returns)
        if ($defaults = $this->getDefaultsForResource($resource)) {
            foreach ($defaults as $key => $default) {
                $resource->setAttribute($key, $default);
            }
        }


        // Log Debug
        $this->flow->getLogger()->debug("Made Resource: $resource");

        // Return the prepared resource
        return $resource;
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
        return (bool)$resource;
    }

    /**
     * Get the default attributes for the resource of this service
     *
     * @param BasicResource $resource
     * @return array|void
     */
    protected function getDefaultsForResource(BasicResource $resource)
    {
        return;
    }
}