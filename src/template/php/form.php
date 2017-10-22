    <form accept-charset="utf-8" action="" enctype="multipart/form-data" method="get" spellcheck="false">

        <fieldset class="box has-text-centered">

          <? foreach($arguments as $argument): ?>
            <? include('arguments.php') ?>
          <? endforeach ?>

          <? if (! $single_argument): ?>
            <? include('submit-button.php') ?>
          <? endif ?>

        </fieldset>

        <? if ($has_options): ?>
        <details class="options has-text-right"<? if($has_option_selected): ?> open<? endif ?>>

          <summary class="button is-info is-outlined is-centered">
            Options
          </summary>

          <div class="box has-text-left">
            <? foreach($flags as $flag): ?>
              <? include('flags.php') ?>
            <? endforeach ?>

            <? foreach($options as $option): ?>
              <? include('options.php') ?>
            <? endforeach ?>
          </div>

        </details>
        <? endif ?>

    </form>
