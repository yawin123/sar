<?
  function generate_item_list($forum, $xml_ptr)
  {
    foreach($forum->sons as $s)
    {
      if($s->rango == 0)
      {
        $item = $xml_ptr->addChild("item");
        $item->addChild("title", htmlspecialchars($s->nombre));
        $item->addChild("link", urlencode('http://'.$_SERVER['HTTP_HOST'].'?p=view&post='.$s->id));
        $item->addChild("description", htmlspecialchars($s->descripcion));

        generate_item_list($s, $xml_ptr);
      }
    }
  }

 $xml_path = $plugin_path.'static';
 $feed_rss = simplexml_load_file($xml_path.".xml");
 foreach($feed_rss->channel as $channel)
 {
   $channel->addChild("link", 'http://'.$_SERVER['HTTP_HOST']);
   break;
 }

 $forum_tree = get_forum_tree();
 foreach($forum_tree as $t)
 {
   if($t->rango == 0)
   {
     generate_item_list($t, $feed_rss->channel);
   }
 }

 $feed_rss->asXML($xml_path.'_full.xml');
?>
