<?php
class Page
{
    var $firstRow     = 0 ;   //
    var $listRows     = 10;   // 
    var $parameter    = '';   // 
    var $totalPages   = 0 ;   // 
    var $totalRows    = 0 ;   // 
    var $nowPage      = 0 ;   // 
    var $showPageJump = True; // 
    var $coolPages    = 0 ;   // 
    var $rollPage     = 10 ;  //
	var $url		  = NULL;

    function Page()
    {
        //$a_url = $_REQUEST;
		$a_url = $_GET;
		foreach($a_url as $key=>$v)
		{
			if(!empty($v)&&!is_array($v))
				$a_url[$key]=urlencode($v);
		}
    
        if (isset($a_url['totalRows']))
            $this->totalRows = $a_url['totalRows'];

        if (isset($a_url['firstRow']))
            $this->firstRow = $a_url['firstRow'];
        else
            $this->firstRow = 0;

        unset($a_url['PHPSESSID']);
        unset($a_url['firstRow']);
        unset($a_url['totalRows']);

        $this->convert($a_url);

        if ($a_url)
            $this->parameter =  implode('&',$a_url);
        if ($this->parameter)
            $this->parameter = '&'.$this->parameter;
		if ($this->totalRows < $this->firstRow+1 && $this->firstRow != 0)
            $this->firstRow = $this->totalRows - 1;
    }

    function __set($name, $value)
    {
        $this->$name = $value;
    }

    function __get($name)
    {
        return $this->$name;
    }

    function convert(& $array)
    {
        if (is_array($array))
        {
            return @array_walk($array, create_function('&$value, $key', '$value = $key ."=". $value;'));
        }
    }

    /*--------------------------------------------------------------------------
    -----------------------------------------------------------------------------*/
   function prompt()
   {
        if(0 == $this->totalRows)
            return;

        $this->totalPages = ceil($this->totalRows/$this->listRows); 
        $this->coolPages  = ceil($this->totalPages/$this->rollPage);

        if ( $this->firstRow >= $this->totalRows )
        { 
            $this->nowPage  = $this->totalPages;
            $this->firstRow = ($this->totalPages-1) * $this->listRows;
        }
        else
            $this->nowPage = floor($this->firstRow/$this->listRows + 1); 
        $nowCoolPage = ceil($this->nowPage/$this->rollPage);
        
		// << < > >>
        if($this->nowPage>1)
        {
            $preRow   =  ($this->nowPage-2) * $this->listRows;
            $prePage  = "<a class='prePage' href='".$this->url."?firstRow=$preRow&totalRows=$this->totalRows$this->parameter'>&nbsp;&nbsp;&nbsp;</a>";
        }
		if($this->nowPage>5)
			$theFirst = " <a href='".$this->url."?firstRow=0&totalRows=$this->totalRows$this->parameter'>1...</a>";
        
   		if($this->nowPage<$this->totalPages)
		{
			$nextRow   = ($this->nowPage) * $this->listRows;
			$theEndRow = ($this->totalPages-1) * $this->listRows;
			$nextPage  = " <a class='nextPage' href='".$this->url."?firstRow=$nextRow&totalRows=$this->totalRows$this->parameter'>下一页</a>";
			$theEnd    = " <a href='".$this->url."?firstRow=$theEndRow&totalRows=$this->totalRows$this->parameter'>..$this->totalPages</a>";
        }

        //list pages
        $linkPage = '';
		if($this->nowPage<6)
			$kpage=10;
		elseif($this->nowPage+5<$this->totalPages)
			$kpage=$this->nowPage+4;
		else
			$kpage=$this->nowPage+($this->totalPages-$this->nowPage);
			
		if($this->nowPage<=5)
			$knpage=1;
		else
			$knpage=$this->nowPage-4;
			
        for($page=$knpage; $page<=$kpage; $page++)
        {
            $rows = ($page-1) * $this->listRows;
            if($page != $this->nowPage)
            {
                if($page <= $this->totalPages)
                    $linkPage .= " <a href='".$this->url."?firstRow=$rows&totalRows=$this->totalRows$this->parameter'>".$page."</a>";
                else
                    break;
            }
            else
            {
                if($this->totalPages != 1)
                    $linkPage .= " <b>".$page."</b>";
            }
        }
		if($this->totalPages<=5)
        	$pageStr =$theFirst.' '.$prePage.' '.$linkPage.' '.$nextPage;
		else
			$pageStr =$theFirst.' '.$prePage.' '.$linkPage.' '.$theEnd.' '.$nextPage;
        return $pageStr;
    }
}
?>