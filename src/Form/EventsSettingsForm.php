<?php

namespace Drupal\event_calendar\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines the admin configuration form for the calendar module.
 *
 * @package Drupal\event_calendar\Form
 */
class EventsSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'events_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $events_config = $this->config('events.settings');

    // Sets default status to "Pending".
    $event_status = $events_config->get('status');
    if (empty($event_status)) {
      $event_status = '0';
    }

    $form['default_status'] = [
      '#type' => 'details',
      '#title' => $this->t('Default events status'),
      '#open' => TRUE,
      '#description' => $this->t("Select the status of the events that will be saved on creation a new event. Default the events status is 'Pending' on creation of a new Event."),
    ];

    $form['default_status']['status'] = [
      '#type' => 'radios',
      '#title' => $this->t('Available default status'),
      '#default_value' => $event_status,
      '#options' => array(0 => 'Pending', 1 => 'Approved'),
      '#required' => TRUE
    ];

    $form['email_recipients_request_events'] = [
      '#type' => 'details',
      '#title' => $this->t('Request Events email recipients'),
      '#description' => $this->t("Select the roles that will receive the email on creation of a new Event and the roles that will receive the email on approved of a new Event. Default admin will receive Email on creation of a new Event. Default all users will receive Email on creation of a new Event."),
    ];

    $form['email_recipients_request_events']['recipients_request_events'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Roles'),
      '#default_value' => $events_config->get('recipients_request_events'),
      '#options' => array(0 => 'Administrator')
    ];

    $form['admin_email'] = [
      '#type' => 'details',
      '#title' => $this->t('Email Settings For Admin'),
      '#open' => FALSE,
      '#description' => $this->t("Edit the email messages sent to administrator/s. Available variables are: [site:name], [site:url], [node:author], [node:created], [node:title], [node:body], [node:url], [event_calendar:start_date], [event_calendar:end_date], [event_calendar:event_status].")
    ];

    $form['admin_email']['admin_email_subject'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Subject'),
      '#default_value' => $events_config->get('admin_email_subject')
    ];

    $form['admin_email']['admin_email_content'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      '#default_value' => $events_config->get('admin_email_content')
    ];

    $form['users_email'] = [
      '#type' => 'details',
      '#title' => $this->t('Email Settings For Users'),
      '#open' => FALSE,
      '#description' => $this->t("Edit the email messages sent to administrator/s. Available variables are: [site:name], [site:url], [node:author], [node:created], [node:title], [node:body], [node:url], [event_calendar:start_date], [event_calendar:end_date], [event_calendar:event_status].")
    ];

    $form['users_email']['users_email_subject'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Subject'),
      '#default_value' => $events_config->get('users_email_subject')
    ];

    $form['users_email']['users_email_content'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      '#default_value' => $events_config->get('users_email_content')
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $this->config('events.settings')
      ->set('recipients_request_events', $form_state->getValue('recipients_request_events'))
      ->set('recipients_approved_events', $form_state->getValue('recipients_approved_events'))
      ->set('admin_email_subject', $form_state->getValue('admin_email_subject'))
      ->set('admin_email_content', $form_state->getValue('admin_email_content'))
      ->set('users_email_subject', $form_state->getValue('users_email_subject'))
      ->set('users_email_content', $form_state->getValue('users_email_content'))
      ->set('status', $form_state->getValue('status'))
      ->save();

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'events.settings',
    ];
  }

}
