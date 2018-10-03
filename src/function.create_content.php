<?php

namespace Potherca\WebApplication\Generic;

function create_content($templateLanguage, $resultTemplate, $context){
  $projectPath = dirname(__DIR__);
  
  $availableLanguages = [
    /* @TOTOD: Move logic per language into callback */
    'mustache' => function () {},
    'php' => function () {},
  ];
  
  if (array_key_exists($templateLanguage, $availableLanguages) === false) {
    $error = vsprintf(
      'Template language "%s" is not supported. Must be one of: "%s"', 
      [
        'language' => $templateLanguage,
        'available' => implode('", "', array_keys($availableLanguages)),
      ]
    );

    throw new \InvalidArgumentException($error);
  }

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
    ob_start(null, 0, PHP_OUTPUT_HANDLER_CLEANABLE | PHP_OUTPUT_HANDLER_REMOVABLE);
    include $projectPath.'/src/template/php/application.php';
    $content = ob_get_clean();
  }
  // =============================================================================

  return $content;
}

/*EOF*/
