<?php
/*
 * Created on 2010-6-24
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_contract_equipment_equipment extends model_base{
	function __construct(){
		$this->tbl_name = "oa_contract_sales_equ";
		$this->sql_map = "contract/equipment/equipmentSql.php";
		parent::__construct();
	}


	/**
	 * 处理合同时显示设备
	 */
	function showDetailByEC($rows){
		$i = 0;
		if ($rows) {
			$str = "";
			foreach ($rows as $val) {
				$val['lockNum']=isset($val['lockNum'])?$val['lockNum']:0;
				$canUseNum = $val['exeNum'] + $val['canCarryAmount'];
				if($val['beforeChangeAmount'] == $val['amount']){
						$str .=<<<EOT
							<tr align="center">
							<td>
						<input type="hidden" id="productId$i" value="$val[productId]" />
								$val[productNumber]/<br/>
								$val[productName]

							<input type="hidden" value="$val[contId]" name="lock[$i][objId]"/>
							<input type="hidden" value="$val[contNumber]" name="lock[$i][objCode]"/>
							<input type="hidden" value="$val[contOnlyId]" name="lock[$i][objEquId]" id="equId$i"/>
							<input type="hidden" value="sales" name="lock[$i][objType]"/></td>
							<input type="hidden" value="$val[productId]" name="lock[$i][productId]"/>
							<input type="hidden" value="$val[productName]" name="lock[$i][productName]"/>
							<input type="hidden" value="$val[productNumber]" name="lock[$i][productNo]"/>
							<input type="hidden" value="" id="inventoryId$i" name="lock[$i][inventoryId]"/>
								</td>
							<td width="8%">$val[beforeChangeAmount]</td>
							<td width="8%"><div id="amount$i">$val[amount]</div></td>
							<td width="8%">$val[alreadyCarryAmount]</td>
							<td width="8%">$val[canCarryAmount]</td>
							<td width="8%"><font color="red"><div id="actNum$i">0</div></td>
							<td width="8%"><font color="red"><div id="exeNum$i">0</div></font></td>
							<td width="8%">
								<font color="red">
							     	<a href="javascript:toLockRecordsPage('$val[contOnlyId]',true)">
							     		<div id="stockLockNum$i">0</div>
							     	</a>
							     </font>
							</td>
							<td width="8%">
								<font color="red">
							     	<a href="javascript:toLockRecordsPage('$val[contOnlyId]',false)">
							     		<div id="lockNum$i"> $val[lockNum]</div>
							     	</a>
							     </font>
							</td>
							<td width="8%">0</td>
							<td width="8%">$val[amountIssued]</td>
							<td width="8%"><input type="text" value="0" name="lock[$i][lockNum]" class="txtshort" onblur="checkLockNum(this,$i)"/></td>
							</tr>
EOT;
					$i++;
				}
			}
		}
		return $str;
	}

	/**
	 *  锁定时显示设备信息
	 */
	function showDetailByLock($rows){
		$i = 0;
		if ($rows) {
			$str = "";
			foreach ($rows as $val) {
				$str .=<<<EOT
					<tr align="center">
						<td>
							$val[productNumber]
							<input type="hidden" value="$val[contId]" name="lock[$i][objId]"/></td>
							<input type="hidden" value="$val[contNumber]" name="lock[$i][objCode]"/></td>
							<input type="hidden" value="sales" name="lock[$i][objType]"/></td>
							<input type="hidden" value="$val[productId]" name="lock[$i][productId]"/></td>
							<input type="hidden" value="$val[productName]" name="lock[$i][productName]"/></td>
							<input type="hidden" value="$val[productNumber]" name="lock[$i][productNo]"/></td>
							<input type="hidden" value="$val[amount]" id="amount$i"/></td>
							<input type="hidden" value="$val[exeNum]" id="exeNum$i"/></td>
						</td>
						<td>$val[productName]</td>
						<td>$val[amount]</td>
						<td>$val[alreadyCarryAmount]</td>
						<td>
							<input type="text" value="$val[notCarryAmount]" name="lock[$i][lockNum]" class="txtshort"/></td>
						<td>$val[exeNum]</td>
						<td>$val[actNum]</td>
					</tr>
EOT;
			}
		}
		return $str;
	}

	/**
	 * 批量插入设备清单
	 */
	function insertRows($id,$contNumb,$contName,$version,$rows){
		$str="insert into ".$this->tbl_name.
				 " (contId,contNumber,contName,version,productNumber,productName,productId,productModel,ptype,amount,price,countMoney,projArraDate,warrantyPeriod,productLine,isSell,beforeChangeAmount) values ";
		$strsuit = "insert into ".$this->tbl_name.
				 " (contId,contNumber,contName,version,productNumber,productName,productId,productModel,ptype,amount,price,countMoney,projArraDate,warrantyPeriod,higherList,beforeChangeAmount) values ";
		foreach($rows as $key => $val){
			if($val['productNumber']!="" && $val['productName']!=""){
				$actstr = $str . " ('$id','$contNumb','$contName','$version','$val[productNumber]','$val[productName]','$val[productId]','$val[productModel]','$val[ptype]','$val[amount]','$val[price]','$val[countMoney]','$val[projArraDate]','$val[warrantyPeriod]','$val[productLine]','$val[isSell]','$val[beforeChangeAmount]') ";
				$highid = $this->query($actstr);
			}
		}
	}

	/**
	 * 根据合同ID和合同编号删除产品清单
	 */
	function delectByIdAndNumber($id,$contNumber){
		try{
			$rows = $this->delete(array( 'contId' => $id, 'contNumber' => $contNumber ));
			return $rows;
		}catch(exception $e){
			throw $e;
			return false;
		}
	}


	/**
	 * 设备列表-在执行销售合同-执行中的合同
	 * 根据合同编号获取设备列表
	 */
	function showEquListInCont($rows){
		$str = "";
		foreach($rows as $key => $val){
			$str .= $val['id'] . ",";
		}
		$str = substr($str,0,-1);
		$sql = 'select e.contId,e.contNumber,e.productName,e.productNumber,e.beforeChangeAmount,e.amountIssued,e.amount,e.alreadyCarryAmount,e.byWayAmount,e.canCarryAmount,e.stockAmount,e.notCarryAmount,s.actNum,s.exeNum from ' .$this->tbl_name .' e left join oa_stock_inventory_info s on e.productId = s.productId where s.stockCode = \'SALES\' and e.contId in( '. $str . ' )';
		return $this->_db->getArray($sql);
	}

	/**
	 *
	 * 设备列表-处理合同
	 * 根据合同编号获取设备列表
	 */
	function showEquListInByEC($contId){
		$sql = 'select e.contId,e.contOnlyId,e.contNumber,e.productId,e.productName,e.productNumber,e.beforeChangeAmount,e.amountIssued,e.amount,e.alreadyCarryAmount,e.byWayAmount,e.canCarryAmount,e.stockAmount,e.notCarryAmount,s.actNum,s.exeNum from ' .$this->tbl_name .' e left join oa_stock_inventory_info s on e.productId = s.productId where s.stockCode = \'SALES\' and e.contId = '.$contId;
		$equs= $this->_db->getArray($sql);
		//获取设备已锁定数量
		$lockDao=new model_stock_lock_lock();
		foreach($equs as $key => $val){
			$lockNum=$lockDao->getEquStockLockNum($val['contOnlyId']);
			$equs[$key]['lockNum']=$lockNum;
		}
		return $equs;
	}

	 /**
	  * 根据合同号和合同ID获取产品清单
	  */
	function showEquipmentList($contractId){
		return $this->findAll(array( 'contid' => $contractId ));
	}

	/**
	 * 根据设备标识contOnlyId更新设备数量
	 * 传入数组
	 * 数量增加：add 数量减少 minus
	 * 已执行数量 alreadyCarryAmount 可执行数量 canCarryAmount
	 * 未执行数量 notCarryAmount 在途数量 byWayAmount
	 * 已采购数量 alreadyStockAmount 已下达数量 alreadyFlwgfollowing
	 *
	 */
	function updateEquipmentQuantity($object,$selectType='add'){
//		print_r($object);
		$contOnlyId =  $object['contOnlyId'];
		unset($object['contOnlyId']);
		foreach ( $object as $key => $value ) {
			$value = $this->__val_escape ( $value );
			if($selectType == 'add'){
				$vals [] = "{$key} = {$key} + '{$value}'";
			}else if($selectType == 'minus'){
				$vals [] = "{$key} = {$key} - '{$value}'";
			}
		}
		$values = join ( ", ", $vals );
		$sql = "UPDATE {$this->tbl_name} SET {$values} where contOnlyId = '$contOnlyId'";
//		echo $sql;
		return $this->_db->query ( $sql );
	}

	/**
	 * 根据合同编号更新设备数量
	 */
	function updateNumberByContNumber($object,$selectType='add',$productId){
//		$contOnlyId =  $object['contOnlyId'];
		$formCode = $object['contNumber'];
		unset($object['contNumber']);
		foreach ( $object as $key => $value ) {
			$value = $this->__val_escape ( $value );
			if($selectType == 'add'){
				$vals [] = "{$key} = {$key} + '{$value}'";
			}else if($selectType == 'minus'){
				$vals [] = "{$key} = {$key} - '{$value}'";
			}
		}
		$values = join ( ", ", $vals );
		$sql = "UPDATE oa_contract_sales s left join oa_contract_sales_equ e on s.id = e.contId SET {$values} where s.contNumber = '$formCode' and s.isUsing = 1 and e.productId = $productId";
//		echo $sql;
		return $this->_db->query ( $sql );
	}

	/**
	 * 根据设备标识号equipListId改变设备某数量
	 * 传入数组
	 * 数量增加：add 数量减少 minus
	 * 已执行数量 alreadyCarryAmount
	 * 未执行数量 notCarryAmount
	 * 在途数量 byWayAmount
	 * 采购完成数量 canCarryAmount
	 * 已采购数量 alreadyStockAmount
	 * 已下达数量 alreadyFlwgfollowing
	 */
	function updateNumberByEquipListId($object,$selectType='add'){
		$equipListId =  $object['equipListId'];
		$version =  $object['version'];
		unset($object['equipListId']);
		unset($object['version']);
		foreach ( $object as $key => $value ) {
			$value = $this->__val_escape ( $value );
			if($selectType == 'add'){
				$vals [] = "{$key} = {$key} + '{$value}'";
			}else if($selectType == 'minus'){
				$vals [] = "{$key} = {$key} - '{$value}'";
			}
		}
		$values = join ( ", ", $vals );
		$sql = "UPDATE {$this->tbl_name} SET {$values} where equipListId = '$equipListId' and version = '$version'";
		return $this->_db->query ( $sql );
	}

	/**
	 * 对设备清单的数量进行初始化
	 */
	function initialize($contId){
		$rows = $this->showEquipmentList($contId);
		$change = new model_contract_change_change();
		$isChange = $change->isChange($contId);
		if($isChange){
			$oldContId = $change->getOldContId($contId);
			$oldrows = $this->showEquipmentList($oldContId);
		}
		foreach($rows as $val){

			//有变更初始化
			if($isChange){
				foreach($oldrows as $temprows){
					if($temprows['productNumber']==$val['productNumber']){
						$val['beforeChangeAmount'] = $temprows['amount'];

						//初始化contOnlyId
						$val['contOnlyId'] = $temprows['contOnlyId'];
						$temprows['contOnlyId'] = "lost";
						parent :: updateById($temprows);
						$val['equipListId'] = $temprows['equipListId'];
						//需求数量不变

						//初始化已执行数量
						$val['alreadyCarryAmount'] = $temprows['alreadyCarryAmount'];
						//初始化未执行数量
						if($val['amount'] > $temprows['amount'] ){
							$val['notCarryAmount'] = $temprows['notCarryAmount'] + ( $val['amount'] - $temprows['amount'] );
						}else if($val['amount'] < $temprows['amount']){
							$val['notCarryAmount'] = $temprows['notCarryAmount'] - ( $temprows['amount'] - $val['amount'] );
						}else{
							$val['notCarryAmount'] = $temprows['notCarryAmount'];
						}
						//初始化在途数量
						$val['byWayAmount'] = $temprows['byWayAmount'];
						//初始化可执行数量
						$val['canCarryAmount'] = $temprows['canCarryAmount'];

						$val['alreadyFlwgfollowing'] = $temprows['alreadyFlwgfollowing'];
						//初始化实际下达数量
						$val['amountIssuedActual'] = $temprows['amountIssuedActual'];
						//初始化下达数量
						$val['amountIssued'] = $temprows['amountIssued'];
						$val['storageId'] = $temprows['storageId'];
						break;
					}else{
						$val['contOnlyId'] = $val['equipListId'] = "salesEquipment-".microtime();
//						$val['beforeChangeAmount'] = $val['amount'];
						$val['beforeChangeAmount'] = 0;
						//需求数量不变
						//初始化已执行数量
						$val['alreadyCarryAmount'] = 0;
						//初始化未执行数量
						$val['notCarryAmount'] = $val['amount'];
						//初始化在途数量
						$val['byWayAmount'] = 0;
						//初始化可执行数量
						$val['canCarryAmount'] = 0;

						$val['alreadyFlwgfollowing'] = 0;

						//初始化实际下达数量
						$val['amountIssuedActual'] = 0;
						//初始化下达数量
						$val['amountIssued'] = 0;
						$val['storageId'] = 0;
					}
				}

			}else{//无变更初始化
				$val['contOnlyId'] = $val['equipListId'] =  "salesEquipment-".microtime();
				//变更前数量
				$val['beforeChangeAmount'] = $val['amount'];
				//需求数量不变

				//初始化已执行数量
				$val['alreadyCarryAmount'] = 0;
				//初始化未执行数量
				$val['notCarryAmount'] = $val['amount'];
				//初始化在途数量
				$val['byWayAmount'] = 0;
				//初始化可执行数量
				$val['canCarryAmount'] = 0;

				$val['alreadyFlwgfollowing'] = 0;

				//初始化实际下达数量
				$val['amountIssuedActual'] = 0;
				//初始化下达数量
				$val['amountIssued'] = 0;
				$val['storageId'] = 0;
			}
			try{
				$this->updateById($val);
			}catch(exception $e){
				throw $e;
			}

		}
	}

	/**
	 * 统计同一合同ID的产品清单中同一个ID的产品的数量
	 */
	function getProductsAmount($contId,$contNumber=null) {
		$sql = "select amount,Count(amount),sum(amount),productNumber from oa_contract_sales_equ where contId = '$contId' Group By productNumber";
		return $this->findSql($sql);
	}

	/**
	 * 显示合同设备列表-合同信息
	 */
	function showlistInfo($rows) {
		$i = 0;
		$j=0;
//		print_r($rows);
		if ($rows) {
			$str = "";
			$equipDatadictDao = new model_system_datadict_datadict ();
			foreach ($rows as $val) {
				if(!empty($val['isSell'])){
					$checked = '是';
				}else{
					$checked = '否';
				}
				$str .=<<<EOT
					<tr align="center">
EOT;
				$i++;
				$str.="<td width='5%'>$i</td>";

				$productLine = $equipDatadictDao->getDataNameByCode($val['productLine']);

				if($val['beforeChangeAmount'] == $val['amount']){
					$str.=<<<EOT
						<td width="9%">$productLine</td>
						<td width="10%">$val[productNumber]</td>
						<td width="16%">$val[productName]</td>
						<td width="9%">$val[productModel]</td>
						<td width="8%">$val[amount]</td>
						<td width="8%" class="formatMoney">$val[price]</td>
						<td width="8%" class="formatMoney">$val[countMoney]</td>
						<td width="10%">$val[projArraDate]</td>
						<td width="10%">$val[warrantyPeriod] </td>
						<td>$checked </td>
					</tr>
EOT;
				}else{
					$str.=<<<EOT
						<td width="9%">$productLine</td>
						<td width="10%">$val[productNumber]</td>
						<td width="16%">$val[productName]</td>
						<td width="9%">$val[productModel]</td>
						<td width="8%" title="变更前：$val[beforeChangeAmount]， 变更后：$val[amount]"><font color='red'>$val[amount]</font></td>
						<td width="8%" class="formatMoney">$val[price]</td>
						<td width="8%" class="formatMoney">$val[countMoney]</td>
						<td width="10%">$val[projArraDate]</td>
						<td width="10%">$val[warrantyPeriod] </td>
						<td>$checked </td>
					</tr>
EOT;
				}

			}
		}else{
			return '<tr align="center"><td colspan="11">暂无相关内容</td></tr>';
		}
		return $str;
	}

	/**
	 * 显示合同设备列表-在执行的销售合同-执行中的合同
	 */
	function showlist($word,$rows) {
		$i = 0;
		if ($rows) {
			$str = "";
			foreach ($rows as $val) {
				if($word==$val['contNumber']){
					$canUseNum = $val['exeNum'] + $val['canCarryAmount'];

					if($val['beforeChangeAmount'] == $val['amount']){
						$str .=<<<EOT
							<tr align="center">
							<td>$val[productNumber]/<br/>
								$val[productName]</td>
							<td width="8%">$val[beforeChangeAmount]</td>
							<td width="8%">$val[amount]</td>
							<td width="8%">$val[alreadyCarryAmount]</td>
							<td width="8%">$val[byWayAmount]</td>
							<td width="8%" title="合同可执行数：$val[canCarryAmount] 仓库可执行数：$val[exeNum]">$canUseNum</td>
							<td width="8%">$val[actNum]</td>
							<td width="8%">$val[notCarryAmount]</td>
							<td width="8%">$val[amountIssued]</td>
							</tr>
EOT;
					}else{
						$str .=<<<EOT
							<tr align="center">
							<td><font color='red'>
								$val[productNumber]/<br/>
								$val[productName]
								</font></td>
							<td width="8%"><font color='red'>$val[beforeChangeAmount]</font></td>
							<td width="8%"><font color='red'>$val[amount]</font></td>
							<td width="8%">$val[alreadyCarryAmount]</font></td>
							<td width="8%">$val[byWayAmount]</td>
							<td width="8%" title="合同可执行数：$val[canCarryAmount] 仓库可执行数：$val[exeNum]">$canUseNum</td>
							<td width="8%">$val[actNum]</td>
							<td width="8%">$val[notCarryAmount]</td>
							<td width="8%">$val[amountIssued]</td>
							</tr>
EOT;
					}

				}
			}
		}
		return $str;
	}

	/**
	 * 更新可执行数量
	 */
	function updateCanCarryAmount($amount,$productNumber,$storageId){
		return $this->query("update oa_contract_sales_equ e,oa_contract_sales s set e.canCarryAmount = e.canCarryAmount - $amount  where e.storageId = '$storageId' and e.productNumber = '$productNumber'  and  s.ExaStatus = '正执行' and e.contNumber = s.contNumber");
	}

	/**
	 * 入库时，增加合同产品的可执行数量
	 */
	 function updateProExeNum($contOnlyId,$changeNum){
			return $this->query("update oa_contract_sales_equ set canCarryAmount=canCarryAmount+$changeNum where contOnlyId='$contOnlyId'");
	 }

	/**
	 * 编辑时显示合同设备
	 */
	function showlistInEdit($rows) {
		$i = 0;
		$str = "";
		if ($rows) {
			//产品线数据字典
			$datadictArr = $this->getDatadicts ( "CPX" );
			foreach ($rows as $val) {
				$tvalue1 = $tvalue2 = $tvalue3 = $tvalue4 = "";
				if($val['warrantyPeriod']=="半年") {
					$tvalue1 = "selected";
				}elseif($val['warrantyPeriod']=="一年"){
					$tvalue2 = "selected";
				}elseif($val['warrantyPeriod']=="两年") {
					$tvalue3 = "selected";
				}else{
					$tvalue4 = "selected";
				}

				if(!empty($val['isSell'])){
					$checked = 'checked="checked"';
				}else{
					$checked = null;
				}
				$i++;
				$productLineStr = $this->getDatadictsStr ( $datadictArr ['CPX'], $val ['productLine'] );
				$str .=<<<EOT
					<tr><td>$i</td>
					 	<td>
					 		<select class="txtshort" name="sales[equipment][$i][productLine]">
					 			$productLineStr
					 		</select>
					 	</td>
					 	<td>
					 		<input type="text" name="sales[equipment][$i][productNumber]" id="EquId$i" value="$val[productNumber]" class="txtshort"/>
					 		<input type="hidden" name="sales[equipment][$i][ptype]" value="soft"/>
					 	</td>
					 	<td>
					        <input type="text" name="sales[equipment][$i][productName]" id="EquName$i" value="$val[productName]" class="txtmiddle"/>
					        <input type="hidden" name="sales[equipment][$i][productId]" id="ProductId$i" value="$val[productId]" />
					 	</td>
					 	<td>
					 		<input type="text" name="sales[equipment][$i][productModel]" id="EquModel$i" value="$val[productModel]" class="txtshort"/>
					 	</td>
					 	<td>
					 		<input type="text" name="sales[equipment][$i][amount]" id="EquAmount$i" value="$val[amount]" onblur="FloatMul('EquAmount$i','EquPrice$i','EquAllMoney$i')" class="txtshort"/>
					 	</td>
					 	<td>
					 		<input type="text" name="sales[equipment][$i][price]" id="EquPrice$i" value="$val[price]" onblur="FloatMul('EquAmount$i','EquPrice$i','EquAllMoney$i')" class="formatMoney txtshort"/>
					 	</td>
					 	<td>
					 		<input type="text" name="sales[equipment][$i][countMoney]" id="EquAllMoney$i" value="$val[countMoney]" class="formatMoney txtshort"/>
					 	</td>
					 	<td>
					        <input type="text" name="sales[equipment][$i][projArraDate]" id="EquDeliveryDT$i" value="$val[projArraDate]" onfocus="WdatePicker();" class="txtshort"/>
					    </td>
					 	<td>
					 		<select name="sales[equipment][$i][warrantyPeriod]" id="warrantyPeriod$i" class="txtshort">
					 			<option value="半年" $tvalue1>半年</option>
					 			<option value="一年" $tvalue2>一年</option>
					 			<option value="两年" $tvalue3>两年</option>
					 			<option value="三年" $tvalue4>三年</option>
					 		</select>
					 	</td>
					 	<td>
					        <input type="checkbox" name="sales[equipment][$i][isSell]" id="isSell$i" $checked/>
					    </td>
					 	<td>
							<img src="images/closeDiv.gif" onclick="mydel(this,'myequ')" title="删除行"/>
					 	</td>
					</tr>
EOT;
			}
		}
		return array($i,$str);
	}
 /**
  * 订单签订合同---设备信息
  */
