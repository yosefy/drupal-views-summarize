<?php

namespace Drupal\views_summarize\Plugin\views\style;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\style\Table;

/**
 * Style plugin to render summarized data as a row in a table.
 *
 * @ingroup views_style_plugins
 *
 * "theme" is the name of the template preprocess function and the Twig template
 * file (with hyphens replacing the underscores) used to render the table.
 *
 * @ViewsStyle(
 *   id = "tablesummarized",
 *   title = @Translation("Summarized table"),
 *   help = @Translation("Displays rows in a table with a summary row for any column."),
 *   theme = "views_summarize_view_summarized_table",
 *   display_types = {"normal"}
 * )
 */
class TableSummarized extends Table {

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    // This is for themeing the form for the Views Summarized table Settings.
    $form['#theme'] = 'views_summarize_style_plugin_summarized_table';

    $options = $this->getHandlers();
    $columns = $this->sanitizeColumns($this->options['columns']);
    foreach (array_keys($columns) as $field) {
      $safe = str_replace(['][', '_', ' '], '-', $field);
      $id = 'edit-style-options-columns-' . $safe;
      $form['info'][$field]['summarize'] = [
        '#type' => 'select',
        '#options' => $options,
        '#default_value' => !empty($this->options['info'][$field]['summarize']) ? $this->options['info'][$field]['summarize'] : 'none',
        '#dependency' => [$id => [$field]],
      ];
    }

    $form['summary_only'] = [
      '#title' => t('Display the summary row only'),
      '#description' => t('If checked only the summary row will be displayed,'),
      '#type' => 'checkbox',
      '#default_value' => !empty($this->options['summary_only']),
    ];
  }

  /**
   * Lists all of the handlers.
   *
   * @return array
   *   A list of the provided summary handlers.
   */
  protected function getHandlers() {
    return [
      'none' => t('None'),
      'count' => t('Count'),
      'total' => t('Total'),
      'currency' => t('Total (Currency)'),
      'average' => t('Average (include empty values)'),
      'average_no_empties' => t('Average (exclude empty values)'),
      'range' => t('Range'),
      'spread' => t('Spread'),
    ];
  }

}
