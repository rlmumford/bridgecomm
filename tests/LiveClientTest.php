<?php

namespace BridgeComm\Tests;

use BridgeComm\Client;
use BridgeComm\EnvRequestDefaults;
use BridgeComm\Exception\ResponseException;
use BridgeComm\Request;
use BridgeComm\RequestFactory;
use BridgeComm\RequestMessage\CardTokenRequestMessage;
use BridgeComm\RequestMessage\ProcessPaymentRequestMessage;
use BridgeComm\RequestMessage\VoidRefundRequestMessage;
use BridgeComm\ResponseInterface;
use BridgeComm\ResponseMessage\CardTokenResponseMessage;
use BridgeComm\ResponseMessage\ProcessPaymentResponseMessage;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class LiveClientTest extends TestCase {

  /**
   * @var \BridgeComm\Client
   */
  protected $client;

  /**
   * @var \BridgeComm\RequestFactory
   */
  protected $requestFactory;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    if (!file_exists(__DIR__.'/.env')) {
      $this->markTestSkipped('No test credentials available');
      return;
    }
    Dotenv::createImmutable(__DIR__)->load();

    $this->client = new Client(new \GuzzleHttp\Client(['base_uri' =>  'https://www.bridgepaynetsecuretest.com/PaymentService/']));
    $this->requestFactory = new RequestFactory(new EnvRequestDefaults());
  }

  public function testLivePing() {
    $request = $this->requestFactory->createRequest(
      $this->requestFactory->createRequestMessage(Request::R_PING)
    );
    $response = $this->client->sendRequest($request);
    $this->assertFalse($response->isError(), 'PING Request Successful');
  }

  public function testLiveCardTokenRequest() {
    /** @var \BridgeComm\RequestMessage\CardTokenRequestMessage $message */
    $message = $this->requestFactory->createRequestMessage(Request::R_MULTI_TOKEN);
    $this->assertInstanceOf(CardTokenRequestMessage::class, $message);

    $message->setPaymentAccountNumber('5439750001500347');
    $message->setExpirationDate('12/29');

    $request = $this->requestFactory->createRequest($message);

    $this->assertInstanceOf(Request::class, $request);

    $response = $this->client->sendRequest($request);

    $this->assertInstanceOf(ResponseInterface::class, $response);
    $this->assertFalse($response->isError(), "Response is not an error.");
    $this->assertEquals($request->getTransactionId(), $response->getId(), 'Transaction IDS match');

    /** @var \BridgeComm\ResponseMessage\CardTokenResponseMessage $token_response */
    $token_response = $response->getMessage();
    $this->assertInstanceOf(CardTokenResponseMessage::class, $token_response);
    $this->assertNotEmpty($token_response->getToken());
    $this->assertNotEmpty($token_response->getExpirationDate());
    $this->assertEquals('12/29', $token_response->getExpirationDate());

    // Next test taking a payment with the token.
    /** @var \BridgeComm\RequestMessage\ProcessPaymentRequestMessage $message */
    $message = $this->requestFactory->createRequestMessage(Request::R_PROCESS_PAYMENT);
    $message->setToken($token_response->getToken())
      ->setAmount('100')
      ->setTransIndustryType(ProcessPaymentRequestMessage::ITC_ECOMMERCE)
      ->setHolderType(ProcessPaymentRequestMessage::HT_PERSONAL)
      ->setAccountType(ProcessPaymentRequestMessage::AT_CREDIT_CARD);
    $request = $this->requestFactory->createRequest($message);

    try {
      $response = $this->client->sendRequest($request);
    }
    catch (ResponseException $e) {
      var_dump($e->getResponse());
      var_dump($e->getResponse()->getRequest()->toXml());
      $response = $e->getResponse();
    }

    $this->assertFalse($response->isError(), 'Response is not an error.');
    $this->assertInstanceOf(ProcessPaymentResponseMessage::class, $response->getMessage());
  }

  public function testLiveProcessPaymentRequest() {
    /** @var \BridgeComm\RequestMessage\ProcessPaymentRequestMessage $message */
    $message = $this->requestFactory->createRequestMessage(Request::R_PROCESS_PAYMENT);
    $message->setPaymentCard('4111111111111111', '1222','999')
      ->setAmount(4500)
      ->setTransactionType(ProcessPaymentRequestMessage::TT_SALE)
      ->setTransIndustryType(ProcessPaymentRequestMessage::ITC_ECOMMERCE)
      ->setAccountHolderName('Bob Jones')
      ->setHolderType('P')
      ->setAccountAddress('123 Main Street', '28540');
    $request = $this->requestFactory->createRequest($message);

    try {
      $response = $this->client->sendRequest($request);
    }
    catch (ResponseException $e) {
      $response = $e->getResponse();
    }

    $this->assertFalse($response->isError(), 'Response is not error');

    /** @var \BridgeComm\RequestMessage\VoidRefundRequestMessage $void_message */
    $void_message = $this->requestFactory->createRequestMessage(Request::R_VOID_REFUND);
    $void_message->setAmount(4500);
    $void_message->setReferenceNumber(
      $response->getMessage()->getGatewayTransID()
    );
    $void_message->setTransactionType(VoidRefundRequestMessage::TT_VOID);
    $void_request = $this->requestFactory->createRequest($void_message);
    $void_message->setTransactionCode($void_request->getTransactionId());
    $response = $this->client->sendRequest($void_request);

    $this->assertFalse($response->isError(), 'Void response is not an error');
    /** @var \BridgeComm\ResponseMessage\VoidRefundResponseMessage $void_response_message */
    $void_response_message = $response->getMessage();
    $this->assertEquals(0, $void_response_message->getRemainingAmount());
  }

}