<?php
/*
 * MundiAPILib
 *
 * This file was automatically generated by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace MundiAPILib\Models;

use JsonSerializable;

/**
 * Recipient response
 */
class GetRecipientResponse implements JsonSerializable
{
    /**
     * Id
     * @required
     * @var string $id public property
     */
    public $id;

    /**
     * Name
     * @required
     * @var string $name public property
     */
    public $name;

    /**
     * Email
     * @required
     * @var string $email public property
     */
    public $email;

    /**
     * Document
     * @required
     * @var string $document public property
     */
    public $document;

    /**
     * Description
     * @required
     * @var string $description public property
     */
    public $description;

    /**
     * Type
     * @required
     * @var string $type public property
     */
    public $type;

    /**
     * Status
     * @required
     * @var string $status public property
     */
    public $status;

    /**
     * Creation date
     * @required
     * @maps created_at
     * @var string $createdAt public property
     */
    public $createdAt;

    /**
     * Last update date
     * @required
     * @maps updated_at
     * @var string $updatedAt public property
     */
    public $updatedAt;

    /**
     * Deletion date
     * @required
     * @maps deleted_at
     * @var string $deletedAt public property
     */
    public $deletedAt;

    /**
     * Default bank account
     * @required
     * @maps default_bank_account
     * @var GetBankAccountResponse $defaultBankAccount public property
     */
    public $defaultBankAccount;

    /**
     * Info about the recipient on the gateway
     * @required
     * @maps gateway_recipients
     * @var GetGatewayRecipientResponse[] $gatewayRecipients public property
     */
    public $gatewayRecipients;

    /**
     * Metadata
     * @required
     * @var array $metadata public property
     */
    public $metadata;

    /**
     * Constructor to set initial or default values of member properties
     * @param string                 $id                 Initialization value for $this->id
     * @param string                 $name               Initialization value for $this->name
     * @param string                 $email              Initialization value for $this->email
     * @param string                 $document           Initialization value for $this->document
     * @param string                 $description        Initialization value for $this->description
     * @param string                 $type               Initialization value for $this->type
     * @param string                 $status             Initialization value for $this->status
     * @param string                 $createdAt          Initialization value for $this->createdAt
     * @param string                 $updatedAt          Initialization value for $this->updatedAt
     * @param string                 $deletedAt          Initialization value for $this->deletedAt
     * @param GetBankAccountResponse $defaultBankAccount Initialization value for $this->defaultBankAccount
     * @param array                  $gatewayRecipients  Initialization value for $this->gatewayRecipients
     * @param array                  $metadata           Initialization value for $this->metadata
     */
    public function __construct()
    {
        if (13 == func_num_args()) {
            $this->id                 = func_get_arg(0);
            $this->name               = func_get_arg(1);
            $this->email              = func_get_arg(2);
            $this->document           = func_get_arg(3);
            $this->description        = func_get_arg(4);
            $this->type               = func_get_arg(5);
            $this->status             = func_get_arg(6);
            $this->createdAt          = func_get_arg(7);
            $this->updatedAt          = func_get_arg(8);
            $this->deletedAt          = func_get_arg(9);
            $this->defaultBankAccount = func_get_arg(10);
            $this->gatewayRecipients  = func_get_arg(11);
            $this->metadata           = func_get_arg(12);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['id']                   = $this->id;
        $json['name']                 = $this->name;
        $json['email']                = $this->email;
        $json['document']             = $this->document;
        $json['description']          = $this->description;
        $json['type']                 = $this->type;
        $json['status']               = $this->status;
        $json['created_at']           = $this->createdAt;
        $json['updated_at']           = $this->updatedAt;
        $json['deleted_at']           = $this->deletedAt;
        $json['default_bank_account'] = $this->defaultBankAccount;
        $json['gateway_recipients']   = $this->gatewayRecipients;
        $json['metadata']             = $this->metadata;

        return $json;
    }
}
