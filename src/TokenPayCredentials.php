<?php

namespace BridgeComm;

class TokenPayCredentials implements RequestCredentialsInterface {

  /**
   * @var string
   */
  protected $privateKey;

  /**
   * @var string
   */
  protected $authenticationTokenId;

  /**
   * TokenPayCredentials constructor.
   *
   * @param string $private_key
   * @param string $auth_token_id
   */
  public function __construct(string $private_key, string $auth_token_id) {
    $this->privateKey = $private_key;
    $this->authenticationTokenId = $auth_token_id;
  }

  /**
   * {@inheritdoc}
   */
  public function applyToXML(\DOMElement $request, \DOMDocument $document): void {
    $request->appendChild(
      $document->createElement('PrivateKey', $this->privateKey)
    );
    $request->appendChild(
      $document->createElement('AuthenticationTokenId', $this->authenticationTokenId)
    );
  }
}