<?php

namespace Drupal\event_calendar;

/**
 * Interface TaxonomyTermsGeneratorInterface.
 */
interface TaxonomyTermsGeneratorInterface {

  /**
   * @param $taxonomy_terms
   * @param $vocabulary_id
   * @return mixed
   */
  public function termsGenerator($taxonomy_terms, $vocabulary_id);

}
