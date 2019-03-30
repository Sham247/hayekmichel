<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 22 Dec, 2014
* Modified date : 22 Dec, 2014
* Description 	: Page contains report module details
*/ 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Getreports_model extends CI_Model
{
	public function __Construct()
	{
		parent::__Construct();
	}
	/* Method used to get report category list - created by Karthik K on 22 Dec, 2014 */
	public function GetReportsCategoryList()
	{
		$Query = "SELECT a.id as CategoryId,a.category_name AS CategoryName,
				  b.id as SubCategoryId,b.sub_category_name AS SubCategoryName FROM 
				  ".GetLangLabel('Tbl_ReportsCategory')." a 
				 LEFT JOIN ".GetLangLabel('Tbl_ReportsSubCategory')." b ON (b.category_id=a.id) 
				 WHERE a.status=1 AND b.status=1";
		$Details 	= $this->db->query($Query);
		$Result		= $Details->result();
		if(isset($Result) && count($Result)>0)
		{
			return $Result;
		}
	}
}
?>