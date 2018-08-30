<?php

/**
 * �ͻ�model����
 */
class model_customer_customer_customer extends model_base {

	function __construct() {
		$this->tbl_name = "customer";
		$this->sql_map = "customer/customer/customerSql.php";
		parent::__construct ();
	}
	/****
	 * ��ȡʡ����Ϣ����
	 * **/
	function province_d() {
		$provice = new model_system_procity_province ();
		$proviceShow = $provice->findAll ();
		return $proviceShow;
	}

	/**���ݿͻ�ID��ȡ�ͻ����
	 *author can
	 *2011-6-28
	 */
	function getObjectCode($id) {
		$condition = 'id=' . $id;
		return $this->get_table_fields ( $this->tbl_name, $condition, "objectCode" );
	}

	function showlist($rows, $showpage) {
		if ($rows) {
			$str = "";
			$i = $n = 0;
			$datadictDao = new model_system_datadict_datadict ();
			foreach ( $rows as $key => $rs ) {
				$i ++;
				$n = ($i % 2) + 1;
				$typeOne = $datadictDao->getDataNameByCode ( $rs ['TypeOne'] );
				$str .= <<<EOT
					<tr id="tr_$rs[id]" class="TableLine$n">
						<td align="center"><input type="checkbox" name="datacb" value="$rs[id]"  onClick="checkOne();"></td>
						<td height="25" align="center">$i</td>
						<td align="center"> $rs[Name] </td>
						<td align="center" >$rs[AreaLeader] </td>
						<td align="center" >$rs[SellMan] </td>
						<td align="center" >$typeOne</td>
						<td align="center" >$rs[Prov]</td>
						<td align="center">
							<p>
								<a href="?model=customer_customer_customer&action=readInfo&id=$rs[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700" title="�鿴<$rs[Name]>��Ϣ" class="thickbox">�鿴</a>
								<a href="?model=customer_customer_customer&action=init&id=$rs[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700" title="�޸�<$rs[Name]>��Ϣ" class="thickbox">�޸�</a>
							</p>
					    </td>
					</tr>
EOT;
			}

		} else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";
		}
		return $str . '<tr><td colspan="9" style="text-align:center;">' . $showpage->show ( 6 ) . '</td></tr>';

	}

	/**
	 *

		function page_d(){
			include ("phprpc/phprpc_client.php");
			$client = new PHPRPC_Client();
			$client->setProxy(NULL);
			$client->useService('http://127.0.0.1:8080/ralasafe/phprpc/ralasafeService');
			$userId= $_SESSION ['USER_ID'];
			$rows= $client->queryCustomers($userId);
			return util_jsonUtil::iconvUTF2GBArr($rows);
	 **/

	/**
	 * ��ͬ�����ѯ�ͻ��Ƿ����
	 * by LiuB 2011-8-11 15:49:03
	 */
	function findCus($customerName) {
		$sql = "select  id,Prov  from customer  where Name = '$customerName'";
		$arr = $this->_db->getArray ( $sql );

		return $arr;
	}
	//���ݺ�ͬ��Ų�
	function findCusCode($customerCode) {
		$sql = "select  id,Prov  from customer  where objectCode = '$customerCode'";
		$arr = $this->_db->getArray ( $sql );

		return $arr;
	}
	/**
	 * �������Ʋ��ҿͻ�Id
	 */
    function findCid($cusName){
        $sql = "select id from customer where Name = '$cusName'";
        $cName = $this->_db->getArray($sql);
        return $cName;
    }
	/***************************************************************************************************
	 * ------------------------------����Ϊ���ýӿڷ���,����Ϊ����ģ��������------------------------------
	 *************************************************************************************************/

	/**
	 * ��д��ȡ����
	 */
	//	function get_d($id) {
	//
	//		try {
	//			$this->start_d ();
	//			$this->add_d ( array ("Name" => '123' ) );
	//			$condition = array ("id" => $id );
	//			$rows = $this->find ( $condition );
	//			 echo "<pre>";
	//			 print_r ($rows);
	//			$datadict = new model_system_datadict_datadict ();
	//			$rows ['TypeOne'] = $datadict->getDataNameByCode ( $rows ['TypeOne'] );
	//			//return $rows;
	//			$this->add_d ( array ("Name" => '222' ) );
	//			$this->commit_d ();
	//		} catch ( Exception $e ) {
	//			$this->rollBack ();
	//			return null;
	//		}
	//	}


	/* ���ݶ�ȡEXCEL�е���Ϣ���뵽ϵͳ��
		 * @param $stockArr
		 * importStockInfo()--->importCustomerInfo()
		 */
	function importCustomerInfo($stockArr) {
		try {
			$this->start_d ();
			set_time_limit ( 0 );
			$resultArr = array ();
			$dataDictDao = new model_system_datadict_datadict ();
			$dataDictOpt = $dataDictDao->getDatadictsByParentCodes ( "KHLX" );
			$codeDao = new model_common_codeRule ();

			foreach ( $stockArr as $key => $obj ) {
				if (! empty ( $obj ['4'] )) {
					foreach ( $dataDictOpt ['KHLX'] as $key1 => $dataObj ) {
						if ($dataObj ['dataName'] == $obj [3]) {
							$customerType = $dataObj ['dataCode'];
							break;
						}
					}
					$queryArr = array ();
					$queryArr2 = array ();
					if (! empty ( $obj [1] )) {
						$sql = "select c.id as cityId ,c.cityName  as  cityName,c.provinceId as provId,p.provinceName as provinceName, p.countryId as countryId ,t.countryName as countryName " . "from  oa_system_city_info c  right  join oa_system_province_info p on(p.id=c.provinceId) right join oa_system_country_info t  on(t.id=p.countryId)  " . "where c.cityName='$obj[1]'";
						$queryArr = $this->findSql ( $sql );
					}

					$area = explode ( '��', $obj [2] );
					if (is_array ( $area )) {
						$sql2 = "select o.id as areaId ,o.areaPrincipal,areaPrincipalId,areaName as area from oa_system_region o where areaName='$area[0]'";
						$queryArr2 = $this->findSql ( $sql2 );
					}

					$tempObj = array ("Name" => $obj [4], "TypeOne" => $customerType, "CityId" => $queryArr [0] ['cityId'], "City" => $queryArr [0] ['cityName'], "ProvId" => $queryArr [0] ['provId'], "Prov" => $queryArr [0] ['provinceName'], "CountryId" => $queryArr [0] ['countryId'], "Country" => $queryArr [0] ['countryName'], "AreaLeaderId" => $queryArr2 [0] ['areaPrincipalId'], "AreaLeader" => $queryArr2 [0] ['areaPrincipal'], "AreaName" => $queryArr2 [0] ['area'], "AreaId" => $queryArr2 [0] ['areaId'] );
					if (isset ( $queryArr [0] ['countryId'] ) && isset ( $queryArr [0] ['cityId'] )) {
						$tempObj ['objectCode'] =
						$codeDao->customerCode ( "customer", $customerType, $queryArr [0] ['countryId'], $queryArr [0] ['cityId'] );
						$this->searchArr = array ("nName" => $obj [4] );
						$searchData = $this->list_d ();
						if (! is_array ( $searchData )) {
							if ($this->add_d ( $tempObj, true ))
								array_push ( $resultArr, array ("docCode" => $obj [4], "result" => "����ɹ�!" ) );
						} else {
							array_push ( $resultArr, array ("docCode" => $obj [4], "result" => "�ÿͻ������Ѿ�����!" ) );
						}
					} else {
						array_push ( $resultArr, array ("docCode" => $obj [4], "result" => "ʡ����Ϣ������,��ȷ��!" ) );
					}

				}
			}

			$this->commit_d ();
			return $resultArr;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}
	/**
	 * ���˿ͻ���Ϣ
	 * by Liub 2011��8��29��14:03:22
	 */
	function customerRows($privlimit, $thisAreaLimit, $userId) {
//		 return $aa = $this->searchArr;
		$areaU = "select id from oa_system_region where areaPrincipalId = '" . $userId . "'";
		$areaId = $this->_db->getArray ( $areaU );
		if (empty ( $areaId )) {
			if (!empty ( $privlimit )) {
//				$sql = "select * from " . $this->tbl_name . " where AreaId in (" . $privlimit . ") or SellManId like '%".$userId."%'";
//				$row = $this->_db->getArray ( $sql );
				return 1;
			}else{
//				$sql = "select * from " . $this->tbl_name . " where  SellManId like '%".$userId."%'";
//				$row = $this->_db->getArray ( $sql );
				return 2;
			}
		} else {
			if (!empty ( $thisAreaLimit )) {
//				$sql = "select * from " . $this->tbl_name . " where AreaId in (" . $thisAreaLimit . ") or SellManId like '%".$userId."%'";
//				$row = $this->_db->getArray ( $sql );
				return 3;
			}
		}
	}

	/**
	 * �������й���ҵ�����ͻ�id
	 */
	function updateBusCustomerIds($customerRelationTableArr) {
		foreach ( $customerRelationTableArr as $key => $value ) {
			if (is_array ( $value )) {
				if (! empty ( $value ['Name'] )) {
					$sql = "update $key left join customer  on $key." . $value ['Name'] . "=customer.Name set $key." . $value ['id'] . "=customer.id where $key." . $value ['id'] . " is null or $key." . $value ['id'] . "=''";
					//	echo $sql;
					$this->query ( $sql );
				}
			} else {
				throw new Exception ( "����ʧ��." );
			}
		}
	}

	/**
	 *
	 * ͨ�����Ƹ���
	 * @param $customer �ͻ�����
	 * @param $relationArr ѡ�е�ҵ���������
	 */
	function updateBusCustomerIdByName($customer, $relationArr) {
		include_once ("model/customer/customer/customerRelationTableArr.php");
		foreach ( $customerRelationTableArr as $objArr ) {
			if (is_array ( $objArr )) {
				foreach ( $objArr as $key => $value ) {
					if (in_array ( $key, $relationArr )) {
						if (! empty ( $value ['Name'] )) {
							$sql = "update $key left join customer  on $key." . $value ['Name'] . "=customer.Name set $key." . $value ['id'] . "=customer.id where $key." . $value ['Name'] . " ='" . $customer['Name'] . "'";
							if (! empty ( $customer ['updateStartDate'] )) {
								$sql .= " and $key.createTime >= cast('$key." . $customer ['updateStartDate'] . "' as datetime)";
							}
							if (! empty ( $customer ['updateEndDate'] )) {
								$sql .= " and $key.createTime <= cast('$key." . $customer ['updateEndDate'] . "' as datetime)";
							}
							//echo $sql;
							$this->query ( $sql );
						}
					}
				}
			}
		}
	}

	/**
	 * �ϲ��ͻ�,���¿ͻ�
	 * @param  $customerNo �ͻ�����
	 * @param  $mergerIdArr �ϲ��Ŀͻ�id����
	 */
	function mergerCustomer($objectCode, $mergerIdArr) {
		try {
			$this->start_d ();
			include_once ("model/customer/customer/customerRelationTableArr.php");
			$this->updateBusCustomerIds ( $customerRelationTableArr );
			$this->searchArr = array ("objectCode" => $objectCode, "customerIds" => implode ( ",", $mergerIdArr ) );
			$customers = $this->list_d ();
			if (empty ( $customers )) {
				throw new Exception ( "����ͻ����벻���ںϲ���������." );
			}
			if (count ( $customers ) != 1) {
				throw new Exception ( "�ϲ�ʧ��,�ͻ����������ݿ��д��ڶ��." );
			}
			$customer = $customers [0];
			//$customer=util_jsonUtil::iconvGB2UTFArr($customer);
			foreach ( $mergerIdArr as $key => $value ) {
				if ($value == $customer ['id']) {
					unset ( $mergerIdArr [$key] );
				}
			}
			$mergerIds = implode ( ",", $mergerIdArr );
			foreach ( $customerRelationTableArr as $objArr ) {
				if (is_array ( $objArr )) {
					foreach ( $objArr as $key => $value ) {
						$sql = "update " . $key . " set ";
						foreach ( $value as $k => $v ) {
							if (! empty ( $v ) && ! empty ( $customer [$k] )) {
								$sql .= $v . "='" . $customer [$k] . "',";
							}
						}
						$sql = substr ( $sql, 0, - 1 );
						$sql .= " where " . $value ['id'] . " in (" . $mergerIds . ");";
						//echo $sql;
						$this->query ( $sql );
					}
				} else {
					throw new Exception ( "�ϲ�ʧ��,�����ļ���������." );
				}
			}
			$sql = "delete from customer where id in(" . $mergerIds . ")";
			$this->query ( $sql );
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			//echo $e->message;
			throw $e;
		}
	}

	/**
	 *
	 * ���¿ͻ���ص�ҵ�������Ϣ
	 * @param  $customer
	 * @param  $relationArr
	 */
	function updateCustomer($customer, $relationArr) {
		try {
			$this->start_d ();
			$this->edit_d ( $customer );
			include_once ("model/customer/customer/customerRelationTableArr.php");
			foreach ( $customerRelationTableArr as $objArr ) {
				if (is_array ( $objArr )) {
					foreach ( $objArr as $key => $value ) {
						if (in_array ( $key, $relationArr )) {
							$sql = "update " . $key . " set ";
							foreach ( $value as $k => $v ) {
								if (! empty ( $v ) && ! empty ( $customer [$k] )) {
									$sql .= $v . "='" . $customer [$k] . "',";
								}
							}
							$sql = substr ( $sql, 0, - 1 );
							$sql .= " where " . $value ['id'] . " =" . $customer ['id'];
							if (! empty ( $customer ['updateStartDate'] )) {
								$sql .= " and createTime >= cast('" . $customer ['updateStartDate'] . "' as datetime)";
							}
							if (! empty ( $customer ['updateEndDate'] )) {
								$sql .= " and createTime <= cast('" . $customer ['updateEndDate'] . "' as datetime)";
							}
							//echo $sql;
							$this->query ( $sql );
						}
					}
				} else {
					throw new Exception ( "�ϲ�ʧ��,�����ļ���������." );
				}
			}
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			//echo $e->message;
			throw $e;
		}
	}

	/**
	 *����ɾ���ͻ�
	 */
	function deletes_d($ids) {
		try {
			$this->start_d ();
			$idArr = explode ( ",", $ids );
			$customerTable = $this->tbl_name;
			foreach ( $idArr as $id ) {
				$this->isCustomerRelated ( $id );
				$this->tbl_name = $customerTable;
				$this->deleteByPk ( $id );
			}
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			//echo $e->message;
			throw $e;
		}
	}

	/**
	 * �ж�һ���ͻ��Ƿ��Ѿ���ҵ��������������Ѿ����������׳�������Ϣ�쳣��ֻ�׳���һ�������õ�ҵ������쳣
	 * @param unknown_type $id
	 */
	function isCustomerRelated($id) {
		//����ɾ��У�飬����Ѿ�������ҵ�����������ɾ��
		include ("model/customer/customer/customerRelationTableArr.php");
		$this->tbl_name = "customer";
		$customer = $this->get_d ( $id );
		foreach ( $customerRelationTableArr as $objArr ) {
			if (is_array ( $objArr )) {
				foreach ( $objArr as $key => $value ) {
					$this->tbl_name = $key;
					$num = $this->findCount ( array ($value ['id'] => $id ) );
					if ($num > 0) {
						throw new Exception ( "ɾ��ʧ��.�ͻ���" . $customer ['Name'] . "���Ѿ���" . $num . "����" . $value [0] . "������." );
						break;
					}
				}
			}
		}
		return false;
	}


	/**
	 * �ж�һ���ͻ��Ƿ��Ѿ���ҵ��������������Ѿ����������׳�������Ϣ�쳣���׳�����
	 * @param unknown_type $id
	 */
	function customerRelateMsg($id) {
		//����ɾ��У�飬����Ѿ�������ҵ�����������ɾ��
		include ("model/customer/customer/customerRelationTableArr.php");
		$this->tbl_name = "customer";
		$customer = $this->get_d ( $id );
		$msg="";
		foreach ( $customerRelationTableArr as $objArr ) {
			if (is_array ( $objArr )) {
				foreach ( $objArr as $key => $value ) {
					$this->tbl_name = $key;
					$num = $this->findCount ( array ($value ['id'] => $id ) );
					if ($num > 0) {
						$msg.="�ͻ���" . $customer ['Name'] . "���Ѿ���" . $num . "����" . $value [0] . "������.</br>" ;
					}
				}
			}
		}
		if(empty($msg)){
			$msg="�ͻ���" . $customer ['Name'] . "��û�б��κ�ҵ��������.";
		}else{
			$msg="�ͻ���" . $customer ['Name'] . "���Ѿ���</br>".$msg ;
		}
		return $msg;
	}

	/**
	 *�������пͻ�����
	 */
	function updateCustomersCode(){
		try {
			$this->start_d ();
			$this->asc=false;//id����
			$customers=$this->list_d();
			$codeDao = new model_common_codeRule ();
			$sql="update customer set objectCode=''";
			$this->query($sql);
			foreach($customers as $customer){
				$customer ['objectCode'] = $codeDao->customerCode ( "customer", $customer ['TypeOne'], $customer ['CountryId'], $customer ['CityId'] );
				$this->edit_d($customer);
			}
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * �޸Ŀͻ���Ϣ
	 */
	function edit_d($object) {
		try {
			$this->start_d ();
			$sconfig = new model_common_securityUtil ("customer");
			$key=$sconfig->md5Row($object);
			$object['skey_']=$key;
//			util_messageUtil::sendMessageByObjCode("customerUpdate",$object);
			parent::edit_d($object,true);
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}



    /**
     * ���±�Ѷ�ͻ���Ϣ����
     */
    function updateCusBX(){
    	$this->titleInfo("���ڻ�ȡ��Ҫ����Ŀͻ�����...");
    	//��ȡ��Ѷ�ͻ�������
    	$rowSql = "select * from customer_bx";
    	$BXrow = $this->_db->getArray($rowSql);
        $this->titleInfo("��ȡ�������,��ʼ׼����������...");
        foreach($BXrow as $k => $v){
        	$this->handleData($v);
        }
        $this->titleInfo("<input type='button' onclick='history.back()' value='����'>");
    }

 //��������
   function handleData($row){
   	   $customerTempInfo = array();
   	   //�����������жϿͻ��Ƿ����
   	   $ta = $this->find(array("Name"=>$row['Name']));
   	   if(!empty($ta)){
   	   	   $this->titleInfo("<span style='color:blue'>�� </span>��Ѷ�ͻ���".$row['Name']."�� �Ѵ���.    ");
   	   }else{
           //�ж���������
           $prinvipalNameId = $this->user($row['AreaLeader']);
           $row['AreaLeaderId'] = $prinvipalNameId;
              	//�ж�����
              	$typesql = "select id from oa_system_region where areaName = '".$row['AreaName']."'";
                $tc = $this->_db->getArray($typesql);
                $row['AreaId'] = $tc[0]['��d'];
                unset($row['id']);
                $id = $this->add_d($row,false);
                if($id){
                	$this->titleInfo("<span style='color:black'> ��</span>��Ѷϵͳ�ͻ���".$row['Name']."�� ����ɹ�.    ");
                }else{
                	$this->titleInfo("<span style='color:red'> ��</span>��Ѷϵͳ�ͻ���".$row['Name']."�� ����ʧ��.    ");
                }
   	   }
   }

	/**
	 * �������ֲ��Ҷ�ӦId
	 */
	function user($userName) {
		if(!empty($userName)){
			$userDao = new model_deptuser_user_user();
			$user = $userDao->getUserByName($userName);
			$userId = $user['USER_ID'];
			return $userId;
		}else{
			return "";
		}

	}
 //��ʾ��Ϣ
	 function titleInfo($ff){
	 	echo str_pad($ff,4096).'<hr />';
		flush();
		ob_flush();
		sleep(0.1);
	 }
}
?>