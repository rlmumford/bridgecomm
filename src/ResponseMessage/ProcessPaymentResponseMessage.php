<?php

namespace BridgeComm\ResponseMessage;

use BridgeComm\ResponseMessageInterface;

class ProcessPaymentResponseMessage implements ResponseMessageInterface {

  protected $token;

  protected $authorizationCode;

  protected $authorizedAmount;

  protected $originalAmount;

  protected $cvResult;
  protected $cvMessage;

  protected $gatewayTransID;
  protected $gatewayMessage;

  protected $cardClass;
  protected $cardType;
  protected $cardModifier;
  protected $cardHolderName;

  protected $providerResponseCode;
  protected $providerResponseMessage;

  protected $remainingAmount;

  public static function createFromXml(\SimpleXMLElement $xml): ResponseMessageInterface {
    return new static(
      (string) $xml->Token,
      (string) $xml->AuthorizationCode,
      (int) $xml->AuthorizedAmount,
      (int) $xml->OriginalAmount,
      (int) $xml->RemainingAmount,
      array_filter([
        'trans_id' => (string) $xml->GatewayTransID,
        'message' => (string) $xml->GatewayMessage,
      ]),
      array_filter([
        'result' => (string) $xml->CVResult,
        'message' => (string) $xml->CVMessage,
      ]),
      array_filter([
        'class' => (string) $xml->CardClass,
        'type' => (string) $xml->CardType,
        'modifier' => (string) $xml->CardModifier,
        'holder_name' => (string) $xml->CardHolderName,
      ])
    );
  }

  /**
   * ProcessPaymentResponseMessage constructor.
   *
   * @param string $token
   * @param string $authorization_code
   * @param int $authorized_amount
   * @param int $original_amount
   * @param int $remaining_amount
   * @param array $gateway_info
   * @param array $cv_info
   * @param array $card_info
   */
  public function __construct(
    string $token,
    string $authorization_code,
    int $authorized_amount,
    int $original_amount,
    int $remaining_amount,
    array $gateway_info = [],
    array $cv_info = [],
    array $card_info = []
  ) {
    $this->token = $token;
    $this->authorizationCode = $authorization_code;
    $this->authorizedAmount = $authorized_amount;
    $this->originalAmount = $original_amount;
    $this->remainingAmount = $remaining_amount;
    if (!empty($gateway_info)) {
      $this->gatewayTransID = $gateway_info['trans_id'];
      $this->gatewayMessage = $gateway_info['message'];
    }
    if (!empty($cv_info)) {
      $this->cvResult = $cv_info['result'];
      $this->cvMessage = $cv_info['message'];
    }
    if (!empty($card_info)) {
      $this->cardClass = $card_info['class'];
      $this->cardHolderName = $card_info['holder_name'];
      $this->cardModifier = $card_info['modifier'];
      $this->cardType = $card_info['type'];
    }
  }

  /**
   * Get gateway trans id
   *
   * @return string
   */
  public function getGatewayTransID(): string {
    return $this->gatewayTransID;
  }
}