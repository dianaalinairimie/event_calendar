<?php

namespace Drupal\event_calendar\Plugin\QueueWorker;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\Core\Mail\MailManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @todo: The indentation is not ok here.
 * @QueueWorker(
 * id = "email_processor",
 * title = "Email recipients Queue Worker",
 * cron = {"time" = 10}
 * )
 */
class EmailEventBase extends QueueWorkerBase implements ContainerFactoryPluginInterface {

  /**
   * The mail manager service.
   *
   * @var \Drupal\Core\Mail\MailManager
   */
  protected $mail;

  /**
   * EmailEventBase constructor.
   *
   * @param \Drupal\Core\Mail\MailManager $mail
   *   Mail manager service.
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
    // If this setting exists, send email when a new event is created.
    $this->mail->mail('event_calendar', 'basic', $data->email, $data->langcode, $data->data);
  }

}
