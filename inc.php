<?php

  $root = '';

  function process_dir($dir,$recursive = FALSE) {
    if (is_dir($dir)) {
      for ($list = array(),$handle = opendir($dir); (FALSE !== ($file = readdir($handle)));) {
        if (($file != '.' && $file != '..' && $file != '.iof') && (file_exists($path = $dir.'/'.$file))) {
          if (is_dir($path) && ($recursive)) {
            $list = array_merge($list, process_dir($path, TRUE));
          } else {
            $entry = array('filename' => $file, 'dirpath' => substr($dir, 2));

             //---------------------------------------------------------//
             //                     - SECTION 1 -                       //
             //          Actions to be performed on ALL ITEMS           //
             //-----------------    Begin Editable    ------------------//

              $entry['modtime'] = filemtime($path);

             //-----------------     End Editable     ------------------//
                        do if (!is_dir($path)) {
             //---------------------------------------------------------//
             //                     - SECTION 2 -                       //
             //         Actions to be performed on FILES ONLY           //
             //-----------------    Begin Editable    ------------------//

              $entry['size'] = filesize($path);
              /*if (strstr(pathinfo($path,PATHINFO_BASENAME),'log')) {
                if (!$entry['handle'] = fopen($path,r)) $entry['handle'] = "FAIL";
              }*/
              $entry['type'] = pathinfo($path, PATHINFO_EXTENSION);
              
             //-----------------     End Editable     ------------------//
                          break;
                        } else {
             //---------------------------------------------------------//
             //                     - SECTION 3 -                       //
             //       Actions to be performed on DIRECTORIES ONLY       //
             //-----------------    Begin Editable    ------------------//
              $entry['type'] = 'dir';
             //-----------------     End Editable     ------------------//
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
    global $srcs;
    $r = $srcs[$type];
    if (!$r){
      $r = $srcs["default"];
    }
    return "http://".$_SERVER['HTTP_HOST'].$root.'/.iof/img/png/'.$r.".png";
  }

  function getRaws($files){
    $r = '';
    foreach ($files as $file) {
      $r .= '<tr class="'.$file['type'].'">';
      $r .= '<td val="'.$file['filename'].'">';
      $r .= '<a class="';
      $r .= ($file['type']=='dir') ? "ajax" : "";
      $r .= '" href="'.$file['dirpath'].$file['filename'];
      $r .= ($file['type']=='dir') ? "/" : "";
      $r .= '">';
      $r .= '<img src="'.getImgSrcFromType($file['type']).'" />'.$file['filename'].'</a></td>';
      $r .= '<td val="'.$file['type'].'">';
      $r .= ($file['type']=='dir') ? "folder":$file['type'];
      $r .= '</td>';
      $r .= '<td val="'.$file['modtime'].'">'.date('Y-m-d H:i:s',$file['modtime']).'</td>';
      $r .= '<td val="'.$file['size'].'">'.human_filesize($file['size']).'</td>';
      $r .= '</tr>';
    }
    return $r;
  }
  ?>