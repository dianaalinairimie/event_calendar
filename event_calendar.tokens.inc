<?php

/**
 * @file
 * Builds placeholder replacement tokens for event calendar related data.
 */

/**
 * Implements hook_token_info().
 */
function events_calendar_token_info() {
  $type = [
    'name' => t('Event Calendar'),
    'description' => t('Tokens related to individual content items, or "event calendar".'),
  ];

  // Tokens for event calendar.
  $event['event_name'] = [
    'name' => t("Event name"),
    'description' => t('The name of the event.'),
  ];
  $event['site_name'] = [
    'name' => t("Site name"),
    'description' => t('The name of the site.'),
  ];
  $event['start_date'] = [
    'name' => t("Event start date"),
    'description' => t('The start date of the event.'),
  ];
  $event['event_author'] = [
    'name' => t("Event author"),
    'description' => t("The name of the event author."),
  ];
  $event['event_status'] = [
    'name' => t("Event Status"),
    'description' => t("The status of the event."),
  ];
  $event['approval_url'] = [
    'name' => t("Approval url"),
    'description' => t("The approval url of event."),
  ];

  return [
    'types' => ['event_calendar' => $type],
    'tokens' => ['event_calendar' => $event],
  ];
}

/**
 * Implements hook_tokens().
 */
function event_calendar_tokens($type, $tokens, $data = [], array $options = []) {
  $replacements = [];

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
