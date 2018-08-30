<?php
/**
 * @author Show
 * @Date 2011��11��25�� ������ 13:59:48
 * @version 1.0
 * @description:��ԴĿ¼(oa_esm_baseinfo_resource) Model�� status
                                                      0 ����
                                                      1.����
 */
class model_engineering_baseinfo_resource extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_baseinfo_resource";
		$this->sql_map = "engineering/baseinfo/resourceSql.php";
		parent::__construct ();
    }

    /**
     * ��֤�Ƿ���ڸ��ڵ㣬������������
     */
    function checkParent_d(){
    	$rs = $this->find(array('id'=> -1),null,'id');
		if(is_array($rs)){
			return true;
		}else{
			$this->create(array('id' => -1,'resourceCode' => 'root' , 'resourceName' => '��ԴĿ¼'));
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
     	$esmresourcesDao = new model_engineering_resources_esmresources();//ʵ������Դ�ƻ�model��
     	$conditions = array('resourceId'=>$id);
     	if($esmresourcesDao->find($conditions , $sort = null, $fields = null, $limit = null)){
     		return 1;
     	}else{
     		return 0;
     	}
     }
}
?>