<?php
/**
 *
 * --------------------------------------------------------------------------
 * PHP Bootstrap Form Class (v3.0.0-public-RC-1): html_builders.php
 * Licensed under MIT (https://github.com/Yohn/PHP-Bootstrap-Form-Class/blob/main/LICENSE)
 * --------------------------------------------------------------------------
 */
// I want an easy way to build a form out. This is the start of a crazy class structure
/*
		6.9.2022
			Changed to using floating labels within Bootstrap 4 & 5
			- need to add ability to add classes to select dropdowns
*/

namespace JW3B\gui;

use JW3B\gui\HTML_Builders;

class Form {

	public $form = '';
	public $form_id = '';
	public $cur_id = null;
	public $cur_name = null;
	protected $ary_count = 0;
	private $row_open = false;
	public $html_builders;
	public $opts;
	public $ensure_row;
	private $is_floating = false;

	public function __construct($opts=[]){ //id, $url='/ajax.php', $method='post', $attr=array('class' => 'form-horizontal jquery-form')){
		$def_opts = [
			'id' => '',
			'url' => '/ajax.php',
			'method' => 'post',
			'form_classes' => 'mf-forms',
			'input_classes' => 'form-control',
			'row_classes' => 'mb-3',
			'form_attr' => [],
			'ensure_row' => true
		];
		$this->opts = [
			'id' => isset($opts['id']) ? $opts['id'] : $def_opts['id'],
			'url' => isset($opts['url']) ? $opts['url'] : $def_opts['id'],
			'action' => isset($opts['action']) ? $opts['action'] : $opts['url'],
			'method' => isset($opts['method']) ? $opts['method'] : $def_opts['method'],
			'form_classes' => isset($opts['form_classes']) ? $opts['form_classes'] : $def_opts['form_classes'],
			'input_classes' => isset($opts['input_classes']) ? $opts['input_classes'] : $def_opts['input_classes'],
			'row_classes' => isset($opts['row_classes']) ? $opts['row_classes'] : $def_opts['row_classes'],
			'form_attr' => [],
			'ensure_row' => isset($opts['ensure_row']) ? $opts['ensure_row'] : $def_opts['ensure_row']
		];
		$this->html_builders = new HTML_Builders;
		$this->form_id = $this->opts['id'];
		$this->ensure_row = $this->opts['ensure_row'];
		$add = $this->html_builders->sort_attr([
			'method' => $this->opts['method']]
			+['class' => $this->opts['form_classes']]
			+$this->opts['form_attr'],
			['role', 'action', 'id']);
		$this->form =
			$this->form_id != false ?
			'<form role="form" id="'.$this->form_id.'" action="'.$this->opts['url'].'"'.$add.'>'.PHP_EOL
			: '';
	}

	public function set_opts($key, $value){
		$this->opts[$key] = $value;
		return $this;
	}

	public function get_opts($key){
		return $this->opts[$key];
	}

	public function add_html($html, $close_row=false){
		if($close_row == true){
			if($this->row_open == true){
				$this->end_row();
			}
			$this->row_open = true;
		}
		$this->form .= '<!-- add_html -->'.$html;
		return $this;
	}

	public function new_row($row_class=''){
		if($this->row_open == true){
			$this->end_row();
		}
		$add_class = $row_class != '' ? ' '.$row_class : '';
		$this->row_open = true;
		$class = $add_class == '' ? '' : ' class="'.$add_class.'"';
		$this->form .= '<div'.$class.'><!-- new_row -->'.PHP_EOL;
		return $this;
	}

	public function end_row(){
		$this->form .= '</div><!-- end_row -->'.PHP_EOL;
		$this->row_open = false;
		return $this;
	}

	public function end_group(){
		$this->form .= '</div><!-- end_group -->'.PHP_EOL;
		$this->row_open = false;
		return $this;
	}

	public function set_name($name){
		$this->cur_name = $name;
		if(strpos($name, '[') > -1){
				$this->ary_count++;
			$this->cur_id = preg_replace('/\[.*?\]/is', '-'.$this->ary_count, $name);
		} else {
			$this->cur_id = $name;
		}
		return $this;
	}

	public function label($txt, $class=''){
		$find_class = $class == '' ? '' : $this->rollover_classes($class);
		$this->form .= '<label for="'.$this->cur_id.'"'.$find_class.'>'.$txt.'</label>'.PHP_EOL;
		return $this;
	}

	public function floating($class=''){
		$add = $class == '' ? '' : ' '.$class;
		$add .= isset($this->opts['row_classes']) ? ' '.$this->opts['row_classes'] : '';
		$this->form .= '<div class="floating-label'.$add.'">'.PHP_EOL;
		$this->row_open = true;
		$this->is_floating = true;
		return $this;
	}

	public function end_floating(){
		$this->form .= '</div><!-- end_floating -->'.PHP_EOL;
		$this->row_open = false;
		$this->is_floating = false;
		return $this;
	}

	public function input_group($class=''){
		$add = $class == '' ? '' : ' '.$class;
		$this->form .= '<div class="input-group'.$add.'">'.PHP_EOL;
		$this->row_open = true;
		return $this;
	}

	public function input_group_text($txt,$class=''){
		$add = $class == '' ? '' : ' '.$class;
		$this->form .= '<span class="input-group-text'.$add.'">'.$txt.'</span>'.PHP_EOL;
		return $this;
	}

