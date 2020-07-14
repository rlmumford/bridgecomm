<?php

namespace BridgeComm;

class RequestFactory {

  public function createRequest(RequestMessageInterface $message,  RequestCredentialsInterface $credentials = NULL): RequestInterface{
    $request = new Request();

    $request->setCredentials($credentials ?: $this->getDefaultCredentials());
    $request->setRequestType($message::REQUEST_TYPE);
    $request->setMessage($message);

    return $request;
  }

  public function createRequestMessage(string $request) {
    $class_name = "\\BridgeComm\\ReqestMessage\\{$request}RequestMessage";
    if (!$class_name) {
      throw new \Exception();
    }

    /** @var \BridgeComm\RequestMessage $message */
    $message = new $class_name();
    return $message;
  }

  protected function getDefaultCredentials(): RequestCredentialsInterface {

  }
}