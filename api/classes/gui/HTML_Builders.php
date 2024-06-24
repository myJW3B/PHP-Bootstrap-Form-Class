<?php
/**
 *
 * --------------------------------------------------------------------------
 * PHP Bootstrap Form Class (v3.0.0-public-RC-1): html_builders.php
 * Licensed under MIT (https://github.com/Yohn/PHP-Bootstrap-Form-Class/blob/main/LICENSE)
 * --------------------------------------------------------------------------
 */
namespace JW3B\gui;

class HTML_Builders {
	public function sort_attr($ary_attr, $not_allows_attr=[]){
		$str = ''; $found = [];
		if(is_array($ary_attr)){
			foreach($ary_attr as $k => $v){
				if($k != '' && !in_array($k, $not_allows_attr) && !in_array($k, $found)){
					$str .= ' '.$k.'="'.$v.'"';
				}
			}
		}
		return $str;
	}

	public function set_class($classes, $cur_ary){
		if(isset($cur_ary['class'])){
			$current = explode(' ', $cur_ary['class']);
			$new = explode(' ', $classes);
			$merged = array_merge($current, $new);
			$found = [];
			foreach($merged as $cls){
				if(!in_array($cls, $found)){
					$found[] = $cls;
				}
			}
			unset($cur_ary['class']);
			return ['class' => implode(' ', $found)]+$cur_ary;
		} else if($classes == ''){
			return $cur_ary;
		} else {
			return ['class' => $classes]+$cur_ary;
		}
	}

