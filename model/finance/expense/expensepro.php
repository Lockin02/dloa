<?php

/**
 * @author Show
 * @Date 2012��9��28�� ������ 11:07:32
 * @version 1.0
 * @description:�������������ϸ(��Ŀ����) Model��
 */
class model_finance_expense_expensepro extends model_base {

	function __construct() {
		$this->tbl_name = "cost_detail_project";
		$this->sql_map = "finance/expense/expenseproSql.php";
		parent :: __construct();
	}

	/**
	 * ���ݴ���Ķ��������Զ������������޸ģ�ɾ��(��Ҫ���ڽ�����ӱ��жԴӱ�������������)
	 * �жϹ���
	 * 1.���idΪ����isDelTag����Ϊ1����������������������Ӻ�ɾ�����,��̨ɶ��������
	 * 2.���idΪ�գ�������
	 * 3.���isDelTag����Ϊ1����ɾ��
	 * 4.�����޸�
	 * @param Array $objs
	 */
	function saveDelBatch($objs) {
		//ʵ������Ʊ��ϸ
		$expenseinvDao = new model_finance_expense_expenseinv();

		try{
			$returnObjs = array ();
			foreach ( $objs as $key => $val ) {
				$isDelTag=isset($val ['isDelTag'])?$val ['isDelTag']:NULL;
				if ((empty ( $val ['ID'] ) && $isDelTag== 1)) {

				} else if (empty ( $val ['ID'] )) {
					//����������ݽ��Ϊ0���ɵ�
					if($val['CostMoney'] == 0){
						continue;
					}
					$expenseinv = $val['expenseinv'];
					unset($val['expenseinv']);

					//���������ò���
					$id = $this->add_d ( $val );
					$val ['ID'] = $id;
					array_push ( $returnObjs, $val );

					//��������Ʊ����
					$addArr = array(
						'BillDetailID' =>$id,
						'BillAssID' =>$val['AssID'],
						'BillNo' =>''
					);
					$expenseinv = util_arrayUtil::setArrayFn($addArr,$expenseinv);
					$expenseinvDao->saveDelBatch($expenseinv);
				} else if ($isDelTag == 1) {
					//��ɾ������
					$this->deletes ( $val ['ID'] );

					//��ɾ����Ʊ
					$expenseinvDao->deleteByDetailId($val['ID']);
				} else {
					//����༭���Ϊ0���ɵ�
					if($val['CostMoney'] == 0){
						//��ɾ������
						$this->deletes ( $val ['ID'] );

						//��ɾ����Ʊ
						$expenseinvDao->deleteByDetailId($val['ID']);
					}else{

						//��ȥ��Ʊ��Ϣ
						$expenseinv = $val['expenseinv'];
						unset($val['expenseinv']);
						//�ȱ༭���ò���
//    					echo "<pre>";
//						print_r($val);
						$this->edit_d ( $val );
						array_push ( $returnObjs, $val );

						//�ٱ༭��Ʊ��ϸ����
						$addArr = array(
							'BillDetailID' => $val['ID'],
							'BillAssID' =>$val['AssID'],
							'BillNo' =>''
						);
						$expenseinv = util_arrayUtil::setArrayFn($addArr,$expenseinv);
						$expenseinvDao->saveDelBatch($expenseinv);
					}
				}
			}
			return $returnObjs;
		}catch(exception $e){
			echo $e->getMessage();
			throw $e;
			return false;
		}
	}

	/**
	 * ���������޸ļ�¼
	 */
	function updateById($object) {
		$condition = array ("ID" => $object ['ID'] );
		return $this->update ( $condition, $object );
	}
}
?>