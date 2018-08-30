<?php
/**
 * @author Administrator
 * @Date 2011年9月14日 11:54:36
 * @version 1.0
 * @description:新增赠送产品清单 Model层
 */
 class model_projectmanagent_present_presentequ  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_present_equ";
		$this->sql_map = "projectmanagent/present/presentequSql.php";
		parent::__construct ();
	}


	/**
	 * 渲染查看页面内从表
	 */
	function initTableView($object,$objId){
		//获取最近一次变更审批的明细记录
			   $dao = new model_common_changeLog();
			   $changeInfo = $dao->getLastDetails("present",$objId);

               $detailId =array();
			   foreach($changeInfo as $k => $v){
			   	  if(!empty($v['detailId'])){
                      $detailId[$v['detailId']] = $k;
			   	  }
			   }
			   if(!empty($detailId)){
                 foreach($detailId as $k => $v){
	                $sql = "select * from oa_present_equ where id = '".$k."' ";
	              	$chIn = $this->_db->getArray($sql);
	              	foreach($chIn as $k => $v){
                       if($v['isDel'] == '1'){
                            $object = array_merge($object,$chIn);
                       }
                    }
			    }
			   }
		$str = "";
		$equipDatadictDao = new model_system_datadict_datadict ();
		$i = 0;
		foreach($object as $key => $val ){
			$i ++ ;
               if(empty($val['License'] )){
               		$license = "";
               }else{
               		$license = "<input type='button' class='txt_btn_a' value='配置' onclick='" .
               				"showThickboxWin(\"?model=yxlicense_license_tempKey&action=toViewRecord&id=".$val['License']."" .
               						"&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900\")'/>";
               }

          if($val['changeTips'] != '0'){
          	  if($val['isDel'] == '1'){
          	  	   $str .=<<<EOT
					<tr style="background:#C8C8C8">
						<td width="5%">$i</td>
						<td>$val[productNo]</td>
						<td><span class="red" >$val[productName]</span></td>
						<td>$val[productModel]</td>
						<td>$val[number]</td>
						<td>$val[unitName]</td>
						<td class="formatMoney">$val[price]</td>
						<td class="formatMoney">$val[money]</td>
						<td>$val[warrantyPeriod]</td>
						<td>$license</td>
					</tr>
EOT;
          	  }else{
          	  	  $str .=<<<EOT
					<tr>
						<td width="5%">$i</td>
						<td>$val[productNo]</td>
						<td><span class="red" >$val[productName]</span></td>
						<td>$val[productModel]</td>
						<td>$val[number]</td>
						<td>$val[unitName]</td>
						<td class="formatMoney">$val[price]</td>
						<td class="formatMoney">$val[money]</td>
						<td>$val[warrantyPeriod]</td>
						<td>$license</td>
					</tr>
EOT;
          	  }
          }else{
          	  $str .=<<<EOT
					<tr>
						<td width="5%">$i</td>
						<td>$val[productNo]</td>
						<td>$val[productName]</td>
						<td>$val[productModel]</td>
						<td>$val[number]</td>
						<td>$val[unitName]</td>
						<td class="formatMoney">$val[price]</td>
						<td class="formatMoney">$val[money]</td>
						<td>$val[warrantyPeriod]</td>
						<td>$license</td>
					</tr>
EOT;
          }
		}

		return $str;
	}


	/**
	 * 渲染编辑页面从表
	 */
	function initTableEdit($object){
		$str = "";
		$i = 0;

		foreach($object as $key => $val ){
			//产品线数据字典
			$datadictArr = $this->getDatadicts ( "CPX" );
			$i ++ ;
				$str .=<<<EOT
					<tr><td width="5%">$i
						</td>
						<td>
			                <input type="text" name="present[presentequ][$i][productNo]" id="productNo$i" class="txtmiddle" value="$val[productNo]"/>
			            </td>
			            <td>
			            	<input type="hidden" id="productId$i" name="present[presentequ][$i][productId]" value="$val[productId]"/>
			                <input type="text" name="present[presentequ][$i][productName]" id="productName$i" class="txt"  value="$val[productName]"/>
			            </td>
			            <td>
			                <input type="text" name="present[presentequ][$i][productModel]" id="productModel$i" class="txtmiddle" readonly="readonly" value="$val[productModel]"/>
			            </td>
			            <td>
			                <input type="text" name="present[presentequ][$i][number]"  id="number$i" class="txtshort" value="$val[number]"/>
			            </td>
			            <td>
			                <input type="text" name="present[presentequ][$i][unitName]" id="unitName$i" class="txtshort" value="$val[unitName]" />
			            </td>
			            <td>
                            <input type="text" name="present[presentequ][$i][price]" id="price$i" class="txtshort formatMoney" onblur="FloatMul('number1','price1','money1')" value="$val[price]"/>
                        </td>
                        <td>
                            <input type="text" name="present[presentequ][$i][money]" id="money$i" class="txtshort formatMoney" value="$val[money]"/>
                        </td>

				        <td nowrap width="8%">
					       <input type="text" class="txtshort" name="present[presentequ][$i][warrantyPeriod]" id="warrantyPeriod$i" value="$val[warrantyPeriod]" />
						</td>
                        <td>
							<input type="hidden" id="licenseId$i" name="present[presentequ][$i][License]" value="$val[License]"/>
		 			        <input type="button" class="txt_btn_a" value="配置" onclick="License('licenseId$i');" />
		 			      </td>
			            <td>
			                <img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="删除行"/>
			            </td>
					</tr>
EOT;
		}
		return array($i,$str);
	}

	/**
	 * 根据项目id获取产品列表
	 */
	function getDetail_d($presentId){
		$this->searchArr['presentId'] = $presentId;
//		$this->searchArr['isDel'] = '0';
//		$this->searchArr['isTemp'] = 0;
		$this->asc = false;
		return $this->list_d();
	}


	/**
	 * 根据合同ID 获取从表数据
	 */
	function getByProId_d($contractId) {
		$this->searchArr['conProductId'] = $contractId;
		$this->searchArr['isDel'] = 0;
		$this->searchArr['isTemp'] = 0;
		$this->asc = false;
		return $this->list_d();
	}

	/**更新已下达采购数量
	 *author zengzx
	 *2011年9月19日 17:36:40
	 */
	function updateAmountIssued($id, $issuedNum, $lastIssueNum = false) {
		if (isset ( $lastIssueNum ) && $issuedNum == $lastIssueNum) {
			return true;
		} else {
			if ($lastIssueNum) {
				$sql = " update " . $this->tbl_name . " set issuedPurNum=(ifnull(issuedPurNum,0)  + $issuedNum - $lastIssueNum) where id='$id'  ";
			} else {
				$sql = " update " . $this->tbl_name . " set issuedPurNum=ifnull(issuedPurNum,0) + $issuedNum where id='$id' ";
			}
			return $this->query ( $sql );
		}
	}


	/**
	 *
	 * 设备列表-处理订单
	 * 根据订单编号获取设备列表
	 * @param zengzx
	 * 2011年9月19日 18:49:22
	 */
	function showEquListInByOrder($id,$docType){
		$sql = 'select e.id,e.presentId,e.presentCode,e.productLine,e.productName,e.isDel,e.productId,e.productNo,e.productModel,e.productType,e.number,e.price,e.money,e.warrantyPeriod,e.executedNum,e.onWayNum,e.purchasedNum,e.issuedPurNum,e.uniqueCode from ' .$this->tbl_name .' e  where e.presentId = '.$id;
		$equs= $this->_db->getArray($sql);
		//获取设备已锁定数量
//		echo "<pre>";
//		print_R( $equs );
		$lockDao=new model_stock_lock_lock();
		foreach($equs as $key => $val){
			$lockNum=$lockDao->getEquStockLockNum($val['id'],null,$docType);
			$equs[$key]['lockNum']=$lockNum;
		}

		return $equs;
	}

	/**
	 * 处理订单时显示设备
	 * @param zengzx
	 * 2011年9月19日 18:49:22
	 */
	function showDetailByOrder($rows){
		$i = 0;
		if ($rows) {
			$str = "";
			foreach ($rows as $key => $val) {
				$val['lockNum']=isset($val['lockNum'])?$val['lockNum']:0;
				$proId=$val['productId'];
				$equId=$val['id'];
				if($val['isDel']==1){
					$productNo="<font color=red>".$val[productNo]."</font>";
					$productName="<font color=red>".$val[productName]."</font>";
				}else{
					$productNo=$val[productNo];
					$productName=$val[productName];
				}
				$canUseNum = $val['exeNum'] + $val['lockNum'];

				$lockNum = $val['number']- $val['lockNum'];
				$str .=<<<EOT
							<tr align="center">
							<td>
						<input type="hidden" id="productId$i" value="$val[productId]" />
								$productNo/<br/>
								$productName

							<input type="hidden" equId="$equId" proId="$proId" value="$val[presentId]" name="lock[$i][objId]"/>
							<input type="hidden" equId="$equId" proId="$proId" value="$val[id]" name="lock[$i][objEquId]" id="equId$i"/>
							<input type="hidden" equId="$equId" proId="$proId" value="oa_present_present" name="lock[$i][objType]"/></td>
							<input type="hidden" equId="$equId" proId="$proId" value="$val[productId]" name="lock[$i][productId]"/>
							<input type="hidden" equId="$equId" proId="$proId" value="$val[productName]" name="lock[$i][productName]"/>
							<input type="hidden" equId="$equId" proId="$proId" value="$val[productNo]" name="lock[$i][productNo]"/>
							<input type="hidden" equId="$equId" proId="$proId" value="" id="inventoryId$i" name="lock[$i][inventoryId]"/>
								</td>

							<td width="8%"><div equId="$equId" proId="$proId" id="amount$i">$val[number]</div></td>
							<td width="8%">$val[executedNum]</td>

							<td width="8%"><font color="red"><div equId="$equId" proId="$proId" id="actNum$i">0</div></td>
							<td width="8%"><font color="red"><div equId="$equId" proId="$proId" id="exeNum$i">0</div></font></td>
							<td width="8%" title="当前仓库的锁定数量">
								<font color="red">
							     	<a  href="javascript:toLockRecordsPage('$val[id]',true)" >
							     		<div equId="$equId" proId="$proId" id="stockLockNum$i"></div>
							     	</a>
							     </font>
							</td>
							<td width="8%" title="所有仓库的锁定数量总和">
								<font color="red">
							     	<a href="javascript:toLockRecordsPage('$val[id]',false)">
							     		<div equId="$equId" proId="$proId" id="lockNum$i"> $val[lockNum]</div>
							     	</a>
							     </font>
							</td>
							<td width="8%">0</td>
							<td width="8%">$val[issuedPurNum]</td>
							<td width="8%">$val[purchasedNum]</td>
							<td width="8%"><input type="text" equId="$equId" proId="$proId"  value="$lockNum" id="lockNumber$i" name="lock[$i][lockNum]" class="txtshort" onclick="$(this).css({'color':'black'})" onblur="checkLockNum(this,$i)"/></td>
							</tr>
EOT;
					$i++;

			}
			$str .= "<input type='hidden' id='rowNum' value='$i'/>";
		}

		return $str;
	}

