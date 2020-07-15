<?php

namespace BridgeComm;

interface ResponseMessageInterface {

  /**
   * Create a response message from xml.
   *
   * @param \SimpleXMLElement $xml
   *
   * @return \BridgeComm\ResponseMessageInterface
   */
  public static function createFromXml(\SimpleXMLElement $xml): ResponseMessageInterface;
}