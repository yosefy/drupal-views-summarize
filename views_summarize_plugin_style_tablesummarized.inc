<?php
/**
 * @file
 * Contains the table style plugin.
 */

/**
 * TODO Avoid manually including these files, they should be loaded
 * by the plugin handler.
 */
require_once drupal_get_path('module', 'views') . '/plugins/views_plugin_style.inc';
require_once drupal_get_path('module', 'views') . '/plugins/views_plugin_style_table.inc';

/**
 * Style plugin to render each item as a row in a table.
 *
 * @ingroup views_style_plugins
 */
class views_summarize_plugin_style_tablesummarized extends views_plugin_style_table {
  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);
    $form['#theme'] = 'views_summarize_plugin_style_tablesummarized';
    $columns = $this->sanitize_columns($this->options['columns']);
    foreach ($columns as $field => $column) {
      $safe = str_replace(array('][', '_', ' '), '-', $field);
      $id = 'edit-style-options-columns-' . $safe;
      $form['info'][$field]['summarize'] = array(
        '#type' => 'select',
        '#options' => views_summarize_get_handlers(),
        '#default_value' => !empty($this->options['info'][$field]['summarize']) ? $this->options['info'][$field]['summarize'] : 'none',
        '#process' => array('views_process_dependency'),
        '#dependency' => array($id => array($field)),
      );
    }
  }
}
