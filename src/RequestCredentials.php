<?php


namespace BridgeComm;


class RequestCredentials implements RequestCredentialsInterface {

  /**
   * @var string
   */
  protected $user;

  /**
   * @var string
   */
  protected $password;

  /**
   * RequestCredentials constructor.
   *
   * @param string $user
   * @param string $password
   */
  public function __construct(string $user, string $password) {
    $this->user = $user;
    $this->password = $password;
  }

  /**
   * {@inheritdoc}
   */
  public function applyToXML(\DOMElement $request, \DOMDocument $document): void {
    $request->appendChild($document->createElement('User', $this->getUser()));
    $request->appendChild($document->createElement('Password', $this->getPassword()));
  }

  /**
   * {@inheritdoc}
   */
  protected function getUser(): string {
    return $this->user;
  }

  /**
   * {@inheritdoc}
   */
  protected function getPassword(): string {
    return $this->password;
  }
}