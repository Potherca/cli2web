<?php

namespace Potherca\WebApplication\Generic;

function create_composer_context(array $composer)
{
  $context = [];

  /* Grab what we can from Composer */
  if (array_key_exists('authors', $composer) && is_array($composer['authors']) && count($composer['authors']) > 0) {
    $author = array_shift($composer['authors']);
    $context['project']['author'] = isset($author['name'])?$author['name']:'';
    $context['project']['author_url'] = isset($author['homepage'])?$author['homepage']:'';
  }

  if (array_key_exists('description', $composer)) {
    $context['sub_title'] = $composer['description'];
  }

  if (isset($composer['support']['source'])) {
    $context['project']['source_url'] = $composer['support']['source'];
  }

  if (isset($composer['license'])) {
    $context['project']['license'] = $composer['license'];
  }

  /* Enhance data */
  if (isset($context['project']['source_url'])) {
    $context['project']['source'] = ucfirst(preg_replace('/^(?:[^.]+\.)*([^.]+)\..*$/', '$1', parse_url($context['project']['source_url'], PHP_URL_HOST)));
  }

  return $context;
}

/*EOF*/
