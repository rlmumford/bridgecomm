<?php

namespace BridgeComm;

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
    return "";
  }

  public function sendRequest(RequestInterface $request): ResponseInterface {
    try {
      $content = new \DOMDocument('1.0');
      $envelope = $content->createElement('soap:Envelope');
      $envelope->setAttribute(
        'xmlns:soapenv',
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
        '//s:Body/ProcessRequestResponse/ProcessRequestResult'
      );

      if (!$result) {
        throw new \Exception($response->getBody());
      }
      $result_string = base64_decode((string) $result[0]);
      $result_xml = simplexml_load_string($result_string);

      return $this->responseFactory()->createFromXml($result_xml, $request);
    }
    catch (ClientException $exception) {
      throw $exception;
    }
    catch (GuzzleException $exception) {
      throw $exception;
    }
  }
}