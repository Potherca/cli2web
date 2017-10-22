
<div class="field is-horizontal">
    <? if ($argument['label']): ?>
    <div class="field-label is-medium">
        <label
            class="label is-medium"
            for="<?= htmlentities($argument['name']) ?>"
            title="<?= htmlentities($argument['description']) ?>"
        >
        <?= htmlentities($argument['label']) ?>
        </label>
    </div>
    <? endif ?>
    <div class="field-body">
        <div class="field <? if ($single_argument): ?>has-addons<? endif ?>">
            <div class="control is-expanded">
                <input
                    class="input is-large"
                    type="<?= htmlentities($argument['type']) ?>"
                    <? if ($argument['has_autocomplete']): ?>list="<?= htmlentities($argument['name']) ?>-autocomplete"<? endif ?>
                    name="<?= htmlentities($argument['name']) ?>"
                    placeholder="<?= htmlentities($argument['example']) ?>"
                    <? if ($argument['value']): ?>value="<?= htmlentities($argument['value']) ?>"
                    <? elseif ($argument['default']): ?>value="<?= htmlentities($argument['default']) ?>"
                    <? endif ?>
                />
                <p class="help is-info"><?= htmlentities($argument['description']) ?></p>
            </div>

            <div class="control">
                <? include('submit-button.php') ?>
            </div>
        </div>
    </div>
</div>

<? if ($argument['has_autocomplete']): ?>
<datalist id="<?= htmlentities($argument['name']) ?>-autocomplete">
    <? foreach ($argument['autocomplete'] as $a): ?>
    <option value="<?= htmlentities($a) ?>">
    <? endforeach ?>
</datalist>
<? endif ?>
