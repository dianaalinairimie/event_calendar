<?php

namespace Drupal\event_calendar\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\Role;

/**
 * Defines the admin configuration form for the event calendar module.
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

    // Sets an array with all users roles.
    $roles = Role::loadMultiple();
    foreach ($roles as $role) {
      if ($role->id() !== 'anonymous') {
        $roles_name[$role->id()] = $role->label();
      }
    }

    // Check if recipients exist for approved events.
    if (empty(array_filter($events_config->get('recipients_approved_events')))) {
      $config_roles_name = $events_config->get('recipients_approved_events');
      // Default sets all authenticated users receive email on approved events.
      $config_roles_name['authenticated'] = 'authenticated';
      $events_config->set('recipients_approved_events', $config_roles_name);
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

    $form['email_recipients_approved_events'] = [
      '#type' => 'details',
      '#title' => $this->t('Approved Events email recipients'),
      '#description' => $this->t("Select the roles that will receive the email on approved of a new Event. Default admin will receive Email on creation of a new Event. Default all users will receive Email on creation of a new Event."),
    ];

    $form['email_recipients_approved_events']['recipients_approved_events'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Roles'),
      '#default_value' => $events_config->get('recipients_approved_events'),
      '#options' => $roles_name
    ];

    $form['admin_email'] = [
      '#type' => 'details',
      '#title' => $this->t('Email Settings For Admin'),
      '#open' => FALSE,
      '#description' => $this->t("Edit the email messages sent to administrator/s. Available variables are: [event_calendar:event_name], [event_calendar:event_author], [event_calendar:event_status], [event_calendar:site_name], [event_calendar:approval_url], [event_calendar:start_date].")
    ];

    $form['admin_email']['admin_email_subject'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Subject'),
      '#default_value' =>
        [
          '#markup' => !empty($events_config->get('admin_email_subject')) ? $events_config->get('admin_email_subject') : $this->t('New event is created')
        ]
    ];

    $form['admin_email']['admin_email_content'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      '#default_value' => !empty($events_config->get('admin_email_content')) ? $events_config->get('admin_email_content') : $this->t('Hello, A new event "[event_calendar:event_name]" is created at "[event_calendar:site_name]" by "[event_calendar:event_author]" Start Date: "[event_calendar:start_date]". Please give your approval to successfully organize this event at [event_calendar:approval_url]. ( [event_calendar:site_name] team')
    ];

    $form['users_email'] = [
      '#type' => 'details',
      '#title' => $this->t('Email Settings For Users'),
      '#open' => FALSE,
      '#description' => $this->t("Edit the email messages sent to users. Available variables are: [event_calendar:event_name], [event_calendar:event_author], [event_calendar:event_status], [event_calendar:site_name], [event_calendar:approval_url], [event_calendar:start_date].")
    ];

    $form['users_email']['users_email_subject'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Subject'),
      '#default_value' => !empty($events_config->get('users_email_subject')) ? $events_config->get('users_email_subject') : $this->t('[event_calendar:event_status] event')
    ];

    $form['users_email']['users_email_content'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      '#default_value' => !empty($events_config->get('users_email_content')) ? $events_config->get('users_email_content') : $this->t('Hello, A new event "[event_calendar:event_name]" was [event_calendar:event_status] at "[event_calendar:site_name]" by "[event_calendar:event_author]" Start Date: "[event_calendar:start_date]". ( [event_calendar:site_name] team')
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $this->config('events.settings')
      ->set('status', $form_state->getValue('status'))
      ->set('recipients_approved_events', $form_state->getValue('recipients_approved_events'))
      ->set('admin_email_subject', $form_state->getValue('admin_email_subject'))
      ->set('admin_email_content', $form_state->getValue('admin_email_content'))
      ->set('users_email_subject', $form_state->getValue('users_email_subject'))
      ->set('users_email_content', $form_state->getValue('users_email_content'))
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
