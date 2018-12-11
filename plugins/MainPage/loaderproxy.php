<?
switch($content)
{
  case "newResponse":
    if($GLOBALS['USER_ID'] < 1)
    {
      return;
    }

    if(!isset($_POST['id']) || !isset($_POST['response_content']))
    {
      return;
    }

    $query = "SELECT id_padre FROM forums WHERE id=".$_POST['id'];
    $row = $GLOBALS['BD']->select($query);
    $r = $row->fetch(PDO::FETCH_ASSOC);
    $id_padre = $r['id_padre'];

    $query = "SELECT rango FROM forums WHERE id=".$id_padre;
    $row = $GLOBALS['BD']->select($query);
    $r = $row->fetch(PDO::FETCH_ASSOC);

    $range = $GLOBALS['SESION']->getRangeLevel($r['rango']);

    if($range > $GLOBALS['RANGE'])
    {
      return;
    }

    $msg = str_replace("\n","<br/>",$_POST['response_content']);

    $query = "INSERT INTO forums_content (thread_id, content, user_id, fecha) VALUES (".$_POST['id'].", '".$msg."', ".$GLOBALS['USER_ID'].", '".date("d/m/y H:i")."')";
    $GLOBALS['BD']->insert($query);

    $query = "SELECT id FROM forums_content WHERE thread_id = ".$_POST['id']." ORDER BY id DESC";
    $row = $GLOBALS['BD']->select($query);
    $r = $row->fetch(PDO::FETCH_ASSOC);
    echo $r['id'];

    $query = "UPDATE forums set ult_mod = '".date("d/m/y H:i")."' WHERE id=".$id_padre. " OR id=".$_POST['id'];
    $GLOBALS['BD']->update($query);
    break;

  case "newThread":
    if($GLOBALS['USER_ID'] < 1)
    {
      return;
    }

    if(!isset($_POST['id']) || !isset($_POST['title']) || !isset($_POST['msg']))
    {
      return;
    }

    $query = "SELECT rango FROM forums WHERE id=".$_POST['id'];
    $row = $GLOBALS['BD']->select($query);
    $r = $row->fetch(PDO::FETCH_ASSOC);

    $range = $GLOBALS['SESION']->getRangeLevel($r['rango']);

    if($range > $GLOBALS['RANGE'])
    {
      return;
    }

    $query = "INSERT INTO forums (nombre, id_padre, tipo, rango, ult_mod) VALUES ('".$_POST['title']."', ".$_POST['id'].", 2, ".$r['rango'].", '".date("d/m/y H:i")."')";
    $GLOBALS['BD']->insert($query);

    $query = "SELECT id FROM forums WHERE id_padre=".$_POST['id']." ORDER BY id DESC";
    $row = $GLOBALS['BD']->select($query);
    $nT = $row->fetch(PDO::FETCH_ASSOC);

    $msg = str_replace("\n","<br/>",$_POST['msg']);
    $query = "INSERT INTO forums_content (thread_id, user_id, fecha, content) VALUES (".$nT['id'].", ".$GLOBALS['USER_ID'].", '".date("d/m/y H:i")."', '".$msg."')";
    $GLOBALS['BD']->insert($query);

    $query = "UPDATE forums set ult_mod = '".date("d/m/y H:i")."' WHERE id=".$_POST['id'];
    $GLOBALS['BD']->update($query);
    break;

  case "editThread":
    if($GLOBALS['USER_ID'] < 1)
    {
      return;
    }

    if(!isset($_POST['id']) || !isset($_POST['msg']))
    {
      return;
    }

    $query = "SELECT user_id FROM forums_content WHERE id=".$_POST['id'];
    $row = $GLOBALS['BD']->select($query);
    $t = $row->fetch(PDO::FETCH_ASSOC);

    if($GLOBALS['USER_ID'] != $t['user_id'])
    {
      return;
    }

    $msg = str_replace("\n","<br/>",$_POST['msg']);
    $query = "UPDATE forums_content SET content = '".$msg."' WHERE id=".$_POST['id'];
    $GLOBALS['BD']->update($query);
    break;

  case "querySearch":

    if(!isset($_POST['search_content'])) {
      return;
    }

    $query = "SELECT fc.id as 'id', u._name as 'username', u.avatar as 'avatar', fc.fecha as 'fecha', fc.content as 'content', f.rango as 'rango', f.id as 'post', f.nombre as 'nombre_post'
              FROM  forums_content fc
              LEFT JOIN forums f ON fc.thread_id = f.id
              LEFT JOIN _user u ON fc.user_id = u._id
              WHERE LOWER(REPLACE(fc.content, '\n', '')) LIKE LOWER('%" . $_POST['search_content'] . "%')
              AND f.rango <= " . $GLOBALS['RANGE'] . "
              ORDER BY fc.thread_id ASC, fc.fecha DESC";
    $result = $GLOBALS['BD']->select($query);

    if($result->rowCount() > 0) {
      $response = '';
      foreach($result as $row) {
        $response .= '
        <div id="' . $row['id'] . '" class="panel panel-default">
          <div class="panel-body">
            <div class="col-md-1 text-center">
              <div class="panel panel-default">
                <div class="panel-body">
                  <img src="' . $GLOBALS['AVATAR_GET']($row['avatar']) . '" class="avatar-fill avatar-small" alt="avatar"/>
                </div>
                <div class="panel-footer">
                  ' . $row['username'] . '
                </div>
              </div>
            </div>
            <div class="col-md-11">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <small>' . date("d/m/y H:i", strtotime($row['fecha'])) . '</small>
                    <span class="pull-right">
                      <a href="?p=view&post=' . $row['post'] . '&jp=' . $row['id'] . '" alt="Ir a post">&#x1F517;</a>&nbsp;
                    </span>
                  </div>
              </div>
              ' . highlight($row['content'], $_POST['search_content']) . '
            </div>
          </div>
        </div>';
      }
    } else {
      $response = '
      <div class="panel panel-default">
        <div class="panel-body">
            No se han encontrado resultados que coincida con:
            <div><code>' . $_POST['search_content'] . '</code></div>
        </div>
      </div>';
    }

//    $data = $result->fetchAll(PDO::FETCH_ASSOC);
//    $count = $result->rowCount();
//    $response = json_response(200, array(
//      'count' => $count,
//      'data' => $data
//    ));

    echo $response;
    break;
}

function highlight($haystack, $needle) {

  // return $haystack if there is no highlight color or strings given, nothing to do.
  if (strlen($haystack) < 1 || strlen($needle) < 1) {
    return $haystack;
  }
  preg_match_all("/" . $needle . "+/i", $haystack, $matches);
  if (is_array($matches[0]) && count($matches[0]) >= 1) {
    foreach ($matches[0] as $match) {
      $haystack = str_replace($match, '<span class="mark">' . $match . '</span>', $haystack);
    }
  }
  return $haystack;
}

function json_response($code = 200, $message = null) {

  // clear the old headers
  header_remove();

  // set the actual code
  http_response_code($code);

  // set the header to make sure cache is forced
  header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");

  // treat this as json
  header('Content-Type: application/json');

  $status = array(
    200 => '200 OK',
    201 => '201 Created',
    204 => '204 No Content',
    400 => '400 Bad Request',
    422 => '422 Unprocessable Entity',
    500 => '500 Internal Server Error'
  );

  // ok, validation error, or failure
  header('Status: ' . $status[$code]);

  // return the encoded json
  return json_encode(array(
    'error' => $code >= 300, // success or not?
    'message' => $message
  ));

}

?>
