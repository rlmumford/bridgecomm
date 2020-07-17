<?php

namespace BridgeComm;

class EnvRequestDefaults implements RequestDefaultsInterface {

  /**
   * @inheritDoc
   */
  public function getSoftwareVendor(): string {
    return $_ENV['BRIDGECOMM_SOFTWARE_VENDOR'];
  }

  /**
   * @inheritDoc
   */
  public function getMerchantCode(): string {
    return $_ENV['BRIDGECOMM_MERCHANT_CODE'];
  }

  /**
   * @inheritDoc
   */
  public function getMerchantAccountCode(): string {
    return $_ENV['BRIDGECOMM_MERCHANT_ACCOUNT_CODE'];
  }

  /**
   * @inheritDoc
   */
  public function getCredentials(): RequestCredentialsInterface {
    return new RequestCredentials($_ENV['BRIDGECOMM_USER'], $_ENV['BRIDGECOMM_PASSWORD']);
  }
}