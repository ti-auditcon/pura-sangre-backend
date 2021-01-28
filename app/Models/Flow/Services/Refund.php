<?php

namespace App\Models\Flow\Services;

use App\Models\Flow\Resources\BasicResource;

class Refund extends BaseService
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
        'commit' => false,
        'create' => true,
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
        if ($urlCallBack = $this->flow->getWebhookUrls('refund.urlCallBack')) {
            return [ 'urlCallBack' => $urlCallBack ];
        }

        return;
    }


}