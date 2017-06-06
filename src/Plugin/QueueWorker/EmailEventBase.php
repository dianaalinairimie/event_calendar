<?php

namespace Drupal\event_calendar\Plugin\QueueWorker;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\Core\Mail\MailManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *
 * @QueueWorker(
 * id = "email_processor",
 * title = "My custom Queue Worker",
 * cron = {"time" = 10}
 * )
 */
class EmailEventBase extends QueueWorkerBase implements ContainerFactoryPluginInterface {

  /**
   *
   * @var Drupal\Core\Mail\MailManager
   */
  protected $mail;

  /**
   * constructor
   */
  public function __construct(MailManager $mail) {
    $this->mail = $mail;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('plugin.manager.mail')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function processItem($data) {

    // Send email when a new event is created if this setting exists.
    $this->mail->mail('event_calendar', 'basic', $data->email, $data->langcode, $data->data);

  }

}
