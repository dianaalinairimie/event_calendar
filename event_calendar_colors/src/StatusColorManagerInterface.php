<?php

namespace Drupal\event_calendar_colors;

/**
 * Interface StatusColorManagerInterface.
 *
 * @package Drupal\event_calendar_colors
 */
interface StatusColorManagerInterface {

  /**
   * Generates CSS files for events status.
   *
   * @param string $status_colors
   *   An associative array with the status name and color.
   *
   * @return mixed
   *   TRUE if it succeeded, FALSE otherwise.
   */
  public function generateCssFiles($status_colors);

}
