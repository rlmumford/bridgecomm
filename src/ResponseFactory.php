<?php

namespace BridgeComm;

class ResponseFactory {

  /**
   * Create a response.
   *
   * @param \SimpleXMLElement $xml
   * @param \BridgeComm\RequestInterface $request
   *
   * @return \BridgeComm\ResponseInterface
   */
  public function createFromXml(\SimpleXMLElement $xml, RequestInterface $request): ResponseInterface {

  }

}