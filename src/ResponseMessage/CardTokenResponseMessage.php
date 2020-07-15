<?php

namespace BridgeComm\ResponseMessage;

use BridgeComm\ResponseMessageInterface;

class CardTokenResponseMessage implements ResponseMessageInterface {

  /**
   * @var string
   */
  protected $token;

  /**
   * @var string
   */
  protected $expirationDate;

  /**
   * {@inheritdoc}
   */
  public static function createFromXml(\SimpleXMLElement $xml): ResponseMessageInterface {
    return new static(
      (string) $xml->Token,
      (string) $xml->ExpirationDate
    );
  }

  /**
   * CardTokenResponseMessage constructor.
   *
   * @param string $token
   * @param string $expirationDate
   */
  public function __construct(string $token, string $expirationDate) {
    $this->token = $token;
    $this->expirationDate = $expirationDate;
  }

  /**
   * @return string
   */
  public function getToken(): string {
    return $this->token;
  }

  /**
   * @return string
   */
  public function getExpirationDate(): string {
    return $this->expirationDate;
  }
}