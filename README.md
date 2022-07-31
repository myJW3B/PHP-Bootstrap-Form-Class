# PHP-Bootstrap-Form-Class
Build your HTML for all your forms fast and easily with a wrapper that builds it for you with only a few variables.

We can add floating labels, input groups or use the row / col combo to each element.

### Disclaimer
I have not used the input groups after some recent changes to the class brought about by the floating labels.

## Functions
95% will return `$this` to make them chain-able.
### __construct()
Default options for the new form() construction.
````php
$options = [
			'id'            => '',  // id of the form element
			'url'           => '/ajax', // the forms action value
			'method'        => 'post', // get or post
			'form_classes'  => 'mf-forms', // classes to be added to the form tag
			'input_classes' => 'form-control', // classes to be added to the input elements
			'row_classes'   => 'mb-3', // classes to be added to each container row
			'form_attr'     => [], // additional form attributes
			'ensure_row'    => true // ensure the rows are closed before each new element
		]
````
### `->set_opts($key, $value)`
You can override some options originally set by the constructor
Returns `$this` so it can be chained.
### `->get_opts($key)`
Returns the value of the option key, so cannot be chained.
### `->add_html($html, $close_row=false)`
Adds additional HTML within the form
### `->floating($additional_classes='')`
To start a floating label block
### `->new_row($row_class='')`
### `->set_name($input_name_and_id_value='')`
You can put `['key']` in the names value to work with radio and checkboxes.

## Element
Floating labels are automatically added after the input tag
````php
/**
	 *  @param (str) type = 'input', 'button', 'textarea', 'select', 'file', 'day', 'time':		
	 * 	@param (str) value of input
	 *  @param (ary) attr for the input
	 *  @param (ary) b4_element - input
	 *  @param (ary) after_element - input
	 * 
	 * return $this
	 */
````
## Example
````php
    $new_form = new form([
		'id' => 'register-form',
		'url' => '/ajax/register-form',
		'form_classes' => 'col-lg-6 mx-auto'
	]);
	$inc = $new_form
		->floating()
			->set_name('uUsername')
			->element('input', '', ['placeholder' => 'My Crazy Name', 'required' => 'required'])
		->end_floating()
		->floating()
			->set_name('uEmail')
			->element('input', '', ['type' => 'email', 'placeholder' => 'Real Email', 'required' => 'required'])
		->end_floating()
		->floating()
			->set_name('uPass')
			->element('input', '', ['type' => 'password', 'placeholder' => 'Password', 'required' => 'required'])
		->end_floating()
		->floating()
			->set_name('uPassCon')
			->element('input', '', ['type' => 'password', 'placeholder' => 'Confirm Password', 'required' => 'required'])
		->end_floating()
		->actions();
````

More examples to come soon