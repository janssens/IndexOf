<?php include_once("inc.php"); ?>
<?php
/* AJAX check  */
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
  /* special ajax here */
  header('Content-type: application/json');
  $dir = $_POST['dir'];
  if ($dir[0]!='/'){
    $dir = '/'.$dir;
  }
  if ($indexFiles){
    foreach ($indexFiles as $key => $value) {
      if(file_exists($_SERVER['DOCUMENT_ROOT'].$dir.$value)){
        //die('Location: '.'http://'.$_SERVER['HTTP_HOST'].$_POST['dir'].$value);
        $json = json_encode(array("type"=>'header',"value"=>'http://'.$_SERVER['HTTP_HOST'].$dir.$value));
        die($json);
      }
    }
  }
  $result = process_dir($_SERVER['DOCUMENT_ROOT'].$dir,FALSE);
  $json = json_encode(array("type"=>'content',"value"=>getRaws($result),"debug_info"=>$_SERVER['DOCUMENT_ROOT'].$dir));
  die($json);
}
$dirname = str_replace($folderName, '', $iofPath);
$dirname .= str_replace($root, '', $_SERVER['REQUEST_URI']);
if ($indexFiles){
    foreach ($indexFiles as $key => $value) {
      if(file_exists($dirname.$value)){
        header('Location: '.'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].$value);
        exit;
      }
    }
  }
?>
<html>
<head>
  <link rel="stylesheet" href="<?php echo "http://".$_SERVER['HTTP_HOST'].$root.'/'.$folderName ?>/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo "http://".$_SERVER['HTTP_HOST'].$root.'/'.$folderName ?>/css/jquery.fancybox.css">
  <link rel="stylesheet" href="<?php echo "http://".$_SERVER['HTTP_HOST'].$root.'/'.$folderName ?>/css/main.css">
  <title>Index of: <?php echo $_SERVER['REQUEST_URI']; ?></title>
</head>
<body>
  <div class="container">
    <div class="raw">
      <h1>Index of: <?php echo $_SERVER['REQUEST_URI']; ?></h1>
      <table class="table table-condensed table-hover tablesorter col-12 col-lg-12">
        <thead>
          <tr>
            <th>Nom<span></span></th>
            <th>Type<span></span></th>
            <th>Derni&egrave;re Modification<span></span></th>
            <th>Taille<span></span></th>
          </tr>
        </thead>
        <tbody>
          <?php echo getRaws(process_dir($dirname,FALSE)); ?>
        </tbody>
      </table>
    </div>
  </div>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <script>
  if (!window.jQuery) { document.write('<script src="<?php echo "http://".$_SERVER['HTTP_HOST'].$root.'/'.$folderName ?>/js/jquery.min.js"><\/script>');}
  </script>
  <script src="<?php echo "http://".$_SERVER['HTTP_HOST'].$root.'/'.$folderName ?>/js/jquery.history.js"></script>
  <script src="<?php echo "http://".$_SERVER['HTTP_HOST'].$root.'/'.$folderName ?>/js/jquery.fancybox.pack.js"></script>
  <script src="<?php echo "http://".$_SERVER['HTTP_HOST'].$root.'/'.$folderName ?>/js/jquery.tablesorter.min.js"></script>
  <script type="text/javascript">
  var iofUrl = '<?php echo $_SERVER['SCRIPT_NAME']; ?>';
  </script>
  <script src="<?php echo "http://".$_SERVER['HTTP_HOST'].$root.'/'.$folderName ?>/js/script.js"></script>
</body>
</html>