/**
 * 变更 渲染从表
 */
function changeTable($object){
		$str = "";
		$i = 0;
		foreach($object as $key => $val ){
			$i ++ ;
			if (empty ( $val ['originalId'] )) {
				$str .= '<input type="hidden" name="present[presentequ][' . $i . '][oldId]" value="' . $val ['id'] . '" />';
			} else {
				$str .= '<input type="hidden" name="present[presentequ][' . $i . '][oldId]" value="' . $val ['originalId'] . '" />';
			}
					$str .=<<<EOT
					<tr id="equTab_$i" parentRowId="$val[isCon]" ><td width="5%">$i</td>
						<td><input type="text" name="present[presentequ][$i][productNo]" id="productNo" class="readOnlyTxtShort" readonly="readonly" value="$val[productNo]"/></td>
			            <td><input type="hidden" id="productId$i" name="present[presentequ][$i][productId]" value="$val[productId]"/>
			                <input type="text" name="present[presentequ][$i][productName]" id="productName" class="readOnlyTxtMiddle"  readonly="readonly" value="$val[productName]"/></td>
			            <td><input type="text" name="present[presentequ][$i][productModel]" id="productModel" class="readOnlyTxtShort" readonly="readonly" readonly="readonly" value="$val[productModel]"/></td>
			            <td><input type="text" name="present[presentequ][$i][number]"  id="number$i"  class="txtshort" value="$val[number]" onblur="FloatMul('number1','price1','money1')"/>
			                <input type="hidden" id="num$i" value="$val[number]">
			                <input type="hidden" id="serialName$i" name="present[presentequ][$i][serialName]" value="$val[serialName]"/>
			                <input type="hidden" id="serialId$i"   name="present[presentequ][$i][serialId]" value="$val[serialId]"/></td>
			            <td><input type="text" name="present[presentequ][$i][unitName]" id="unitName$i" class="readOnlyTxtShort" readonly="readonly" value="$val[unitName]" /></td>
			            <td><input type="text" name="present[presentequ][$i][price]" id="price$i" readonly="readonly" class="readOnlyTxtShort formatMoney" onblur="FloatMul('number1','price1','money1')" value="$val[price]"/></td>
                        <td><input type="text" name="present[presentequ][$i][money]" id="money$i" class="txtshort formatMoney" value="$val[money]"/></td>
                        <td nowrap width="8%"><input type="text" class="txtshort" name="present[presentequ][$i][warrantyPeriod]" id="warrantyPeriod$i" value="$val[warrantyPeriod]" /></td>
                        <td><input type="hidden" id="licenseId$i" name="present[presentequ][$i][License]" value="$val[license]"/>
		 			        <input type="button" class="txt_btn_a" value="配置" onclick="License('licenseId$i');" />
		 			    </td>
		 			    <td><img src="images/closeDiv.gif" onclick="mydel(this,'invbody','presentequ')" title="删除行" id="Del$i"/></td>
					</tr>
EOT;
		}
		return array($i,$str);
	}
	/**
	 * 更新在途数量
	 * $temId 合同从表ID
	 * $num 数量
	 * $type +/-  （add为 + /subtraction为减）
	 */
	function updateOnWayNum($temId,$num,$type){
        if($type == "add"){
            $onWayNum = $num;
        }else{
            $onWayNum = $num * (-1);
        }
        $sql = "update " . $this->tbl_name . " set onWayNum = onWayNum + $onWayNum where id=".$temId."";
        $this->query($sql);
	}

