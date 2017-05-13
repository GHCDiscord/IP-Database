<?php
/**
*@author  				The-Di-Lab
*@email   				thedilab@gmail.com
*@website 				www.the-di-lab.com
*@version               1.0
**/
class Paginator {		
		public $itemsPerPage;
		public $range;
		public $currentPage;
		public $total;
		public $textNav;
		public $maxpages;
		private $_navigation;		
		private $_link;
		private $_pageNumHtml;
		private $_itemHtml;
        /**
         * Constructor
         */
        public function __construct()
        {
        	//set default values
        	$this->itemsPerPage = 100;
			$this->range        = 4;
			$this->currentPage  = 1;		
			$this->total		= 0;
			$this->maxpages = 0;
			$this->textNav 		= true;
			$this->itemSelect   = array(50,100,500,'All');			
			//private values
			$this->_navigation  = array(
					'next'=>'-->',
					'pre' =>'<--',
					'ipp' =>'Item per page'
			);			
        	$this->_link 		 = filter_var($_SERVER['PHP_SELF'], FILTER_SANITIZE_STRING);
        	$this->_pageNumHtml  = '';
        	$this->_itemHtml 	 = '';
        }
        
        /**
         * paginate main function
         * 
         * @author              The-Di-Lab <thedilab@gmail.com>
         * @access              public
         * @return              type
         */
		public function paginate()
		{
			//get current page
			if(isset($_GET['current'])){
				$this->currentPage  = $_GET['current'];		
			}			
			//get item per page
			if(isset($_GET['item'])){
				$this->itemsPerPage = $_GET['item'];
			}			
			//get page numbers
			$this->_pageNumHtml = $this->_getPageNumbers();			
			//get item per page select box
			$this->_itemHtml	= $this->_getItemSelect();	
		}
				
        /**
         * return pagination numbers in a format of UL list
         * 
         * @author              The-Di-Lab <thedilab@gmail.com>
         * @access              public
         * @param               type $parameter
         * @return              string
         */
    public function pageNumbers()
        {
        	if(empty($this->_pageNumHtml)){
        		exit('Please call function paginate() first.');
        	}
        	return $this->_pageNumHtml;
        }
        
        /**
         * return jump menu in a format of select box
         *
         * @author              The-Di-Lab <thedilab@gmail.com>
         * @access              public
         * @return              string
         */
        public function itemsPerPage()
        {          
        	if(empty($this->_itemHtml)){
        		exit('Please call function paginate() first.');
        	}
        	return $this->_itemHtml;	
        } 
        
       	/**
         * return page numbers html formats
         *
         * @author              The-Di-Lab <thedilab@gmail.com>
         * @access              public
         * @return              string
         */
        private function  _getPageNumbers()
        {
        	$html  = '<center>';
        	//previous link button
			if($_GET['current'] >1){
				
				$html .= '<a href="'.$this->_link .'?current='.($this->currentPage-1).'&item='.($this->itemsPerPage).'"';
				$html .= '>'.$this->_navigation['pre'].'</a>';
			}        	
			$this->maxpages = ceil($this->total / $this->itemsPerPage);
        	//do ranged pagination only when total pages is greater than the range
        	if($this->maxpages > $this->range){				
				$start = ($this->currentPage <= $this->range)?1:($this->currentPage - $this->range);
				$end   = ($this->total - $this->currentPage >= $this->range)?($this->currentPage+$this->range): $this->total;
				
				echo $this->maxpages;
        	}else{
        		$start = 1;
				$end   = $this->maxpages;
				
        	}    
        	//loop through page numbers
        	for($i = $start; $i <= $end; $i++){
					$html .= '<a href="'.$this->_link .'?current='.$i.'&item='.($this->itemsPerPage).'"'; 
					if($i==$this->currentPage){
$x = '['; $y = ']';
} else {
$x = ' '; $y = ' ';
}
					$html .= '>'.$x.$i.$y.'</a>';
			}        	
        	//next link button
        	if($_GET['current']<$this->maxpages){
				$html .= '<a href="'.$this->_link .'?current='.($this->currentPage+1).'&item='.($this->itemsPerPage).'"';
				$html .= '>'.$this->_navigation['next'].'</a>';
				
			}
        	$html .= '</center>';
        	return $html;
        }
		
        /**
         * return item select box
         *
         * @author              The-Di-Lab <thedilab@gmail.com>
         * @access              public
         * @return              string
         */
        private function  _getItemSelect()
        {
        	$items = '';
               
   			$ippArray = $this->itemSelect;   	
              $ippArray[3] = $this->total;
               
   			foreach($ippArray as $ippOpt){   
   	if($ippOpt == $this->total){
   	$items .= ($ippOpt == $this->itemsPerPage) ? "<option selected value=\"$ippOpt\">All</option>\n":"<option value=\"$ippOpt\">All</option>\n";
   }else{
		    	$items .= ($ippOpt == $this->itemsPerPage) ? "<option selected value=\"$ippOpt\">$ippOpt</option>\n":"<option value=\"$ippOpt\">$ippOpt</option>\n";
		}
   			}   			
	    	return "<span class=\"paginate\">".$this->_navigation['ipp']."</span>
	    	<select class=\"paginate\" onchange=\"window.location='$this->_link?current=1&item='+this[this.selectedIndex].value;return false\">$items</select>\n";   	
        }
}