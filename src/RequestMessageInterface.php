<?php

namespace BridgeComm;

interface RequestMessageInterface {

  const REQUEST_TYPE = '000';

  public function setMerchantCode(string $code): RequestMessageInterface;

  public function getMerchantCode(): string;

  public function setMerchantAccountCode(string $code): RequestMessageInterface;

  public function getMerchantAccountCode(): string;

  public function setSoftwareVendor(string $vendor): RequestMessageInterface;

  public function getSoftwareVendor(): string;

  public function toXml(\DOMDocument $doc = NULL): \DOMNode;

}