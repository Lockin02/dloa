<?php
class model_rd_hrms_field extends model_base
{
	function __construct()
	{
		parent::__construct();
		$this->tbl_name = 'hrms_files_field';
		$this->pk='id';
	}
	/**
	 * ¸²¸Ç»ùÀàÉ¾³ýº¯Êý
	 * @param $id
	 */
	function Del($id)
	{
		if (($this->query("delete from hrms_files_field_content where field_id=".$id)))
		{
			return parent::Del($id);
		}else{
			return false;
		}
	}
}

?>