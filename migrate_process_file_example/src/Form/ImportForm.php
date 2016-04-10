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
    // Get our term.
    $term = $form_state->getValue('term');

    // Setup the migration to use our term.
    $migration = Migration::load('shows');
    $source = $migration->get('source');
    $source['path'] .= urlencode($term);
    $migration->set('source', $source);

    // Import the migration.
    $log = new MigrateMessage();
    $executable = new MigrateExecutable($migration, $log);
    $executable->import();

    // Redirect to /node.
    $form_state->setRedirect('view.frontpage.page_1');
  }

}
