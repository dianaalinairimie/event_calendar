<?php

namespace Drupal\event_calendar;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Url;
use Drupal\node\NodeInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class MessageBuilder.
 *
 * @package Drupal\event_calendar
 */
class MessageBuilder implements MessageBuilderInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Contains the configuration object factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * TaxonomyTermsGenerator constructor.
   *
   * @param \EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory to get the installed themes.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, ConfigFactoryInterface $config_factory, RequestStack $request_stack) {
    $this->entityTypeManager = $entity_type_manager;
    $this->configFactory = $config_factory;
    $this->requestStack = $request_stack;
  }

  /**
   * {@inheritdoc}
   */
  public function buildMessage(NodeInterface $node) {
    // Checks if event has status field.
    if (!empty($event_status = $node->get('field_event_status')
      ->first())) {
      // Sets events status name
      $status_name = $this->entityTypeManager
        ->getStorage('taxonomy_term')
        ->load($event_status->getValue()['target_id']);
    }

    // Sets data for email content.
    $data = [
      'event_name' => $node->getTitle(),
      'site_name' => $this->configFactory->get('system.site')->get('name'),
      'start_date' => $node->get('field_event_date')
        ->first()
        ->getValue()['value'],
      'event_author' => $node->getOwner()->getDisplayName(),
      'approval_url' => $this->requestStack->getCurrentRequest()
          ->getSchemeAndHttpHost() . Url::fromRoute('event_calendar.manage_events')
          ->toString(),
      'event_status' => $status_name->getName()
    ];

    return $data;
  }

}
