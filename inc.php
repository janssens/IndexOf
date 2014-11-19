<?php

  /*******************
   * BEGIN Settings:
   *******************/
  $folderName = '.iof'; //name of the directory
  $indexFiles = array('index.php','index.html'); //list of file to use prior to File listing?
  $imgFiles = array('jpg','jpeg','gif','png'); //list of what should be lightboxed
  define('DEBUG', true);
  /*******************
   * END Settings:
   *******************/

  $iofPath = pathinfo($_SERVER['SCRIPT_FILENAME']);
  $iofPath = $iofPath['dirname'];
  $root = str_replace($folderName, '', str_replace($_SERVER['DOCUMENT_ROOT'], '', $iofPath));  

  function process_dir($dir,$recursive = FALSE) {
    global $folderName;
    if (is_dir($dir)) {
      for ($list = array(),$handle = opendir($dir); (FALSE !== ($file = readdir($handle)));) {
        if (($file != '.' && $file != '..' && $file != $folderName) && (file_exists($path = $dir.'/'.$file))) {
          if (is_dir($path) && ($recursive)) {
            $list = array_merge($list, process_dir($path, TRUE));
          } else {
            $entry = array('filename' => $file, 'dirpath' => substr($dir, 2));
            $entry['modtime'] = filemtime($path);
            do if (!is_dir($path)) {
              $entry['size'] = filesize($path);
              $entry['type'] = pathinfo($path, PATHINFO_EXTENSION);
              break;
            } else {
              $entry['type'] = 'dir';
              break;
            } while (FALSE);
            $list[] = $entry;
          }
        }
      }
      closedir($handle);
      return $list;
    } else return FALSE;
  }

  function human_filesize($bytes, $decimals = 2) {
    $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
  }
  $srcs = array();
  $srcs["default"] = 'txt';
  $srcs["pdf"] = 'acrobat';
  $srcs["zip"] = 'archive';
  $srcs["rar"] = 'archive';
  $srcs["gz"] = 'archive';
  $srcs["7zip"] = 'archive';
  $srcs["css"] = 'css';
  $srcs["less"] = 'css';
  $srcs["scss"] = 'css';
  $srcs["xls"] = 'excel';
  $srcs["xlsx"] = 'excel';
  $srcs["flv"] = 'flash';
  $srcs["fla"] = 'flash';
  $srcs["html"] = 'html';
  $srcs["htm"] = 'html';
  $srcs["phtml"] = 'php';
  $srcs["idd"] = 'indesign';
  $srcs["js"] = 'js';
  $srcs["json"] = 'js';
  $srcs["otf"] = 'otf';
  $srcs["php"] = 'php';
  $srcs["svg"] = 'svg';
  $srcs["ptt"] = 'powerpoint';
  $srcs["ttf"] = 'ttf';
  $srcs["avi"] = 'video';
  $srcs["mov"] = 'video';
  $srcs["wmv"] = 'video';
  $srcs["vid"] = 'video';
  $srcs["mp4"] = 'video';
  $srcs["doc"] = 'word';
  $srcs["docx"] = 'word';
  $srcs["ae"] = 'after effects';
  $srcs["au"] = 'audition';
  $srcs["dw"] = 'dreamweaver';
  $srcs["fw"] = 'fw';
  $srcs["gif"] = 'gif';
  $srcs["ai"] = 'illustrator';
  $srcs["jpg"] = 'jpg';
  $srcs["jpeg"] = 'jpg';
  $srcs["png"] = 'png';
  $srcs["mp3"] = 'music';
  $srcs["wav"] = 'wav';
  $srcs["wave"] = 'music';
  $srcs["flac"] = 'music';
  $srcs["txt"] = 'txt';
  $srcs["psd"] = 'photoshop';
  $srcs["pre"] = 'premiere pro';
  $srcs["torrent"] = 'torrent';

  $srcs["dir"] = 'folder';
  
  function getImgSrcFromType($type){
    global $srcs,$root,$folderName;
    if (isset($srcs[$type])){
      $r = $srcs[$type];
    }else{
      $r = $srcs["default"];
    }
    return "http://".$_SERVER['HTTP_HOST'].$root.'/'.$folderName.'/img/png/'.$r.".png";
  }

  function getRaws($files){
    global $imgFiles;
    $r = '';
    if ($files && is_array($files)){
      foreach ($files as $file) {
        if (!isset($file['size'])){
          $file['size'] = 0;
        }

        $r .= '<tr class="'.$file['type'].'">';
        $r .= '<td val="'.strtolower($file['filename']).'">';
        $r .= '<a class="';
        $r .= ($file['type']=='dir') ? "ajax" : "";
        $r .= '" href="'.$file['filename'];
        $r .= ($file['type']=='dir') ? "/" : "";
        $r .= (in_array($file['type'], $imgFiles)) ? '" rel="imageFiles"' : '"';
        $r .= '>';
        $r .= '<img src="'.getImgSrcFromType($file['type']).'" />'.$file['filename'].'</a></td>';
        $r .= '<td val="'.$file['type'].'">';
        $r .= ($file['type']=='dir') ? "folder":$file['type'];
        $r .= '</td>';
        $r .= '<td val="'.$file['modtime'].'">'.date('Y-m-d H:i:s',$file['modtime']).'</td>';
        $r .= '<td val="'.$file['size'].'">';
        $r .= ($file['type']=='dir') ? "" : human_filesize($file['size']);
        $r .= '</td>';
        $r .= '</tr>';
      }
    }
    return $r;
  }
  ?>