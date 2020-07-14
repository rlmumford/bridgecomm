<?php

namespace BridgeComm;

abstract class RequestMessage implements RequestMessageInterface {

  /**
   * @var string
   */
  protected $merchantCode;

  /**
   * @var string
   */
  protected $merchantAccountCode;

  /**
   * @var string
   */
  protected $softwareVendor;

  public function setMerchantCode(string $code): RequestMessageInterface {
    $this->merchantCode = $code;
    return $this;
  }

  public function getMerchantCode(): string {
    return $this->merchantCode;
  }

  public function setMerchantAccountCode(string $code): RequestMessageInterface {
    $this->merchantAccountCode = $code;
    return $this;
  }

  public function getMerchantAccountCode(): string {
    return $this->merchantAccountCode;
  }

  public function setSoftwareVendor(string $vendor): RequestMessageInterface {
    $this->softwareVendor = $vendor;
    return $this;
  }

  public function getSoftwareVendor(): string {
    return $this->softwareVendor;
  }

  public function toXml(\DOMDocument $doc = NULL): \DOMNode {
    $return = $doc ? 'doc' : 'message';
    $doc = $doc ?: new \DOMDocument('1.0', 'utf-8');
    $message = $doc->createElement('requestMessage');
    $this->buildMessageXml($message, $doc);

    if ($return === 'doc') {
      $doc->appendChild($message);
    }
    return $$return;
  }

  protected function buildMessageXml(\DOMElement $message, \DOMDocument $document): void {
    $message->appendChild($document->createElement('MerchantCode', $this->getMerchantCode()));
    $message->appendChild($document->createElement('MerchantAccountCode', $this->getMerchantAccountCode()));
    if ($sv = $this->getSoftwareVendor()) {
      $message->appendChild($document->createElement('SofwareVendor', $this->getSoftwareVendor()));
    }
  }
}