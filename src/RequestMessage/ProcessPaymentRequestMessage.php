<?php

namespace BridgeComm\RequestMessage;

use BridgeComm\RequestMessage;

class ProcessPaymentRequestMessage extends RequestMessage {

  const REQUEST_TYPE = '004';

  /**
   * Account type constants
   */
  const AT_CREDIT_CARD = 'R';
  const AT_DEBIT_CARD = 'D';
  const AT_SAVING_ACC = 'S';
  const AT_CHECK_ACC = 'C';
  const AT_FOOD_EBT = 'F';
  const AT_CASH_EBT = 'H';
  const AT_GIFT_CARD = 'G';
  const AT_FLEET = 'L';
  const AT_CHECK = 'K';
  const AT_CASH = 'A';

  /**
   * Holder type constants
   */
  const HT_PERSONAL = 'P';
  const HT_ORGANIZATION = 'O';

  /**
   * Transaction type constants.
   */
  const TT_SALE = 'sale';
  const TT_SALE_AUTH = 'sale-auth';
  const TT_CREDIT = 'credit';
  const TT_CREDIT_AUTH = 'credit-auth';
  const TT_INCREMENT = 'increment';
  const TT_SALE_INFO = 'sale-info';
  const TT_CREDIT_INFO = 'credit-info';
  const TT_ADJUSTMENT = 'adjustment';

  /**
   * Trans industry type.
   */
  const ITC_RETAIL = 'RE';
  const ITC_RESTAURANT = 'RS';
  const ITC_ECOMMERCE = 'EC';
  const ITC_DIRECT = 'DM';
  const ITC_LODGING = 'LD';
  const ITC_CAR_RENTAL = 'CR';
  const ITC_HEALTHCARE = 'HC';
  const ITA_CORPORATE_CREDIT = 'CCD';
  const ITA_PRE_PAYMENT = 'PPD';
  const ITA_POINT_PURCHASE = 'POP';
  const ITA_TEL_INIT = 'TEL';
  const ITA_WEB_INIT = 'WEB';
  const ITA_CHECK21 = 'C21';


  protected $amount;

  protected $transactionType = self::TT_SALE;

  protected $transIndustryType;

  /**
   * Account Type. One of:
   * - 'R' - Credit Card & Branded Debit Cards
   * - 'D' - Unbranded Debit cards
   * - 'S' - Savings bank account
   * - 'C' - Checking Bank Account
   * - 'F' - EBT Food Stamp
   * - 'H' - EBT Cash Benefit
   * - 'G' - Gift card
   * - 'L' - Fleet
   * - 'K' - Check
   * - 'A' - Cash
   *
   * @var string
   */
  protected $acctType;

  /**
   * The holder type. One of:
   * - 'P' - Personal
   * - 'O' - Organiztional.
   *
   * @var string
   */
  protected $holderType;

  /**
   * @var string
   */
  protected $paymentAccountNumber;

  protected $expirationDate;

  protected $securityCode;

  protected $token;

  /**
   * The transaction mode - N for card not present.
   *
   * @var string
   */
  protected $transactionMode = 'N';

  /**
   * The transaction category code:
   *
   * Supported values are: B (BillPayment), R (Recurring), I (Installment), H (Healthcare).
   *
   * @var string
   */
  protected $transCatCode = 'B';

  protected $bankAccountNum;

  protected $routingNum;

  protected $accountHolderName;

  protected $accountStreet;

  protected $accountZip;

  protected $accountPhone;

  /**
   * @param string $type
   *
   * @return \BridgeComm\RequestMessage\ProcessPaymentRequestMessage
   */
  public function setAccountType(string $type): ProcessPaymentRequestMessage {
    $this->acctType = $type;
    return $this;
  }

  /**
   * Set the amount.
   *
   * @param int $amount
   *
   * @return \BridgeComm\RequestMessage\ProcessPaymentRequestMessage
   */
  public function setAmount(int $amount): ProcessPaymentRequestMessage {
    $this->amount = $amount;
    return $this;
  }

  /**
   * Set the holder type.
   *
   * @param string $type
   *
   * @return \BridgeComm\RequestMessage\ProcessPaymentRequestMessage
   */
  public function setHolderType(string $type): ProcessPaymentRequestMessage {
    if (strlen($type) > 1 || !in_array($type, ['P',  'O'])) {
      throw new \InvalidArgumentException("{$type} is not a valid holder type.");
    }

    $this->holderType = $type;
    return $this;
  }

  /**
   * Set the transaction type.
   *
   * @param string $type
   *
   * @return \BridgeComm\RequestMessage\ProcessPaymentRequestMessage
   */
  public function setTransactionType(string $type): ProcessPaymentRequestMessage {
    $this->transactionType = $type;
    return $this;
  }

