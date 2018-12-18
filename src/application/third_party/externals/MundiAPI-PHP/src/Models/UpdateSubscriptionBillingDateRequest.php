<?php
/*
 * MundiAPILib
 *
 * This file was automatically generated by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace MundiAPILib\Models;

use JsonSerializable;

/**
 * Request for updating the due date from a subscription
 */
class UpdateSubscriptionBillingDateRequest implements JsonSerializable
{
    /**
     * The date when the next subscription billing must occur
     * @required
     * @maps next_billing_at
     * @var string $nextBillingAt public property
     */
    public $nextBillingAt;

    /**
     * Constructor to set initial or default values of member properties
     * @param string $nextBillingAt Initialization value for $this->nextBillingAt
     */
    public function __construct()
    {
        if (1 == func_num_args()) {
            $this->nextBillingAt = func_get_arg(0);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['next_billing_at'] = $this->nextBillingAt;

        return $json;
    }
}