/***************************************物料确认 start*****************************************/
	/**
	 * 获取产品下的物料信息
	 */
	function getProductEqu_d($id) {
		$productDao = new model_projectmanagent_present_product();
		$goodsArr = $productDao->resolve_d($id);
		$equArr = array ();
		if ($goodsArr != 0) {
			$equItemDao = new model_goods_goods_propertiesitem();
			$productInfoDao = new model_stock_productinfo_productinfo();
			$goodsInfoIdArr = array ();
			foreach ($goodsArr as $key => $val) {
				$goodsInfoIdArr[] = $key;
			}
			$goodsInfoIdStr = implode(',', $goodsInfoIdArr);
			$equItemDao->searchArr['ids'] = $goodsInfoIdStr;
			$productArr = $equItemDao->list_d();
			$productIdArr = array ();
			foreach ($productArr as $key => $val) {
				$productIdArr[] = $val['productId'];
			}
			$productIdStr = implode(',', $productIdArr);
			$productInfoDao->searchArr['idArr'] = $productIdStr;
			$equArr = $productInfoDao->list_d();
			foreach ($productArr as $row => $index) {
				foreach ($equArr as $key => $val) {
					if ($index['productId'] == $val['id']) {
						$equArr[$key]['productId'] = $val['id'];
						$equArr[$key]['productNo'] = $val['productCode'];
						$equArr[$key]['productModel'] = $val['pattern'];
						$equArr[$key]['number'] = $index['defaultNum'];
						unset ($equArr[$key]['id']);
					}
				}
			}
		}
		return $equArr;
	}

	/**
	 * @description 新增物料处理页面显示产品信息
	 * @param $rows
	 */
	function showItemView($rows) {
		$j = 0;
		$str = ""; //返回的模板字符串
		if (is_array($rows)) {
			$goodDao = new model_goods_goods_goodscache();
			$i = 0; //列表记录序号
			foreach ($rows as $key => $val) {
				$deployShow = '';
				$deployShow = $goodDao -> showDeploy($val['deploy']);
				//合同设备是否赠送
				$j = $i +1;
				if( $val['license']!=0 || $val['license']!='' ){
					$licenseHtml = "<input type='button'  value='加密配置'  class='txt_btn_a' onclick='showLicense($val[license])'/>";
				}else{
					$licenseHtml='无';
				}
				if($val['isDel']=="1"){
					$style='<img title="变更删除的产品" src="images/box_remove.png" />';
				}
				if($val['changeTips']=="2"){
					$style='<img title="变更新增的产品" src="images/new.gif" />';
				}
				if($val['changeTips']=="1"){
					$style='<img title="变更编辑的产品" src="images/changeedit.gif" />';
				}
				$str .=<<<EOT
						<tr height="30px" bgcolor='#ECFFFF'>
							<td width="35px">$j</td>
<!--							<td>$val[conProductCode]<input type="hidden" id="conProductId$j" value="$val[id]"/></td>-->
							<td>$val[conProductName]<input type="hidden" id="conProductId$j" value="$val[id]"/></td>
							<td>$val[conProductDes]</td>
							<td width="8%">$val[number]<input type="hidden" id="number$j" value="$val[number]"/></td>
<!--							<td width="8%">$val[unitName]</td>
							<td width="8%">
							<input type="hidden" value="$val[deploy]" id="deploy$j"/>
							<input type="hidden" value="$val[license]" id="license$j"/>
							<input type="button"  value="加密配置"  class="txt_btn_a" onclick="showLicense($val[license])"/></td>-->
							<td width="8%"><input type="button"  value="产品配置"  class="txt_btn_a" onclick="showGoods('$val[deploy]','$val[conProductName]')" /></td>
						</tr>$deployShow
						<tr id="product$j.H" style="display:none;text-align:left;">
							<td colspan="8"><b onclick="hideList('product$j');">物料清单</b>
							  <img src="images/icon/icon002.gif" onclick="hideList('product$j');" title="展开" alt="新增选项" /></td>
						</tr>
						<tr id="product$j">
							<td colspan="8" style="padding-left:35px">
							<fieldset style="width:200"><legend style="text-align:left">
							  <b onclick="hideList('product$j');">物料清单</b>
							    <img src="images/icon/icon002.gif" onclick="hideList('product$j');" title="隐藏" alt="新增选项" /></legend>
								<div id="productInfo$j"></div>
							</fieldset>
							</td>
						</tr>
EOT;
				$i++;
			}
			if ($i == 0) {
				$str = '<tr><td colspan="11">暂无相关信息</td></tr>';
			}
		} else {
			$str = '<tr><td colspan="11">暂无相关信息</td></tr>';
		}
		$str .= "<input type='hidden' id='rowNum' value='" . $j . "'/>";
		return $str;
	}

	/**
	 * @description 变更/编辑 物料处理页面显示产品信息
	 * @param $rows
	 */
	function showItemChange($rows) {
		$j = 0;
		$str = ""; //返回的模板字符串
		if (is_array($rows)) {
			$goodDao = new model_goods_goods_goodscache();
			$equDao = new model_projectmanagent_present_presentequ();
			$i = 0; //列表记录序号

			foreach ($rows as $key => $val) {
				$style='';
				$deployShow = '';
				$deployShow = $goodDao -> showDeploy($val['deploy']);
				$equArr = $equDao->getByProId_d($val['id']);
				if($val['isDel']==1){
					if(!(is_array($equArr)&&count($equArr)>0)){
						continue;
					}
				}
				if($val['isDel']=="1"){
					$style='<img title="变更删除的产品" src="images/box_remove.png" />';
				}
				if($val['changeTips']=="2"){
					$style='<img title="变更新增的产品" src="images/new.gif" />';
				}
				if($val['changeTips']=="1"){
					$style='<img title="变更编辑的产品" src="images/changeedit.gif" />';
				}
				$img = '';
				if( $val['license']!=0 || $val['license']!='' ){
					$licenseHtml = "<input type='button'  value='加密配置'  class='txt_btn_a' onclick='showLicense($val[license])'/>";
				}else{
					$licenseHtml='无';
				}
				if( $val['isDel']=='1' ){
					$trStyle = " bgcolor='#efefef'";
				}else{
					$trStyle = " bgcolor='#ECFFFF'";
				}
				$img = '';
				//合同设备是否赠送
				$j = $i +1;
				$str .=<<<EOT
						<tr height="30px" $trStyle >
							<td width="8%">$style &nbsp; $j</td>

							<td>$img &nbsp;$val[conProductName]<input type="hidden" id="conProductId$j" value="$val[id]"/></td>
							<td>$val[conProductDes]</td>
							<td width="8%">$val[number]<input type="hidden" id="number$j" value="$val[number]"/></td>
							<td width="8%">$val[unitName]</td>
							<td width="8%">
							<input type="hidden" value="$val[deploy]" id="deploy$j"/>
							<input type="hidden" value="$val[license]" id="license$j"/>
							<input type="button"  value="加密配置"  class="txt_btn_a" onclick="showLicense($val[license])"/></td>
							<td width="8%"><input type="button"  value="产品配置"  class="txt_btn_a" onclick="showGoods('$val[deploy]','$val[conProductName]')" /></td>
						</tr>$deployShow
						<tr id="product$j.H" style="display:none;text-align:left;">
							<td colspan="8"><b onclick="hideList('product$j');">物料清单</b>
							  <img src="images/icon/icon002.gif" onclick="hideList('product$j');" title="展开" alt="新增选项" /></td>
						</tr>
						<tr id="product$j">
							<td colspan="8" style="padding-left:20px">
							<fieldset style="width:200"><legend style="text-align:left">
							  <b onclick="hideList('product$j');">物料清单</b>
							    <img src="images/icon/icon002.gif" onclick="hideList('product$j');" title="隐藏" alt="新增选项" /></legend>
								<div id="productInfo$j"></div>
							</fieldset>
							</td>
						</tr>
EOT;
				$i++;
			}
			if ($i == 0) {
				$str = '<tr><td colspan="11">暂无相关信息</td></tr>';
			}
		} else {
			$str = '<tr><td colspan="11">暂无相关信息</td></tr>';
		}
		$str .= "<input type='hidden' id='rowNum' value='" . $j . "'/>";
		return $str;
	}

	/**
	 * 渲染产品配置
	 */
	function showProductInfo($object, $id, $rowNum, $itemNum) {
		$name = 'present[detail][' . $itemNum . ']';
		$trId = $id . "_" . $rowNum;
		$str = "<tr id='goodsDetail_$trId'><td class='innerTd' colspan='20'><table class='form_in_table' id='contractequ_$id'>";
		if (is_array($object)) {
			$titleStr = "<tr class='main_tr_header1'>";
			$infoStr = '';
			$titleStr .=<<<EOT
				<th width="30px"></th>
				<th width="35px">序号</th>
				<th>物料编码</th>
				<th>物料名称</th>
				<th>版本型号</th>
				<th>数量</th>
				<th>交货期
EOT;
			foreach ($object as $key => $val) {
				$j = $key +1;
				$trName = $name . '[' . $rowNum . '0' . $j . ']';
				{
					$productId_n = $trName . '[productId]';
					$productCode_n = $trName . '[productCode]';
					$productName_n = $trName . '[productName]';
					$productpattern_n = $trName . '[pattern]';
					$productnumber_n = $trName . '[number]';
					$productarrivalPeriod_n = $trName . '[arrivalPeriod]';
					$infoStr .= "<tr class='tr_inner'>";
				}

				$infoStr .=<<<EOT
					<td><img src='images/removeline.png' onclick='mydel(this,"contractequ_$id")' title='删除行'></td>
					<td>$j</td>
					<td>$val[productCode]
						<input type="hidden" class="txtshort" id="inner_productCode$id-$key" name='$productCode_n' value="$val[productCode]"/>
						<input type="hidden" class="txtshort" id="inner_productId$id-$key" name='$productId_n' value="$val[id]"/>
						</td>
					<td>$val[productName]<input type="hidden" class="txtshort" id="inner_productName$id-$key" name='$productName_n' value="$val[productName]"/></td>
					<td>$val[pattern]<input type="hidden" class="txtshort" id="inner_pattern$id-$key" name='$productpattern_n' value="$val[pattern]"/></td>
					<td><input type="text" class="txtshort" id="inner_number$id-$key" name='$productnumber_n' value="$val[configNum]"/></td>
					<td>$val[arrivalPeriod]<input type="hidden" class="txtshort" id="inner_arrivalPeriod$id-$key" name='$productarrivalPeriod_n' value="$val[arrivalPeriod]"/>
EOT;
				$infoStr .= "</td></tr>";
			}
			$titleStr .= "</th></tr>";
			$infoStr .= "</td></tr>";
			$str .= $titleStr . $infoStr . "</table></td></tr>";
			return $str;
		} else {
			return '';
		}
	}

	/**
	 * 物料分配 新增
	 */
	function equAdd_d($object,$audti=false) {
		try {
			$this->start_d();
			if ($object['id']) {
				$contDao = new model_projectmanagent_present_present();
				$linkDao = new model_projectmanagent_present_presentequlink();
				$contObj = $contDao->get_d($object['id']);
				$linkDao->searchArr['presentId']=$object['id'];
				$linkInfo = $linkDao->list_d();
				if($contObj['dealStatus']=='1' || !empty($linkInfo) ){
					echo "<script>alert('该需求已经确认，请刷新原列表页，查看该单据处理状态!')</script>";
					throw new Exception("该需求已经确认，请刷新原列表页，查看该单据处理状态!");
				}

				$equs = array ();
				foreach ($object['detail'] as $k => $v) {
					if (is_array($v)) {
						foreach ($v as $key => $value) {
							if( !empty($value['productId']) ){
								$equs[] = $value;
							}
						}
					}
				}
				//加入关联表
				$linkArr = array (
					"presentId" => $contObj['id'],
					"presentCode" => $contObj['Code'],
					"presentType" => 'oa_present_present',
				);
				$dateObj = array(
					'id'=>$object['id'],
					'standardDate'=>$object['standardDate'],
				);
				if($audti){
					$dateObj['dealStatus']=1;
					$linkArr['ExaStatus']='完成';
					$linkArr['ExaDT']=day_date;
					$linkArr['ExaDTOne']=day_date;
				}else{
					$linkArr['ExaStatus']='未提交';
				}
				$contDao->updateById($dateObj);
				$linkId = $linkDao->add_d($linkArr, true);
				if($linkId){
					$linkDao->confirmAudit($linkId);
				}else{
					throw new Exception("单据信息不完整，请确认!");
				}
				$linkArr['linkId']=$linkId;
				//处理相同属性
				$equs = $this->processEquCommonInfo($equs, $linkArr);
				$lastEquId = 0;
				foreach ($equs as $key => $val) {
					if(empty($val['isDel'])){
						$val['isDel'] = '0';
					}
					if (empty ($val['configCode'])) {
						$val['parentEquId'] = $lastEquId;
					}
//					$val['productNo'] = $val['productCode'];
					if( isset($val['id'])&&$val['id']!='' ){
						$this->edit_d($val);
					}else{
						$id = $this->add_d($val);
						if (!empty ($val['configCode'])) {
							$lastEquId = $id;
						}
					}
				}
				if($audti){
					$contDao->updateShipStatus_d($object['id']);
					$contDao->updateOutStatus_d($object['id']);
					$this->sendMailAtAudit($object);
				}
			} else {
				throw new Exception("单据信息不完整，请确认!");
			}
			$this->commit_d();
			return $linkId;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 获取某个物料清单的配件信息
	 * add by chengl
	 */
	function getEquByParentEquId_d($parentEquId) {
		$this->searchArr['parentEquId'] = $parentEquId;
		return $this->list_d();
	}
	/**
	 * 物料分配 编辑
	 */
	function equEdit_d($object,$audti=false) {
		try {
			$this->start_d();
			if ($object['id']) {
				$contDao = new model_projectmanagent_present_present();
				$linkDao = new model_projectmanagent_present_presentequlink();
				$linkObj = $linkDao->findBy('presentId', $object['id']);
                $contObj = $contDao->get_d($object['id']);

                if(empty($linkObj)){
                    //如果不存在联表数据
                    $linkArr = array (
                        "presentId" => $contObj['id'],
                        "presentCode" => $contObj['Code'],
                        "presentType" => 'oa_present_present',
                        "ExaStatus" => '未提交'
                    );
                    $linkId = $linkDao->add_d($linkArr, true);
                }

				if($contObj['dealStatus']=='1'){
					echo "<script>alert('该需求已经确认，请刷新原列表页，查看该单据处理状态!')</script>";
					throw new Exception("该需求已经确认，请刷新原列表页，查看该单据处理状态!");
				}
				$dateObj = array(
					'id'=>$object['id'],
					'standardDate'=>$object['standardDate'],
                    'costEstimates'=>$object['costEstimates'],
                    'costEstimatesTax'=>$object['costEstimatesTax'],
				);
				if($audti){
					$dateObj['dealStatus']=1;
					$linkObj['ExaStatus']='完成';
					$linkObj['ExaDT']=day_date;
					$linkObj['ExaDTOne']=day_date;
					$linkDao->edit_d($linkObj);
				}
				$contDao->updateById($dateObj);
				$this->delete(array (
					'presentId' => $object['id']
				));
				$equs = array ();
				foreach ($object['detail'] as $k => $v) {
					if (is_array($v)) {
						foreach ($v as $key => $value) {
							unset($value['id']);
							$equs[] = $value;
						}
					}
				}
				$contObj['linkId'] = $linkObj['id'];
				//处理相同属性
//				echo "<pre>";
//				print_R($equs);
				$contObj['presentId']=$contObj['id'];
				$equs = $this->processEquCommonInfo($equs, $contObj);
				$lastEquId = 0;
				foreach ($equs as $key => $val) {
					if(empty($val['isDel'])){
						$val['isDel'] = '0';
					}
					if (empty ($val['configCode'])) {
						$val['parentEquId'] = $lastEquId;
					}
					if ($val['isDelTag'] != 1) {
						$id = $this->add_d($val);
						if (!empty ($val['configCode'])) {
							$lastEquId = $id;
						}
					}
				}
				if($audti){
					$contDao->updateShipStatus_d($object['id']);
					$contDao->updateOutStatus_d($object['id']);
					$this->sendMailAtAudit($object,'提交');
				}
			} else {
				throw new Exception("单据信息不完整，请确认!");
			}
			$this->commit_d();
			return $linkObj['id'];
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 物料分配 变更
	 */
	function equChange_d($object) {
		try {
			$this->start_d();
			$contDao = new model_projectmanagent_present_present();
			$linkDao = new model_projectmanagent_present_presentequlink();
			$contract = $contDao->get_d($object['id']);
			$linkObj = $linkDao->findBy('presentId', $object['id']);
			$updateTipsSql = " update oa_present_equ set changeTips='0' where presentId='".$object['id']."'";
			$this->_db->query( $updateTipsSql );

			$linkObj['ExaStatus']='完成';
			$linkObj['ExaDT']=day_date;
			$linkDao->edit_d($linkObj);

			$dateObj = array(
				'id'=>$object['id'],
				'standardDate'=>$object['standardDate'],
				'dealStatus'=>'1'
			);
			$contDao->updateById($dateObj);
			$equs = array ();
			//echo "<pre>";print_r($object['detail']);
			foreach ($object['detail'] as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $key => $value) {
//						if (!empty ($value['configCode'])) { //把配置转物料的更新下
//							$value['parentRowNum'] = 0;
//							$value['parentEquId'] = 0;
//						}
						if (isset ($value['rowNum_'])) { //物料
							$value['isCon'] = $value['productId'] . "_" . $value['rowNum_'];
						}
						$value['isConfig'] = $value['parentRowNum'];
						if (!empty ($value['id'])) {
							$value['oldId'] = $value['id'];
							unset ($value['id']);
						}
						if(empty($value['isDel'])){
                            if( empty($value['isDelTag'] )){
							    $value['isDel'] = 0;
							}else{
								$value['isDel'] = $value['isDelTag'];
							}
						}
						$equs[] = $value;
					}
				}
			}
			$linkObj['equs'] = $equs;
			$linkObj['oldId'] = $linkObj['id'];
			$changeLogDao = new model_common_changeLog('presentequ',false);
			$tempObjId = $changeLogDao->addLog($linkObj);
			$contract['presentId']=$contract['id'];
			$equs = $this->processEquCommonInfo($equs, $contract);
			$equArr = $equs;
			foreach( $equs as $key=>$val ){
				if($val['isCon']!=''&&$val['isDel']==1){
					foreach($equArr as $index => $value){
						if($val['isCon']==$value['isConfig']){
							$equArr[$index]['isConfig']='';
							$equArr[$index]['parentEquId']=0;
						}
					}
				}
			}
//			echo "<pre>";
//			print_R($equArr);
			$lastEquId = 0;
			foreach($equArr as $key => $val){
				if ( $val['productId'] == '' ){
					unset($equArr[$key]);
					continue;
				}
				//如果变更数量是0，删除计划设备清单
				if ($val ['isDel'] == 1) {
					$val['id']= $val ['oldId'];
					$val['isDel']= 1;
					$val['changeTips'] = 3;
					$this->edit_d ( $val );
				} elseif($val ['oldId']) {
					$val['id']= $val ['oldId'];
					$this->edit_d ( $val );
				} else {
					$val['linkId']=$linkObj['id'];
					$val['changeTips'] = 2;
					if (empty ($val['configCode'])||$val['configCode']===0) {
						$val['parentEquId'] = $lastEquId;
					}
					$id = $this->add_d ( $val );
					if (!empty ($val['configCode'])) {
						$lastEquId = $id;
					}
				}
			}
//			unset ($linkObj['id']);
//			unset ($linkObj['isTemp']);
//			unset ($linkObj['originalId']);
//			$linkDao->confirmChange($linkObj['id']);
			$this->sendMailAtAudit($object,'变更');
			$contDao->updateShipStatus_d($object['id']);
			$contDao->updateOutStatus_d($object['id']);
//			//更新配件与物料关联
//			$sql = "update oa_present_equ o1 left join oa_present_equ o2 on " .
//			"o1.presentId=o2.presentId and o1.id!=o2.id and o1.linkId=o2.linkId and o2.isDel=0 SET o1.parentEquId=o2.id " .
//			"where o1.isConfig=o2.isCon and o1.isConfig!='' and o1.isConfig is not null";
//			$this->query($sql);
			$this->commit_d();
			return $linkObj['id'];
		} catch (Exception $e) {
			echo $e->getMessage();
			$this->rollBack();
			return null;
		}
	}

     /**
      * 赠送变更物料确认
      */
     function equChangeNew_d($object) {
         try {
             $this->start_d();
             $contDao = new model_projectmanagent_present_present();
             $linkDao = new model_projectmanagent_present_presentequlink();
             $contract = $contDao->get_d($object['id']);
             $linkObj = $linkDao->findBy('presentId', $object['oldId']);
             $updateTipsSql = " update oa_present_equ set changeTips='0' where presentId='".$object['id']."'";
             $this->_db->query( $updateTipsSql );

             // 更新临时记录成本概算
             $updateEstimatesSql = " update oa_present_present set costEstimates='".$object['costEstimates']."',costEstimatesTax='".$object['costEstimatesTax']."' where id='".$object['id']."'";
             $this->_db->query( $updateEstimatesSql );

             $linkObj['ExaStatus']='变更审批中';
             $linkObj['ExaDT']=day_date;
             $linkDao->edit_d($linkObj);
//
//             $dateObj = array(
//                 'id'=>$object['id'],
//                 'standardDate'=>$object['standardDate'],
//             );
//             $contDao->updateById($dateObj);
             $equs = array ();
             //echo "<pre>";print_r($object['detail']);
             foreach ($object['detail'] as $k => $v) {
                 if (is_array($v)) {
                     foreach ($v as $key => $value) {
                         if (isset ($value['rowNum_'])) { //物料
                             $value['isCon'] = $value['productId'] . "_" . $value['rowNum_'];
                         }
                         $value['isConfig'] = $value['parentRowNum'];
                         if (!empty ($value['id'])) {
                             $value['oldId'] = $value['id'];
                             unset ($value['id']);
                         }
                         if(empty($value['isDel'])){
                             if( empty($value['isDelTag'] )){
                                 $value['isDel'] = 0;
                             }else{
                                 $value['isDel'] = $value['isDelTag'];
                             }
                         }
                         $equs[] = $value;
                     }
                 }
             }
             $linkObj['equs'] = $equs;
             $linkObj['oldId'] = $linkObj['id'];
             $changeLogDao = new model_common_changeLog('presentequ',false);
             $tempObjId = $changeLogDao->addLog($linkObj);

             $contract['presentId']=$contract['id'];
             $equs = $this->processEquCommonInfo($equs, $contract);
             $equArr = $equs;
             foreach( $equs as $key=>$val ){
                 if($val['isCon']!=''&&$val['isDel']==1){
                     foreach($equArr as $index => $value){
                         if($val['isCon']==$value['isConfig'] && (isset($val['conProductId']) && $val['conProductId'] != 0)){// 防止新增物料与产品物料一样的时候,会把产品物料配件误删
                             $equArr[$index]['isConfig']='';
                             $equArr[$index]['parentEquId']=0;
                         }
                     }
                 }
             }
//			echo "<pre>";
//			print_R($equArr);
             $lastEquId = 0;
             foreach($equArr as $key => $val){
                 if ( $val['productId'] == '' ){
                     unset($equArr[$key]);
                     continue;
                 }
                 //如果变更数量是0，删除计划设备清单
                 if ($val ['isDel'] == 1) {
                     $val['id']= $val ['oldId'];
                     $val['isDel']= 1;
                     $val['changeTips'] = 3;
                     $this->edit_d ( $val );
                 } elseif($val ['oldId']) {
                     $val['id']= $val ['oldId'];
                     $this->edit_d ( $val );
                 } else {
                     $val['linkId']=$linkObj['id'];
                     $val['changeTips'] = 2;
                     if (empty ($val['configCode'])||$val['configCode']===0) {
                         $val['parentEquId'] = $lastEquId;
                     }
                     $id = $this->add_d ( $val );
                     if (!empty ($val['configCode'])) {
                         $lastEquId = $id;
                     }
                 }
             }
             $this->commit_d();
             return $linkObj['id'];
         } catch (Exception $e) {
             echo $e->getMessage();
             $this->rollBack();
             return null;
         }
     }

	/**
	 * 处理物料清单相同属性
	 */
	function processEquCommonInfo($equs, $contract) {
		$mainArr = array (
			"presentId" => $contract['presentId'],
			"presentCode" => $contract['presentCode'],
			"presentType" => 'oa_present_present',
		);
		if (!empty ($contract['linkId'])) {
			$mainArr["linkId"] = $contract['linkId'];
		}
		$equs = util_arrayUtil :: setArrayFn($mainArr, $equs);
		return $equs;
	}
/****************************************物料确认 end*********************************************************/

	/**
	 * 物料确认 独立新增物料
	 */
	function getNoProductEqu_d($contractId){
		$this->searchArr['presentId'] = $contractId;
		$this->searchArr['noContProductId'] = 0;
//		$this->searchArr['isTemp'] = 0;
//		$this->searchArr['notDel'] = 0;
		return $rows = $this->list_d();
	}

	/**
	 * 物料确认邮件通知
	 */
	 function sendMailAtAudit($object,$title ){
	 	$mainDao = new model_projectmanagent_present_present();
	 	$mainObj = $mainDao->get_d($object['id']);
	 	$outmailArr=array(
	 		$mainObj['salesNameId']
	 	);
	 	$outmailStr = implode(',',$outmailArr);
	 	$outmailStr .= ",".EQUCONFIRMUSER;
		$addMsg = $this->sendMesAsAdd($mainObj);
		$emailDao = new model_common_mail();
		$emailInfo = $emailDao->batchEmail("y", $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, $title, $mainObj['Code'], $outmailStr, $addMsg, '1');
	 }


	/**
	 * 邮件中附加物料信息
	 */
	 function sendMesAsAdd($object){
			if(is_array($object ['presentequ'])){
				$j=0;
				$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor='#D5D5D5' align=center ><td>序号</td><td>物料编号</td><td>物料名称</td><td>规格型号</td><td>单位</td><td>数量</td></tr>";
				foreach($object ['presentequ'] as $key => $equ ){
					$j++;
					$productCode=$equ['productNo'];
					$productName=$equ['productName'];
					$productModel=$equ ['productModel'];
					$unitName=$equ ['unitName'];
					$number=$equ ['number'];
					$addmsg .=<<<EOT
						<tr bgcolor='#7AD730' align="center" ><td>$j</td><td>$productCode</td><td>$productName</td><td>$productModel</td><td>$unitName</td><td>$number</td></tr>
EOT;
					}
//					$addmsg.="</table>" .
//							"<br><span color='red'>以上列表若有背景色为绿色的物料，说明该物料是借试用转销售的。</span></br>";
			}
			return $addmsg;
	 }

     /**
      * 数组过滤 - 同表单的表单数据不予显示
      */
     function filterRows_d($object) {
         if ($object) {
             $markId = null;
             foreach ($object as $key => $val) {
                 if ($markId == $val['conProductId']) {
                     unset ($object[$key]['conProductName']);
                 } else {
                     $markId = $val['conProductId'];
                 }
             }
         }
         return $object;
     }

     // 打回到赠送申请列表重新下推物料确认
     function ajaxBack($presentId,$isSubAppChange){
         $contDao = new model_projectmanagent_present_present();
         $result = 0;
         if($isSubAppChange == 1){// 变更物料确认打回
             // 更新原单状态
             $conditions = "id={$presentId}";
             $row['dealStatus'] = '1';
             $row['ExaStatus'] = '完成';
             $result = ($contDao->update($conditions, $row))? 1 : 0;

             // 更新临时单据状态
             if($result == 1){
                 $conditions = "originalId={$presentId}";
                 $row['dealStatus'] = '1';
                 $result = ($contDao->update($conditions, $row))? 1 : 0;
             }
         }else{
             // 更新原单状态
             $conditions = "id={$presentId}";
             $row['dealStatus'] = '0';
             $row['ExaStatus'] = '未审批';
             $result = ($contDao->update($conditions, $row))? 1 : 0;
         }

         return $result;
     }
 }
?>