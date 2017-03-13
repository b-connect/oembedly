<?php

namespace Drupal\oembedly;

use Drupal\Core\Config\ConfigFactory;

/**
 * Class OembedlyManagerService.
 *
 * @package Drupal\oembedly
 */
class OembedlyManagerService implements OembedlyManagerServiceInterface {

  /**
   * Drupal\Core\Config\ConfigFactory definition.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * Constructor.
   */
  public function __construct(ConfigFactory $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * Constructor.
   */
  public function createEmbed($url, array $config = []) {
    $config = [
      'min_image_width' => 100,
      'min_image_height' => 100,
      'images_blacklist' => 'example.com/*',
      'choose_bigger_image' => TRUE,
      'html' => [
        'max_images' => 10,
        'external_images' => TRUE,
      ],
      'oembed' => [
        'parameters' => [],
        'embedly_key' => 'YOUR_KEY',
        'iframely_key' => 'YOUR_KEY',
        'iframely_url' => 'http://88.198.62.12:8080',
      ],
      'google' => [
        'key' => 'YOUR_KEY',
      ],
      'soundcloud' => [
        'key' => 'YOUR_KEY',
      ],
    ];
    return OembedlyEmbed::create($url, $config);
  }

  public function build($url) {
    $info = $this->createEmbed($url);
    return [
      '#code' => $info->getCode(),
      '#theme' => 'oembedly',
      '#info' => $info,
    ];
  }

}
