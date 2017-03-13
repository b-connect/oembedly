<?php

namespace Drupal\oembedly;

use Drupal\Core\Config\Config;

/**
 * Interface OembedlyManagerServiceInterface.
 *
 * @package Drupal\oembedly
 */
interface OembedlyManagerServiceInterface {
  public function createEmbed($url, Config $config);
  public function build($url);
}
