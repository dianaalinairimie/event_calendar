<?php

/**
 * @file
 * Builds placeholder replacement tokens for event calendar related data.
 */

/**
 * Implements hook_token_info().
 */
function events_calendar_token_info() {
  $type = array(
    'name' => t('Event Calendar'),
    'description' => t('Tokens related to individual content items, or "event calendar".'),
  );

  // Tokens for event calendar.
  $event['event_name'] = array(
    'name' => t("Event name"),
    'description' => t('The name of the event.'),
  );
  $event['site_name'] = array(
    'name' => t("Site name"),
    'description' => t('The name of the site.'),
  );
  $event['start_date'] = array(
    'name' => t("Event start date"),
    'description' => t('The start date of the event.'),
  );
  $event['event_author'] = array(
    'name' => t("Event author"),
    'description' => t("The name of the event author."),
  );
  $event['event_status'] = array(
    'name' => t("Event Status"),
    'description' => t("The status of the event."),
  );
  $event['approval_url'] = array(
    'name' => t("Approval url"),
    'description' => t("The approval url of event."),
  );

  return array(
    'types' => array('event_calendar' => $type),
    'tokens' => array('event_calendar' => $event),
  );
}

/**
 * Implements hook_tokens().
 */
function event_calendar_tokens($type, $tokens, $data = array(), array $options = array()) {
  $replacements = array();

  if ($type == 'event_calendar') {
    foreach ($tokens as $name => $original) {
      switch ($name) {
        case 'event_name':
          $replacements[$original] = $data['event_name'];
          break;

        case 'site_name':
          $replacements[$original] = $data['site_name'];
          break;

        case 'start_date':
          $replacements[$original] = $data['start_date'];
          break;

        case 'event_author':
          $replacements[$original] = $data['event_author'];
          break;

        case 'approval_url':
          $replacements[$original] = $data['approval_url'];
          break;

        case 'event_status':
          $replacements[$original] = $data['event_status'];
          break;

        default;
      }
    }

    return $replacements;
  }

}
