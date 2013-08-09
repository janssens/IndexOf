<?php include_once("inc.php"); ?>
<?php
/* AJAX check  */
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
  /* special ajax here */
  //header('Content-type: application/json');
  $result = process_dir("..".$_POST['dir'],FALSE);
  //$json = json_encode(getRaws($result));
  die(getRaws($result));
}
?>
<html>
<head>
  <!-- Bootstrap stylesheet -->
  <link rel="stylesheet" href="<?php echo "http://".$_SERVER['HTTP_HOST'].$root ?>/.iof/css/bootstrap.min.css">
  <!-- bootstrap widget theme -->
  <link rel="stylesheet" href="<?php echo "http://".$_SERVER['HTTP_HOST'].$root ?>/.iof/css/main.css">
  <title>Index of: <?php echo $_SERVER['SERVER_NAME']."/";echo dirname('..'); ?></title>
</head>
<body>
  <div class="container">
    <div class="raw">
      <table class="table table-condensed table-hover tablesorter col-12 col-lg-12">
        <thead>
          <tr>
            <th>Nom</th>
            <th>Type</th>
            <th>Derni&egrave;re Modification</th>
            <th>Taille</th>
          </tr>
        </thead>
        <tbody>
          <?php echo getRaws(process_dir("..".$_SERVER['REQUEST_URI'],FALSE)); ?>
        </tbody>
      </table>
    </div>
  </div>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <script>
  if (!window.jQuery) { document.write('<script src="<?php echo "http://".$_SERVER['HTTP_HOST'].$root ?>/.iof/js/jquery.min.js"><\/script>');}
  </script>
  <script src="<?php echo "http://".$_SERVER['HTTP_HOST'].$root ?>/.iof/js/jquery.history.js"></script>
  <script src="<?php echo "http://".$_SERVER['HTTP_HOST'].$root ?>/.iof/js/script.js"></script>
</body>
</html>
