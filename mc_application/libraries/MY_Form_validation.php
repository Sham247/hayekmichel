<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class MY_Form_validation extends CI_Form_validation 
{
	public $CI;
	function __construct($config = array())
	{
	    // Merged super-global $_FILES to $_POST to allow for better file validation inside of Form_validation library
	    $_POST = (isset($_FILES) && is_array($_FILES) && count($_FILES) > 0) ? array_merge($_POST,$_FILES) : $_POST;

	    parent::__construct($config);

	}
	public function run($module='', $group='')
	{
	    (is_object($module)) AND $this->CI =& $module;
	    return parent::run($group);

	}
}?>