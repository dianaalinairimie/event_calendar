<?php

namespace Drupal\event_calendar;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\token\TokenInterface;

/**
 * Class EmailSender.
 *
 * @package Drupal\event_calendar
 */
class EmailSender implements EmailSenderInterface {

  /**
   * Contains the configuration object factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The current user service.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * The token service.
   *
   * @var \Drupal\Core\Utility\Token
   */
  protected $token;

  /**
   * TaxonomyTermsGenerator constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration object factory.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user service.
   * @param \Drupal\token\TokenInterface $token
   *   The token service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, AccountInterface $current_user, TokenInterface $token) {
    $this->configFactory = $config_factory;
    $this->currentUser = $current_user;
    $this->token = $token;
  }

  /**
   * {@inheritdoc}
   */
  public function sendEmail($data) {
    // Gets events settings.
    $events_settings = $this->configFactory->get('events.settings');
    // Gets email configuration from settings form.
    $params = [
      'subject' => $this->token
        ->replace($events_settings->get('admin_email_subject'), $data),
      'body' => $this->token
        ->replace($events_settings->get('admin_email_content'), $data)
    ];

    // If this setting exists, send email when a new event is created.
    send_email('event_calendar', 'basic', $this->currentUser->getEmail(),
      $this->currentUser->getPreferredLangcode(), $params);
  }

}
