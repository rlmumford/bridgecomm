<?php

namespace BridgeComm\RequestMessage;

use BridgeComm\RequestMessage;

class PingRequestMessage extends RequestMessage {

  const REQUEST_TYPE = '099';

  /**
   * {@inheritdoc}
   */
  protected function buildMessageXml(\DOMElement $message, \DOMDocument $document): void {
    // Ping has no further information. So add nothing to the message.
  }

}