<?php

namespace BridgeComm\Tests;

use BridgeComm\Client;
use BridgeComm\Exception\ResponseException;
use BridgeComm\Request;
use BridgeComm\RequestCredentials;
use BridgeComm\RequestFactory;
use BridgeComm\RequestMessage\CardTokenRequestMessage;
use BridgeComm\RequestMessage\PingRequestMessage;
use BridgeComm\ResponseInterface;
use BridgeComm\ResponseMessage\CardTokenResponseMessage;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase {

  /**
   * @var \BridgeComm\Client
   */
  protected $client;

  /**
   * @var \GuzzleHttp\Handler\MockHandler
   */
  protected $mockHandler;

  /**
   * @var \BridgeComm\RequestFactory
   */
  protected $requestFactory;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    $this->mockHandler = new MockHandler();
    $this->requestFactory = new RequestFactory();
    $this->client = new Client(
      new \GuzzleHttp\Client([
        'handler' => $this->mockHandler,
      ])
    );
  }

  public function testPingRequest() {
    $this->mockHandler->append(new Response(
      200,
      [],
      ResponseHelper::wrapResult("<?xml version=\"1.0\" encoding=\"utf-8\"?>
<Ping>
<TransactionID></TransactionID>
<RequestType>099</RequestType>
<ResponseCode>00000</ResponseCode>
<ResponseDescription>SuccessfulRequest</ResponseDescription>
</Ping>")
    ));
    $request = $this->requestFactory->createRequest(
      $this->requestFactory->createRequestMessage(Request::R_PING),
      new RequestCredentials('test', 'test')
    );
    $request->setTransactionId(uniqid());

    $this->assertInstanceOf(Request::class, $request);
    $this->assertInstanceOf(PingRequestMessage::class, $request->getMessage());

    $response = $this->client->sendRequest($request);

    $this->assertInstanceOf(ResponseInterface::class, $response);
    $this->assertFalse($response->isError(), "Response is not an error.");
  }

  public function testErrorPingRequest() {
    $this->mockHandler->append(new Response(
      200,
      [],
      ResponseHelper::wrapResult("<?xml version=\"1.0\" encoding=\"utf-8\"?>
<ErrorResponse>
<TransactionID></TransactionID>
<RequestType>099</RequestType>
<ResponseCode>00100</ResponseCode>
<ResponseDescription>ErrorRequest</ResponseDescription>
</ErrorResponse>")
    ));
    $request = $this->requestFactory->createRequest(
      $this->requestFactory->createRequestMessage(Request::R_PING),
      new RequestCredentials('test', 'test')
    );
    $request->setTransactionId(uniqid());

    $this->assertInstanceOf(Request::class, $request);
    $this->assertInstanceOf(PingRequestMessage::class, $request->getMessage());

    try {
      $this->client->sendRequest($request);
      $this->assertTrue(FALSE, "No exception thrown.");
    }
    catch (ResponseException $exception) {
      $response = $exception->getResponse();

      $this->assertInstanceOf(ResponseInterface::class, $response);
      $this->assertTrue($response->isError(), "Response is an error");

      $this->assertEquals('00100', $response->getCode());
      $this->assertEquals('ErrorRequest', $response->getDescription());
    }
  }

  public function testCardTokenRequest() {
    $trans_id = uniqid();

    $this->mockHandler->append(new Response(
      200,
      [],
      ResponseHelper::wrapResult("<?xml version=\"1.0\" encoding=\"utf-8\"?>
<GetToken>
<TransactionID>{$trans_id}</TransactionID>
<RequestType>001</RequestType>
<ResponseCode>00000</ResponseCode>
<ResponseDescription>Successful Request</ResponseDescription>
<responseMessage>
<Token>1000000010260347</Token>
<ExpirationDate>1229</ExpirationDate>
</responseMessage>
</GetToken>")
    ));

    /** @var \BridgeComm\RequestMessage\CardTokenRequestMessage $message */
    $message = $this->requestFactory->createRequestMessage(Request::R_MULTI_TOKEN);
    $message->setMerchantAccountCode(uniqid());
    $message->setMerchantCode(uniqid());
    $message->setSoftwareVendor('php');
    $this->assertInstanceOf(CardTokenRequestMessage::class, $message);

    $message->setPaymentAccountNumber('5439750001500347');
    $message->setExpirationDate('12/29');

    $request = $this->requestFactory->createRequest(
      $message,
      new RequestCredentials('test', 'test')
    );
    $request->setTransactionId($trans_id);

    $this->assertInstanceOf(Request::class, $request);

    $response = $this->client->sendRequest($request);

    $this->assertInstanceOf(ResponseInterface::class, $response);
    $this->assertFalse($response->isError(), "Response is not an error.");
    $this->assertEquals($trans_id, $response->getId());

    /** @var \BridgeComm\ResponseMessage\CardTokenResponseMessage $message */
    $message = $response->getMessage();
    $this->assertInstanceOf(CardTokenResponseMessage::class, $message);
    $this->assertNotEmpty($message->getToken());
    $this->assertEquals('1000000010260347', $message->getToken());
    $this->assertNotEmpty($message->getExpirationDate());
    $this->assertEquals('1229', $message->getExpirationDate());
  }

}