  /**
   * Set the transaction industry type.
   *
   * This is also set by serPaymentCard or setAchAccount if not already set.
   *
   * @param string $type
   *
   * @return \BridgeComm\RequestMessage\ProcessPaymentRequestMessage
   */
  public function setTransIndustryType(string $type): ProcessPaymentRequestMessage {
    $this->transIndustryType = $type;
    return $this;
  }

  /**
   * Set payment card details.
   *
   * @param string $number
   * @param string $expiration
   * @param string $cvv
   * @param string $card_type
   *
   * @return \BridgeComm\RequestMessage\ProcessPaymentRequestMessage
   */
  public function setPaymentCard(string $number, string $expiration, string $cvv, string $card_type = self::AT_CREDIT_CARD): ProcessPaymentRequestMessage {
    $this->paymentAccountNumber = $number;
    $this->expirationDate = $expiration;
    $this->securityCode = $cvv;
    $this->acctType = $card_type;

    if (empty($this->transIndustryType)) {
      $this->transIndustryType = static::ITC_ECOMMERCE;
    }

    return $this;
  }

  /**
   * Set the token.
   *
   * @param string $token
   *
   * @return \BridgeComm\RequestMessage\ProcessPaymentRequestMessage
   */
  public function setCardToken(string $token, string $expiration, string $card_type = self::AT_CREDIT_CARD): ProcessPaymentRequestMessage {
    $this->token = $token;
    $this->expirationDate = $expiration;
    $this->acctType = $card_type;

    if (empty($this->transIndustryType)) {
      $this->transIndustryType = static::ITC_ECOMMERCE;
    }

    return $this;
  }

  /**
   * Set the ACH  account
   *
   * @param string $account_no
   * @param string $routing_no
   *
   * @param string $type
   *
   * @return \BridgeComm\RequestMessage\ProcessPaymentRequestMessage
   */
  public function setAchAccount(string $account_no, string $routing_no, string $type = self::AT_CHECK_ACC): ProcessPaymentRequestMessage {
    unset($this->paymentAccountNumber);

    $this->bankAccountNum = $account_no;
    $this->routingNum = $routing_no;
    $this->acctType = $type;

    if (empty($this->transIndustryType)) {
      $this->transIndustryType = static::ITA_WEB_INIT;
    }

    return $this;
  }

  /**
   * Set the account holder name.
   *
   * @param string $name
   *
   * @return \BridgeComm\RequestMessage\ProcessPaymentRequestMessage
   */
  public function setAccountHolderName(string $name): ProcessPaymentRequestMessage {
    $this->accountHolderName = $name;
    return $this;
  }

  /**
   * Set the account address.
   *
   * @param string $street
   * @param string $zip
   * @param string $phone
   *
   * @return \BridgeComm\RequestMessage\ProcessPaymentRequestMessage
   */
  public function setAccountAddress(string $street, string $zip, string $phone = ''): ProcessPaymentRequestMessage {
    $this->accountStreet = $street;
    $this->accountZip = $zip;
    $this->accountPhone = $phone;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  protected function buildMessageXml(\DOMElement $message, \DOMDocument $document): void {
    parent::buildMessageXml($message, $document);

    if (!empty($this->token)) {
      $message->appendChild($document->createElement('Token', $this->token));
      $message->appendChild($document->createElement('ExpirationDate', $this->expirationDate));
    }
    else if (!empty($this->paymentAccountNumber)) {
      $message->appendChild($document->createElement('PaymentAccountNumber', $this->paymentAccountNumber));
      $message->appendChild($document->createElement('ExpirationDate', $this->expirationDate));
      $message->appendChild($document->createElement('SecurityCode', $this->securityCode));
    }
    else {
      $message->appendChild($document->createElement('BankAccountNum', $this->bankAccountNum));
      $message->appendChild($document->createElement('RoutingNum', $this->routingNum));
    }

    foreach (['accountHolderName', 'accountStreet', 'accountZip', 'accountPhone'] as $account_field) {
      if (!empty($this->{$account_field})) {
        $message->appendChild($document->createElement(ucfirst($account_field), $this->{$account_field}));
      }
    }

    $message->appendChild($document->createElement('Amount', $this->amount));
    $message->appendChild($document->createElement('TransactionType', $this->transactionType));
    $message->appendChild($document->createElement('TransIndustryType', $this->transIndustryType));
    $message->appendChild($document->createElement('HolderType', $this->holderType));
    $message->appendChild($document->createElement('AcctType', $this->acctType));
    $message->appendChild($document->createElement('TransactionMode', $this->transactionMode));
    $message->appendChild($document->createElement('TransCatCode', $this->transCatCode));
  }


}