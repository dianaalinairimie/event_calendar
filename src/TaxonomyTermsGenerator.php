<?php

namespace Drupal\event_calendar;

use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class TaxonomyTermsGenerator.
 */
class TaxonomyTermsGenerator implements TaxonomyTermsGeneratorInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * TaxonomyTermsGenerator constructor.
   *
   * @param \EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function termsGenerator($taxonomy_terms, $vocabulary_id) {
    // Create required terms for specified vocabulary.
    if (!empty($this->entityTypeManager->getStorage('taxonomy_vocabulary')
      ->load($vocabulary_id))
    ) {
      foreach ($taxonomy_terms as $new_term) {
        $term = $this->entityTypeManager->getStorage('taxonomy_term')->create([
          'vid' => $vocabulary_id,
          'name' => $new_term,
        ]);
        $term->save();
      }
    }
  }

}
