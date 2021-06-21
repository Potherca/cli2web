For now a PHP config is fine. For the "load from any source" take a look at:

https://github.com/hassankhan/config <- I picked this one
https://github.com/PHLAK/Config
https://github.com/romaricdrigon/MetaYaml

=======================================================================================

Terminology
-----------

A "parameter" the declaration of a variable in a command definition. When a
command is called, an "argument" is the actual value passed to the command.

Arguments are instructions that change the behaviour of a script (or the
environment the script runs in). Any information a script requires to run is
called an "argument", to distinguis them from optional arguments. The word
"option" is assumed to come from "optional", meaning arguments that are NOT
required. A "flag" or "switch" is an option that has a boolean value and is
false by default.


    find-github-typos <search-term> [--false-positives-threshold=3] [--show-false-positives] [--skip-typo-check]

    |--- command ---| |- argument -| |---------- option ----------| |--------------- flags --------------------|

Or, to be more precise:

    |--- command ---| |-------------------------------------- arguments ---------------------------------------|
                                    |-------------------------------- options ---------------------------------|
                                                                    |----------------- flags ------------------|

=======================================================================================

For "type", valid HTML <input> types are used.

- file: A control that lets the user select a file. Use the accept attribute to define the types of files that the control can select.

- password: A single-line text field whose value is obscured. Use the maxlength attribute to specify the maximum length of the value that can be entered.

- radio: A radio button, allowing a single value to be selected out of multiple choices.

- text: A single-line text field. Line-breaks are automatically removed from the input value.
  - color: HTML5 A control for specifying a color. A color picker's UI has no required features other than accepting simple colors as text (more info).
  - datetime-local: HTML5 A control for entering a date and time, with no time zone.
    - date: HTML5 A control for entering a date (year, month, and day, with no time).
    - month: HTML5 A control for entering a month and year, with no time zone.
    - time: HTML5 A control for entering a time value with no time zone.
    - week: HTML5 A control for entering a date consisting of a week-year number and a week number with no time zone.
  - email: HTML5 A field for editing an e-mail address.
  - number: HTML5 A control for entering a number.
    - range: HTML5 A control for entering a number whose exact value is not important.
  - search: HTML5 A single-line text field for entering search strings. Line-breaks are automatically removed from the input value.
  - tel: HTML5 A control for entering a telephone number.
  - url: HTML5 A field for entering a URL.
