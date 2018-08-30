<?php

/**
 * @author Show
 * @Date 2012��6��14�� ������ 20:39:00
 * @version 1.0
 * @description:����Ԥ����Ŀ(oa_esm_baseinfo_person) Model��
 */
class model_engineering_baseinfo_eperson extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_baseinfo_person";
		$this->sql_map = "engineering/baseinfo/epersonSql.php";
		parent :: __construct();
	}

    /**
     * ��֤�Ƿ���ڸ��ڵ㣬������������
     */
    function checkParent_d(){
//    	$this->searchArr['id'] = -1;
    	$rs = $this->find(array('id'=> -1),null,'id');
		if(is_array($rs)){
			return true;
		}else{
			$this->create(array('id' => -1,'personLevel' => 'Ԥ��Ŀ¼'));
			return false;
		}
    }

    /**
     * ��дadd_d
     */
    function add_d($object){
		try {
			$this->start_d();
			//���ø��ڵ�Ҷ������
			if ($object['parentId'] != PARENT_ID) {
				$parent = array (
					"id" => $object['parentId'],
					"isLeaf" => 0
				);
				parent :: edit_d($parent);
			}
			//��������
			$newId = parent :: add_d($object);
			$this->commit_d();
			return $newId;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
    }
    
    /**
     * ��ȡ���ȼ�Id
     * @param unknown $obj
     */
    function getPersonLevelId_d($obj){
    	$sql = "select id from oa_esm_baseinfo_person where personLevel = '$obj'";
    	return $this->_db->getArray($sql);
    }
}
?>