<?php

namespace BridgeComm\RequestMessage;

use BridgeComm\RequestMessage;

class TokenizeAccountRequestMessage extends RequestMessage {

  const REQUEST_TYPE = '013';

  protected $bankAccountNum;

  /**
   * Set the ACH account.
   *
   * @param string $number
   *
   * @return \BridgeComm\RequestMessage\TokenizeAccountRequestMessage
   */
  public function setAchAccount(string $number): TokenizeAccountRequestMessage {
    $this->bankAccountNum = $number;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  protected function buildMessageXml(\DOMElement $message, \DOMDocument $document): void {
    parent::buildMessageXml($message, $document);

    $message->appendChild($document->createElement('BankAccountNum', $this->bankAccountNum));
  }

}