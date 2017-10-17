<?php

namespace Potherca\GiFiTy;

use Dotenv\Dotenv;
use Mustache_Engine;

$projectPath = dirname(__DIR__);

require $projectPath.'/vendor/autoload.php';

/* Load `.env` */
if (is_readable($projectPath . '/.env')) {
  $dotenv = new Dotenv($projectPath, '.env');
  $dotenv->load();
  unset($dotenv);
}

/* Load GET/POST parameters  */
/* @FIXME: Set "options" from $_GET params! */
/* @TODO: If any of the "options" has a value, the <DETAILS> should be expande */
$query = $_GET['q']?:'';
/* Only grab the first word */
$query = array_shift(explode(' ', $query));

/* Load $context from `composer.json` */
$composer = json_decode(file_get_contents($projectPath.'/composer.json'), true);
$context = create_context(
  $composer, 
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

/* Load templates */
$applicationTemplate = file_get_contents(dirname(__DIR__).'/src/template/application.mustache');
$formTemplate = file_get_contents(dirname(__DIR__).'/src/template/form.mustache');
$resultTemplate = file_get_contents(dirname(__DIR__).'/src/template/results.mustache');

$typos = require dirname(__DIR__).'/src/typo-list.php';

/* Create objects*/
$templatEngine = new Mustache_Engine();

/* Get data */
$results = fetch_results($query);

/* Feed data to sub-template  */
$formHtml = $templatEngine->render($formTemplate, [
  'query' => $query,
  'typo-list' => $typos,
  'has-options' => true,
  'options' => [
    ['name' => 'show-duplicates', 'label' => 'Show duplicates', 'is-flag' => true],
    ['name' => 'show-false-positives', 'label' => 'Show false positives', 'is-flag' => true],
    ['name' => 'skip-typo-check', 'label' => 'Skip typo check', 'is-flag' => true],
  ],
]);

$resultHtml = $templatEngine->render($resultTemplate, [
  'results' => count($results),
  'result-list' => $results,
]);

$context['content'] = $formHtml.$resultHtml;

/* Feed data to main template */
echo $templatEngine->render($applicationTemplate, $context);

exit;


/*EOF*/

/*
As is to be expected this project is not the only one to have a fascination with 
typo's. So to avoid false-positives, a black-list needs to be added for sites/files
that should not be considered a viable candidate for a pull request.

Another way to go is to expand the list of "known" typos to "as much as possible"
and check the found text for more typo's. At a given count (3?) such results 
should be ignored. The typo-count could be added as configurable via the form.

Word input _could_ be checked against wordnik to make sure they don't actually exist.
If they exist, instead of the Github view, show the Wordnik output.
@see https://github.com/wordnik/wordnik-php

Sources for more typo's:

- https://github.com/zeke/zeke.sikelianos.com/blob/HEAD/outcasts/index.md

*/
