<?php
header("Content-type: text/html; charset=gb2312");

//引入接口
include_once WEB_TOR . 'model/purchase/external/planBasic.php';

/**
 * @description: 补库采购入库model
 * @date 2010-12-17 下午04:28:25
 * @author oyzx
 * @version V1.0
 */
class model_purchase_external_stock extends planBasic {

	private $externalDao; //外部对象dao接口
	private $externalEquDao; //外部对象设备dao接口

	function __construct() {
		$this->externalDao = new model_stock_fillup_fillup();
		$this->externalEquDao = new model_stock_fillup_filluppro();

		//调用初始化对象关联类
		$this->objass = new model_common_objass();
	}

	/***************************************************************************************************
	 * ------------------------------以下为页面模板显示调用方法------------------------------------------*
	 **************************************************************************************************/

	/**
	 * @desription 添加采购计划-显示列表
	 * @param tags
	 * @date 2010-12-17 下午05:17:09
	 */
	function showAddList($equs,$mainRows) {
       if( is_array( $equs ) ){
		$i = 0;
		$baseDao=new model_base();
		$datadictArr = $baseDao->getDatadicts ( "CGZJSX" );
		$checkTypeStr=$baseDao->getDatadictsStr( $datadictArr ['CGZJSX']);
		foreach ($equs as $key => $val) {
            if($val[stockNum]-$val[issuedPurNum]>0){
			++ $i;
            $YMD = date("Y-m-d");
           	$val[amountAll] = $val[stockNum]-$val[issuedPurNum];
			$str .=<<<EOT
						<tr height="28" align="center">
							<td>
								$i
							</td>
							<td>
								<input type="text"  class="readOnlyTxtItem" name="basic[equipment][$key][productNumb]" value="$val[sequence]" />
							</td>
							<td>
								<input type="text"  class="readOnlyTxtItem"  name="basic[equipment][$key][productName]" value="$val[productName]" />
								<input type="hidden" name="basic[equipment][$key][productId]" value="$val[productId]" />
							</td>
							<td>
								<input type="text" id="pattem$i" name="basic[equipment][$key][pattem]" value="$val[pattern]" class="readOnlyTxtItem" readonly='readonly'/>
							</td>
							<td>
								<input type="text" id="unitName$i" name="basic[equipment][$key][unitName]" value="$val[unitName]"   class="readOnlyTxtMin"/>
							</td>
							<td>
								<select  name="basic[equipment][$key][qualityCode]">$checkTypeStr</select>
							</td>
							<td>
								<input type="text" class="amount txtshort" id="list_amountAll$key" name="basic[equipment][$key][amountAll]" value="$val[amountAll]" onblur="addPlan(this);">
								<input type="hidden" name="amountAll" value="$val[amountAll]" >
							</td>
							<td>
								<input type="text" class="txtshort" name="basic[equipment][$key][dateIssued]" value="$YMD" onfocus="WdatePicker()" readonly >
							</td>
							<td>
								<input type="text" class="txtshort" name="basic[equipment][$key][dateHope]" value="$val[intentArrTime]" onfocus="WdatePicker()" readonly >
							</td>
							<td>
								<input type="text" class="txt" name="basic[equipment][$key][remark]"></input>
								<input type="hidden" id="list_applyEquId$key" name="basic[equipment][$key][applyEquId]" value="$val[id]" />
								<input type="hidden" name="basic[equipment][$key][equObjAssId]" value="$val[id]" />
								<input type="hidden" name="basic[equipment][$key][purchType]" value="stock" />
							</td>
							<td>
							<img src="images/closeDiv.gif" onclick="mydel(this , 'mytable')" title="删除行" />
						    </td>
						</tr>

EOT;
            }
		}
		if($i==0){
			$str="<tr align='center'><td colspan='50'>暂无补库采购物料清单信息</td></tr>";
		}
		$str .=<<<EOT
					<input type="hidden" name="basic[objAssId]" value="$mainRows[id]" />
					<input type="hidden" name="basic[objAssName]" value="补库采购" />
					<input type="hidden" name="basic[objAssType]" value="stock" />
					<input type="hidden" name="basic[objAssCode]" value="$mainRows[fillupCode]" />
					<input type="hidden" name="basic[equObjAssType]" value="stock_equ" />
EOT;
	}else{
		$str="<tr align='center'><td colspan='50'>暂无补库采购物料清单信息</td></tr>";
	}
		return $str;
	}
	/**
	 * @desription 补库单产品列表显示
	 * @param tags
	 * @date 2011-1-17 下午03:16:47
	 * @qiaolong
	 */
	function showProlist ($rows) {
 		if ( is_array( $rows ) ) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ($rows as $key => $val) {
				$str .=<<<EOT
					<tr align="center" >
					<td class="tabledata">
						<!--input type="hidden" value="$val[id]"-->
						<input type="hidden" name="basic[equipment][$key][contOnlyId]" value="$val[id]">
						<input type="text" size="15" name="basic[equipment][$key][productNumb]" value="$val[sequence]" readOnly class="readOnlyTxt" >
					</td>
					<td class="tabledata">
						<input type="text" size="20" name="basic[equipment][$key][productName]" value="$val[productName]"  id="productName$i" readOnly class="readOnlyTxt" />
						<input type="hidden" name="basic[equipment][$key][productId]" value="$val[productId]"  id="productId$i" >
					</td>
					<td class="tabledata">
						<input type="text" size="15" name="basic[equipment][$key][amountAll]" value="$val[stockNum]"  readOnly class="readOnlyTxt"/>
					</td>
					<td class="tabledata">
						<input type="text" size="15" name="basic[equipment][$key][dateIssued]" value="$val[intentArrTime]"  readOnly class="readOnlyTxt" />
					</td>
					<td class="tabledata">
						<input type="text" size="15" name="basic[equipment][$key][dateHope]"  onfocus="WdatePicker()"/>
					</td>
					<td class="tabledata">
						<input type="text" name="basic[equipment][$key][remark]" size="15" />
						<input type="hidden" name="basic[equipment][$key][purchType]" value="stock" />
					</td>
					</tr>
EOT;
		$i++;
			}
			return $str;
			}
	 }

	/***************************************************************************************************
	 * ------------------------------以下接口方法,可供其他模块调用---------------------------------------*
	 **************************************************************************************************/

	/**
	 * @desription 通过补库单Id获取产品数据
	 * @param $parentId		补库单ID
	 */
	function getItemsByParentId ($parentId) {
		$itemRows=$this->externalEquDao->getItemByFillUpId($parentId);
		return $itemRows;
	}

	/**
	 * 根据采购类型的ID，获取其信息
	 *
	 * @param $id	补库单ID
	 */
	function getInfoList ($id) {
		$mainRows=$this->externalDao->get_d($id);
		$mainRows['sourceName']=$mainRows['fillupCode'];
		$mainRows['sourceCode']=$mainRows['fillupCode'];
		return $mainRows;
	}
	/**
	 * 根据不同的类型采购计划，进行业务处理
	 *
	 * @param $paramArr		参数数组
	 */
	function dealInfoAtPlan ($paramArr){
		$fillupEquId=$paramArr['equObjAssId'];
		$issuedPurNum=$paramArr['issuedPurNum'];
		return $this->externalEquDao->updateIssuedPurNum($fillupEquId,$issuedPurNum);
	}

	/**更新已下达采购数量
	*author can
	*2011-3-22
	*/
	function updateAmountIssued($id,$issuedNum,$lastIssueNum){
		return $this->externalEquDao->updateAmountIssued($id,$issuedNum,$lastIssueNum);
	}




	/**
	 * @desription 查找补库产品
	 * @param $id	补库单ID
	 * @date 2011-1-17 下午03:11:27
	 * @qiaolong
	 */
	function getFillupProInfo ($id) {
		$filluppro = new model_stock_fillup_filluppro();
		$filluppro->searchArr['fillUpId']=$id;
		$fillupPros = $filluppro->listBySqlId('select_default');
		return $fillupPros;
	}
	/**
	 * @desription 获取补库单信息
	 * @param $id	补库单ID
	 * @date 2011-1-17 下午03:31:50
	 * @qiaolong
	 */
	function getFillupInfo ($id) {
		$fillupDao = new model_stock_fillup_fillup();
		$fillupDao->searchArr['id']=$id;
		$fillups = $fillupDao->listBySqlId('select_default');
		return $fillups;
	}

	/**
	 * 下达采购计划后页面原跳转
	 *
	 * @param $id	采购申请保存ID
	 */
	function toShowPage ($id,$type=null) {
			if($type){    //从补库管理页面下达采购计划后的页面跳转
				if($id){
					msg('下达成功');
				}else{
					msg('物料信息不完整，没有物料或数量为0，下达失败');
				}
			}else{		//从统一接口下面采购计划后的跳转页面
				parent::toShowPage($id);
			}
	}

}
?>
