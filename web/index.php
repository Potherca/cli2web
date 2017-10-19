<?php

namespace Potherca\GiFiTy;

use Dotenv\Dotenv;
use Mustache_Engine;

$projectPath = dirname(__DIR__);

// =============================================================================
/*/ Specfic Configuration /*/
// -----------------------------------------------------------------------------
require $projectPath.'/src/GiFiTy/function.fetch_results.php';

$callback = 'Potherca\\GiFiTy\\fetch_results';
$interface = [
  'submit-name' => 'Search',
  'submit-icon' => 'search',
];
$parameters = require $projectPath.'/src/GiFiTy/config.command.php';
$resultTemplate = file_get_contents($projectPath.'/src/GiFiTy/result.mustache');
// =============================================================================

// =============================================================================
/*/ Generic logic /*/
// -----------------------------------------------------------------------------

require $projectPath.'/vendor/autoload.php';

// -----------------------------------------------------------------------------
/* Load `.env` */
if (is_readable($projectPath . '/.env')) {
  $dotenv = new Dotenv($projectPath, '.env');
  $dotenv->load();
  unset($dotenv);
}

// -----------------------------------------------------------------------------
/* Load GET parameters  */
$arguments = load_values($parameters, [
  'search-term' => function ($searchTerm) {
    /* Only grab the first word */
    return array_shift(explode(' ', $searchTerm));
  },
]);

// -----------------------------------------------------------------------------
/* Create the result  */
$results = $callback($arguments);

// -----------------------------------------------------------------------------
/* Load $context from `composer.json` */
$context = create_context(
  json_decode(file_get_contents($projectPath.'/composer.json'), true),
  [
    /*/ Generic for all Potherca (blog) projects /*/
    'project' => ['author' => 'Potherca', 'version' => 'v0.1.0'],
    'stylesheets' => [
      'https://pother.ca/CssBase/css/created-by-potherca.css',
      /*/ GiFiTy Project specific values /*/
      '/bulma-switch.css',
      '/application.css',
    ],

    /*/ GiFiTy Project specific values /*/
    'description' => 'Fill in your faborite typing mistake (typo) in the field below and press the buttin',
    'title' => 'Find Typos on Github',
  ]
);



// =============================================================================
/* Load templates */
// -----------------------------------------------------------------------------
/* Create objects*/
$templatEngine = new \Mustache_Engine([
    // This loader is used for both the original template and the partials.
    'loader' => new \Mustache_Loader_FilesystemLoader($projectPath.'/src/template/'),
    // For partials stored in another directory, a loader specifically for partials is needed
    'partials_loader' => new \Mustache_Loader_CascadingLoader([
      new \Mustache_Loader_FilesystemLoader($projectPath.'/src/template/'),
      new \Mustache_Loader_ArrayLoader(['result' =>  $resultTemplate,]),
    ]),
]);

// -----------------------------------------------------------------------------
/* Create the Form context */
$form = create_form_context($arguments);
$form = array_merge($form, $interface);

// -----------------------------------------------------------------------------
/* Create Result context */
$context = array_merge($context, [
  'results' => count($results),
  'result-list' => $results,
], $form);

// -----------------------------------------------------------------------------
/* Feed data to main template */
echo $templatEngine->render('application.mustache', $context);

exit;
/*EOF*/
