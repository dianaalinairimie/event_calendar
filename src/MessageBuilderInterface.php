<?php

namespace Drupal\event_calendar;

use Drupal\node\NodeInterface;

/**
 * Interface MessageBuilderInterface.
 *
 * @package Drupal\event_calendar
 */
interface MessageBuilderInterface {

  /**
   * Construct the message for email.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The node entity.
   *
   * @return mixed
   *   An array with data.
   */
  public function buildMessage(NodeInterface $node);

}
