<?php

namespace BridgeComm;

interface RequestCredentialsInterface {

  /**
   * Apply these credentials to the xml.
   *
   * @param \DOMElement $request
   * @param \DOMDocument $document
   */
  public function applyToXML(\DOMElement $request, \DOMDocument $document): void;
}