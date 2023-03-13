<?php

namespace Upanupstudios\TerminalSystems\Php\Client;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;

class TerminalSystems
{
  /**
   * The REST API URL.
   *
   * @var string $api_url
   */
  private $api_url = 'https://[code].terminalsystems.com/api_export.php';

  private $config;
  private $httpClient;

  public function __construct(Config $config, ClientInterface $httpClient)
  {
    $this->config = $config;
    $this->httpClient = $httpClient;
  }

  public function getApiUrl()
  {
    return $this->api_url;
  }

  public function getConfig(): Config
  {
    return $this->config;
  }

  public function request(string $method, array $options = [])
  {
    try {
      $apiKey = $this->config->getApiKey();
      $airportCode = $this->config->getAirportCode();

      $api_url = str_replace('[code]', $airportCode, $this->api_url);

      $defaultOptions = [
        'headers' => [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json',
        ],
        'query' => ['apikey' => $apiKey]
      ];

      if(!empty($options)) {
        // Deep merge array
        $options = array_merge_recursive($defaultOptions, $options);
      } else {
        $options = $defaultOptions;
      }

      $request = $this->httpClient->request($method, $api_url, $options);

      $response = $request->getBody();
      $response = $response->__toString();
    } catch (RequestException $exception) {
      $response = $exception->getMessage();
    }

    return $response;
  }

  public function arrivals() {
    $data = $this->request('GET', ['query' => ['arrdep' => 'a']]);

    try {
      $data = json_decode($data, TRUE);
    } catch (\JsonException $exeption) {
      throw new \JsonException($exeption->getMessage(), $exeption->getCode(), $exeption);
    }

    return $data;
  }

  public function departures() {
    $data = $this->request('GET', ['query' => ['arrdep' => 'd']]);

    try {
      $data = json_decode($data, TRUE);
    } catch (\JsonException $exeption) {
      throw new \JsonException($exeption->getMessage(), $exeption->getCode(), $exeption);
    }

    return $data;
  }
}