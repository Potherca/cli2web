<?php

namespace Potherca\WebApplication\Generic;

use Dotenv\Dotenv;
use Mustache_Engine;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$projectPath = dirname(__DIR__);

// =============================================================================
/*/ Project specfic configuration /*/
// -----------------------------------------------------------------------------
require $projectPath.'/src/GiFiTy/function.fetch_results.php';

$callback = '\\Potherca\\GiFiTy\\fetch_results';

$parameters = require $projectPath.'/src/GiFiTy/config.command.php';

$templateLanguage = 'php';
$resultTemplatePath = $projectPath.'/src/GiFiTy/template/result.'.$templateLanguage;

$resultTemplate = file_get_contents($resultTemplatePath);

$userContext = [
  'description' => 'Fill in your faborite typing mistake (typo) in the field below and press the buttin',
  'project' => ['version' => exec('git tag | tail -n1')],
  'submit_icon' => 'search',
  'submit_name' => 'Search',
  'title' => 'Find Typos on Github',
];

$valueDecoraters = [
  'search-term' => function ($searchTerm) {
    /* Only grab the first word */
    $words = explode(' ', trim($searchTerm));
    return array_shift($words);
  },
];
// =============================================================================

// =============================================================================
/*/ Potherca projects generic configuration /*/
// -----------------------------------------------------------------------------
$userContext = array_replace_recursive(
  $userContext,
  [
    'project' => ['author' => 'Potherca'],
    'stylesheets' => [
      'https://pother.ca/CssBase/css/created-by-potherca.css',
      '/application.css',
    ],
  ]
);
// =============================================================================

// =============================================================================
/*/ Generic logic /*/
// -----------------------------------------------------------------------------
require $projectPath.'/vendor/autoload.php';

// =============================================================================
/*/ Gran things from Disk, DB, Request, Environment, etc. /*/
// -----------------------------------------------------------------------------
/* Load `.env` */
if (is_readable($projectPath . '/.env')) {
  $dotenv = new Dotenv($projectPath, '.env');
  $dotenv->load();
  unset($dotenv);
}

// -----------------------------------------------------------------------------
/* Load GET parameters  */
$arguments = load_values($parameters, $valueDecoraters);

// -----------------------------------------------------------------------------
/* Read `composer.json` content */
$composerContent = file_get_contents($projectPath.'/composer.json');
// =============================================================================

// =============================================================================
/* Create the result */
$results = $callback($arguments);
// =============================================================================

// =============================================================================
$context = create_context($arguments, $results, $composerContent, $userContext);

// =============================================================================
/* Load template */

// -----------------------------------------------------------------------------
/* Load templates using mustache */
// -----------------------------------------------------------------------------
if ($templateLanguage === 'mustache') {// && class_exists('Mustache_Engine')) {
  /* Create objects*/
  $templatEngine = new \Mustache_Engine([
      // This loader is used for both the original template and the partials.
      'loader' => new \Mustache_Loader_FilesystemLoader($projectPath.'/src/template/mustache'),
      // For partials stored in another directory, a loader specifically for partials is needed
      'partials_loader' => new \Mustache_Loader_CascadingLoader([
        new \Mustache_Loader_FilesystemLoader($projectPath.'/src/template/mustache'),
        new \Mustache_Loader_ArrayLoader(['result' =>  $resultTemplate,]),
      ]),
  ]);
  // -----------------------------------------------------------------------------
  /* Feed data to main template */
  $content = $templatEngine->render('application.mustache', $context);
}
// -----------------------------------------------------------------------------

// -----------------------------------------------------------------------------
/* Load templates using plan PHP */
// -----------------------------------------------------------------------------
if ($templateLanguage === 'php') {
  // -----------------------------------------------------------------------------
  /* Create objects*/
  extract($context);
  /* Feed data to main template */
  include $projectPath.'/src/template/php/application.php';
}
// =============================================================================

echo $content;
exit;
/*EOF*/
