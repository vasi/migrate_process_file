Migrate Process File
====================

This is a Migrate process[1] that imports files into Drupal as a side-effect
of a migration. It takes a URL as input, downloads the file at that URL,
and yields the ID of the newly-created file.

There is an example of using this process in the migrate_process_file_example
module. The simplest way to use it is in your migration YAML file, like so:

    id: my_migration
    ...
    process:
      field_image:
        - source: uri_field
          plugin: migrate_process_file

[1] https://www.drupal.org/node/2129651


Caveats
-------

The following abilities would be useful, but are not built-in:

* Error handling
* Multiple item handling ("handles_multiples")
* An option to not download a file if it already exists
* An option to choose a non-conflicting filename, if the destination exists
* An option for the new file's destination
* Rollback of imported files
* Dependency injection and unit tests
