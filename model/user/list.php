<?php
class model_user_list
{
	private $db;
	
	function __construct()
	{
		$this->db = new mysql();
	}
	
	function user_list()
	{
		$query = $this->db->query("select * from user limit 10");
		while (($rs = $this->db->fetch_array($query))!=false)
		{
			$str .=$rs['USER_ID'].'--<br />';
		}
		return $str;
	}
	
	function __destruct()
	{
		$this->db->close();
	}
}
?>