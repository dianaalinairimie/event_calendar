<?php

namespace Drupal\event_calendar_popup\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Controller\ControllerBase;

/**
 * Class EventModalController.
 *
 * @package Drupal\event_calendar_popup\Controller
 */
class EventModalController extends ControllerBase {

  /**
   * AJAX callback for open modal.
   *
   * @param string $js
   *   Ajax request parameter.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   The AJAX response.
   */
  public function modal($js = 'nojs') {
    if ($js == 'ajax') {
      // Sets options for modal.
      $options = [
        'dialogClass' => 'popup-dialog-class',
        'width' => '75%',
        'height' => '75%',
      ];
      $node = $this->entityTypeManager()->getStorage('node')->create(array(
        'type' => 'event_calendar',
      ));
      $form = $this->entityFormBuilder()->getForm($node);

      // Creates new AJAX response.
      $response = new AjaxResponse();
      // Creates a modal with the form for adding a new node of type "event".
      $response->addCommand(new OpenModalDialogCommand('Add Event on calendar', $form, $options));

      return $response;
    }
  }

}
