<?php

if (!defined("CLASS_LIB")) {
	echo "hacker attack";
	exit;
}

class Page {
	private $total;					// total page
	private $total_list;			// each page show line data
	private $total_page;			// total page
	private $method = 'default';	// get data method: post or get, default is get
	private $offset;
	private $url;
	private $current_page = 1;
	private $each_page_num;
	private $page_name;

	/*
	 *	$param = array('total'	=>'total page',
	 *				   'each'	=>'10',
	 *				   'url'	=>'localhost/index.php',
	 *				   'name'	=>'page=1');
	**/
	public function __construct($param = array()) {
		$this->total = empty($param['total']) ? 1 : $param['total'];
		$this->each_page_num = empty($param['each']) ? 10 : $param['each'];
		$this->url = empty($param['url']) ? '' : $param['url'];
		$this->page_name = empty($param['name']) ? 'page' : $param['name'];

		$this->total_page = ceil($this->total / $this->each_page_num);	

		// 处理page_name 数据
		if (isset($_GET[$this->page_name]))
			$page = intval($_GET[$this->page_name]);
		else
			$page = 1;
		if ($page < 1)
			$this->current_page = 1;
		else if ($page > $this->total_page)
			$this->current_page = $this->total_page;
		else
			$this->current_page = $page;
		
	}

	/*
	 *	@param 	 string		$db    which database
	 *  @return  string 	$sql   sql query string
	 *
	 **/
	public function sql_query($db) {
		$start = ($this->current_page - 1) * $this->each_page_num;
		$end = ($this->total - $start) >= $this->each_page_num ? 10 : $this->total - $start;
		$sql = "SELECT * FROM $db LIMIT $start, $end";

		return $sql;
	}

	public function show() {
		
		$html = '';
		$html .= '<div>';
		$html .= '总计' . $this->total . '个记录，分为' . $this->total_page . '页，当前第' .
					$this->current_page . '页，每页' . $this->each_page_num . ' ';
		$html .= $this->first_page(); 
		$html .= $this->prev_page();
		$html .= $this->next_page();
		$html .= $this->last_page();
		$html .= $this->go_to_which_page();

		$html .= '</div>';

		echo $html;
	}

	private function first_page() {
		return '<a href="' . $this->url . '?' . $this->page_name . '=1">第一页</a> ' . PHP_EOL;
	}

	private function prev_page() {
		$page = $this->current_page > 1 ? $this->current_page-1 : 1;
		return '<a href="' . $this->url . '?' . $this->page_name . '=' . $page . '">上一页</a> ' . PHP_EOL;
	}

	private function next_page() {
		$page = $this->current_page < $this->total_page ? $this->current_page+1 : $this->total_page;
		return '<a href="' . $this->url . '?' . $this->page_name . '=' . $page . '">下一页</a> ' . PHP_EOL;
	}

	private function last_page() {
		return '<a href="' . $this->url . '?' . $this->page_name . '=' . $this->total_page . '">最后一页</a> ' . PHP_EOL;
	}

	private function go_to_which_page() {
		$html = '<input type="text" name="' . $this->page_name . '" id="' . $this->page_name . 'Value" style="width:40px;">' . PHP_EOL;
		$html .= '<input type="button" id="' . $this->page_name . '" value="Go">' . PHP_EOL;
		$html .= '<script>' . PHP_EOL;
		$html .= 'var page = document.getElementById("' . $this->page_name . '");' . PHP_EOL;
		$html .= 'var pageValue = document.getElementById("' . $this->page_name . 'Value");' . PHP_EOL;
		$html .= 'page.onclick = function() {' . PHP_EOL;
		$html .= '	if (pageValue.value.trim().length == 0)' . PHP_EOL;
		$html .= '		return false;' . PHP_EOL;
		$html .= '	else{' . PHP_EOL;
		$html .= '		location.href="?' . $this->page_name . '=" + pageValue.value;' . PHP_EOL;
		//$html .= '		alert(1);' . PHP_EOL;
		$html .= '	}' . PHP_EOL;
		$html .= '}' . PHP_EOL;
		$html .= '</script>' . PHP_EOL;

		return $html;
	}
}

?>
