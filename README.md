# CLI2Web (CommandLine Interface to Web)

## Introduction

The aim of this project is to make it trivial to expose a shell script("command-line 
script" or "CLI script") as a web application.

This library offers conversion, logic and templates to gather user input, feed it
to a shell script and render the output in the browser.

All that has to be provided is a shell script and configuration. Optionally logic
can be added to compliment the input, output or render process. 

## Requirements

- As this is a web application, it is assumed a web-server is available.
- This project is written in PHP. Any actively support version is fine.

## Installation

Installation is done using composer:

    composer require potherca/cli2web

As this is a web application, it is assumed a web-server is available.

## Usage

- Create an index file in a public facing directory in the web-server
- Include the cli2web engine
- Feed the engine a configuration
- Done.

For more advanced scenario's any of the following steps can also be taken:

- Add logic that is called with the user input  
  <small>(instead of the CLI script being called directly)</small>
- Add logic that is called with the shell script output  
  <small>(instead of the output being displayed directly, "raw", as-is)</small>
- Add custom UI to display the returned output
  <small>(instead of the output being displayed as plain-text)</small>
