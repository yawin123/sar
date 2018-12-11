<?
  $pf = fopen("./tos.txt", "r");
  $txt = fread($pf, filesize("./tos.txt"));
  fclose($pf);

  echo nl2br($txt);
?>
