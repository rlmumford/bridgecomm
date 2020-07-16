<?php

namespace BridgeComm\ResponseMessage;

use BridgeComm\ResponseMessageInterface;

class TokenizeAccountResponseMessage implements ResponseMessageInterface {

  /**
   * @var string
   */
  protected $token;


  /**
   * {@inheritdoc}
   */
  public static function createFromXml(\SimpleXMLElement $xml): ResponseMessageInterface {
    return new static(
      (string) $xml->Token
    );
  }

  /**
   * CardTokenResponseMessage constructor.
   *
   * @param string $token
   */
  public function __construct(string $token) {
    $this->token = $token;
  }

  /**
   * @return string
   */
  public function getToken(): string {
    return $this->token;
  }
}