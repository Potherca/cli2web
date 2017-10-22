
  <div class="card">
    <header class="card-header">
      <p class="card-header-title">
        <a href="<?= htmlentities($result['repository_url']) ?>" target="_blank"><?= htmlentities($result['repository_name']) ?></a>
      </p>
    </header>
    <? if($result['repository_description']): ?>
    <p style="padding: .75rem; font-style: italic;">
      <?= htmlentities($result['repository_description']) ?>
    </p>
    <? endif ?>
    <div class="card-content">
      <div class="content">
          <?= $result['file_fragment'] ?>
      </div>
    </div>
    <p><code style="display:block; width: 100%;"><?= htmlentities($result['file_path']) ?></code></p>
    <footer class="card-footer">
      <p class="card-footer-item">
        <span class="icon fa fa-star"></span>
        <span class=""><?= htmlentities($result['stars']) ?></span>
      </p>
      <p class="card-footer-item">
        <a href="<?= htmlentities($result['edit_url']) ?>" target="_blank">Edit</a>
      </p>
    </footer>
  </div>