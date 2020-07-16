<?php

namespace BridgeComm\RequestMessage;

use BridgeComm\RequestMessage;

class VoidRefundRequestMessage extends RequestMessage {

  /**
   * Transaction types.
   */
  const TT_VOID = 'void';
  const TT_REFUND = 'refund';

  /**
   * Void reason codes.
   */
  const VC_CUSTOMER = 'CustomerInitiated';
  const VC_TIMEOUT = 'TimeoutReversal';
  const VC_MALFUNCTION = 'System Malfunction';
  const VC_SUSP_FRAUD = 'SuspectedFraud';
  const VC_CARD_REMOVAL = 'PrematureCardRemoval';
  const VC_CHIP_DECLINE = 'ChipDecline';

  const REQUEST_TYPE = '012';

  protected $amount;

  protected $referenceNumber;

  protected $transactionType = self::TT_VOID;

  protected $transactionCode;

  protected $purchaseToken;

  protected $originatingTechnologySource;

  protected $securityTechnology;

  protected $customerAccountCode;

  protected $invoiceNum;

  protected $deviceMake;

  protected $deviceModel;

  protected $deviceSerial;

  protected $deviceFirmware;

  protected $registrationKey;

  protected $appHostMatchingId;

  protected $integrationMethod;

  protected $emvTags;

  protected $voidReasonCode;

  /**
   * Set the amount to refund/void
   *
   * @param int $amount
   *
   * @return \BridgeComm\RequestMessage\VoidRefundRequestMessage
   */
  public function setAmount(int $amount) : VoidRefundRequestMessage {
    $this->amount = $amount;
    return $this;
  }

  /**
   * Set the reference number.
   *
   * @param string $number
   *
   * @return \BridgeComm\RequestMessage\VoidRefundRequestMessage
   */
  public function setReferenceNumber(string $number): VoidRefundRequestMessage {
    $this->referenceNumber = $number;
    return $this;
  }

  /**
   * Set the transaction code.
   *
   * This must match the TransactionID on th request.
   *
   * @param string $code
   *
   * @return \BridgeComm\RequestMessage\VoidRefundRequestMessage
   */
  public function setTransactionCode(string $code): VoidRefundRequestMessage {
    $this->transactionCode = $code;
    return $this;
  }

  /**
   * Set the transaction type.
   *
   * @param string $type
   *
   * @return \BridgeComm\RequestMessage\VoidRefundRequestMessage
   */
  public function setTransactionType(string $type): VoidRefundRequestMessage {
    if (!in_array($type, [static::TT_VOID, static::TT_REFUND])) {
      throw new \InvalidArgumentException("{$type} is not a valid transaction type for ".static::class);
    }

    $this->transactionType = $type ;
    return $this;
  }

  /**
   * @param string $code
   *
   * @return \BridgeComm\RequestMessage\VoidRefundRequestMessage
   */
  public function setVoidReasonCode(string $code): VoidRefundRequestMessage {
    if (!in_array($code, [
      static::VC_CARD_REMOVAL, static::VC_CHIP_DECLINE, static::VC_CUSTOMER,
      static::VC_MALFUNCTION, static::VC_SUSP_FRAUD, static::VC_TIMEOUT
    ])) {
      throw new \InvalidArgumentException("{$code} is not a valid reason code for ".static::class);
    }

    $this->voidReasonCode = $code;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  protected function buildMessageXml(\DOMElement $message, \DOMDocument $document): void {
    parent::buildMessageXml($message, $document);

    $message->appendChild($document->createElement('ReferenceNumber', $this->referenceNumber));
    $message->appendChild($document->createElement('Amount', $this->amount));
    $message->appendChild($document->createElement('TransactionType', $this->transactionType));
    $message->appendChild($document->createElement('TransactionCode', $this->transactionCode));
    if (!empty($this->voidReasonCode)) {
      $message->appendChild($document->createElement('VoidReasonCode', $this->voidReasonCode));
    }
  }


}