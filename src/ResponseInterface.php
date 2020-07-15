<?php

namespace BridgeComm;

interface ResponseInterface {

  /**
   * Set the message
   *
   * @param \BridgeComm\ResponseMessageInterface $message
   *
   * @return $this|\BridgeComm\ResponseInterface
   */
  public function setMessage(ResponseMessageInterface $message): ResponseInterface;

  /**
   * Get the response message
   *
   * @return \BridgeComm\ResponseMessageInterface
   */
  public function getMessage(): ResponseMessageInterface;

  /**
   * @return \BridgeComm\RequestInterface
   */
  public function getRequest(): RequestInterface;

  /**
   * @return string
   */
  public function getId(): string;

  /**
   * @return string
   */
  public function getCode(): string;

  /**
   * @return string
   */
  public function getDescription(): string;

  /**
   * Is this an error response.
   *
   * @return bool
   */
  public function isError(): bool;

}