<?php

namespace Potherca\GiFiTy;

function load_values($parameters, array $values = [])
{
    $arguments = [];

    $availableParameters= array_merge($parameters['arguments'], $parameters['flags'], $parameters['options']);
    $availableParameters =  array_column($availableParameters, 'name');

    $userInput = filter_input_array(INPUT_GET, array_fill_keys($availableParameters, FILTER_SANITIZE_STRING));

    if ($userInput === null) {
        $userInput = array_fill_keys($availableParameters, null);
    }

    $subjects = ['arguments', 'flags', 'options'];

    array_walk($userInput, function (&$inputValue, $name) use (&$values) {
        if (isset($values[$name])) {
            $value = $values[$name];
            unset($values[$name]);

            if (is_callable($value)) {
                $value = $value($inputValue);
            }

            $inputValue = $value;
        }
    });

    array_walk($subjects, function ($subject) use (&$arguments, $parameters, $userInput) {
        array_walk($parameters[$subject], function ($argument) use (&$arguments, $subject, $userInput) {

            $name = $argument['name'];
            $argument['value'] = $userInput[$name];

            $arguments[$subject][] = $argument;
        });
    });

    return $arguments;
}
