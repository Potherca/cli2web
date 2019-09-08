<?php

namespace Potherca\WebApplication\Generic;

// =============================================================================
/*/ Create contexts /*/
// -----------------------------------------------------------------------------
function create_context(array $arguments, array $results, array $composer = [], $userContext = [])
{
  /* All of the keys available from the generic application template */
  $defaults = [
    /* Application level values  */
    'description' => '',  // Text explaining how the application is to be used
    'javascript' => [],   // Javascript to load on every page
    'page_title' => '',   // Page specific override for Title
    'stylesheets' => [],  // Stylesheets to load on every page
    'sub_title' => '',    // description of the application placed beneath the main <H1>
    'title' => '',        // Title of the application, used for the <TITLE> tag and main <H1>
    'title_link' => '',   // URL to link the main header to

    /* Project specific values */
    'project' => [
      'version' => '',    // Version of the application
      'source_url' => '', // URL where the application's source code can be found
      'source' => '',     // Name of location the source code can be found
      'license' => '',    // License the source-code is realesed under
      'author_url' => '', // Url of the main author
      'author' => '',     // Name of the main author
    ],

    /* Page specific values */
    'content' => '',            // HTML content to be placed on the page
    'javascript_inline' => '',  // Page-specific <SCRIPT>
    'messages' => [],           // User mesages ['message' => '', '' => 'details' type' => 'primary |  link |  info | success | warning | danger']
    'stylesheet_inline' => '',  // Page-specific <STYLE>
  ];

  /* Create context from `composer.json` */
  $composerContext = create_composer_context($composer);

  // -----------------------------------------------------------------------------
  $results = array_filter($results);
  /* Create the result context */
  $resultContext = [
    'error_list' =>[],
    'errors' => 0,
    'result_list' => [],
    'results' => 0,
  ];

  array_walk($results, function ($result) use (&$resultContext) {
    if ($result instanceof \Exception) {
      $resultContext['errors']++;
      $resultContext['error_list'][] = vsprintf('A %s occurred: %s', [
        get_class($result),
        $result->getMessage(),
      ]);
    } else {
      $resultContext['results']++;
      $resultContext['result_list'][] = $result;
    }
  });

  // -----------------------------------------------------------------------------
  /* Create the form context */
  $formContext = create_form_context($arguments);

  // -----------------------------------------------------------------------------
  $userContext['stylesheets'][] = $_SERVER['SCRIPT_NAME'].'/bulma-switch.css';

  // -----------------------------------------------------------------------------
  /* Merge all contexts together */
  $context = array_replace_recursive(
    $defaults,
    $composerContext,
    $formContext,
    $resultContext,
    $userContext
  );

  /* Remove content from empty arrays */
  $context = array_map(function ($value) {
    if (is_array($value)) {
      if (array_sum(array_map(function ($value) {return ! empty($value);}, $value)) === 0) {
        $value = [];
      }
    }
    return $value;
  }, $context);

  return $context;
}
// =============================================================================

// =============================================================================
/*/ Potherca projects generic context /*/
// -----------------------------------------------------------------------------
function create_potherca_context($userContext) {
  return array_replace_recursive(
    $userContext,
    [
      'project' => ['author' => 'Potherca'],
      'stylesheets' => [
        'https://pother.ca/CssBase/css/created-by-potherca.css',
        $_SERVER['SCRIPT_NAME'].'/application.css',
      ],
    ]
  );
}
// =============================================================================

/*EOF*/
