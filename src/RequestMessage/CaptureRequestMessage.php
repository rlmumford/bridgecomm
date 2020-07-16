<?php

namespace BridgeComm\RequestMessage;

use BridgeComm\RequestMessage;

class CaptureRequestMessage extends RequestMessage {

  protected $amount;

  protected $referenceNumber;

  protected $transactionType;

  protected $transactionCode;

  protected $token;

  /**
   * @param \DOMElement $message
   * @param \DOMDocument $document
   */
  public function buildMessageXml(\DOMElement $message, \DOMDocument $document): void {
    parent::buildMessageXml($message, $document);
  }

}