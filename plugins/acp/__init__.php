<?
  if($GLOBALS['RANGE'] == 101)
  {
    $GLOBALS['ACP_PLUGIN'] = $plugin_path;
    $GLOBALS['INDEX_CONTENT']->addStyleSheet($plugin_path."style.css");
    $GLOBALS['INDEX_CONTENT']->addPage("acp", $plugin_path."acp.php", "Administración");

    $GLOBALS['ACP_NAV'] = new Menu("acp", "");
    $GLOBALS['ACP_NAV']->addPage("acp&section=inicio", $plugin_path."acp.php", "Opciones generales");
    $GLOBALS['ACP_NAV']->addPage("acp&section=forums", $plugin_path."acp.php", "Foros");
    $GLOBALS['ACP_NAV']->addPage("acp&section=rangos", $plugin_path."acp.php", "Usuarios y rangos");
  }

  function get_forum_tree()
  {
    class forum_struct
    {
      public $id;
      public $nombre;
      public $id_padre;
      public $tipo;
      public $descripcion;
      public $rango;
      public $sons = array();
      public function __construct($id, $nombre, $id_padre, $tipo, $descripcion, $rango)
      {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->id_padre = $id_padre;
        $this->tipo = $tipo;
        $this->descripcion = $descripcion;
        $this->rango = $rango;
      }
    }

    $query = 'SELECT id, nombre, id_padre, tipo, descripcion, rango FROM forums WHERE tipo < 2';
    $row = $GLOBALS['BD']->select($query);

    $forum_tree = array();
    $huerfanos = array();
    $post_process = array();

    foreach($row as $r)
    {
      if($r['tipo'] == 0)
      {
        array_push($forum_tree, new forum_struct($r['id'], $r['nombre'], $r['id_padre'], $r['tipo'], $r['descripcion'], $r['rango']));
      }
      else
      {
        $fpt = NULL;
        foreach($forum_tree as $t)
        {
          $fpt = find_father($t, $r['id_padre']);
          if(!is_null($fpt))
          {
            array_push($fpt->sons, new forum_struct($r['id'], $r['nombre'], $r['id_padre'], $r['tipo'], $r['descripcion'], $r['rango']));
            break;
          }
        }

        if(is_null($fpt))
        {
          if($r['id_padre'] > -1)
          {
            array_push($post_process, new forum_struct($r['id'], $r['nombre'], $r['id_padre'], $r['tipo'], $r['descripcion'], $r['rango']));
          }
          else
          {
            array_push($huerfanos, new forum_struct($r['id'], $r['nombre'], $r['id_padre'], $r['tipo'], $r['descripcion'], $r['rango']));
          }
        }
      }
    }

    $limit = 10000;
    while(count($post_process) > 0)
    {
      if($limit-- <= 0){break;}

      $pp = array();
      foreach($post_process as $pop)
      {
        $fpt = NULL;
        foreach($forum_tree as $t)
        {
          $fpt = find_father($t, $pop->id_padre);
          if(!is_null($fpt))
          {
            array_push($fpt->sons, $pop);
            break;
          }
        }

        if(is_null($fpt))
        {
          array_push($pp, $pop);
        }

        $post_process = $pp;
      }
    }

    if(count($huerfanos) > 0)
    {
      $fh = new forum_struct(-1, "Foros huérfanos", -1, 0, '', 0);
      array_push($forum_tree, $fh);

      foreach($huerfanos as $h)
      {
        array_push($fh->sons, $h);
      }
    }

    return $forum_tree;
  }

  function find_father($forum, $id)
  {
    if($forum->id == $id)
    {
      return $forum;
    }

    foreach($forum->sons as $s)
    {
      $r = find_father($s, $id);
      if(!is_null($r))
      {
        return $r;
      }
    }

    return NULL;
  }
?>
