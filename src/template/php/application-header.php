  <header class="hero is-white has-text-centered">
    <? if ($title): ?>
    <h1 class="title has-text-info">
      <? if ($title_link): ?>
      <a href="<?= htmlentities($title_link) ?>">
        <?= htmlentities($title) ?>
      </a>
      <? else: ?>
        <?= htmlentities($title) ?>
      <? endif ?>
    </h1>
    <? endif ?>

    <? if ($sub_title): ?>
    <span class="subtitle has-text-grey-light">
      <?= htmlentities($sub_title) ?>
    </span>
    <? endif ?>
    <? if ($description): ?>
    <p class="box message is-primary">
      <?= htmlentities($description) ?>
    </p>
    <? endif ?>
  </header>
