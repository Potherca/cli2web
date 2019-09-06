
<button type="submit" class="button is-info is-large">
    <?php if($submit_icon): ?>
    <span class="icon" aria-hidden="true">
          <i class="fa fa-<?= htmlentities($submit_icon) ?>"></i>
    </span>
    <?php endif ?>

    <span>
        <?= htmlentities($submit_name) ?>
    </span>
</button>
