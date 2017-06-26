<?php

namespace Drupal\event_calendar;

/**
 * Interface EmailSenderInterface.
 *
 * @package Drupal\event_calendar
 */
interface EmailSenderInterface {

  /**
   * Construct content and send email.
   *
   * @param $data
   *   An array with information for email content.
   */
  public function sendEmail($data);

}