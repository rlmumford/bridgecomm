<?php

namespace BridgeComm\Exception;

use BridgeComm\RequestInterface;
use Throwable;

class RequestException extends BridgeCommException {

  /**
   * @var \BridgeComm\RequestInterface
   */
  protected $request;

  /**
   * RequestException constructor.
   *
   * @param string $message
   * @param int $code
   * @param \BridgeComm\RequestInterface|null $request
   * @param \Throwable|null $previous
   */
  public function __construct($message = "", $code = 0, Throwable $previous = NULL, RequestInterface $request = NULL) {
    $this->request = $request;

    //exception code conversion
    $code = filter_var($code, \FILTER_SANITIZE_NUMBER_INT);
    if ($code === false) {
      $code = 0;
    } else {
      $code = (int) $code;
    }

    parent::__construct($message, $code, $previous);
  }

  /**
   * @return \BridgeComm\RequestInterface
   */
  public function getRequest(): RequestInterface {
    return $this->request;
  }

}