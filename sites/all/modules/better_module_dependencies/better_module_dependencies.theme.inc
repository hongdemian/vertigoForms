<?php

/**
 * Returns HTML for the modules list.
 *
 * @param $variables
 *   An associative array containing:
 *   - modules: An array of the depending modules.
 *
 * @rerurn array
 */
function theme_better_module_dependencies_add_selectors($variables) {
  foreach ($variables['module']['#required_by'] as $key => $module) {
    $variables['module']['#required_by'][$key] = '<span class="better-module-dependencies-selector">' . $module . '</span>';
  }
  foreach ($variables['module']['#requires'] as $key => $module) {
    if (strpos($module, 'class="admin-missing"')) {
      $path = 'http://drupal.org/project/' . $key;
      $args = array(
        'attributes' => array(
          'class' => array(
            'better-module-dependencies-link',
          ),
          'target' => '_blank',
        ),
        'html' => TRUE,
      );
      $link = l($module, $path, $args);
      $variables['module']['#requires'][$key] = $link;
    }
    else {
      $variables['module']['#requires'][$key] = '<span class="better-module-dependencies-selector">' . $module . '</span>';
    }
  }
  $variables['module']['name']['#markup'] = '<span class="better-module-dependencies-name">' . $variables['module']['name']['#markup'] . '</span>';
  return $variables['module'];
}
