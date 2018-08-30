<?php
 class model_manufacture_basic_produceconfiguration  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_manufacture_produceconfiguration";
		$this->tbl_classify = "oa_manufacture_classify";
		$this->tbl_process = "oa_manufacture_process";
//		$this->sql_map = "manufacture/basic/classifySql.php";
		parent::__construct ();
	}

 	/**
     * 树结构数据集
     * @param Int $parentId
     * @return Array
     */
    public function loadTree($parentId=null, $type = '') {

		if($type == 'SF'){
			$conditions = " AND `parentId` = ".$parentId;
		}else if($_POST['id']){
			$conditions = " AND `parentId` = '".$_POST['id']."'";
		}else{
			$conditions = " AND `parentId` = '0'";
		}

		$SQL = "SELECT id,produceName as text FROM $this->tbl_name WHERE `produceName` <> '' ".$conditions;
        $query = $this->_db->query($SQL);

        $treeData = array();
	    while (($rs = $this->_db->fetch_array($query)) != false) {
	        $datas = array();
	        $datas = $this->loadTree($rs['id'],'SF');
	        $state = 'open';
	        $rs['state'] = $state;
	        $rs['children'] = $datas;
	        $treeData[] = $rs;
	    }

        return $treeData;
    }

    public function get_configurationInfo($id){
    	$SQL = "SELECT b.*,(select produceName FROM $this->tbl_name as a WHERE a.id = b.parentId) as parentName FROM $this->tbl_name as b WHERE b.`id` = '$id' ";
    	return $this->_db->get_one($SQL);

    }

	public function get_classify($id  = ''){

		if(!empty($id)){
			$con = "`id` =" . $id ;
		} else {
			$con = " 1 AND 1 " ;
		}
		$SQL = "SELECT * FROM $this->tbl_classify WHERE " . $con ;
		$query = $this->_db->query($SQL);
		while (($rs = $this->_db->fetch_array($query)) != false) {
	        $data[] = $rs;
	    }

	    return $data;
	}

	public function get_process($id  = ''){

		if(!empty($id)){
			$con = "`id` =" . $id ;
		} else {
			$con = " 1 AND 1 " ;
		}
		$SQL = "SELECT * FROM $this->tbl_process WHERE " . $con ;
		$query = $this->_db->query($SQL);
		while (($rs = $this->_db->fetch_array($query)) != false) {
	        $data[] = $rs;
	    }

	    return $data;
	}
	public function get_parent($id  = '',$parentId = ''){

		if(!empty($id)){
			$con = "`id` = '" . $id ."'";
		} elseif(!empty($parentId)) {
			$con = "`id` <> '" . $id ."'";
		} else {
			$con = " 1 AND 1 " ;
		}
		$SQL = "SELECT * FROM $this->tbl_name WHERE " . $con ;
		$query = $this->_db->query($SQL);
		while (($rs = $this->_db->fetch_array($query)) != false) {
	        $data[] = $rs;
	    }

	    return $data;
	}

	public function update($data){

		$SQL = "UPDATE $this->tbl_name SET " .
			   "`produceName`='".iconv('utf-8','gbk', $data[produceName])."' ,".
			   "`productCode`='$data[productCode]' ,".
			   "`parentId`='$data[parentId]' ,".
			   "`processId`='$data[processId]' ,".
			   "`classifyId`='$data[classifyId]' ,".
			   "`templateId`='$data[templateId]' ,".
			   "`updateName`='". $_SESSION['USERNAME'] ."' ,".
			   "`updateId`='" .$_SESSION ['USER_ID']."' ,".
			   "`updateTime`='".date('Y-m-d')."' ,".
			   "`remark`='".iconv('utf-8','gbk', $data[remark])."'".
			   "WHERE `id` = '$data[id]'";

		return $this->_db->query($SQL);

	}
	function delete($id){
		if(!empty($id)){
			$SQL = "DELETE FROM $this->tbl_name WHERE `id` = '$id'";
			return $this->_db->query($SQL);
		}
	}
	public function add($data){

		$SQL = " INSERT INTO $this->tbl_name " .
				"(`produceName`," .
				"`productCode`," .
				"`parentId`," .
				"`processId`," .
				"`classifyId`," .
				"`templateId`," .
				"`remark`," .
				"`createName`," .
				"`createId`," .
				"`createTime`)".
				"VALUES " .
				"('".iconv('utf-8','gbk', $data[produceName])."', " .
					"'".$data[productCode]."'," .
					"'".$data[parentId]."'," .
					"'".$data[processId]."'," .
					"'".$data[classifyId]."'," .
					"'".$data[templateId]."'," .
					"'".iconv('utf-8','gbk', $data[remark])."'," .
					"'".$_SESSION['USERNAME']."'," .
					"'".$_SESSION ['USER_ID']."'," .
					"'".date('Y-m-d')."') ";

		return $this->_db->query($SQL);

	}

	function get_template($state,$id){
		if($state == 'parent'){
			$SQL = "SELECT * FROM oa_manufacture_template WHERE `classifyId` = '$id' ";
			$query = $this->_db->query($SQL);
			while (($rs = $this->_db->fetch_array($query)) != false) {
		        $data[] = $rs;
		    }

		    return $data;
		}elseif($state == 'son'){
			$SQL = "SELECT * FROM oa_manufacture_template WHERE `id` = '$id' ";
    		return $this->_db->get_one($SQL);
		}
	}
}
?>