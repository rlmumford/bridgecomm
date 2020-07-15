<?php

namespace BridgeComm\Tests;

final class ResponseHelper {

  /**
   * Wrap a result message xml string in the soap xml structure.
   *
   * @param string $result_xml
   *
   * @return string
   */
  public static function wrapResult(string $result_xml): string {
    return "<?xml version=\"1.0\" encoding=\"utf-8\" ?>
            <s:Envelope xmlns:s=\"http://schemas.xmlsoap.org/soap/envelope/\">
              <s:Body>
                <ProcessRequestResponse xmlns=\"http://bridgepaynetsecuretx.com/requesthandler\">
                  <ProcessRequestResult>".
      base64_encode($result_xml).
                 "</ProcessRequestResult>
                </ProcessRequestResponse>
              </s:Body>
            </s:Envelope>";
  }

}