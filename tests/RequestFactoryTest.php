<?php


namespace BridgeComm\Tests;

use BridgeComm\Exception\UnsupportedRequestTypeException;
use BridgeComm\Request;
use BridgeComm\RequestFactory;
use BridgeComm\RequestMessage\ProcessPaymentRequestMessage;
use PHPUnit\Framework\TestCase;

class RequestFactoryTest extends TestCase {

  public function testUnsupportedRequestType() {
    $this->expectException(UnsupportedRequestTypeException::class);
    (new RequestFactory())->createRequestMessage('076');
  }

  public function testProcessPaymentRequestMessage() {
    $factory = new RequestFactory();

    /** @var ProcessPaymentRequestMessage $message */
    $message = $factory->createRequestMessage(Request::R_PROCESS_PAYMENT);

    $this->assertInstanceOf(ProcessPaymentRequestMessage::class, $message);
    $message->setPaymentCard('5439750001500347', '12/29', '999');
  }

}