<?php
/**
 * @author Show
 * @Date 2013��10��8�� 0:20:42
 * @version 1.0
 * @description:���ģ�����ģ�� Model��
 */
class model_contract_outsourcing_outtemplate extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_outsourcing_template";
		$this->sql_map = "contract/outsourcing/outtemplateSql.php";
		parent :: __construct();
	}

	//����ģ��
	function saveTemplate_d($itemArr){
		//ʵ����ģ����ϸ
		$outtemplateitemDao = new model_contract_outsourcing_outtemplateitem();
		try{
			$this->start_d();

			//��ѯ��ǰ�û��Ƿ��Ѵ���ģ��
			$obj = $this->find(array('createId' => $_SESSION['USER_ID']),null,'id');
			if($obj){
				$condition = array('mainId'=>$obj['id']);
				$outtemplateitemDao->delete($condition);
				$itemArr = util_arrayUtil::setArrayFn($condition,$itemArr);
				$outtemplateitemDao->saveDelBatch($itemArr);
			}else{
				$newArr = array('templateName' => '����ģ��');
				$newId = $this->add_d($newArr,true);
				$itemArr = util_arrayUtil::setArrayFn(array('mainId'=>$newId),$itemArr);
				$outtemplateitemDao->saveDelBatch($itemArr);
			}

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��ȡģ��
	 */
	function getTemplate_d(){
		//ʵ����ģ����ϸ
		$outtemplateitemDao = new model_contract_outsourcing_outtemplateitem();
		//��ѯ��ǰ�û��Ƿ��Ѵ���ģ��
		$obj = $this->find(array('createId' => $_SESSION['USER_ID']),null,'id');
		if($obj){
			return $outtemplateitemDao->findAll(array('mainId' => $obj['id']),'parent');
		}else{
			return false;
		}
	}
}
?>