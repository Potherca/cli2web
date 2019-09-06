    <form accept-charset="utf-8" action="" enctype="multipart/form-data" method="get" spellcheck="false">

        <fieldset class="box has-text-centered">

          <?php foreach($arguments as $argument): ?>
            <?php include('arguments.php') ?>
          <?php endforeach ?>

          <?php if (! $single_argument): ?>
            <?php include('submit-button.php') ?>
          <?php endif ?>

        </fieldset>

        <?php if ($has_options): ?>
        <details class="options has-text-right"<?php if($has_option_selected): ?> open<?php endif ?>>

          <summary class="button is-info is-outlined is-centered">
            Options
          </summary>

          <div class="box has-text-left">
            <?php foreach($flags as $flag): ?>
              <?php include('flags.php') ?>
            <?php endforeach ?>

            <?php foreach($options as $option): ?>
              <?php include('options.php') ?>
            <?php endforeach ?>
          </div>

        </details>
        <?php endif ?>

    </form>
