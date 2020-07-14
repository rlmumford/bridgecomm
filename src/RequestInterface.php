<?php

namespace BridgeComm;

interface RequestInterface {

  /**
   * Request type constants.
   */
  const R_MULTI_TOKEN = '001';
  const R_PROCESS_PAYMENT = '004';
  const R_IS_DEBIT = '005';
  const R_CHANGE_PASSWORD = '007';
  const R_GET_MERCH_DATA = '011';
  const R_VOID_REFUND = '012';
  const R_ACCOUNT_TOKEN = '013';
  const R_GIFT_CARD = '014';
  const R_CAPTURE = '019';
  const R_INIT_SETTLE = '020';
  const R_PING = '099';

  /**
   * @param string $type
   *
   * @return \BridgeComm\RequestInterface
   */
  public function setRequestType(string $type): RequestInterface;

  public function getRequestType():? string;

  public function setRequestDateTime(\DateTimeInterface $date_time): RequestInterface;

  public function getRequestDateTime():? \DateTimeInterface;

  public function setMessage(RequestMessageInterface $message): RequestInterface;

  public function getMessage(): RequestMessageInterface;

  public function setTransactionId(string $id): RequestInterface;

  public function getTransactionId(): string;

  /**
   * Set the credentials
   *
   * @param \BridgeComm\RequestCredentialsInterface $credentials
   *
   * @return \BridgeComm\RequestInterface
   */
  public function setCredentials(RequestCredentialsInterface $credentials): RequestInterface;

  /**
   * Get the request XML
   *
   * @return string
   */
  public function toXml(): string;

}