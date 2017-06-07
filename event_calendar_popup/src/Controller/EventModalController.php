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

  public function modal($js = 'nojs') {
    if ($js == 'ajax') {
      // Sets options for modal.
      $options = [
        'dialogClass' => 'popup-dialog-class',
        'width' => '75%',
        'height' => '75%',
      ];
      $node = \Drupal::entityTypeManager()->getStorage('node')->create(array(
        'type' => 'event_calendar',
      ));
      $form = \Drupal::service('entity.form_builder')->getForm($node);

      // Create new AJAX response.
      $response = new AjaxResponse();
      // On click, returns a modal with the form for adding a new node of type "event".
      $response->addCommand(new OpenModalDialogCommand('Add Event on calendar', $form, $options));
      return $response;
    }
  }
}
