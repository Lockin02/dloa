<?php
/**
 * Created on 2011-2-15 13:45
 */
class model_purchase_change_equipmentchange extends model_base {
	/*
	 * @description ���캯��
	 */
	function __construct() {
		$this->tbl_name = "oa_purch_apply_equ";
		$this->sql_map = "purchase/change/equipmentchangeSql.php";
		parent :: __construct();

	}

	/**
	 * @description ����ͬ�Ĳɹ��豸�����ݱ��浽�豸�İ汾����
	 * @author qian
	 * @date 2011-2-16 16:22
	 */
	function addEquVersion_d($rows){
		try{
			$this->start_d();
			//�ֶΡ�changeType��Ϊ0ʱ��ʾ�޸ģ�Ϊ1ʱ��ʾɾ��
			if($rows['amountAll']==0){
				$rows['changeType'] = 1;
			}else{
				$rows['changeType'] = 0;
			}

			$id = $this->add_d($rows);
			$this->commit_d();
		}catch(Exception $e){
			$this->rollBack();
			return null;
		}
	}


	/**
	 * @description �����豸�汾���������
	 * @author qian
	 * @date 2011-2-19 12:18
	 */
	function toEquChangeVersion_d($equipment){

	}
}

?>
