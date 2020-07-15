<?php

namespace BridgeComm\Exception;

use BridgeComm\ResponseInterface;
use Throwable;

class ResponseException extends BridgeCommException {

  /**
   * @var \BridgeComm\ResponseInterface
   */
  protected $response;

  /**
   * RequestException constructor.
   *
   * @param \BridgeComm\ResponseInterface $response
   * @param \Throwable|null $previous
   */
  public function __construct(ResponseInterface $response, Throwable $previous = NULL) {
    $this->response = $response;

    $code = $response->getCode();
    $code = filter_var($code, \FILTER_SANITIZE_NUMBER_INT);
    if ($code === false) {
      $code = 0;
    } else {
      $code = (int) $code;
    }

    parent::__construct($response->getDescription(), $code, $previous);
  }

  /**
   * @return \BridgeComm\ResponseInterface
   */
  public function getResponse(): ResponseInterface {
    return $this->response;
  }

}