<?php

namespace BridgeComm\ResponseMessage;

use BridgeComm\ResponseMessageInterface;

class VoidRefundResponseMessage implements ResponseMessageInterface {

  /**
   * Response type constants
   */
  const RT_VOID = 'void';
  const RT_REFUND = 'refund';

  /**
   * @var string
   */
  protected $referenceNumber;

  /**
   * @var string
   */
  protected $transactionCode;

  /**
   * @var integer
   */
  protected $remainingAmount;

  /**
   * @var string
   */
  protected $responseType;

  /**
   * @var string
   */
  protected $merchantAccountCode;

  /**
   * @var string
   */
  protected $cardType;

  /**
   * VoidRefundResponseMessage constructor.
   *
   * @param string $reference
   * @param string $transactionCode
   * @param int $remainingAmount
   * @param string $responseType
   * @param string $merchantAccountCode
   * @param string $cardType
   */
  public function __construct(
    string $reference, string $transactionCode, int $remainingAmount,
    string $responseType, string $merchantAccountCode, string $cardType = ''
  ) {
    $this->referenceNumber = $reference;
    $this->transactionCode = $transactionCode;
    $this->remainingAmount = $remainingAmount;

    if (!in_array($responseType, [static::RT_REFUND, static::RT_VOID])) {
      throw new \InvalidArgumentException("{$responseType} is not a valid Response Type");
    }
    $this->responseType = $responseType;
    $this->merchantAccountCode = $merchantAccountCode;
    if (!empty($cardType)) {
      $this->cardType = $cardType;
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function createFromXml(\SimpleXMLElement $xml): ResponseMessageInterface {
    return new static(
      (string) $xml->ReferenceNumber,
      (string) $xml->TransactionCode,
      (int) $xml->RemainingAmount,
      (string) $xml->ResponseType,
      (string) $xml->MerchantAccountCode,
      $xml->CardType ? (string) $xml->CardType : ''
    );
  }

  /**
   * Get the reference number
   *
   * @return string
   */
  public function getReferenceNumber(): string {
    return $this->referenceNumber;
  }

  /**
   * The transaction code.
   *
   * @return string
   */
  public function getTransactionCode(): string {
    return $this->transactionCode;
  }

  /**
   * Get the remaining amount.
   *
   * @return int
   */
  public function getRemainingAmount(): int {
    return $this->remainingAmount;
  }

  /**
   * Get the response type.
   *
   * @return string
   */
  public function getResponseType(): string {
    return $this->responseType;
  }

}