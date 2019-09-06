
<div class="field is-horizontal">
    <?php if ($argument['label']): ?>
    <div class="field-label is-medium">
        <label
            class="label is-medium"
            for="<?= htmlentities($argument['name']) ?>"
            title="<?= htmlentities($argument['description']) ?>"
        >
        <?= htmlentities($argument['label']) ?>
        </label>
    </div>
    <?php endif ?>
    <div class="field-body">
        <div class="field <?php if ($single_argument): ?>has-addons<?php endif ?>">
            <div class="control is-expanded">
                <input
                    class="input is-large"
                    type="<?= htmlentities($argument['type']) ?>"
                    <?php if ($argument['has_autocomplete']): ?>list="<?= htmlentities($argument['name']) ?>-autocomplete"<?php endif ?>
                    name="<?= htmlentities($argument['name']) ?>"
                    placeholder="<?= htmlentities($argument['example']) ?>"
                    <?php if ($argument['value']): ?>value="<?= htmlentities($argument['value']) ?>"
                    <?php elseif ($argument['default']): ?>value="<?= htmlentities($argument['default']) ?>"
                    <?php endif ?>
                />
                <p class="help is-info"><?= htmlentities($argument['description']) ?></p>
            </div>

            <div class="control">
                <?php include('submit-button.php') ?>
            </div>
        </div>
    </div>
</div>

<?php if ($argument['has_autocomplete']): ?>
<datalist id="<?= htmlentities($argument['name']) ?>-autocomplete">
    <?php foreach ($argument['autocomplete'] as $a): ?>
    <option value="<?= htmlentities($a) ?>">
    <?php endforeach ?>
</datalist>
<?php endif ?>
