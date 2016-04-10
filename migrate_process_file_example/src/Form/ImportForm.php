<?php

/**
 * @file
 * Contains \Drupal\migrate_process_file_example\Form\ImportForm.
 */

namespace Drupal\migrate_process_file_example\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\migrate\Entity\Migration;
use Drupal\migrate\MigrateExecutable;
use Drupal\migrate\MigrateMessage;

/**
 * Import TV shows as articles.
 */
class ImportForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'migrate_process_file_example_import';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['term'] = [
      '#title' => $this->t('Search term'),
      '#type' => 'textfield',
      '#required' => TRUE,
    ];
    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Import'),
        '#button_type' => 'primary',
      ],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Redirect to /node when we're done.
    $form_state->setRedirect('view.frontpage.page_1');

    // Run a batch.
    $term = $form_state->getValue('term');
    batch_set([
      'title' => $this->t('Importing TV shows'),
      'progress_message' => $this->t('Importing TV shows'),
      'operations' => [
        [
          [get_class($this), 'importShows'],
          [$term],
        ],
      ],
    ]);
  }

  /**
   * Import shows with the given term.
   *
   * @param string $term
   *   A search term for TV shows.
   * @param $context
   *   Batch context.
   */
  public static function importShows($term, &$context) {
    // Setup the migration to use our term.
    $migration = Migration::load('shows');
    $source = $migration->get('source');
    $source['path'] .= urlencode($term);
    $migration->set('source', $source);

    // Import the migration.
    $log = new MigrateMessage();
    $executable = new MigrateExecutable($migration, $log);
    $executable->import();
  }

}