function orderListEqu($rows) {
		$i = 0;
		$str = "";
		if ($rows) {
			//产品线数据字典
			$datadictArr = $this->getDatadicts ( "CPX" );
			foreach ($rows as $val) {
				$tvalue1 = $tvalue2 = $tvalue3 = $tvalue4 = "";
				if($val['warrantyPeriod']=="半年") {
					$tvalue1 = "selected";
				}elseif($val['warrantyPeriod']=="一年"){
					$tvalue2 = "selected";
				}elseif($val['warrantyPeriod']=="两年") {
					$tvalue3 = "selected";
				}else{
					$tvalue4 = "selected";
				}

				if(!empty($val['isSell'])){
					$checked = 'checked="checked"';
				}else{
					$checked = null;
				}
				$i++;
				$productLineStr = $this->getDatadictsStr ( $datadictArr ['CPX'], $val ['productLine'] );
				$str .=<<<EOT
					<tr><td>$i</td>
					 	<td>
					 		<select class="txtshort" name="order[equipment][$i][productLine]">
					 			$productLineStr
					 		</select>
					 	</td>
					 	<td>
					 		<input type="text" name="order[equipment][$i][productNumber]" id="EquId$i" value="$val[productNo]" class="txtshort"/>
					 		<input type="hidden" name="order[equipment][$i][ptype]" value="soft"/>
					 	</td>
					 	<td>
					        <input type="text" name="order[equipment][$i][productName]" id="EquName$i" value="$val[productName]" class="txtmiddle"/>
					        <input type="hidden" name="order[equipment][$i][productId]" id="ProductId$i" value="$val[productId]" />
					 	</td>
					 	<td>
					 		<input type="text" name="order[equipment][$i][productModel]" id="EquModel$i" value="$val[productModel]" class="txtshort"/>
					 	</td>
					 	<td>
					 		<input type="text" name="order[equipment][$i][amount]" id="EquAmount$i" value="$val[number]" onblur="FloatMul('EquAmount$i','EquPrice$i','EquAllMoney$i')" class="txtshort"/>
					 	</td>
					 	<td>
					 		<input type="text" name="order[equipment][$i][price]" id="EquPrice$i" value="$val[price]" onblur="FloatMul('EquAmount$i','EquPrice$i','EquAllMoney$i')" class="formatMoney txtshort"/>
					 	</td>
					 	<td>
					 		<input type="text" name="order[equipment][$i][countMoney]" id="EquAllMoney$i" value="$val[money]" class="formatMoney txtshort"/>
					 	</td>
					 	<td>
					        <input type="text" name="order[equipment][$i][projArraDate]" id="EquDeliveryDT$i" value="$val[projArraDate]" onfocus="WdatePicker();" class="txtshort"/>
					    </td>
					 	<td>
					 		<select name="order[equipment][$i][warrantyPeriod]" id="warrantyPeriod$i" class="txtshort">
					 			<option value="半年" $tvalue1>半年</option>
					 			<option value="一年" $tvalue2>一年</option>
					 			<option value="两年" $tvalue3>两年</option>
					 			<option value="三年" $tvalue4>三年</option>
					 		</select>
					 	</td>
					 	<td>
					        <input type="checkbox" name="order[equipment][$i][isSell]" id="isSell$i" $checked/>
					    </td>
					 	<td>
							<img src="images/closeDiv.gif" onclick="mydel(this,'myequ')" title="删除行"/>
					 	</td>
					</tr>
EOT;
			}
		}
		return array($i,$str);
	}

	/**
	 * 显示合同产品清单-变更时用
	 */
	function showlistInChange($rows,$equipRow = null) {
		$i = 0;
		$str = "";
		if ($rows) {
			//产品线数据字典
			$datadictArr = $this->getDatadicts ( "CPX" );
			foreach ($rows as $key => $val) {
				$tvalue1 = $tvalue2 = $tvalue3 = $tvalue4 = "";
				if($val['warrantyPeriod']=="半年") {
					$tvalue1 = "selected";
				}elseif($val['warrantyPeriod']=="一年"){
					$tvalue2 = "selected";
				}elseif($val['warrantyPeriod']=="两年") {
					$tvalue3 = "selected";
				}else{
					$tvalue4 = "selected";
				}

				if(!empty($val['isSell'])){
					$checked = 'checked="checked"';
				}else{
					$checked = null;
				}

				if($equipRow){
					$beforeChangeAmount = isset($equipRow[$key]['amount'])?$equipRow[$key]['amount']:0;
				}else{
					$beforeChangeAmount = $val['amount'];
				}
				$productLineStr = $this->getDatadictsStr ( $datadictArr ['CPX'], $val ['productLine'] );
				$i++;
				$str .=<<<EOT
					<tr>
					 	<td>
					 		$i
					 	</td>
					 	<td>
					 		<select name="sales[equipment][$i][productLine]" />
					 			$productLineStr
					 		</select>
					 	</td>
					 	<td>
					 		<input type="text" name="sales[equipment][$i][productNumber]" id="EquId$i" value="$val[productNumber]" class="txtshort"/>
					 		<input type="hidden" name="sales[equipment][$i][ptype]" value="soft"/>
					 	</td>
					 	<td>
					        <input type="text" name="sales[equipment][$i][productName]" id="EquName$i" value="$val[productName]" class="txtmiddle">
					        <input type="hidden" name="sales[equipment][$i][productId]" id="ProductId$i" value="$val[productId]" />
					 	</td>
					 	<td>
					 		<input type="text" name="sales[equipment][$i][productModel]" id="EquModel$i" value="$val[productModel]" class="txtshort"/>
					 	</td>
					 	<td>
					 		<input type="text" name="sales[equipment][$i][amount]" id="EquAmount$i" value="$val[amount]" onblur="FloatMul('EquAmount$i','EquPrice$i','EquAllMoney$i')" class="txtshort"/>
					 		<input type="hidden" name="sales[equipment][$i][beforeChangeAmount]" value="$beforeChangeAmount"/>
					 	</td>
					 	<td>
					 		<input type="text" name="sales[equipment][$i][price]" id="EquPrice$i" value="$val[price]" onblur="FloatMul('EquAmount$i','EquPrice$i','EquAllMoney$i')" class="txtshort formatMoney"/>
					 	</td>
					 	<td>
					 		<input type="text" name="sales[equipment][$i][countMoney]" id="EquAllMoney$i" value="$val[countMoney]" class="txtshort formatMoney"/>
					 	</td>
					 	<td>
					        <input type="text" name="sales[equipment][$i][projArraDate]" id="EquDeliveryDT$i" value="$val[projArraDate]" onfocus="WdatePicker();" class="txtshort"/>
					    </td>
					 	<td>
					 		<select name="sales[equipment][$i][warrantyPeriod]" id="warrantyPeriod$i" class="txtshort">
					 			<option value="半年" $tvalue1>半年</option>
					 			<option value="一年" $tvalue2>一年</option>
					 			<option value="两年" $tvalue3>两年</option>
					 			<option value="三年" $tvalue4>三年</option>
					 		</select>
					 	</td>
					 	<td>
					        <input type="checkbox" name="sales[equipment][$i][isSell]" id="isSell$i" $checked/>
					    </td>
					 	<td>
					 	</td>
					 </tr>
EOT;
			}
		}
		return array($i,$str);
	}

	/**
	 * @exclude 更新合同设备已下达数量
	 * @author ouyang
	 * @param contOnlyId 合同设备id
	 * @param $issuedNum 下达数量
	 * @param $lastIssueNum 还原数量
	 * @return
	 * @version 2010-8-10 下午06:27:28
	 */
	function updateAmountIssued($contOnlyId,$issuedNum,$lastIssueNum=false){
		if(isset($lastIssueNum)&&$issuedNum==$lastIssueNum){
			return true;
		}else{
			if($lastIssueNum){
				$sql = " update ".$this->tbl_name." set amountIssued=ifnull(amountIssued,0)  + $issuedNum - $lastIssueNum where contOnlyId='$contOnlyId'  ";
			}else{
				$sql = " update ".$this->tbl_name." set amountIssued=ifnull(amountIssued,0) + $issuedNum where contOnlyId='$contOnlyId' ";
			}
			return $this->query($sql);
		}
	}
}
?>
