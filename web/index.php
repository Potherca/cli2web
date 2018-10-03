<?php

namespace Potherca\WebApplication;

ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);

// =============================================================================
/*/ Load any project assets that might be requested.  /*/
// -----------------------------------------------------------------------------
if(isset($_SERVER['PATH_INFO']) 
    && in_array(ltrim($_SERVER['PATH_INFO'],'/'), [
        'application.css',
        'bulma-switch.css'
    ])
) {
    $path = dirname(__DIR__).'/web/'.$_SERVER['PATH_INFO'];
    header('Content-Type: text/css');
    readfile($path);
    die;
}
// =============================================================================

// =============================================================================
/*/ Default values /*/
// -----------------------------------------------------------------------------
// @FIXME: These need to come from a config so they can be JOINED
//         NOT be hard-coded and overwritten in PHP
isset($callback) || $callback = function (array $arguments) {
    $value = [];
    $argument = array_shift($arguments['arguments']);
    if ($argument['value'] !== null) {
        $value[] = $argument['value'];
    }
    return $value;
};

isset($parameters) || $parameters = [
    'arguments' => [
        [
            'name' => 'input',
            'type' => 'text',
            'autocomplete' => null,
            'description'=> null,
            'default' => null,
            'example' => null,
        ]
    ],
    'options' => [],
    'flags' => [],
];

isset($resultTemplates) || $resultTemplates = [
    'php' => <<<'PHP'
        echo "
        <div class=\"box\">
            <pre class=\"has-text-left\">${result}</pre>
        </div>";
PHP
    ,
    'mustache' => <<<'MUSTACHE'
        <div class="box">
            <pre class="has-text-left">{{.}}</pre>
        </div>
MUSTACHE
    ,
];

isset($templateLanguage) || $templateLanguage = 'php';
isset($resultTemplate)  || $resultTemplate = $resultTemplates[$templateLanguage];

isset($userContext) || $userContext = [
  'submit_icon' => 'arrow-right',
  'submit_name' => null,
];

isset($valueDecoraters) || $valueDecoraters = [
    'input' => function ($result) {
        return print_r($result, true);
    }
];
// =============================================================================


// =============================================================================
/*/ Grab things from Disk, DB, Request, Environment, etc. /*/
// -----------------------------------------------------------------------------
require PROJECT_ROOT.'/vendor/autoload.php';

/* Load `.env` */
if (is_readable(PROJECT_ROOT . '/.env')) {
  $dotenv = new \Dotenv\Dotenv(PROJECT_ROOT, '.env');
  $dotenv->load();
  unset($dotenv);
}

// -----------------------------------------------------------------------------
/* Read `composer.json` content */
$project = json_decode(file_get_contents(PROJECT_ROOT.'/composer.json'), true);
// =============================================================================



// =============================================================================
// Call "Potherca\WebApplication" logic
// -----------------------------------------------------------------------------
$userContext = \Potherca\WebApplication\Generic\create_potherca_context($userContext);

/* Load GET parameters  */
$arguments = \Potherca\WebApplication\Generic\load_values(
  $parameters,      // array - configures which arguments, options and flags are available
  $valueDecoraters  // array - values or callbacks that are applied to the user input values
);

/* Create the result */
$results = $callback($arguments);

/* Context the UI content is based on */
$context =\Potherca\WebApplication\Generic\create_context(
  $arguments,   // array - Created by "potherca/webapplication"
  $results,     // array - Created by "callback"
  $project,     // array - from `composer.json` content
  $userContext  // array - override values in the context that is fead to the templates
);

/* Create UI content */
$content = \Potherca\WebApplication\Generic\create_content(
  $templateLanguage,  // language the result template is written in. Can be plain PHP or Mustache
  $resultTemplate,    // string that consists of the template the result array is fed to
  $context            // Created by "potherca/webapplication"
);
// =============================================================================


echo $content;
exit;

/*EOF*/
