<?php

namespace Drupal\event_calendar_colors\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines the admin configuration form for the event colors module.
 *
 * @package Drupal\event_calendar_colors\Form
 */
class ColorsSettingsForm extends ConfigFormBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * ColorsSettingsForm constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   */
  public function __construct(\Drupal\Core\Config\ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($config_factory);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'event_colors_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $events_colors = $this->config('event_colors.settings');

    $form['event_colors'] = [
      '#type' => 'details',
      '#title' => $this->t('Set color of events'),
      '#open' => TRUE,
      '#description' => $this->t("Select the color for each type of event."),
    ];

    foreach ($this->getEventsStatus() as $status) {
      $form['event_colors'][$status->name] = [
        '#type' => 'color',
        '#title' => $this->t($status->name),
        '#default_value' => $events_colors->get($status->name)
      ];
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $events_colors = $this->config('event_colors.settings');

    foreach ($this->getEventsStatus() as $status) {
      // Set the color for each event status.
      $events_colors
        ->set($status->name, $form_state->getValue($status->name));
    }
    $events_colors->save();

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'event_colors.settings',
    ];
  }

  /**
   * Helper function that return all events status.
   *
   * @return mixed
   *   An array with taxonomy terms.
   */
  private function getEventsStatus() {
    // Gets all terms of "events_status" taxonomy.
    return $this->entityTypeManager
      ->getStorage('taxonomy_term')
      ->loadTree('events_status');
  }

}
