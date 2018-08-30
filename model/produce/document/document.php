<?php
/*
 * Created on 2010-7-17
 * 生产文档Model
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_produce_document_document extends model_treeNode {
	public $db;

	function __construct() {
		$this->tbl_name = "oa_uploadfile_manage";
		$this->sql_map = "produce/document/documentSql.php";
		parent::__construct ();
	}

	/**
	 * 更新业务对象及附件关联关系
	 * @param $fileuploadIds
	 * @param $obj
	 * @param $guideArr
	 */
	function updateObjWithFile($fileuploadIds,$obj,$guideArr = array()) {
		if(!empty($obj)){
			if(!empty($guideArr)){// ID2195 2016-12-07
				$pass = true;$setStr = "";
				foreach ($obj as $k => $v){
					$setStr .= "{$k}='{$v}',";
				}
				$setStr = rtrim($setStr,',');
				foreach($guideArr as $k => $v){
					$sub_setStr = "";
					$fileuploadIdstr = implode(",",$v['fileIds']);
					$sub_setStr .= ",styleThree='{$v['styleThree']}',styleTwo='{$v['styleTwo']}',isTemp=0";
					$sql = "update " . $this->tbl_name . " set ".$setStr.$sub_setStr." where id in(" . $fileuploadIdstr . ")";
					if(!$this->_db->query($sql)){
						$pass = false;
					}
				}
				return $pass;
			}else if(!empty($fileuploadIds)){
				$fileuploadIdstr = implode(",",$fileuploadIds);
				$sql = "update " . $this->tbl_name . " set ";
				foreach ($obj as $k => $v){
					$sql .= "{$k}='{$v}',";
				}
				$sql = substr($sql,0,strlen($sql)-1);
				$sql .= " where id in(" . $fileuploadIdstr . ")";
				return $this->_db->query($sql);
			}
		}else{
			return false;
		}
	}
}