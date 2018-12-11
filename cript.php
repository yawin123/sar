<?
function cript ($contra,$semilla)
{
  $algo=$contra;
  for($i=0;$i<strlen($semilla);$i++)
  {
    switch($semilla[$i])
    {
      case 0:
        $algo=hash('md2',$algo);
        break;
      case 1:
        $algo=hash('md5',$algo);
        break;
      case 2:
        $algo=hash('sha256',$algo);
        break;
      case 3:
        $algo=hash('sha1',$algo);
        break;
      case 4:
        $algo=hash('crc32',$algo);
        break;
      case 5:
        $algo=hash('adler32',$algo);
        break;
      case 6:
        $algo=hash('sha512',$algo);
        break;
      case 7:
        $algo=hash('snefru',$algo);
        break;
      case 8:
        $algo=hash('sha384',$algo);
        break;
      case 9:
        $algo=hash('md4',$algo);
        break;
      default:
        $algo=hash('whirlpool',$algo);
        break;
    }
  }
  return $algo;
}
?>
