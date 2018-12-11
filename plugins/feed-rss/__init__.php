<?
  $GLOBALS['LAZY_LOAD']->addContent($plugin_path);
  $GLOBALS['INDEX_CONTENT']->addSpecialPage("feed", $plugin_path."feed.php");
  $GLOBALS['FEED_RSS_PATH'] = $plugin_path;
  $GLOBALS['INDEX_FEED_RSS'] = 'http://'.$_SERVER['HTTP_HOST']."?sp=feed";
?>
