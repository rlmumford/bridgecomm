<?php

namespace BridgeComm;

use BridgeComm\Exception\UnsupportedRequestTypeException;
use BridgeComm\RequestMessage\CardTokenRequestMessage;
use BridgeComm\RequestMessage\PingRequestMessage;
use BridgeComm\RequestMessage\ProcessPaymentRequestMessage;

class RequestFactory {

  const REQUEST_CLASSES = [
    Request::R_MULTI_TOKEN => CardTokenRequestMessage::class,
    Request::R_PROCESS_PAYMENT => ProcessPaymentRequestMessage::class,
    Request::R_PING => PingRequestMessage::class
  ];

  /**
   * Create a request from the message provided.
   *
   * @param \BridgeComm\RequestMessageInterface $message
   * @param \BridgeComm\RequestCredentialsInterface|null $credentials
   *
   * @return \BridgeComm\RequestInterface
   */
  public function createRequest(RequestMessageInterface $message,  RequestCredentialsInterface $credentials = NULL): RequestInterface {
    $request = new Request();

    $request->setCredentials($credentials ?: $this->getDefaultCredentials());
    $request->setRequestType($message::REQUEST_TYPE);
    $request->setMessage($message);

    return $request;
  }

  /**
   * Create a new request message.
   *
   * @param string $request
   *
   * @return \BridgeComm\RequestMessageInterface
   * @throws \BridgeComm\Exception\UnsupportedRequestTypeException
   */
  public function createRequestMessage(string $request): RequestMessageInterface {
    if (isset(static::REQUEST_CLASSES[$request])) {
      $class_name = static::REQUEST_CLASSES[$request];
    }
    else {
      $class_name = "\\BridgeComm\\RequestMessage\\{$request}RequestMessage";
    }

    if (!$class_name || !class_exists($class_name) || !is_subclass_of($class_name, RequestMessageInterface::class)) {
      throw new UnsupportedRequestTypeException("{$request} is not a supported request type.");
    }

    /** @var \BridgeComm\RequestMessageInterface $message */
    $message = new $class_name();
    return $message;
  }

  protected function getDefaultCredentials(): RequestCredentialsInterface {

  }
}