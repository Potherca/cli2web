<footer class="footer has-text-right">
<? if ($project): ?>
  <? if($project['version']): ?><span class="version"><?= htmlentities($project['version']) ?></span> &ndash;<? endif ?>
  The Source Code for this project is available
  <? if($project['source_url']): ?><a href="<?= htmlentities($project['source_url']) ?>"> on <?= htmlentities($project['source']) ?></a><? endif ?>
  <? if($project['license']): ?>under a <a href="https://spdx.org/licenses/<?= htmlentities($project['license']) ?>.html" rel="license" target="_blank"><?= htmlentities($project['license']) ?> License</a><? endif ?>

  <? if($project['author']): ?>
  &ndash;
  <a <? if($project['author_url']): ?>href="<?= htmlentities($project['author_url']) ?>"<? endif ?>class="created-by">
    Created by
    <span class="<?= htmlentities($project['author']) ?>"><?= htmlentities($project['author']) ?></span>
  </a>
  <? endif ?>
<? endif ?>
</footer>

