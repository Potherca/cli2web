<?php

namespace Potherca\WebApplication\Generic;

// =============================================================================
/*/ Create contexts /*/
// -----------------------------------------------------------------------------
function create_context(array $arguments, array $results, $composerContent = '', $userContext = [])
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
  $composerContext = create_composer_context(json_decode($composerContent, true));

  // -----------------------------------------------------------------------------
  /* Create the result context */
  $resultContext = [
    'results' => count($results),
    'result_list' => $results,
  ];

  // -----------------------------------------------------------------------------
  /* Create the form context */
  $formContext = create_form_context($arguments);

  // -----------------------------------------------------------------------------
  $userContext['stylesheets'][] = '/bulma-switch.css';

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

/*EOF*/