	/**
	 *  @param (string) type = 'input', 'button', 'textarea', 'select', 'file', 'day', 'time':
	 * 	@param (string) value of input
	 *  @param (array) attr for the input
	 *  @param (string) b4_element - input
	 *  @param (string) after_element - input
	 *
	 * return $this
	 */
	public function element($type, $value='', $attr=[], $b4_element='', $after_element=''){
		if($this->ensure_row == true && $this->row_open == false) $this->new_row();
		//$find_class = $this->rollover_classes($cols);
		//if($cols > 0){
		//	$this->form .= '<div class="col-sm-'.$cols.'"><!-- element -->'.$b4_element.PHP_EOL;
		//}
		$this->form .= '<!-- element -->'.$b4_element.PHP_EOL;
		switch($type){
			case 'input':		$this->form .= $this->html_builders->create_input($this->cur_name, $this->cur_id, $value, $attr).PHP_EOL; break;
			case 'button': 		$this->form .= $this->html_builders->create_button($this->cur_name, $this->cur_id, $value, $attr).PHP_EOL; break;
			case 'textarea': 	$this->form .= $this->html_builders->create_textarea($this->cur_name, $this->cur_id, $value, $attr).PHP_EOL; break;
			case 'select': 		$this->form .= $this->html_builders->create_select($this->cur_name, $this->cur_id, $value, $attr).PHP_EOL; break;
			case 'file':			$this->form .= $this->html_builders->create_upload($this->cur_name, $this->cur_id, $value, $attr).PHP_EOL; break;
			case 'day':				$this->form .= $this->html_builders->create_day_picker($this->cur_name, $this->cur_id, $value, $attr).PHP_EOL; break;
			case 'time':			$this->form .= $this->html_builders->create_time_picker($this->cur_name, $this->cur_id, $value, $attr).PHP_EOL; break;
		}
		if(isset($attr['placeholder']) && $attr['placeholder'] != '' && $this->is_floating == true){
			$this->label($attr['placeholder']);
		}
		$this->form .= $after_element.'<!-- end_element -->'.PHP_EOL;
		return $this;
	}

	public function add_hidden($ary){
		if($this->row_open == true) $this->end_row();
		$this->form .= '<div class="clearfix">'.PHP_EOL;
		foreach($ary as $name => $val){
			$this->form .= '<input type="hidden" name="'.$name.'" id="'.$name.'" value="'.$val.'">'.PHP_EOL;
		}
		$this->form .= '</div>'.PHP_EOL;
		return $this;
	}

	public function actions($btn_txt='Submit', $btn_class='btn btn-outline-primary', $b4_btn='', $after_btn='', $btn_id='', $results_id='', $dont_close=false){
		if($this->ensure_row == true && $this->row_open == true) $this->end_row();
		if($this->ensure_row == true && $this->is_floating == true) $this->end_floating();
		$btn_id = $btn_id == '' ? $this->form_id.'-btn' : $btn_id;
		$results_id = $results_id == '' ? $this->form_id.'-results' : $results_id;
		if(is_array($btn_txt)){
			$div_classes = $btn_txt[1];
			$btn_txt = $btn_txt[0];
		} else {
			$div_classes = 'text-center clearfix';
		}
		// form submissions
		if(!isset($_SESSION['token']) || empty($_SESSION['token'])
			|| empty($_SESSION['token'][$this->form_id]) || !is_array($_SESSION['token'][$this->form_id])
			|| !isset($_SESSION['token'][$this->form_id]['token2'])){
			if(isset($_SESSION['token'])
				&& (!is_array($_SESSION['token'])
				|| (isset($_SESSION['token'][$this->form_id])
					&& !is_array($_SESSION['token'][$this->form_id])
					)
				)
			) unset($_SESSION['token']);
			$_SESSION['token'][$this->form_id] = [
				'token2' => bin2hex(random_bytes(32)),
				'time' => time()
			];
		}
		$this->form .= '<div id="'.$results_id.'"></div>
		<input type="hidden" name="form_id" value="'.$this->form_id.'" id="form-id-'.$this->form_id.'">
		<input type="hidden" name="token" value="'.$_SESSION['token'].'" id="'.$this->form_id.'-token">
		<div class="'.$div_classes.'">
			'.$b4_btn
			.$this->html_builders->create_button('', $this->form_id.'-btn',
				$btn_txt,
				['type' => 'submit', 'class' => $btn_class]
			).$after_btn.'
		</div>';
		return $dont_close == false ? $this->close() : $this;
	}


	/** Checked the token of submitted form..
	 * @param string $form_id = the id of the form you're checking
	 * @param string $post_token = the $_POST['token'] value
	 *
	 * @return boolean
	 */
	static function check_token($form_id='', $post_token='') : bool {
		$form_id = $form_id == '' ? $_POST['form_id'] : $form_id;
		$post_token = $post_token == '' ? $_POST['token'] : $post_token;
		if(isset($_SESSION['token'])
			&& isset($_SESSION['token'][$form_id])
			&& isset($_SESSION['token'][$form_id]['token2'])){
			if($_SESSION['token'][$form_id]['token2'] == $post_token){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function close($close_form=true){
		$completed = $this->form.'</form>'.PHP_EOL;
		return $close_form == true ? $this->form.'</form>'.PHP_EOL : $this->form;
	}

	private function rollover_classes($cols, $add=''){
		$classes = explode(' ', $cols);
		if(count($classes) > 0){
			if($classes[0] > 0){
				$classes[0] = 'col-sm-'.$classes[0];
			}
			return ' class="'.implode(' ', $classes).'"';
		} else {
			return $add == '' ? '' : ' class="'.$add.'"';
		}
	}
}
