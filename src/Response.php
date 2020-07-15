<?php

namespace BridgeComm;

class Response implements ResponseInterface {

  /**
   * @var \BridgeComm\RequestInterface
   */
  protected $request;

  /**
   * @var string
   */
  protected $transactionId;

  /**
   * @var string
   */
  protected $code;

  /**
   * @var string
   */
  protected $description;

  /**
   * @var \BridgeComm\ResponseMessageInterface
   */
  protected $message;

  /**
   * Response constructor.
   *
   * @param \BridgeComm\RequestInterface $request
   * @param string $id
   * @param string $code
   * @param string $description
   */
  public function __construct(RequestInterface $request, string $id, string $code, string $description = '') {
    $this->request = $request;
    $this->transactionId = $id;
    $this->code = $code;
    $this->description = $description;
  }

  /**
   * {@inheritdoc}
   */
  public function setMessage(ResponseMessageInterface $message): ResponseInterface {
    $this->message = $message;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getMessage(): ResponseMessageInterface {
    return $this->message;
  }

  /**
   * {@inheritdoc}
   */
  public function getRequest(): RequestInterface {
    return $this->request;
  }

  /**
   * {@inheritdoc}
   */
  public function getId(): string {
    return $this->transactionId;
  }

  /**
   * {@inheritdoc}
   */
  public function getCode(): string {
    return $this->code;
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription(): string {
    return $this->description;
  }

  /**
   * {@inheritdoc}
   */
  public function isError(): bool {
    return $this->code != '00000';
  }
}