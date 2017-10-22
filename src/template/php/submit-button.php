
<button type="submit" class="button is-info is-large">
    <? if($submit_icon): ?>
    <span class="icon" aria-hidden="true">
          <i class="fa fa-<?= htmlentities($submit_icon) ?>"></i>
    </span>
    <? endif ?>

    <span>
        <?= htmlentities($submit_name) ?>
    </span>
</button>
