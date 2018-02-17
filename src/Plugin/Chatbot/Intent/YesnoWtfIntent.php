<?php

namespace Drupal\chatbot_api_yesnowtf\Plugin\Chatbot\Intent;

use Drupal\chatbot_api\Plugin\IntentPluginBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use GuzzleHttp\Client;

/**
 * Plugin implementation of the 'yesnowtf' Chatbot API Intent.
 *
 * @Intent(
 *   id = "yesnowtf",
 *   label = @Translation("YesnoWTF")
 * )
 */
class YesnoWtfIntent extends IntentPluginBase implements ContainerFactoryPluginInterface {

  protected $client;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, Client $client) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->client = $client;
  }

  /**
   * {inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('http_client')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function process() {
    // @TODO: add response as custom payload.
    $response = $this->getResponseFromYesnowtf();
    $this->response->setIntentResponse($response->answer);
    $this->response->setIntentDisplayCard($response->image);
  }

  public function getResponseFromYesnowtf() {
    $request = $this->client->get('https://yesno.wtf/api');
    return json_decode((string) $request->getBody());
  }

}
