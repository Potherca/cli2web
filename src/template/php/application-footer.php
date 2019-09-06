<footer class="footer has-text-right">
<?php if ($project): ?>
  <?php if($project['version']): ?><span class="version"><?= htmlentities($project['version']) ?></span> &ndash;<?php endif ?>
  The Source Code for this project is available
  <?php if($project['source_url']): ?><a href="<?= htmlentities($project['source_url']) ?>"> on <?= htmlentities($project['source']) ?></a><?php endif ?>
  <?php if($project['license']): ?>under a <a href="https://spdx.org/licenses/<?= htmlentities($project['license']) ?>.html" rel="license" target="_blank"><?= htmlentities($project['license']) ?> License</a><?php endif ?>

  <?php if($project['author']): ?>
  &ndash;
  <a <?php if($project['author_url']): ?>href="<?= htmlentities($project['author_url']) ?>"<?php endif ?>class="created-by">
    Created by
    <span class="<?= htmlentities($project['author']) ?>"><?= htmlentities($project['author']) ?></span>
  </a>
  <?php endif ?>
<?php endif ?>
</footer>

