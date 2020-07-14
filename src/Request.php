<?php

namespace BridgeComm;

class Request implements RequestInterface {

  /**
   * @var string
   */
  protected $type;

  /**
   * @var \DateTimeInterface
   */
  protected $dateTime;

  /**
   * @var \BridgeComm\RequestMessageInterface
   */
  protected $message;

  /**
   * @var string
   */
  protected $id;

  /**
   * @var \BridgeComm\RequestCredentialsInterface
   */
  protected $credentials;

  /**
   * {@inheritdoc
   */
  public function setRequestType(string $type): RequestInterface {
    $this->type = $type;
    return $this;
  }

  /**
   * {@inheritdoc
   */
  public function getRequestType(): ?string {
    return $this->type;
  }

  /**
   * {@inheritdoc
   */
  public function setRequestDateTime(\DateTimeInterface $date_time): RequestInterface {
    $this->dateTime = $date_time;
    return $this;
  }

  /**
   * {@inheritdoc
   */
  public function getRequestDateTime(): ?\DateTimeInterface {
    return $this->dateTime ?: new \DateTime();
  }

  /**
   * {@inheritdoc
   */
  public function setMessage(RequestMessageInterface $message): RequestInterface {
    $this->message = $message;
    return $this;
  }

  /**
   * {@inheritdoc
   */
  public function getMessage(): RequestMessageInterface {
    return $this->message;
  }

  /**
   * {@inheritdoc
   */
  public function setTransactionId(string $id): RequestInterface {
    $this->id = $id;
    return $this;
  }

  /**
   * {@inheritdoc
   */
  public function getTransactionId(): string {
    return $this->id;
  }

  /**
   * {@inheritdoc
   */
  public function setCredentials(RequestCredentialsInterface $credentials): RequestInterface {
    $this->credentials = $credentials;
    return $this;
  }

  /**
   * Get the request XML
   *
   * @return string
   */
  public function toXml(): string {
    $xml = new \DOMDocument('1.0', 'utf-8');
    $root = $xml->createElement('requestHeader');

    $root->appendChild($xml->createElement('ClientIdentifier', 'SOAP'));
    $root->appendChild($xml->createElement('RequestType', $this->getRequestType()));
    $root->appendChild($xml->createElement('RequestDateTime', $this->getRequestDateTime()->format('YmdHis')));
    if ($this->credentials) {
      $root->appendChild($xml->createElement('User', $this->credentials->getUser()));
      $root->appendChild($xml->createElement('Password', $this->credentials->getPassword()));
    }
    if ($id = $this->getTransactionId()) {
      $root->appendChild($xml->createElement('TransactionId', $id));
    }
    if ($message = $this->getMessage()) {
      $root->appendChild($message->toXml($xml));
    }

    $xml->appendChild($root);
    return $xml->saveXML();
  }
}