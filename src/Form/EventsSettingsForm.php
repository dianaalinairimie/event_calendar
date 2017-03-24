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
    protected function getEditableConfigNames() {
        return [
            'events.settings',
        ];
    }

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
        $form = parent::buildForm($form, $form_state);
        $events_config = $this->config('events.settings');

        $form['email_recipients_request_events'] = [
            '#type' => 'details',
            '#title' => $this->t('Request Events email recipients'),
            '#open' => TRUE,
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

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        parent::submitForm($form, $form_state);

        $this->config('events.settings')
            ->set('recipients_request_events', $form_state->getValue('recipients_request_events'))
            ->set('recipients_approved_events', $form_state->getValue('recipients_approved_events'))
            ->set('admin_email_subject', $form_state->getValue('admin_email_subject'))
            ->set('admin_email_content', $form_state->getValue('admin_email_content'))
            ->set('users_email_subject', $form_state->getValue('users_email_subject'))
            ->set('users_email_content', $form_state->getValue('users_email_content'))
            ->save();
    }

}
