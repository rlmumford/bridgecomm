<?php


namespace BridgeComm\Tests;

use BridgeComm\Exception\UnsupportedRequestTypeException;
use BridgeComm\Request;
use BridgeComm\RequestFactory;
use BridgeComm\RequestMessage\ProcessPaymentRequestMessage;
use BridgeComm\TokenPayCredentials;
use PHPUnit\Framework\TestCase;

class RequestFactoryTest extends TestCase {

  public function testUnsupportedRequestType() {
    $this->expectException(UnsupportedRequestTypeException::class);
    (new RequestFactory(new TestRequestDefaults()))->createRequestMessage('076');
  }

  public function testProcessPaymentRequestMessage() {
    $factory = new RequestFactory(new TestRequestDefaults());

    /** @var ProcessPaymentRequestMessage $message */
    $message = $factory->createRequestMessage(Request::R_PROCESS_PAYMENT);

    $this->assertInstanceOf(ProcessPaymentRequestMessage::class, $message);
    $message->setPaymentCard('5439750001500347', '12/29', '999');
  }

  public function testTokenPayAuthorizationRequest() {
    $factory = new RequestFactory(new TestRequestDefaults());

    /** @var \BridgeComm\RequestMessage\ProcessPaymentRequestMessage $message */
    $message = $factory->createRequestMessage(Request::R_PROCESS_PAYMENT);
    $message->setTransactionType(ProcessPaymentRequestMessage::TT_SALE);
    $message->setHolderType(ProcessPaymentRequestMessage::HT_PERSONAL);
    $message->setTransIndustryType(ProcessPaymentRequestMessage::ITC_ECOMMERCE);

    $request = $factory->createRequest(
      $message,
      uniqid(),
      new TokenPayCredentials('PrivateKeyCode', 'AUTHTOKEN')
    );

    $xml_string = $request->toXml();
    $xml = simplexml_load_string($xml_string);
    $this->assertEquals('PrivateKeyCode', (string) $xml->PrivateKey);
    $this->assertEquals('AUTHTOKEN', (string) $xml->AuthenticationTokenId);
  }

}