<?php

namespace Drupal\event_calendar\Plugin\Action;

use Drupal\Core\Action\ActionBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Redirects to a node deletion form.
 *
 * @Action(
 *   id = "update_event",
 *   label = @Translation("Update Event"),
 *   type = "node",
 * )
 */
class UpdateEvent extends ActionBase {

  /**
   * {@inheritdoc}
   */
  public function executeMultiple(array $entities) {
    $status = $entities['action'];
    unset($entities['action']);

    foreach ($entities as $node) {
      $node->set('field_event_status', $status);
      $node->save();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function execute($object = NULL) {
    $this->executeMultiple(array($object));
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    // @todo: after development declarations like that should be removed.
    // @todo: it affects the performance.
    /** @var \Drupal\node\NodeInterface $object */
    $result = $object->access('update', $account, TRUE)
      ->andIf($object->status->access('edit', $account, TRUE));

    return $return_as_object ? $result : $result->isAllowed();
  }

}
