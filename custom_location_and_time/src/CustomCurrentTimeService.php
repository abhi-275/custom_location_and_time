<?php

namespace Drupal\custom_location_and_time;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Instance of service.
 *
 * @var Drupal\custom_location_and_time\Services
 */
class CustomCurrentTimeService {

  /**
   * The datetime.time service.
   *
   * @var Drupal\Component\Datetime\TimeInterface
   */
  protected $timeService;

  /**
   * The config factory.
   *
   * @var Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The DrupalDateTime instance.
   *
   * @var Drupal\Core\Datetime\DrupalDateTime
   */
  protected $dateAndTime;

  /**
   * CustomCurrentTimeService constructor.
   *
   * @param Drupal\Component\Datetime\TimeInterface $time_service
   *   The datetime.time service.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory service.
   */
  public function __construct(TimeInterface $time_service, ConfigFactoryInterface $config_factory) {
    $this->timeService = $time_service;
    $this->configFactory = $config_factory;
  }

  /**
   * Retrives current time according to timezone selected from config form.
   *
   * @return string
   *   Returns string of timezone based time.
   */
  public function getCurrentTimeValue() {
    $config = $this->configFactory->get('custom_location_and_time.settings');
    if ($config->get('timezone')) {
      $current_timestamp = $this->timeService->getCurrentTime();
      return DrupalDateTime::createFromTimestamp($current_timestamp, $config->get('timezone'))->format("jS M Y - g:i A");
    }
    else {
      return FALSE;
    }
  }

}
