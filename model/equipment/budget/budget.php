<?php
/**
 * @author Administrator
 * @Date 2012-10-31 09:22:20
 * @version 1.0
 * @description:�豸Ԥ��� Model��
 */
 class model_equipment_budget_budget  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_equ_budget";
		$this->sql_map = "equipment/budget/budgetSql.php";
		parent::__construct ();
	}

	/**
	 *����Զ�����
	 */
     function budgetCode(){
        $billCode = "YS".date("Ymd");
//        $billCode = "JL201208";
		$sql="select max(RIGHT(c.budgetCode,4)) as maxCode,left(c.budgetCode,10) as _maxbillCode " .
				"from oa_equ_budget c group by _maxbillCode having _maxbillCode='".$billCode."'";

		$resArr=$this->findSql($sql);
		$res=$resArr[0];
		if(is_array($res)){
			$maxCode=$res['maxCode'];
			$maxBillCode=$res['maxbillCode'];
			$newNum=$maxCode+1;
			switch(strlen($newNum)){
				case 1:$codeNum="000".$newNum;break;
				case 2:$codeNum="00".$newNum;break;
				case 3:$codeNum="0".$newNum;break;
				case 4:$codeNum=$newNum;break;
			}
			$billCode.=$codeNum;
		}else{
			$billCode.="0001";
		}

		return $billCode;
	}

	/**
	 * ��дadd_d����
	 */
	function add_d($object) {
		try {
			$this->start_d();
			$object['budgetCode'] = $this->budgetCode();
			//����������Ϣ
			$newId = parent :: add_d($object, true);

           //����ӱ���Ϣ
			if (!empty ($object['info'])) {
				$infoDao = new model_equipment_budget_budgetinfo();
				$infoDao->createBatch($object['info'], array (
					'budgetId' => $newId
				));
			}

			$this->commit_d();
//						$this->rollBack();
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
			//�޸�������Ϣ
			parent :: edit_d($object, true);
			if (!empty ($object['info'])) {
				$infoDao = new model_equipment_budget_budgetinfo();
				$infoDao->delete(array (
					'budgetId' => $object['id']
				));
				$infoDao->createBatch($object['info'], array (
					'budgetId' => $object['id']
				));
			}
			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

   /**
    * Ԥ���������ϸ
    */
   function deployList($equId){
   	 if(!empty($equId)){
   	 	//       $arr = $this->findAll(array("equId"=>$equId));
       $sql = "SELECT name,GROUP_CONCAT(CAST(id AS char)) as cid,remark FROM `oa_equ_baseinfo_deploy` where equId=".$equId." GROUP BY name order by id;";
	   $arr = $this->_db->getArray($sql);
   	 }
		if ($arr) {
			$i = 1; //���������
			$n = 1; //�б��¼���
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
               	  $num = "<td rowspan='".$spanNum."'>$n</td>";
               	  $nameTD = "<td rowspan='".$spanNum."'>$name</td>";
               	  $remarkTD = "<td rowspan='".$spanNum."'>$remark</td>";
               }
               $str .= <<<EOT
			<tr align="center">
			        $num
			        $nameTD
			        <td>
			          <input type="hidden" name="budget[info][$i][name]" value="$v[name]" />
			          <input type="hidden" name="budget[info][$i][info]" value="$v[info]" />
			          <input type="hidden" name="budget[info][$i][remark]" value="$remark" />
			          $info
			        </td>
			        <td>
			          <input type="hidden" id="price$i" name="budget[info][$i][price]" value="$v[price]" />$price
			        </td>
			        <td>
			          <input type="text" class="rimless_textB" id="num$i" name="budget[info][$i][num]" />
			        </td>
			        <td>
			          <input type="text" class="rimless_textA formatMoney" readonly id="money$i" name="budget[info][$i][money]" />
			        </td>
    				$remarkTD
			</tr>
EOT;
                 $i ++;
              }
               $n ++ ;
			}
		}
		return $str;
	}

	/**
	 * Ԥ�����ϸ���� =-====�鿴
	 */
	function deployViewList($deployId){
//       $arr = $this->findAll(array("equId"=>$equId));
       $sql = "SELECT name,remark FROM `oa_equ_budget_info` where budgetId=".$deployId." GROUP BY name order by id;";
	   $arr = $this->_db->getArray($sql);
		if ($arr) {
			$i = 1; //���������
			$n = 1; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			//��һ��ѭ������ȡgroup by ����
			foreach ( $arr as $key => $val ) {
			  $name=$val['name'];
			  $remark=$val['remark'];
			   $findSql="select info,price,name,num,money from oa_equ_budget_info where budgetId=".$deployId." and name='".$name."'";
			   $infoArr = $this->_db->getArray($findSql);
              $spanNum = count($infoArr);
              $nameTemp = $name;
              //�ڶ���ѭ������ȡ��ϸ���ݣ�������ϲ���
              foreach($infoArr as $k=>$v){
              	$info = $v['info'];
              	$price = $v['price'];
              	$number = $v['num'];
              	$money = $v['money'];
               if($v['name'] == $nameTemp && $k != '0'){
               	  $num = "";
               	  $nameTD = "";
               	  $remarkTD = "";
               }else{
               	  $num = "<td rowspan='".$spanNum."'>$n</td>";
               	  $nameTD = "<td rowspan='".$spanNum."'>$name</td>";
               	  $remarkTD = "<td rowspan='".$spanNum."'>$remark</td>";
               }
               $str .= <<<EOT
			<tr align="center">
			        $num
			        $nameTD
			        <td>$info</td>
			        <td class="formatMoney">$price</td>
			        <td>$number</td>
			        <td class="formatMoney">$money</td>
    				$remarkTD
			</tr>
EOT;
                 $i ++;
              }
               $n ++ ;
			}
		}
		return $str;
	}

	/**
	 * Ԥ�����ϸ���� =-====�༭
	 */
	function deployEditList($deployId){
		$uniqid = uniqid();
//       $arr = $this->findAll(array("equId"=>$equId));
       $sql = "SELECT name,remark FROM `oa_equ_budget_info` where budgetId=".$deployId." GROUP BY name order by id;";
	   $arr = $this->_db->getArray($sql);
		if ($arr) {
			$i = 1; //���������
			$n = 1; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			//��һ��ѭ������ȡgroup by ����
			foreach ( $arr as $key => $val ) {
			  $name=$val['name'];
			  $remark=$val['remark'];
			   $findSql="select info,price,name,num,money from oa_equ_budget_info where budgetId=".$deployId." and name='".$name."'";
			   $infoArr = $this->_db->getArray($findSql);
              $spanNum = count($infoArr);
              $nameTemp = $name;
              //�ڶ���ѭ������ȡ��ϸ���ݣ�������ϲ���
              foreach($infoArr as $k=>$v){
              	$info = $v['info'];
              	$price = $v['price'];
              	$number = $v['num'];
              	$money = $v['money'];
               if($v['name'] == $nameTemp && $k != '0'){
               	  $num = "";
               	  $nameTD = "";
               	  $remarkTD = "";
               }else{
               	  $num = "<td rowspan='".$spanNum."'>$n</td>";
               	  $nameTD = "<td rowspan='".$spanNum."'>$name</td>";
               	  $remarkTD = "<td rowspan='".$spanNum."'>$remark</td>";
               }
               $str .= <<<EOT
			<tr align="center">
			        $num
			        $nameTD
			        <td>
			          <input type="hidden" name="budget[info][$uniqid][name]" value="$v[name]" />
			          <input type="hidden" name="budget[info][$uniqid][info]" value="$v[info]" />
			          <input type="hidden" name="budget[info][$uniqid][remark]" value="$remark" />
			          $info
			        </td>
			        <td>
			          <input type="hidden" id="price$uniqid" name="budget[info][$uniqid][price]" value="$v[price]" />$price
			        </td>
			        <td>
			          <input type="text" class="rimless_textB" id="num$uniqid" name="budget[info][$$uniqidi][num]" value="$v[num]"/>
			        </td>
			        <td>
			          <input type="text" class="rimless_textA formatMoney" readonly id="money$uniqid" name="budget[info][$uniqid][money]" value="$v[money]"/>
			        </td>
    				$remarkTD
			</tr>
EOT;
                 $i ++;
              }
               $n ++ ;
			}
		}
		return $str;
	}

	/**
	 * �޸�Ԥ���˵��
	 */
	function editExplain_d($object){

         $url =  WEB_TOR . 'view/template/equipment/budget/budgetExplain.txt';

		//�ļ���������
		$file = fopen($url,'w');

		fwrite($file,$object['content']);

		fclose($file);

	}

	/**
	 *  �ӱ�����
	 */
	function baseinfoList_d($ids,$type){
		if($type=='add'){
			if(!empty($ids)){
		       $sql = "SELECT * FROM oa_equ_budget_baseinfo where id in (".$ids.");";
			   $arr = $this->_db->getArray($sql);
		   	 }
		}else{
			if(!empty($ids)){
		       $sql = "SELECT * FROM oa_equ_budget_info where budgetId in (".$ids.");";
			   $arr = $this->_db->getArray($sql);
		   	 }
		}

		if ($arr) {
			$n = 1; //�б��¼���
			$i = 1;
			$str = ""; //���ص�ģ���ַ���
			foreach ( $arr as $k => $v ) {
				$uniqid = uniqid();
				$budgetType = $v['budgetTypeName'];
				$equCode = $v['equCode'];
				$equName = $v['equName'];
				$pattern = $v['pattern'];
				$brand = $v['brand'];
				$unitName = $v['unitName'];
				$quotedPrice = $v['quotedPrice'];
				$remark = $v['remark'];
				$budnum = $v['num'];
			    $money = $v['money'];
			    if($type == 'view'){
			    	$str .= <<<EOT
			<tr align="center">
			        <td class="removeBn"></td>
			        <td>$n</td>
			        <td> $budgetType </td>
			        <td> $equCode </td>
			        <td> $equName </td>
			        <td> $pattern </td>
			        <td> $brand </td>
			        <td> $unitName </td>
			        <td> $quotedPrice </td>
			        <td> $budnum </td>
			        <td class="formatMoney"> $money </td>
    				<td> $remark </td>
			</tr>
EOT;
			    }else{

			    	$str .= <<<EOT
			<tr align="center">
			        <td  class="removeBn" onclick = "mydel(this)"></td>
			        <td>$n</td>
			        <td>
			          <input type="hidden" id="budgetTypeName$i" name="budget[info][$uniqid][budgetTypeName]" value="$v[budgetTypeName]" />
			          <input type="hidden" id="budgetTypeId$i" name="budget[info][$uniqid][budgetTypeId]" value="$v[budgetTypeId]" />
			          $budgetType
			        </td>
			        <td>
			          <input type="hidden" name="budget[info][$uniqid][equId]" value="$v[equId]" />
			          <input type="hidden" name="budget[info][$uniqid][equCode]" value="$v[equCode]" />
			          $equCode
			        </td>
			        <td>
			          <input type="hidden" name="budget[info][$uniqid][equName]" value="$v[equName]" />
			          $equName
			        </td>
			        <td>
			          <input type="hidden" name="budget[info][$uniqid][pattern]" value="$v[pattern]" />
			          $pattern
			        </td>
			        <td>
			          <input type="hidden" name="budget[info][$uniqid][brand]" value="$v[brand]" />
			          $brand
			        </td>
			        <td>
			          <input type="hidden" name="budget[info][$uniqid][unitName]" value="$v[unitName]" />
			          $unitName
			        </td>
			        <td>
			          <input type="hidden" id="price$uniqid" name="budget[info][$uniqid][quotedPrice]"  value="$v[quotedPrice]"/>
			          $quotedPrice
			        </td>
			        <td>
			          <input type="text" class="rimless_textB" id="num$uniqid" name="budget[info][$uniqid][num]" onblur="countMoney();" value="$v[num]"/>
			        </td>
			        <td>
			          <input type="text" class="rimless_textA formatMoney" readonly id="money$uniqid" name="budget[info][$uniqid][money]" value="$v[money]"/>
			        </td>
    				<td>
			          <input type="hidden" name="budget[info][$uniqid][remark]" value="$v[remark]" />
			          $remark
			        </td>
			</tr>
EOT;
			    }

                 $n ++;
                $i ++;
			}
		}
		return $str;
	}

	/**
	 * �����豸Ԥ���ID��ȡԤ��������� ��Ч��ֹ���� ��С������
	 */
	function getUseEndDate($budgetId){
		$sql =  "SELECT min(o.useEndDate) as minDate FROM oa_equ_budget_info i
         left join oa_equ_budget_baseinfo o  on i.budgetTypeName = o.budgetTypeName and i.equName=o.equName  where i.budgetId = '".$budgetId."'";
        $minDateArr = $this->_db->getArray($sql);
        return $minDateArr[0]['minDate'];
	}

}
?>