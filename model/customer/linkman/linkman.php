<?php

/**
 * ��ϵ��model����
 */
class model_customer_linkman_linkman extends model_base {

	function __construct() {
		$this->tbl_name = "oa_customer_linkman";
		$this->sql_map = "customer/linkman/linkmanSql.php";
		parent::__construct ();
	}
	/***************************************************************************************************
	 * ------------------------------����Ϊҳ��ģ����ʾ���÷���------------------------------
	 *************************************************************************************************/

	function showlist($rows, $showpage) {
		$str = "";//���ص�ģ���ַ���
		if ($rows) {
			$i = $n = 0; //�б��¼���
			$datadictDao = new model_system_datadict_datadict ();
			foreach ( $rows as $key => $val ) {
				$i ++;
				$n = ($i%2)+1;
				$str .= <<<EOT
						<tr id="tr_$val[id]" class="TableLine$n">
							<td><input type="checkbox" name="datacb"  value="$val[id]" onClick="checkOne();"></td>
							<td align="center">$i</td>
							<td align="center">$val[customerName]</td>
							<td align="center">$val[linkmanName]</td>
							<td align="center">$val[phone]</td>
							<td align="center">$val[mobile]</td>
							<td align="center">$val[MSN]</td>
							<td align="center">$val[QQ]</td>
							<td align="center">$val[email]</td>
							<td align="center" id="m_$val[id] ">
								<a href="?model=customer_linkman_linkman&action=readInfo&id=$val[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700" title="�鿴< $val[linkmanName] >��Ϣ" class="thickbox">�鿴</a>
								<a href="?model=customer_linkman_linkman&action=init&id=$val[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700" title="�޸�< $val[linkmanName] >��Ϣ" class="thickbox">�޸�</a>
							</td>
						</tr>
EOT;
			}
		} else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";
		}
		return $str . '<tr><td colspan="50" style="text-align:center;">' . $showpage->show ( 6 ) . '</td></tr>';
	}

	/**
	 * �ڿͻ���Ϣ����ʾ��ϵ���б�
	 */
	function showlistInCustomer($rows, $showpage) {
		$str = "";
		if ($rows) {
			$i = $n = 0;
			foreach ($rows as $key => $val) {
				$i++;
				$n = ($i%2)+1;
				$str .=<<<EOT
					<tr id="tr_$val[id]" class="TableLine$n">
						<td align="center">$i</td>
							<td align="center">$val[customerName]</td>
							<td align="center">$val[linkmanName]</td>
							<td align="center">$val[phone]</td>
							<td align="center">$val[mobile]</td>
							<td align="center">$val[email]</td>
						<td>
							<p>
								<a href="?model=customer_linkman_linkman&action=readInfoInS&id=$val[id]" title="�鿴< $val[linkmanName] >��Ϣ">�鿴</a>
							</p>
					    </td>
					</tr>
EOT;
			}

		}else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";
		}
		return $str . '<tr><td colspan="10" style="text-align:center;">' . $showpage->show(6) . '</td></tr>';
		//return $str;
	}

	/***************************************************************************************************
	 * ------------------------------����Ϊ���ýӿڷ���,����Ϊ����ģ��������------------------------------
	 *************************************************************************************************/

	/**
	 * �����Ա�
	 */
	function showSexInRead($value){
		if ($value == 1) {
			$value = "δ֪";
		}else if ($value == 2) {
			$value = "��";
		} else if($value== 3){
			$value = "Ů";
		}
		return $value;
	}

	function showSexInEdit($value){
		$value1 = $value2 = $value3 = "";
		if ($value == 1) {
			$value1 = "selected";
		}else if ($value == 2) {
			$value2 = "selected";
		} else if($value== 3){
			$value3 = "selected";
		}
		$str=<<<EOT
			<option value="1" $value1>δ֪</option>
			<option value="2" $value2>��</option>
			<option value="3" $value3>Ů</option>
EOT;
		return $value;
	}

	/**
	 * ����������ȡ����
	 */
	function get_d($id,$seltype = "read") {
		$this->searchArr  = array ("id" => $id );
		$arr = $this->listBySqlId ();
		if($seltype == "read"){
			$arr[0]['sex'] = $this->showSexInRead($arr[0]['sex']);
		}else{
			$arr[0]['sex'] = $this->showSexInEdit($arr[0]['sex']);
		}
		return $arr [0];
	}

	/**
	 * ��ȡ�����ϵ���б�
	 */
	function getLinkManBySId(){
		$arr = $this->listBySqlId ();
		return $arr;
	}

	/**
	 * ��дPAGE����
	 */
	function page_d() {
		return $this->pageBySqlId ();
	}

	/**
	 * ��ȡ����Ȩ��
	 */
	function getAreaIds_d(){
		$areaDao = new model_system_region_region();
		return $areaDao->getUserAreaId($_SESSION['USER_ID'],0);
	}

	/**
	 * ������ϵ������
	 */
	 function linkmanInfo($row){
               foreach ($row as $k => $v){
               	    $cusId[$k] = $row[$k]['id'];
               }
               $rows = array();
               if(!empty($cusId)){
               	   $cusId = implode(",",$cusId);
		           $sql = "select l.id,l.customerId,l.linkmanName,l.sex,l.weight,l.age,l.duty," .
		           		  "l.remark,l.height,l.phone,l.mobile,l.address,l.MSN,l.QQ,c.areaName,c.email,c.id as cusId,c.Name as customerName" .
		           		  " from oa_customer_linkman l left join customer c on l.customerId=c.id where customerId in (".$cusId.")";
		           $rows = $this->_db->getArray($sql);
               }
               return $rows;

	 }

	 /**
	  * �ͻ���ϵ�˵��� by Liub
	  */
	 function importExcel($stockArr) {
		try {
			$this->start_d ();
			set_time_limit ( 0 );
			$resultArr = array ();//�������
            $addArr = array();//��ȷ��Ϣ����
			foreach ( $stockArr as $key => $obj ) {
				   $cusName = $obj['customerName'];
				   $sql = "select id from customer where Name = '$cusName'"; //���ҿͻ�ID
	               $cus =  $this->_db->getArray($sql); //
	               foreach($cus as $k => $v){
                       $cusId = $v['id'];
	               }
	               $linkName = $obj['linkmanName'];
	               $linkSql = "select id from oa_customer_linkman where linkmanName = '$linkName'";
	               $linkmanName =  $this->_db->getArray($linkSql); //
                  if(!empty($obj['customerName']) && !empty($obj['linkmanName']) && !empty($cus) && empty($linkmanName)){
//                      $addArr[$key]['customerName'] = $obj['customerName'];
                      $addArr[$key]['customerId'] = $cusId;
                      $addArr[$key]['linkmanName'] = $obj['linkmanName'];
                      $addArr[$key]['sex'] = $obj['sex'];
                      $addArr[$key]['height'] = $obj['height'];
                      $addArr[$key]['weight'] = $obj['weight'];
                      $addArr[$key]['age'] = $obj['age'];
                      $addArr[$key]['duty'] = $obj['duty']; //ְ��
                      $addArr[$key]['phone'] = $obj['phone'];
                      $addArr[$key]['mobile'] = $obj['mobile'];
                      $addArr[$key]['MSN'] = $obj['MSN'];
                      $addArr[$key]['QQ'] = $obj['QQ'];
                      $addArr[$key]['address'] = $obj['address'];
                      $addArr[$key]['remark'] = $obj['remark'];

                      array_push ( $resultArr, array ("docCode" => $obj['linkmanName'], "result" => "����ɹ���" ) );
                  }else if(empty($obj['customerName']) || empty($cus)){
                  	  array_push ( $resultArr, array ("docCode" => $obj['linkmanName'], "result" => "ʧ�ܣ��ͻ�����Ϊ�ջ򲻴���" ) );
                  }else if(empty($obj['linkmanName'])){
                  	  array_push ( $resultArr, array ("docCode" => $obj['linkmanName'], "result" => "ʧ�ܣ���ϵ������Ϊ��" ) );
                  }else if(!empty($linkmanName)){
                      array_push ( $resultArr, array ("docCode" => $obj['linkmanName'], "result" => "ʧ�ܣ���ϵ�������Ѵ���" ) );
                  }
			}
                  $this->addBatch_d($addArr);
			$this->commit_d ();
			return $resultArr;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}



    /**
     * ���±�Ѷ��ϵ����Ϣ����
     */
    function updateLinkmanBX(){
    	$this->titleInfo("���ڻ�ȡ��Ҫ�������ϵ������...");
    	//��ȡ��Ѷ�ͻ�������
    	$rowSql = "select c.Name,l.* from oa_customer_linkman_bx l left join customer_bx c on l.customerId = c.id;";
    	$BXrow = $this->_db->getArray($rowSql);
        $this->titleInfo("��ȡ�������,��ʼ׼����������...");
        foreach($BXrow as $k => $v){
        	$this->handleData($v);
        }
        $this->titleInfo("<input type='button' onclick='history.back()' value='����'>");
    }


 //��������
   function handleData($row){
   	   //���ݿͻ������жϹ����Ŀͻ��Ƿ���ڲ���д�ͻ�id
   	   $cSql = "select id from customer where Name = '".$row['Name']."'";
   	   $cArr = $this->_db->getArray($cSql);
   	   if(empty($cArr)){
   	   	   $this->titleInfo("<span style='color:red'>�� </span>��Ѷ��ϵ�ˡ�".$row['linkmanName']."�� δ�ҵ������ͻ�.    ");
   	   }else{
   	   	  $row['customerId'] = $cArr[0]['id'];
   	   	  //�ж���ϵ���Ƿ��ظ�
   	   	  $tt = $this->find(array("linkmanName"=>$row['linkmanName'],"duty"=>$row['duty']));
   	   	  if(!empty($tt)){
   	   	  	$this->titleInfo("<span style='color:blue'>  ��</span>��Ѷ��ϵ�ˡ�".$row['linkmanName']."�� ϵͳ���Ѵ���.    ");
   	   	  }else{
   	   	      unset($row['Name']);
	   	   	  unset($row['id']);
	   	   	  $this->add_d($row,false);
	   	   	  $this->titleInfo("<span style='color:black'> �� </span>��Ѷ��ϵ�ˡ�".$row['linkmanName']."�� ����ɹ�.    ");
   	   	  }
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