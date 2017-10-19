<?php

return [
  'arguments' => [
    [
      'autocomplete' => '!'.__DIR__.'/typo-list.php',
      'default' => '',
      'description' => 'Select a typo from the list or invent one yourself',
      'example' => 'Spellng misaake', // for documentation and placeholder attribute
      'label' => false,               // `false` or empty string '' for NO label
      'name' => 'search-term',        //
      'type' => 'text',               // For validation and <INPUT> shape
    ],
  ],
  'options' => [
    [
      'description' => '',
      'name'        => 'false-positives-threshold',
      'default'     => 3,
      'type'        => 'number'
    ],
  ],
  'flags' => [
    ['name' => 'show-duplicates', 'description' => ''],
    ['name' => 'show-false-positives', 'description' => ''],
    ['name' => 'skip-typo-check', 'description' => ''],
  ],
];
