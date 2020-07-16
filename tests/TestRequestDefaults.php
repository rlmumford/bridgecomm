<?php
namespace BridgeComm\Tests;

use BridgeComm\RequestCredentials;
use BridgeComm\RequestCredentialsInterface;
use BridgeComm\RequestDefaultsInterface;

class TestRequestDefaults implements RequestDefaultsInterface {

  /**
   * {@inheritdoc}
   */
  public function getSoftwareVendor(): string {
    return 'RazorSync Portal 6.9.10';
  }

  /**
   * {@inheritdoc}
   */
  public function getMerchantCode(): string {
    return '575000';
  }

  /**
   * {@inheritdoc}
   */
  public function getMerchantAccountCode(): string {
    return '575001';
  }

  /**
   * {@inheritdoc}
   */
  public function getCredentials(): RequestCredentialsInterface {
    return new RequestCredentials('dhaaspgtest1', '57!sE@3Fm');
  }
}