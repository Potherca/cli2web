<?php

namespace Potherca\WebApplication\Generic;

function create_form_context($arguments)
{
  $form = [];

  $value = [];

  $defaults = [
    'arguments' => [],
    'flags' => [],
    'options' => [],
  ];

  $form['has_options'] = (bool) count($arguments['options']) + count($arguments['flags']);

  /* Sort out autoloading */
  array_walk($arguments['arguments'], function (&$value) {

    if (isset($value['autocomplete']) === false) {
      $value['autocomplete'] = [];
    }

    if (
      is_string($value['autocomplete'])
      && $value['autocomplete']{0} === '!'
    ) {
      $value['autocomplete'] = require substr($value['autocomplete'], 1);
    }

    $value['has_autocomplete'] = (bool) count($value['autocomplete']);
  });

  /* Combine contexts */
  $form = array_replace_recursive($defaults, $form, $arguments);

  $labelFromName = function (&$value) {

    if (isset($value['label']) === false) {
      $value['label'] = ucfirst(str_replace('-', ' ', $value['name']));
    }
  };

  array_walk($form['arguments'], $labelFromName);
  array_walk($form['flags'], $labelFromName);
  array_walk($form['options'], $labelFromName);

  $form['single_argument'] = count($form['arguments']) <= 1;

  /* @TODO: If any of the "options" has a value, the <DETAILS> should be expanded */
  $form['has_option_selected'] =
    count(array_filter(array_column($form['flags'], 'value')))
    + count(array_filter(array_column($form['options'], 'value')))
  ;

  return $form;
}
