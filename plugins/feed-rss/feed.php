<?
  function getStatic()
  {
    echo file_get_contents($GLOBALS['FEED_RSS_PATH']."static_full.xml");
  }

  if(!isset($_GET['rss']))
  {
    getStatic();
    return;
  }

  $post_id = $_GET['rss'];
  $query = "SELECT tipo, rango FROM forums WHERE id=".$post_id;
  $row = $GLOBALS['BD']->select($query);

  $res = $row->fetch(PDO::FETCH_ASSOC);

  if($res['tipo'] < 1 || $GLOBALS['SESION']->getRangeLevel($res['rango']) > $GLOBALS['RANGE'])
  {
    getStatic();
    return;
  }

  $query = "SELECT nombre, descripcion, tipo FROM forums WHERE id=".$post_id;
  $row = $GLOBALS['BD']->select($query);
  $res = $row->fetch(PDO::FETCH_ASSOC);

  echo '<?xml version="1.0" encoding="UTF-8"?>';
  ?>

    <rss version="2.0">
      <channel>
          <title><? echo htmlspecialchars($res['nombre']);?></title>
          <description><? echo ($res['descripcion'] != '') ? htmlspecialchars($res['descripcion']) : "Sin descripción"; ?></description>
          <? switch($res['tipo'])
          {
            case 1:
              $query = "SELECT forums.id as id, forums.nombre as nombre, forums.ult_mod as ult_mod, forums.descripcion as descripcion FROM forums INNER JOIN _ranges ON forums.rango = _ranges._id WHERE forums.id_padre=".$post_id." AND forums.tipo=1 AND _ranges._range_level <=".$GLOBALS['RANGE'];
              $row = $GLOBALS['BD']->select($query);
              foreach($row as $r)
              {?>
                <item>
                  <title><? echo htmlspecialchars($r['nombre']);?></title>
                  <link><? echo urlencode('http://'.$_SERVER['HTTP_HOST'].'?p=view&post='.$r['id']);?></link>
                  <description><? echo ($res['descripcion'] != '') ? htmlspecialchars($res['descripcion']) : "Sin descripción"; ?></description>
                </item>
              <?}

                $query = "SELECT forums.id as id, forums.nombre as nombre, forums.ult_mod as ult_mod, forums.descripcion as descripcion FROM forums INNER JOIN _ranges ON forums.rango = _ranges._id WHERE forums.id_padre=".$post_id." AND forums.tipo=2 AND _ranges._range_level <=".$GLOBALS['RANGE'];
                $row = $GLOBALS['BD']->select($query);
                foreach($row as $r)
                {
                  $query = "SELECT content, COUNT(id) as contador FROM forums_content WHERE thread_id=".$r['id'].' ORDER BY id ASC';
                  $comments = $GLOBALS['BD']->select($query);
                  $com = $comments->fetch(PDO::FETCH_ASSOC);?>

                  <item>
                    <title><? echo htmlspecialchars($r['nombre']);?> (<? echo $com['contador']; echo ($com['contador'] > 1) ? " respuestas" : " respuesta";?>)</title>
                    <link><? echo urlencode('http://'.$_SERVER['HTTP_HOST'].'?p=view&post='.$r['id']);?></link>
                    <description><? echo htmlspecialchars($com['content']); ?></description>
                  </item>
                <?}
              break;

            case 2:
              $query = "SELECT forums_content.id as id, forums_content.user_id as u_id, forums_content.fecha as fecha, forums_content.content as content, _user._name as u_name, _user.avatar as avatar, _user._range as rango FROM forums_content INNER JOIN _user ON forums_content.user_id = _user._id  WHERE forums_content.thread_id=".$post_id.' ORDER BY forums_content.id ASC';
              $row = $GLOBALS['BD']->select($query);
              foreach($row as $r)
              {?>
                <item>
                  <title><? echo htmlspecialchars($r['u_name']);?></title>
                  <link><? echo urlencode('http://'.$_SERVER['HTTP_HOST'].'?p=view&post='.$r['id']);?></link>
                  <description><? echo htmlspecialchars($r['content']);?></description>
                </item>
              <?}
              break;
          }?>
        </channel>
</rss>
