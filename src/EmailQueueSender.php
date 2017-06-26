<?php

namespace Drupal\event_calendar;

use Drupal\Core\Database\Database;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Queue\QueueFactory;
use Drupal\token\TokenInterface;

/**
 * Class EmailQueueSender.
 *
 * @package Drupal\event_calendar
 */
class EmailQueueSender implements EmailQueueSenderInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The token service.
   *
   * @var \Drupal\Core\Utility\Token
   */
  protected $token;

  /**
   * The queue service.
   *
   * @var \Drupal\Core\Queue\QueueFactory
   */
  protected $queueFactory;

  /**
   * TaxonomyTermsGenerator constructor.
   *
   * @param \EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\token\TokenInterface $token
   *   The token service.
   * @param \Drupal\Core\Queue\QueueFactory $queue_factory
   *   The queue service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, TokenInterface $token, QueueFactory $queue_factory) {
    $this->entityTypeManager = $entity_type_manager;
    $this->token = $token;
    $this->queueFactory = $queue_factory;
  }

  /**
   * {@inheritdoc}
   */
  public function sendEmail($events_settings, $recipients_options, $data) {
    // Gets email configuration from settings form.
    $params = [
      'subject' => $this->token
        ->replace($events_settings->get('users_email_subject'), $data),
      'body' => $this->token
        ->replace($events_settings->get('users_email_content'), $data)
    ];

    $queue = $this->queueFactory->get('email_processor');

    // Adds emails of recipients in queue.
    foreach ($recipients_options as $role) {
      if (!empty($role)) {
        $ids = Database::getConnection('default')
          ->select('user__roles', 'ur')
          ->fields('ur', ['entity_id'])
          ->condition('ur.roles_target_id', $role)
          ->execute()
          ->fetchAll();
        foreach ($ids as $id) {
          $user = $this->entityTypeManager
            ->getStorage('user')
            ->load($id->entity_id);
          $item = new \stdClass();
          $item->id = $id->entity_id;
          $item->data = $params;
          $item->email = $user->getEmail();
          $item->langcode = $user->getPreferredLangcode();
          $queue->createItem($item);
        }
      }
    }
  }

}
