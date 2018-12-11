  <div id="MainContent">
    <?foreach($GLOBALS['INDEX_CONTENT']->content as $c)
    {
      if($c->related_div!='')
      {
        if($c->id == 'header')
        {
          echo $c->related_div;
        }
        else if($c->id == 'script')
        {?>
          <div class="hidden">
            <? echo $c->related_div;?>
          </div>
        <?}
        else
        {?>
          <div id="<? echo $c->id;?>" class="container-fluid">
            <? echo $c->related_div;?>
          </div>
        <?}
      }
    }?>
  </div>
