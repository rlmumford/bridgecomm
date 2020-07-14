<?php

namespace BridgeComm\RequestMessage;

use BridgeComm\RequestMessage;

class CardTokenRequestMessage extends RequestMessage {

  const REQUEST_TYPE = '001';

  protected $paymentAccountNumber;

  protected $expirationDate;

  protected $msrKey;

  protected $secureFormat;

  protected $bdkSlot;

  protected $track1;

  protected $track2;

  protected $track3;

  protected $encryptionId;

  protected $deviceMake;

  protected $deviceModel;

  protected $deviceSerial;

  protected $deviceFirmware;

  protected $registrationKey;

  protected $appHostMachineId;

  protected $integrationMethod;

  protected $originatingTechnologySource;

  protected $securityTechnology;

  /**
   * Set the payment account number.
   *
   * @param string $number
   *
   * @return $this
   */
  public function setPaymentAccountNumber(string $number): CardTokenRequestMessage {
    $this->paymentAccountNumber = $number;
    return $this;
  }

  /**
   * Get the payment account number.
   *
   * @return string
   */
  public function getPaymentAccountNumber(): string {
    return $this->paymentAccountNumber;
  }

  /**
   * Get the payment method expiration date.
   *
   * @param string $date
   *
   * @return \BridgeComm\RequestMessage\CardTokenRequestMessage
   */
  public function setExpirationDate(string $date): CardTokenRequestMessage {
    $this->expirationDate = $date;
    return $this;
  }

  /**
   * Get the expiration date.
   *
   * @return string
   */
  public function getExpirationDate(): string {
    return $this->expirationDate;
  }

  /**
   * {@inheritdoc}
   */
  protected function buildMessageXml(\DOMElement $message, \DOMDocument $document): void {
    parent::buildMessageXml($message, $document);
    $message->appendChild($document->createElement(
      'PaymentAccountNumber',
      $this->paymentAccountNumber
    ));
    $message->appendChild($document->createElement(
      'ExpirationDate',
      $this->expirationDate
    ));
  }


}