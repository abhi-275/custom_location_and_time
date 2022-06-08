<?php

namespace Drupal\custom_location_and_time\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\custom_location_and_time\CustomCurrentTimeService;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a Plugin for the Time and Location Block.
 *
 * @Block(
 *   id = "custom_location_and_time_block",
 *   admin_label = @Translation("Custom Location and Time"),
 * )
 */
class CustomLocationTimeBlock extends BlockBase implements ContainerFactoryPluginInterface {
  /**
   * Stores the configuration factory.
   *
   * @var Drupal\custom_location_and_time\CustomCurrentTimeService
   */
  protected $currentTime;

  /**
   * The config factory.
   *
   * @var Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Creates a SystemBrandingBlock instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param Drupal\custom_location_and_time\CustomCurrentTimeService $current_time
   *   The factory for configuration objects.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory mservice.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CustomCurrentTimeService $current_time, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentTime = $current_time;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('custom_location_and_time.current_time'),
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->configFactory->get('custom_location_and_time.settings');
    return [
      '#theme' => 'custom_location_and_time',
      '#country' => !is_null($config->get('country')) ? $config->get('country') : '',
      '#city' => !is_null($config->get('city')) ? $config->get('city') : '',
      '#time' => $this->currentTime->getCurrentTimeValue(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 60;
  }

}
