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
      '/application.css',
    ],

    /*/ Project specific values /*/
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

function create_context(array $composer, $userContext){
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

  $projectContext = [
    'project' => [
      'source-url' => $composer['homepage'], // might not be set?
      'version' => $version,
    ],
  ];
}

function fetch_results($query) {
  $results = [];
  $data = [];
  
  if ($query !== '') {
    $params = http_build_query([
      'per_page' => '100',
      'q' => $query.' language:Markdown in:file',
    ]);
    $url = "https://api.github.com/search/code?".$params;
  
    $data = fetch_url($url);
  }
  
  /*/ @FIXME: Because of possible false-positives (and many-starred-repo's being 
      hidden at the end of the queue) I don't think we can get around fetchin all
      of the pages from the search results.
      This means we need the headers as well to fetch the "next" list.
  /*/
  
  if (is_array($data) && array_key_exists('items', $data)) {
    array_walk($data['items'], function ($result) use (&$results, $query) {
      $results[] = [
         'file_fragment' => $result['text_matches'][0]['fragment'],
         'file_path' => $result['path'],
         'file_url' => $result['html_url'],
         'repository_description' => $result['repository']['description'],
         'repository_name' => $result['repository']['full_name'],
         'repository_url' => $result['repository']['html_url'],
         'stargazers_url' => $result['repository']['stargazers_url'],
      ];
    });  
  }
  
  /* Add stars per repo */
  array_walk($results, function (&$result) {
    $stargazers = fetch_url($result['stargazers_url']);
    $result['stars'] = count($stargazers);
  });
  
  /* Sort repo's by star count */
  usort($results, function ($a, $b) {
      return $a['stars'] < $b['stars'];
  });
  
  /* Create URL to directly edit a file */
  array_walk($results, function (&$result) {
    $parts = explode('/', $result['file_url']);
    $parts['5'] = 'edit';   // blob
    $parts['6'] = 'master'; // SHA1 hash
    $result['edit-url'] = implode('/', $parts);
  });
  
  /* Highlight search term */
  array_walk($results, function (&$result) use ($query) {
    $result['file_fragment'] = str_ireplace(
        $query, 
        '<span style="background: yellow;">'.$query.'</span>', 
        htmlentities($result['file_fragment'])
      );
  });
  
  
  // @FIXME: Multiple results for the same repo need to be added together!
  // @CHECKME: Do we want to do anything for _exactly_ the same matches within one repo?
  // @CHECKME: Repo's can still be clones even if they are not marked as forks! What then?
  
  return $results;
}

function fetch_url($url) {
  $githubToken = getenv('GITHUB_TOKEN');
  $githubUser = getenv('GITHUB_USER');

  $ch = curl_init();
  
  $headers = [
    "Accept: application/vnd.github.v3.text-match+json",
    'User-Agent: http://developer.github.com/v3/#user-agent-required)',
  ];

  $options = [
    CURLOPT_HEADER => false,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $url,
    CURLOPT_USERPWD => $githubUser.':'.$githubToken,
  ];

  curl_setopt_array($ch, $options);
  
  $result = curl_exec($ch);

  if (curl_errno($ch)) {
    $result = '{"error":"' . curl_error($ch).'"}';
  }
  curl_close ($ch);
  
  return json_decode($result, true);
}

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
