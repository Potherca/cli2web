<?php

namespace Potherca\GiFiTy;

function create_context(array $composer, $userContext)
{
  $context = [];

  /* All of the keys available from the generic application template */
  $defaults = [
    /* Application level values  */
    'description' => '',  // Text explaining how the application is to be used
    'javascript' => [],   // Javascript to load on every page
    'stylesheets' => [],  // Stylesheets to load on every page
    'sub-title' => '',    // description of the application placed beneath the main <H1>
    'title' => '',        // Title of the application, used for the <TITLE> tag and main <H1>
    'title-link' => '',   // URL to link the main header to

    /* Project specific values */
    'project' => [
      'version' => '',    // Version of the application
      'source-url' => '', // URL where the application's source code can be found
      'source' => '',     // Name of location the source code can be found
      'license' => '',    // License the source-code is realesed under
      'author-url' => '', // Url of the main author
      'author' => '',     // Name of the main author
    ],

    /* Page specific values */
    'content' => '',            // HTML content to be placed on the page
    'javascript-inline' => '',  // Page-specific <SCRIPT>
    'messages' => [],           // User mesages ['message' => '', '' => 'details' type' => 'primary |  link |  info | success | warning | danger']
    'stylesheet-inline' => '',  // Page-specific <STYLE>
  ];

  /* Grab what we can from Composer */
  if (array_key_exists('authors', $composer) && is_array($composer['authors']) && count($composer['authors']) > 0) {
    $author = array_shift($composer['authors']);
    $context['project']['author'] = isset($author['name'])?$author['name']:'';
    $context['project']['author-url'] = isset($author['homepage'])?$author['homepage']:'';
  }
  if (array_key_exists('description', $composer)) {
    $context['sub-title'] = $composer['description'];
  }
  if (isset($composer['support']['source'])) {
    $context['project']['source-url'] = $composer['support']['source'];
  }
  if (isset($composer['license'])) {
    $context['project']['license'] = $composer['license'];
  }

  /* Combine contexts */
  $context = array_replace_recursive($defaults, $context, $userContext);

  /* Remove content from empty arrays */
  $context = array_map(function ($value) {
    if (is_array($value)) {
      if (array_sum(array_map(function ($value) {return ! empty($value);}, $value)) === 0) {
        $value = [];
      }
    }
    return $value;
  }, $context);

  /* Enhance data */
  if (isset($context['project']['source-url'])) {
    $context['project']['source'] = ucfirst(preg_replace('/^(?:[^.]+\.)*([^.]+)\..*$/', '$1', parse_url($context['project']['source-url'], PHP_URL_HOST)));
  }

  return $context;
}

/*EOF*/