	public function create_select($name, $id, $value, $attr){
		$ret = '<select name="'.$name.'" id="'.$id.'" class="form-control">';
		if(isset($attr['do'])){
			if($attr['do'] == 'timezone'){
				$attr['options'] = ['' => '<!-- Select -->', 'America/New_York' => 'America/New_York', 'America/Chicago' => 'America/Chicago', 'America/Denver' => 'America/Denver', 'America/Phoenix' => 'America/Phoenix', 'America/Los_Angeles' => 'America/Los_Angeles'];
			} else if($attr['do'] == 'states'){
				// put states array here..
				$attr['options'] = ['AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas', 'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware', 'DC' => 'District Of Columbia', 'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho', 'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas', 'KY' => 'Kentucky', 'LA" selected="selected' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland', 'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi', 'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada', 'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York', 'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma', 'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina', 'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah', 'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia', 'WI' => 'Wisconsin', 'WY' => 'Wyoming'];
			}
		}
		foreach($attr['options'] as $v => $txt){
			$add = $v == $value ? ' selected="selected"' : '';
			$ret .= '<option value="'.str_replace('"', '&quot;', $v).'"'.$add.'>'.$txt.'</option>';
		}
		return $ret.'</select>';
	}

	public function create_input($name, $id, $value, $attr){
		if(!isset($attr['type'])) $attr['type'] = 'text';
		if(!in_array($attr['type'], ['checkbox', 'radio'])){
			$organize_class = $this->set_class('form-control', $attr);
		} else {
			$organize_class = $attr;
		}
		$add = $this->sort_attr(['id' => $id, 'name' => $name, 'value' => str_replace('"', '&quot;', $value)]+$organize_class);

		return '<input'.$add.'>';
	}

	public function create_button($name, $id, $value, $attr){
		if(!isset($attr['type'])) $attr['type'] = 'button';
		$organize_class = $this->set_class('btn', $attr);
		$ary = [];
		if($id != '') $ary['id'] = $id;
		if($name != '') $ary['name'] = $name;
		$add = $this->sort_attr($ary+$organize_class+$attr);
		return '<button'.$add.'>'.$value.'</button><!-- '.print_r($attr, 1).' -->';
	}

	public function create_textarea($name, $id, $value, $attr){
		$organize_class = $this->set_class('form-control', $attr);
		$add = $this->sort_attr(['id' => $id, 'name' => $name]+$organize_class);
		return '<textarea'.$add.'>'.$value.'</textarea>';
	}

	/**
	 * create_upload function
	 * same as the date picker, use elements already provided.
	 * Below worked for booystrap 3
	 * @param [type] $name
	 * @param [type] $id
	 * @param [type] $value
	 * @param [type] $attr
	 * @return string
	 */
	public function create_upload($name, $id, $value, $attr){
		$class = $value == '' ? 'new' : 'exists';
		$img = $value == '' ? '' : '<img src="'.$value.'" alt="Preview" class="img-responsive">';
		$img_style = isset($attr['img-styles']) ? $attr['img-styles'] : 'height:150px; width:95%;';
		$multiple = isset($attr['multiple']) ? ' multiple' : '';
		if(isset($attr['no-img'])){
			$hide = '';
			$img = '';
			return '<div class="fileinput fileinput-'.$class.' input-group" data-provides="fileinput">
				<div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
				<span class="input-group-addon btn btn-default btn-file">
					<span class="fileinput-new">Select file</span>
					<span class="fileinput-exists">Change</span>
					<input type="file" name="'.$name.'" id="'.$id.'"'.$multiple.'>
					<input type="hidden" name="cur-'.$name.'" id="cur-'.$id.'" value="'.$value.'">
				</span>
				<a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
			</div>';
		} else {
			$hide = ' hide';
			$img = '<div class="fileinput-preview thumbnail" data-trigger="fileinput" style="'.$img_style.'">'.$img.'</div>';
			return '<div class="fileinput fileinput-'.$class.'" data-provides="fileinput" style="width:98%;">
				'.$img.'
				<input class="form-control hide" id="'.$id.'-hidden-filenames" type="text" readonly>
				<div>
					<span class="btn btn-default btn-file">
						<span class="fileinput-new">Select File</span>
						<span class="fileinput-exists">Change</span>
						<input type="file" name="'.$name.'" id="'.$id.'"'.$multiple.'>
					</span>
					<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
					<input type="hidden" name="cur-'.$name.'" id="cur-'.$id.'" value="'.$value.'">
				</div>
			</div>';
		}
	}

	/**
	 * create_tags function
	 *
	 * @param string $name
	 * @param string $id
	 * @param string $checks
	 * @param array $attr
	 * @return string
	 */
	public function create_tags($name, $id, $checks, $attr){
		return '';
	}

	//https://codepen.io/yusufdogandev/pen/OJBNmPP
	// https://github.com/mymth/vanillajs-datepicker
	/**
	 * create_day_picker function
	 * dont use this, use the functions already available to make it work
	 * 		->input_group()
	 *		->input_group_text($icons->icon('people-circle'))
	 *		->floating()->set_name('mf-rname')
	 *			->element('input', '',  [
	 *				'required' => 'required',
	 *				'placeholder' => 'Display Name',
	 *				'class' => 'mf-valid',
	 *				'minlength' => '3',
	 *				'maxlength' => '30',
	 *				'data-error-msg' => 'Display name has to be at between 3-30 characters long'
	 *			])->end_floating()
	 *	->end_group()
	 * @param string $name
	 * @param string $id
	 * @param string $checks
	 * @param array $attr
	 * @return string
	 */
	public function create_day_picker($name, $id, $checks, $attr){
		// need to run checks to see if they need a checkbox checked or not.
		// glyphicon glyphicon-remove-circle
		/*
		->input_group()
				->input_group_text($icons->icon('people-circle'))
				->floating()->set_name('mf-rname')
					->element('input', '',  [
						'required' => 'required',
						'placeholder' => 'Display Name',
						'class' => 'mf-valid',
						'minlength' => '3',
						'maxlength' => '30',
						'data-error-msg' => 'Display name has to be at between 3-30 characters long'
					])->end_floating()
			->end_group()
		*/
		return '<div class="input-group date" id="datepicker">
			<span class="input-group-text">

			</span>
			<input type="text" class="form-control" id="date" placeholder="Date Of Birth" />
			<span class="input-group-append">
				<span class="input-group-text bg-light d-block">
					<i class="fa fa-calendar"></i>
				</span>
			</span>
		</div>';
	}

	// https://codepen.io/yusufdogandev/pen/OJBNmPP
	//	https://github.com/ovelilla/timepicker-responsive-vanillajs
	public function create_time_picker($name, $id, $checks, $attr){
		// find the one i had before
		return '<div class="input-group clockpicker" data-placement="bottom" data-align="bottom" data-autoclose="true">
			<input type="text" class="form-control" placeholder="Time Of Birth" />
			<span class="input-group-addon">
				<i class="bi bi-clock"></i>
			</span>
		</div>';
	}
}
