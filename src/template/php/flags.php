
<div class="field is-horizontal">
  <div class="field-label is-medium">
    <label
      class="label is-medium"
      for="<?= htmlentities($flag['name']) ?>"
      title="<?= htmlentities($flag['description']) ?>"
    >
      <?= htmlentities($flag['label']) ?>
    </label>
  </div>
  <div class="field-body">
    <div class="field">
      <div class="control">
        <input
          data-class="input is-info"
          data-type="<?= htmlentities($flag['type']) ?>"
          class="switch is-rounded is-info is-medium"
          id="<?= htmlentities($flag['name']) ?>"
          name="<?= htmlentities($flag['name']) ?>"
          type="checkbox"
          value="true"
          <? if($flag['value']): ?>checked<? endif ?>
        />
        <label class="label is-medium" for="<?= htmlentities($flag['name']) ?>" title="<?= htmlentities($flag['description']) ?>">&nbsp;</label>
      </div>
      <p class="help is-info"><?= htmlentities($flag['description']) ?></p>
    </div>
  </div>
</div>
