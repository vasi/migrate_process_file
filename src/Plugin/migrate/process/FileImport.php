<?php

/**
 * @file
 * Contains \Drupal\migrate_process_file\Plugin\migrate\process\FileImport.
 */

namespace Drupal\migrate_process_file\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Import a file as a side-effect of a migration.
 *
 * Fetches the file, and yields a file ID.
 *
 * @MigrateProcessPlugin(
 *   id = "file_import"
 * )
 */
class FileImport extends ProcessPluginBase {
  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    // Save the file, return an ID.
    $file = system_retrieve_file($value, 'public://', TRUE, FILE_EXISTS_REPLACE);
    return $file->id();
  }

}
