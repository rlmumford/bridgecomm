<?php

namespace BridgeComm;

use BridgeComm\Exception\RequestException;
use BridgeComm\Exception\ResponseException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

class Client {

  /**
   * @var \GuzzleHttp\ClientInterface
   */
  protected $http;

  /**
   * @var \BridgeComm\ResponseFactory
   */
  protected $responseFactory;

  /**
   * Client constructor.
   *
   * @param \GuzzleHttp\ClientInterface|null $http
   * @param \BridgeComm\ResponseFactory|null $response_factory
   */
  public function __construct(ClientInterface $http = NULL, ResponseFactory $response_factory = NULL) {
    $this->http = $http;
    $this->responseFactory = $response_factory;
  }

  /**
   * Get the http client.
   *
   * @return \GuzzleHttp\ClientInterface
   */
  protected function http(): ClientInterface {
    return $this->http ?: new \GuzzleHttp\Client();
  }

  /**
   * Get the response factory.
   *
   * @return \BridgeComm\ResponseFactory
   */
  protected function responseFactory(): ResponseFactory {
    return $this->responseFactory ?: new ResponseFactory();
  }

  /**
   * Get the url
   *
   * @return string
   */
  protected function url(): string {
    return "RequestHandler.svc";
  }

  /**
   * Send a request to BridgeComm
   *
   * @param \BridgeComm\RequestInterface $request
   *
   * @return \BridgeComm\ResponseInterface
   * @throws \BridgeComm\Exception\RequestException
   * @throws \BridgeComm\Exception\ResponseException
   */
  public function sendRequest(RequestInterface $request): ResponseInterface {
    try {
      $content = new \DOMDocument('1.0');
      $envelope = $content->createElement('soap:Envelope');
      $envelope->setAttribute(
        'xmlns:soap',
        'http://schemas.xmlsoap.org/soap/envelope/'
      );
      $envelope->setAttribute(
        'xmlns:req',
        'http://bridgepaynetsecuretx.com/requesthandler'
      );
      $envelope->appendChild($content->createElement('soap:Header'));
      $body = $content->createElement('soap:Body');
      $body->appendChild(
        $content->createElement('req:ProcessRequest')
      )->appendChild(
        $content->createElement(
          'req:requestMsg',
          base64_encode($request->toXml())
        )
      );
      $envelope->appendChild($body);
      $content->appendChild($envelope);

      $response = $this->http()->request(
        'POST',
        $this->url(),
        [
          'headers' => [
            'SOAPAction' => 'http://bridgepaynetsecuretx.com/requesthandler/IRequestHandler/ProcessRequest',
            'Content-Type' => 'text/xml',
          ],
          'body' => $content->saveXML(),
        ]
      );

      $response_xml = simplexml_load_string((string) $response->getBody());
      $result = $response_xml->xpath(
        '//s:Body'
      );

      if (empty($result)) {
        throw new RequestException('Invalid XML response.', '', NULL, $request);
      }
      $result_string = base64_decode((string) reset($result)->ProcessRequestResponse->ProcessRequestResult);
      $result_xml = simplexml_load_string($result_string);

      $response = $this->responseFactory()->createFromXml($result_xml, $request);
      if ($response->isError()) {
        throw new ResponseException($response);
      }
      return $response;
    }
    catch (GuzzleException $exception) {
      throw new RequestException($exception->getMessage(), $exception->getCode(), $exception, $request);
    }
  }
}