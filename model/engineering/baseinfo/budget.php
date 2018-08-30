<?php
/**
 * @author Show
 * @Date 2011��11��25�� ������ 9:38:59
 * @version 1.0
 * @description:Ԥ����Ŀ(oa_esm_baseinfo_budget) Model�� status
                                                    0 ����
                                                    1.����
 */
class model_engineering_baseinfo_budget extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_baseinfo_budget";
		$this->sql_map = "engineering/baseinfo/budgetSql.php";
		parent::__construct ();
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
			$this->create(array('id' => -1,'budgetCode' => 'root' , 'budgetName' => 'Ԥ����Ŀ'));
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
     * ɾ��ʱ�ж��Ƿ�����
     */
     function deleteCheck_d($rowData){
     	$id = $rowData['id'];
     	$esmbudgetDao = new model_engineering_budget_esmbudget();
     	$conditions = array('budgetId'=>$id);
     	if($esmbudgetDao->find($conditions , $sort = null, $fields = null, $limit = null)){
     		return 1;
     	}else{
     		return 0;
     	}
     }
}
?>