  <header class="hero is-white has-text-centered">
    <?php if ($title): ?>
    <h1 class="title has-text-info">
      <?php if ($title_link): ?>
      <a href="<?= htmlentities($title_link) ?>">
        <?= htmlentities($title) ?>
      </a>
      <?php else: ?>
        <?= htmlentities($title) ?>
      <?php endif ?>
    </h1>
    <?php endif ?>

    <?php if ($sub_title): ?>
    <span class="subtitle has-text-grey-light">
      <?= htmlentities($sub_title) ?>
    </span>
    <?php endif ?>
    <?php if ($description): ?>
    <p class="box message is-primary">
      <?= htmlentities($description) ?>
    </p>
    <?php endif ?>
  </header>
