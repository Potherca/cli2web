
<div class="field is-horizontal">
  <div class="field-label is-medium">
    <label class="label is-medium" for="<?= htmlentities($option['name']) ?>" title="<?= htmlentities($option['description']) ?>">
      <?= htmlentities($option['label']) ?>
    </label>
  </div>
  <div class="field-body">
    <div class="field">
      <div class="control is-pulled-left">
      <input
          class="input is-info"
          id="<?= htmlentities($option['name']) ?>"
          name="<?= htmlentities($option['name']) ?>"
          type="<?= htmlentities($option['type']) ?>"
          <? if($option['value']): ?>value="<?= htmlentities($option['value']) ?>"
          <? elseif($option['default']): ?>data-value="<?= htmlentities($option['default']) ?>"<? endif ?>
          <? if($option['default']): ?>placeholder="<?= htmlentities($option['default']) ?>"<? endif ?>
        />
      </div>
      <p class="help is-info"><?= htmlentities($option['description']) ?></p>
    </div>
  </div>
</div>
