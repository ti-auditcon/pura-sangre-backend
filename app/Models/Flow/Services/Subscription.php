<?php

namespace App\Models\Flow\Services;

use App\Models\Flow\Contracts\ResourceInterface;
use App\Models\Flow\Resources\SubscriptionResource;

/**
 * Class Subscription
 * @package DarkGhostHunter\FlowSdk\Services
 *
 * @method SubscriptionResource create(array $attributes)
 * @method SubscriptionResource get(string $id, $options = null)
 * @method SubscriptionResource update($id, ...$attributes)
 * @method \DarkGhostHunter\FlowSdk\Responses\PagedResponse getPage(int $page, array $options = null)
 *
 */
class Subscription extends BaseService
{
    use Concerns\HasCrudOperations,
        Concerns\HasPagination;

    /**
     * Main identifier
     *
     * @var string
     */
    protected $id = 'subscriptionId';

    /**
     * Change the default endpoint for the method for another
     *
     * @var array
     */
    protected $verbsMap = [
        'update' => 'changeTrial',
    ];

    /**
     * Update-able attributes. If null, no attributes will be filtered
     *
     * @var array|null
     */
    protected $updateableAttributes = [
        'trial_period_days'
    ];

    /**
     * Resource Class to instantiate
     *
     * @var SubscriptionResource
     */
    protected $resourceClass = SubscriptionResource::class;

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
        'delete' => false,
    ];

    /*
    |--------------------------------------------------------------------------
    | Existence
    |--------------------------------------------------------------------------
    */


    /**
     * Calculates the Resource existence based its attributes (or presence)
     *
     * @param \DarkGhostHunter\Fluid\Fluid&ResourceInterface $resource
     * @return bool
     */
    protected function calcResourceExistence(ResourceInterface $resource)
    {
        // It exists if the "status" is not cancelled
        return $resource->getAttribute('status') !== 4;
    }

    /*
    |--------------------------------------------------------------------------
    | Operations
    |--------------------------------------------------------------------------
    */

    /**
     * Cancels a Subscription
     *
     * @param string $id
     * @param bool $atPeriodEnd
     * @return \DarkGhostHunter\FlowSdk\Resources\BasicResource & SubscriptionResource
     * @throws \Exception
     */
    public function cancel(string $id, bool $atPeriodEnd)
    {
        // Log Debug
        $this->flow->getLogger()->debug("Cancelling Subscription $id" . ($atPeriodEnd ? ' at period end.' : '.'));

        return $this->make(
            $this->flow->send(
                'post',
                $this->endpoint . '/cancel',
                [
                    'subscriptionId' => $id,
                    'at_period_end' => (int) $atPeriodEnd,
                ]
            )
        );
    }

    /**
     * Adds a Coupon to a Subscription
     *
     * @param string $subscriptionId
     * @param string $couponId
     * @return \DarkGhostHunter\FlowSdk\Resources\BasicResource & SubscriptionResource
     * @throws \Exception
     */
    public function addCoupon(string $subscriptionId, string $couponId)
    {
        // Log Debug
        $this->flow->getLogger()->debug("Adding Coupon $couponId to $subscriptionId");

        return $this->make(
            $this->flow->send(
                'post',
                $this->endpoint . '/addCoupon',
                [
                    'subscriptionId' => $subscriptionId,
                    'couponId' => $couponId,
                ]
            )
        );
    }

    /**
     * Removes a Coupon from the Subscription
     *
     * @param string $subscriptionId
     * @return \DarkGhostHunter\FlowSdk\Resources\BasicResource & SubscriptionResource
     * @throws \Exception
     */
    public function removeCoupon(string $subscriptionId)
    {
        // Log Debug
        $this->flow->getLogger()->debug("Removing Coupons from $subscriptionId");

        return $this->make(
            $this->flow->send(
                'post',
                $this->endpoint . '/deleteCoupon',
                ['subscriptionId' => $subscriptionId]
            )
        );
    }
}