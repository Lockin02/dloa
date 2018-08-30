<?php

/**
 * @author Show
 * @Date 2012年6月14日 星期四 20:39:00
 * @version 1.0
 * @description:人力预算项目(oa_esm_baseinfo_person) Model层
 */
class model_engineering_baseinfo_eperson extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_baseinfo_person";
		$this->sql_map = "engineering/baseinfo/epersonSql.php";
		parent :: __construct();
	}

    /**
     * 验证是否存在根节点，不存在则新增
     */
    function checkParent_d(){
//    	$this->searchArr['id'] = -1;
    	$rs = $this->find(array('id'=> -1),null,'id');
		if(is_array($rs)){
			return true;
		}else{
			$this->create(array('id' => -1,'personLevel' => '预算目录'));
			return false;
		}
    }

    /**
     * 重写add_d
     */
    function add_d($object){
		try {
			$this->start_d();
			//设置父节点叶子属性
			if ($object['parentId'] != PARENT_ID) {
				$parent = array (
					"id" => $object['parentId'],
					"isLeaf" => 0
				);
				parent :: edit_d($parent);
			}
			//插入数据
			$newId = parent :: add_d($object);
			$this->commit_d();
			return $newId;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
    }
    
    /**
     * 获取到等级Id
     * @param unknown $obj
     */
    function getPersonLevelId_d($obj){
    	$sql = "select id from oa_esm_baseinfo_person where personLevel = '$obj'";
    	return $this->_db->getArray($sql);
    }
}
?>