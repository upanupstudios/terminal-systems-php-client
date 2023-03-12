<?php

namespace Upanupstudios\TerminalSystems\Php\Client;

final class Config
{
  private $apiKey;
  private $airportCode;

  public function __construct(string $apiKey, string $airportCode)
  {
    $this->apiKey = $apiKey;
    $this->airportCode = $airportCode;
  }

  /**
   * Get API key.
   */
  public function getApiKey(): string
  {
    return $this->apiKey;
  }

  /**
   * Get airport code.
   */
  public function getAirportCode(): string
  {
    return $this->airportCode;
  }
}