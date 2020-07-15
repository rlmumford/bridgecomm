<?php


namespace BridgeComm;


class RequestCredentials implements RequestCredentialsInterface {

  protected $user;

  protected $password;

  public function __construct(string $user, string $password) {
    $this->user = $user;
    $this->password = $password;
  }

  /**
   * @inheritDoc
   */
  public function getUser(): string {
    return $this->user;
  }

  /**
   * @inheritDoc
   */
  public function getPassword(): string {
    return $this->password;
  }
}