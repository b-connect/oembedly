<?php

namespace Drupal\oembedly;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Config\Config;

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
  protected $cacheBackend;
  const MAX_AGE = 3600;

  /**
   * Constructor.
   */
  public function __construct(ConfigFactory $config_factory, CacheBackendInterface $cache_backend) {
    $this->configFactory = $config_factory;
    $this->cacheBackend = $cache_backend;
  }

  /**
   * Constructor.
   */
  public function createEmbed($url, Config $config) {
    $configKey = 'oembedly_' . md5($url);
    if ($data = $this->cacheBackend->get($configKey)) {
      return $data->data;
    }
    $info = OembedlyEmbed::create($url, $config->getRawData());
    $this->cacheBackend->set($configKey, $info, time() + OembedlyManagerService::MAX_AGE);
    return $info;
  }

  /**
   * {@inheritdoc}
   */
  public function build($url) {
    $config = $this->configFactory->get('oembedly.settings');
    $info = $this->createEmbed($url, $config);
    if (!$info) {
      return [];
    }
    $renderer = \Drupal::service('renderer');
    $build = [
      '#code' => $info->getCode(),
      '#theme' => 'oembedly',
      '#info' => $info,
      '#cache' => ['max-age' => OembedlyManagerService::MAX_AGE],
    ];
    $renderer->addCacheableDependency($build, $config);
    return $build;
  }

}
