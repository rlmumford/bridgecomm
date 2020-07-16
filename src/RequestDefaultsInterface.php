<?php

namespace BridgeComm;

interface RequestDefaultsInterface {

  /**
   * Get software vendor
   *
   * @return string
   */
  public function getSoftwareVendor(): string;

  /**
   * Get merchant code
   *
   * @return string
   */
  public function getMerchantCode(): string;

  /**
   * Get merchant account code.
   *
   * @return string
   */
  public function getMerchantAccountCode(): string;

  /**
   * Get the credentials
   *
   * @return \BridgeComm\RequestCredentialsInterface
   */
  public function getCredentials(): RequestCredentialsInterface;

}