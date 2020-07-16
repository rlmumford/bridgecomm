<?php

namespace BridgeComm;

use BridgeComm\ResponseMessage\CardTokenResponseMessage;
use BridgeComm\ResponseMessage\ProcessPaymentResponseMessage;
use BridgeComm\ResponseMessage\TokenizeAccountResponseMessage;
use BridgeComm\ResponseMessage\VoidRefundResponseMessage;

class ResponseFactory {

  const RESPONSE_CLASSES = [
    Request::R_MULTI_TOKEN => CardTokenResponseMessage::class,
    Request::R_PROCESS_PAYMENT => ProcessPaymentResponseMessage::class,
    Request::R_VOID_REFUND => VoidRefundResponseMessage::class,
    Request::R_ACCOUNT_TOKEN => TokenizeAccountResponseMessage::class
  ];

  /**
   * Create a response.
   *
   * @param \SimpleXMLElement $xml
   * @param \BridgeComm\RequestInterface $request
   *
   * @return \BridgeComm\ResponseInterface
   */
  public function createFromXml(\SimpleXMLElement $xml, RequestInterface $request): ResponseInterface {
    $response = new Response(
      $request,
      (string) $xml->TransactionID,
      (string) $xml->ResponseCode,
      (string) $xml->ResponseDescription
    );

    if ($response->isError()) {
      return $response;
    }

    $message_xml = $xml->responseMessage[0];
    if (isset(static::RESPONSE_CLASSES[$request->getRequestType()])) {
      $class_name = static::RESPONSE_CLASSES[$request->getRequestType()];
    }

    if (
      $message_xml && !empty($class_name) && class_exists($class_name) &&
      is_subclass_of($class_name, ResponseMessageInterface::class)
    ) {
      $response->setMessage($class_name::createFromXml($message_xml));
    }

    return $response;
  }



}