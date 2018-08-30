<?php
/**
 * @author Michael
 * @Date 2014��4��24�� 9:50:35
 * @version 1.0
 * @description:�����ڼ��� Model��
 */
 class model_hr_worktime_set  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_worktime_set";
		$this->sql_map = "hr/worktime/setSql.php";
		parent::__construct ();
	}

	/**
	 * ��дadd
	 */
	function add_d($object) {
		try {
			$this->start_d();

			//��ȡ������˾����
			$object['formBelong'] = $_SESSION['USER_COM'];
			$object['formBelongName'] = $_SESSION['USER_COM_NAME'];
			$object['businessBelong'] = $_SESSION['USER_COM'];
			$object['businessBelongName'] = $_SESSION['USER_COM_NAME'];

			$id = parent :: add_d($object ,true);  //����������Ϣ

			$setequDao = new model_hr_worktime_setequ();
			if(is_array($object['equ'])) { //������ϸ
				foreach($object['equ'] as $key => $val) {
					$val['parentId'] = $id;
					$setequDao->add_d($val);
				}
			}

			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

	/**
	 * ��дedit
	 */
	function edit_d($object){
		try {
			$this->start_d();

			$id = parent :: edit_d($object ,true); //����������Ϣ

			$setequDao = new model_hr_worktime_setequ();
			$setequDao->delete(array('parentId' => $object['id']));
			if(is_array($object['equ'])){  //������ϸ
				foreach($object['equ'] as $key => $val) {
					if ($val['isDelTag'] != 1) {
						$val['parentId'] = $object['id'];
						$setequDao->add_d($val);
					}
				}
			}

			$this->commit_d();
			return $object['id'];
		} catch (exception $e) {
			return false;
		}
	}
 }
?>