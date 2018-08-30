<?php

/**
 * @author Administrator
 * @Date 2012-10-29 14:46:46
 * @version 1.0
 * @description:�豸���� Model��
 */
class model_equipment_budget_deploy extends model_base {

	function __construct() {
		$this->tbl_name = "oa_equ_baseinfo_deploy";
		$this->sql_map = "equipment/budget/deploySql.php";
		parent :: __construct();
	}

	/**
	 * ��дadd_d����
	 */
	function add_d($object) {
		try {
			$this->start_d();
			//������������
			//��ȡequ��Ϣ
			$equDao = new model_equipment_budget_budgetbaseinfo();
			$equInfo = $equDao->get_d($object['equId']);
			$arr = array ();
			if (!empty ($object['info'])) {
				foreach ($object['info'] as $key => $val) {
					$arr[$key]['budgetTypeId'] = $equInfo['budgetTypeId'];
					$arr[$key]['budgetTypeName'] = $equInfo['budgetTypeName'];
					$arr[$key]['equId'] = $equInfo['id'];
					$arr[$key]['equName'] = $equInfo['equName'];
					$arr[$key]['name'] = $object['name'];
					$arr[$key]['remark'] = $object['remark'];
					$arr[$key]['info'] = $val['info'];
					$arr[$key]['price'] = $val['price'];
					$arr[$key] ['createId'] = $_SESSION ['USER_ID'];
					$arr[$key] ['createName'] = $_SESSION ['USERNAME'];
					$arr[$key] ['createTime'] = date ( "Y-m-d H:i:s" );
					$arr[$key] ['updateId'] = $_SESSION ['USER_ID'];
					$arr[$key] ['updateName'] = $_SESSION ['USERNAME'];
					$arr[$key] ['updateTime'] = date ( "Y-m-d H:i:s" );
				}
			}
			//����������Ϣ
			$this->createBatch($arr);
//			$newId = parent :: add_d($arr, true);

			$this->commit_d();
			//			$this->rollBack();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��д�༭����
	 */
	function edit_d($object) {
		try {
			$this->start_d();

         $arr = $object['info'][0];
         $arr['id'] = $object['id'];
			//�޸�������Ϣ
			parent :: edit_d($arr, true);
			//���� �豸��ע��Ϣ
			$this->update(array("name"=>$object['name'],"equId"=>$object['equId']),array("remark"=>$object['remark']));

			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��Ⱦ ���ñ�ӱ�--�鿴
	 */
	function deployList($equId){
//       $arr = $this->findAll(array("equId"=>$equId));
       $sql = "SELECT name,GROUP_CONCAT(CAST(id AS char)) as cid,remark FROM `oa_equ_baseinfo_deploy` where equId=".$equId." GROUP BY name order by id;";
	   $arr = $this->_db->getArray($sql);
		if ($arr) {
			$i = 1; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			//��һ��ѭ������ȡgroup by ����
			foreach ( $arr as $key => $val ) {
			  $cid=$val['cid'];
			  $name=$val['name'];
			  $remark=$val['remark'];
			   $findSql="select info,price,name from oa_equ_baseinfo_deploy where id in (".$cid.")";
			   $infoArr = $this->_db->getArray($findSql);
              $spanNum = count($infoArr);
              $nameTemp = $name;
              //�ڶ���ѭ������ȡ��ϸ���ݣ�������ϲ���
              foreach($infoArr as $k=>$v){
              	$info = $v['info'];
              	$price = $v['price'];
               if($v['name'] == $nameTemp && $k != '0'){
               	  $num = "";
               	  $nameTD = "";
               	  $remarkTD = "";
               }else{
               	  $num = "<td rowspan='".$spanNum."'>$i</td>";
               	  $nameTD = "<td rowspan='".$spanNum."'>$name</td>";
               	  $remarkTD = "<td rowspan='".$spanNum."'>$remark</td>";
               }
               $str .= <<<EOT
			<tr align="center">
			        $num
			        $nameTD
			        <td>$info</td>
			        <td class="formatMoney">$price</td>
    				$remarkTD
			</tr>
EOT;
              }
				$i ++;
			}
		}
		return $str;
	}
}
?>