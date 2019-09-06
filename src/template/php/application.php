<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php if($page_title): ?><?= htmlentities($page_title) ?><?php else: ?><?= htmlentities($title) ?><?php endif ?></title>

  <?php foreach($stylesheets as $s): ?>
  <link rel="stylesheet" href="<?= htmlentities($s) ?>">
  <?php endforeach ?>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.0/css/bulma.min.css" integrity="sha256-HEtF7HLJZSC3Le1HcsWbz1hDYFPZCqDhZa9QsCgVUdw=" crossorigin="anonymous" />

  <style>
      html {
      background-color: #e7f4fd;
    }
    .hero .box {
      background-color: #d0eafb;
    }

    .footer {
      bottom: 0;
      padding-bottom: 2rem;
      padding-top: 1rem;
      position: fixed;
      width: 100%;
    }
    <?php if($stylesheet_inline): ?>
    <?= $stylesheet_inline ?>
    <?php endif ?>
  </style>
</head>

<body>

  <?php include('application-header.php') ?>

  <section class="section coloumns column is-10 is-offset-1">

    <?php foreach ($messages as $m): ?>
    <div class="message<?= $m['type']?> is-<?= htmlentities($m['type']) ?>">

      <div class="message-header">
        <p><?= htmlentities($m['message']) ?></p>
        <button class="delete" aria-label="delete" onclick="this.parentNode.parentNode.classList.toggle('is-hidden');"></button>
      </div>

      <?php if ($m['details']): ?>
      <p class="message-body">
        <?= htmlentities($m['details']) ?>
      </p>
      <?php endif ?>
    </div>
    <?php endforeach ?>

    <?php include('form.php') ?>

    <?php if ($results): ?>
    <div class="results has-text-centered">
        <h2 class="title">Results (<?= htmlentities($results) ?>)</h2>

        <?php foreach ($result_list as $result): ?>
          <?php /*include 'data://text/plain;base64,'.base64_encode($resultTemplate) */ ?>
          <?php eval($resultTemplate); ?>
        <?php endforeach ?>
    </div>
    <?php endif ?>
  </section>

  <?php include('application-footer.php') ?>

    <?php foreach($javascript as $j): ?>
    <script src="<?= $j ?>"></script>
    <?php endforeach ?>
    <?php if($javascript_inline): ?>
    <script><?= $javascript_inline ?></script>
    <?php endif ?>
</body>
</html>
