<?
  /*La clase Content representa al contenido que se añade al menú. Éste puede ser un fragmento
    de la página principal, un enlace, un trigger que ejecute una función de javascript, o
    varias cosas simultáneamente.*/
    class Content
    {
      public $id, $title, $href, $onclick, $related_div;

      public function __construct($id, $title="", $href="", $onclick="", $related_div="")
      {
        $this->id = $id;
        $this->title = $title;
        $this->href = $href;
        $this->onclick = $onclick;
        $this->related_div = $related_div;
      }

      public function __toString()
      {
  			return $this->id.", ".$this->title.", ".$this->href.", ".$this->onclick;//.", ".$this->related_div;
      }
    }

  /*La clase menú almacena el contenido de una página y su menú en forma de nodos independientes.*/
    class Menu
    {
      protected $id;
      public $nav_logo = '';
      public $pages = array();
      public $content = array();

      public function __construct($id = '', $logo = '')
      {
        $this->id = $id;
        $this->nav_logo = $logo;
      }

      public function addContent($id, $title="", $href="", $onclick="", $related_div="")
      {
        array_push($this->content, new Content($id, $title, $href, $onclick, $related_div));
      }

      public function addPage($key, $path, $nav="")
      {
        $this->pages[$key]=$path;
        if($nav != "")
        {
          $this->addContent(
            "",
            $nav,
            "",
            "location.href ='?p=".$key."'",
            '');
        }
      }

      public function draw()
      {?>
        <nav id="navbar<? echo $this->id;?>" class="navbar navbar-default navbar-fixed-top">
           <div style="width:100%;" class="container">
             <div class="navbar-header">
               <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#<? echo $this->id;?>_navbar">
                 <span class="icon-bar"></span>
                 <span class="icon-bar"></span>
                 <span class="icon-bar"></span>
               </button>
               <a class="navbar-brand" href="./"><? echo $this->nav_logo;?></a>
             </div>
             <div class="collapse navbar-collapse" id="<? echo $this->id;?>_navbar">
               <ul class="nav navbar-nav navbar-right">
                 <?foreach($this->content as $c)
                 {
                   $href="";
                   if($c->href!='' || $c->onclick!='')
                   {
                     if($c->href!=''){$href='href="./'.$c->id.'"';}?>
                     <li><a <? echo $href;?> OnClick="<?echo $c->onclick;?>"><? echo strtoupper($c->title);?></a></li>
                   <?}
                 }?>
               </ul>
             </div>
           </div>
        </nav>
      <?}
      public function __toString()
      {
  			$ret = "Id: ".htmlentities($this->id)."<br/>";
        $ret = $ret.'Content: <ul>';
        foreach($this->content as $g)
        {
          $ret = $ret.'<li>'.htmlentities((string)$g).'</li>';
        }
        $ret = $ret.'</ul>';

        $ret = $ret.'Pages: <ul>';
        foreach($this->pages as $k => $t)
        {
          $ret = $ret.'<li>'.htmlentities($k).' => '.htmlentities($t).'</li>';
        }
        $ret = $ret.'</ul>';

        return $ret;
      }
    }
?>
