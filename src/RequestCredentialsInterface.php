<?php

namespace BridgeComm;

interface RequestCredentialsInterface {

  /**
   * Username
   *
   * @return string
   */
  public function getUser(): string;

  /**
   * Get the password.
   *
   * @return string
   */
  public function getPassword(): string;

}