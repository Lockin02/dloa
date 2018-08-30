<?php
/*
 * Created on 2010-7-17
 *	产品基本信息Model
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_stock_productinfo_productinfoAdd extends model_base {
	public $db;
	private $state=array('0'=>'保存','1'=>'未确认','2'=>'已确认','3'=>'打回');
	function __construct() {
		include (WEB_TOR."model/common/mailConfig.php");
		$this->tbl_name = "oa_stock_product_info_temp";
		$this->sql_map = "stock/productinfo/productinfoAddSql.php";
		$this->mailArr=$mailUser[$this->tbl_name];
		$this->statusDao = new model_common_status();
		$this->statusDao->status = array (
			0 => array (
				'statusEName' => 'save',
				'statusCName' => '未提交',
				'key' => '0'
			),
			1 => array (
				'statusEName' => 'submit',
				'statusCName' => '提交',
				'key' => '1'
			)
		);
		parent::__construct ();
	}
	/**
	 *
	 * 新增是配件清单模板
	 * @param unknown_type $rows
	 */
	function showAccessAtAdd($rows) {
		$accessStr = ""; //配件信息字符串
		$j = 0;
		$accessSeNum = 0;
		if (is_array ( $rows )) {
			foreach ( $rows as $key => $val ) {
				$accessSeNum = $j + 1;
				$accessStr .= <<<EOT
					<tr align="center">
						<td>
			                 <img align="absmiddle" src="images/removeline.png" onclick="delAccessItem(this);" title="删除行" />
			            </td>
                        <td>
                       		 $accessSeNum
                        </td>
						<td>
							<input type="text" class="txtshort"  id="aConfigCode$j" value="$val[configCode]" name="productinfoAdd[accessItem][$j][configCode]" />
							<input type="hidden" class="txt" id="aConfigId$j" value="$val[configId]" name="productinfoAdd[accessItem][$j][configId]" />
							<input type="hidden" class="txt" id="aConfigType$j" value="proaccess" name="productinfoAdd[accessItem][$j][configType]" />
						</td>
						<td>
							<input type="text" class="txt" id="aConfigName$j" value="$val[configName]" name="productinfoAdd[accessItem][$j][configName]" />
						</td>
						<td>
							<input type="text" class="readOnlyTxtItem" readOnly id="aConfigPattern$j" value="$val[configPattern]" name="productinfoAdd[accessItem][$j][configPattern]" />
						</td>
						<td>
							<input type="text" class="txtshort" value="$val[configNum]" name="productinfoAdd[accessItem][$j][configNum]"  />
						</td>
						<td>
							<input type="text" class="txt" value="$val[explains]" name="productinfoAdd[accessItem][$j][explains]" />
						</td>
				 	</tr>
EOT;
				$j ++;
			}
		}
		return $accessStr;
	}
	/**
	 * 查看页面显示硬件的配置信息模板
	 */
	function showItemAtView($rows) {
		$configStr = ""; //配置信息字符串
		$accessStr = ""; //配置信息字符串
		

		$str = "";
		
		$i = 0; //列表记录序号
		$j = 0;
		$accessSeNum = 0;
		$configSeNum = 0;
		if (is_array ( $rows )) {
			foreach ( $rows as $key => $val ) {
				if (empty ( $val ['configId'] ) || $val ['configId'] == null) {
					$configSeNum = $i + 1;
					$configStr .= <<<EOT
					<tr align="center">
                        <td>
                       		 $configSeNum
                        </td>
						<td>
							$val[configName]

						</td>
						<td>
							$val[configNum]
						</td>
						<td>
							$val[explains]
						</td>
				 	</tr>
EOT;
					$i ++;
				} else {
					$accessSeNum = $j + 1;
					$accessStr .= <<<EOT
					<tr align="center">
                        <td>
                       		 $accessSeNum
                        </td>
						<td>
							$val[configCode]
						</td>
						<td>
							$val[configName]
						</td>
						<td>
							$val[configPattern]
						</td>
						<td>
							$val[configNum]
						</td>
						<td>
							$val[explains]
						</td>
				 	</tr>
EOT;
					$j ++;
				}
			}
		}
		
		if ($configStr != "") {
			$str .= <<<EOT
			<table id="itemConfigTable" class='form_main_table'>
				<tr>
					<td colspan="4" align="left" class="form_header">&nbsp; 物料配置信息:&nbsp;</td>
				</tr>
				<tr class="main_tr_header">
					<th>序号</th>
					<th>配置名称</th>
					<th>数量</th>
					<th>说明</th>
				</tr>
				<tbody id="itemConfigBody">
				$configStr
				</tbody>
			</table>
EOT;
		}
		
		if ($accessStr != "") {
			$str .= <<<EOT
				<table id="itemAccessTable" class='form_main_table'>
					<tr>
						<td colspan="6" align="left" class="form_header">&nbsp; 物料配件清单:&nbsp;</td>
					</tr>
					<tr class="main_tr_header">
						<th>序号</th>
						<th>配件编码</th>
						<th>配件名称</th>
						<th>型号/版本号</th>
						<th>数量</th>
						<th>说明</th>
					</tr>
					<tbody id="itemAccessBody">
					$accessStr
					</tbody>
			</table>
EOT;
		
		}
		
		return $str;
	}
	
	/**
	 * 修改页面显示硬件的配置信息模板
	 */
	function showItemAtEdit($rows) {
		$configStr = ""; //配置信息字符串
		$accessStr = ""; //配置信息字符串
		

		$i = 0; //列表记录序号
		$j = 0;
		$accessSeNum = 0;
		$configSeNum = 0;
		if (is_array ( $rows )) {
			foreach ( $rows as $key => $val ) {
				if (empty ( $val ['configId'] ) || $val ['configId'] == null) {
					$configSeNum = $i + 1;
					$configStr .= <<<EOT
					<tr align="center">
						<td>
			                 <img align="absmiddle" src="images/removeline.png" onclick="delConfigItem(this);" title="删除行" />
			            </td>
                        <td>
                       		 $configSeNum
                        </td>
						<td>
							<input type="text" class="txt"  id="cConfigName$i" value="$val[configName]" name="productinfoAdd[configItem][$i][configName]" />
							<input type="hidden" class="txt" id="cConfigId$i" value="$val[configId]" name="productinfoAdd[configItem][$i][configId]" />
						</td>
						<td>
							<input type="text" class="txtshort" value="$val[configNum]" name="productinfoAdd[configItem][$i][configNum]" />
						</td>
						<td>
							<input type="text" class="txt" value="$val[explains]" name="productinfoAdd[configItem][$i][explains]" />
							<input type="hidden" value="$val[id]" name="productinfoAdd[configItem][$i][id]" />
						</td>
				 	</tr>
EOT;
					$i ++;
				} else {
					$accessSeNum = $j + 1;
					$accessStr .= <<<EOT
					<tr align="center">
						<td>
			                 <img align="absmiddle" src="images/removeline.png" onclick="delAccessItem(this);" title="删除行" />
			            </td>
                        <td>
                       		 $accessSeNum
                        </td>
						<td>
							<input type="text" class="txtshort"  id="aConfigCode$j" value="$val[configCode]" name="productinfoAdd[accessItem][$j][configCode]" />
							<input type="hidden" class="txt" id="aConfigId$j" value="$val[configId]" name="productinfoAdd[accessItem][$j][configId]" />
						</td>
						<td>
							<input type="text" class="txt" id="aConfigName$j" value="$val[configName]" name="productinfoAdd[accessItem][$j][configName]" />
						</td>
						<td>
							<input type="text" class="readOnlyTxtItem" id="aConfigPattern$j" value="$val[configPattern]" name="productinfoAdd[accessItem][$j][configPattern]" />
						</td>
						<td>
							<input type="text" class="txtshort" value="$val[configNum]" name="productinfoAdd[accessItem][$j][configNum]"  />
						</td>
						<td>
							<input type="text" class="txt" value="$val[explains]" name="productinfoAdd[accessItem][$j][explains]" />
							<input type="hidden" value="$val[id]" name="productinfoAdd[accessItem][$j][id]" />
						</td>
				 	</tr>
EOT;
					$j ++;
				}
			}
		}
		return array ($configStr, $accessStr, $configSeNum, $accessSeNum );
	}
	/**
	 * 
	 * 批量更新物料配件模板
	 * @param $accessArr
	 */
	function showEditProAccessItem($productArr){
		$accessStr = ""; //配件信息字符串
		$j = 0;
		$accessSeNum = 0;
		if (is_array ( $productArr )) {
			foreach ( $productArr as $key => $val ) {
				$accessSeNum = $j + 1;
				$accessStr .= <<<EOT
					<tr align="center">
						<td>
							<input type="checkbox" id="isDelTag$j" name="productinfoAdd[productItem][$j][isDelTag]"  />
			            </td>
                        <td>
                       		 $accessSeNum
                        </td>
						<td>
							<input type="text" class="txtshort"  id="productCode$j" value="$val[productCode]" name="productinfoAdd[productItem][$j][productCode]" />
							<input type="hidden" class="txt" id="aConfigId$j" value="$val[id]" name="productinfoAdd[productItem][$j][productId]" />
						</td>
						<td>
							<input type="text" class="txt" id="aConfigName$j" value="$val[productName]" name="productinfoAdd[productItem][$j][productName]" />
						</td>
						<td>
							<input type="text" class="readOnlyTxtItem" readOnly id="aConfigPattern$j" value="$val[pattern]" name="productinfoAdd[productItem][$j][pattern]" />
						</td>
				 	</tr>
EOT;
				$j ++;
			}
		}
		return $accessStr;		
	}
	
	/*====================================================业务数据处理=======================================*/
	/**
	 * 新增产品信息时，如果产品为硬件类时，同时添加所对应的配件信息
	 */
	function add_d($productinfo,$isAddInfo=true,$stat='save') {
		try {	
			$this->start_d ();
			$status=$this->statusDao->statusEtoK($stat);
			$productinfo['status']=$status;
			$productinfo['state']=$this->state[$status];
			$datadictDao = new model_system_datadict_datadict ();
			$productinfo ['statTypeName'] = $datadictDao->getDataNameByCode ( $productinfo ['statType'] );
			//判断重复
			if (parent::isRepeat ( array ("productCodeEq" => $productinfo ['productCode'] ), '' )) {
				throw new Exception ( "物料编号已存在！" );
			}
			$id = parent::add_d ( $productinfo, true );
			$this->updateObjWithFile ( $id );
								
			$configurationDao = new model_stock_productinfo_configurationAdd ();
			//新增配置信息
			if (is_array ( $productinfo ['configItem'] )) {
				$configItemArr = $this->setItemMainId ( "hardWareId", $id, $productinfo ['configItem'] );
				
				$configItemObj = $configurationDao->saveDelBatch ( $configItemArr );
			}
			//新增配件清单
			if (is_array ( $productinfo ['accessItem'] )) {
				$accessItemArr = $this->setItemMainId ( "hardWareId", $id, $productinfo ['accessItem'] );
				$accessItemObj = $configurationDao->saveDelBatch ( $accessItemArr );
			}

			//更新操作日志
			$logSettringDao = new model_syslog_setting_logsetting ();
			$logSettringDao->addObjLog ( $this->tbl_name, $id, $productinfo );
			/*
			//发送邮件
			$wlstatus="开放";
			if("WLSTATUSKF"!=$productinfo ['ext1']){
				$wlstatus="关闭";
			}
			$dataDict=new model_system_datadict_datadict();
			$wlsx=$dataDict->getDataNameByCode($productinfo ['properties'] );
			$sendContent = "各位好:<br/>OA系统新增了物料,详细如下：.<br/>";
			$sendContent .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr><td>物料类型:</td><td>" . $productinfo ['proType'] . "</td><td>状态:</td><td>" . $wlstatus. "</td></tr>"
							."<tr><td>物料编码:</td><td>" . $productinfo ['productCode'] . "</td><td>物料名称：</td><td>" . $productinfo ['productName'] . "</td></tr>"
							."<tr><td>型号/版本号:</td><td>" . $productinfo ['pattern'] . "</td><td>保修期：</td><td>" . $productinfo ['warranty'] . "</td></tr>"
							."<tr><td>交货周期:</td><td>" . $productinfo ['arrivalPeriod'] . "</td><td>采购周期：</td><td>" . $productinfo ['purchPeriod'] . "</td></tr>"
							."<tr><td>采购负责人:</td><td>" . $productinfo ['purchUserName'] . "</td><td>物料属性：</td><td>" . $wlsx . "</td></tr>"
							."<tr><td>单位:</td><td>" . $productinfo ['unitName'] . "</td><td>品牌：</td><td>" . $productinfo ['brand'] . "</td></tr>"
							."<tr><td>K3编码:</td><td>" . $productinfo ['ext2'] . "</td><td>物料成本：</td><td>" . $productinfo ['priCost'] . "</td></tr>"
							."<tr><td>封装:</td><td colspan='3'>" . $productinfo ['packageInfo'] . "</td></tr></table>";					
			$sendContent .= "</table>";
			$emailDao = new model_common_mail ();
			$emailDao->mailClear ( "新增物料", $this->mailArr['TO_ID'], $sendContent );*/
			
			$this->commit_d ();
	
			return $id;
		} catch ( Exception $e ) {
			echo $e->getMessage();
			$this->rollBack ();
			return null;
		}
	
	}
	/**
	 * 修改硬件类产品信息时，同时修改硬件所对应的配件信息
	 */
	function edit_d($productinfo,$isAddInfo=true,$stat=null) {
		try {
			$this->start_d ();
			//判断重复
			if (parent::isRepeat ( array ("nproductCode" => $productinfo ['productCode'] ), $productinfo ['id'] )) {
				throw new Exception ( "物料编号已存在！" );
			}
			if (! isset ( $productinfo ['encrypt'] )) { //判断是否为加密配置
				$productinfo ['encrypt'] = "";
			}
			if (! isset ( $productinfo ['esmCanUse'] )) { //判断是否为工程可用
				$productinfo ['esmCanUse'] = 0;
			}
			if (! isset ( $productinfo ['allocation'] )) { //判断是否为物料配置
				$productinfo ['allocation'] = "";
			}
			if($stat){
				$oldObj = $this->get_d ( $productinfo ['id'] );
				$status=$this->statusDao->statusEtoK($stat);
				$productinfo['status']=$status;
				$productinfo['state']=$this->state[$status];
			}
			parent::edit_d ( $productinfo, true );
			
			$configurationDao = new model_stock_productinfo_configurationAdd ();
			//新增配置信息
			if (is_array ( $productinfo ['configItem'] )) {
				$configItemArr = $this->setItemMainId ( "hardWareId", $productinfo ['id'], $productinfo ['configItem'] );
				$configItemObj = $configurationDao->saveDelBatch ( $configItemArr );
			}
			//新增配件清单
			if (is_array ( $productinfo ['accessItem'] )) {
				$accessItemArr = $this->setItemMainId ( "hardWareId", $productinfo ['id'], $productinfo ['accessItem'] );
				$accessItemObj = $configurationDao->saveDelBatch ( $accessItemArr );
			}
			
			//更新操作日志
			$logSettringDao = new model_syslog_setting_logsetting ();
			$logSettringDao->compareModelObj ( $this->tbl_name, $oldObj, $productinfo );
			//
			$this->commit_d ();
			return $productinfo;
		} catch ( Exception $e ) {
			echo $e->getMessage();
			$this->rollBack ();
			return null;
		}
	
	}
	
	/**
	 * 重写根据主键获取产品信息，并获取硬件对应的配件信息
	 */
	function get_d($id) {
		$configurationDao = new model_stock_productinfo_configurationAdd ();
		$configurations = $configurationDao->getConfigByHardWareId ( $id );
		$productinfo = parent::get_d ( $id );
		$productinfo ['configurations'] = $configurations;
		return $productinfo;
	}
	
	/**
	 * @desription 根据仓库Id找仓库中的产品
	 * @param tags
	 * @date 2010-12-25 下午05:12:55
	 * @qiaolong
	 */
	function getPdinfoByStockId($stockId) {
		$inventoryinfonewDao = new model_stock_inventoryinfo_inventoryinfo ();
		$inventoryinfonewDao->searchArr ['stockId'] = $stockId;
		return $inventoryinfonewDao->pageBySqlId ( 'select_all' );
	}
	
	/**
	 *根据编码查找产品信息
	 */
	function getProByCode($proCode) {
		$this->searchArr ['nproductCode'] = $proCode;
		$proArr = $this->list_d ();
		if (is_array ( $proArr ))
			return $proArr [0];
		else
			return null;
	
	}
	
	/**
	 *
	 * 根据Excel信息新增物料
	 * @param $objArr
	 */
	function importProInfo($objArr) {
		try {
			$this->start_d ();
			set_time_limit ( 0 );
			$resultArr = array ();
			$proTypeDao = new model_stock_productinfo_producttype ();
			$dataDictDao = new model_system_datadict_datadict ();
			$commonDataDao = new model_common_otherdatas ();
			$dataDictOpt = $dataDictDao->getDatadictsByParentCodes ( array ("WLSTATUS", "WLSX" ) );
			
			$propertiesArr = array ("外购商品" => "WLSXWG", "原材料" => "WLSXWG", "包装物" => "WLSXWG", "成品" => "WLSXZZ", "半成品" => "WLSXZZ" );
			
			foreach ( $objArr as $key => $obj ) {
				if (! empty ( $obj ['0'] )) {
					$ext1StatusCode = "WLSTATUSKF"; //状态编码
					$proTypeObj = $proTypeDao->getByTypeLevel ( $obj [0], $obj [1], $obj [2], $obj [3] );
					
					if (isset ( $dataDictOpt ['WLSTATUS'] )) { //物料状态
						foreach ( $dataDictOpt ['WLSTATUS'] as $key1 => $dataObj ) {
							if ($dataObj ['dataName'] == $obj [10]) {
								$ext1StatusCode = $dataObj ['dataCode'];
								break;
							}
						}
					}
					$propertiesCode = "";
					if (isset ( $dataDictOpt ['WLSX'] )) { //物料属性(自制、外购)
						foreach ( $dataDictOpt ['WLSX'] as $key1 => $dataObj ) {
							if ($dataObj ['dataName'] == $obj [11]) {
								$propertiesCode = $dataObj ['dataCode'];
								break;
							}
						}
					}
					
					$purchUserCode = $commonDataDao->getUserID ( $obj [14] );
					$purchUserName = "";
					if ($purchUserCode != "") {
						$purchUserName = $obj [15];
					}
					
					$tempObj = array ("proTypeId" => $proTypeObj ['id'], "proType" => $proTypeObj ['proType'], //物料类型
"productCode" => $obj [5], //物料编码
"productName" => $obj [6], //物料名称
"ext2" => $obj [7], //k3编码
"unitName" => $obj [8], "pattern" => $obj [9], "ext1" => $ext1StatusCode, //开放状态,
"properties" => $propertiesCode, "warranty" => $obj [12], //保修期
"arrivalPeriod" => $obj [13], //交货周期
"purchPeriod" => $obj [14], //采购周期
"purchUserCode" => $purchUserCode, //采购负责人code
"purchUserName" => $purchUserName, //采购负责人Name
"brand" => $obj [16], //品牌
"packageInfo" => $obj [17], //封装
"material" => $obj [18], //材料
"color" => $obj [19], //颜色
"leastPackNum" => $obj [20], //最小包装量
"leastOrderNum" => $obj [21], //最小订单量
"supplier" => $obj [22], //厂家信息
"remark" => $obj [23] );
					//判断物料编码对应信息是否存在
					$this->searchArr = null;
					$this->searchArr ['productCode'] = $tempObj ['productCode'];
					$searchResult = $this->list_d ();
					if (is_array ( $searchResult )) {
						array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "此物料编号已存在" ) );
					} else { //不存在的进行独立新增
						if (! empty ( $tempObj ['ext2'] )) { //k3编码不为空,表示是旧物料,相反则是新系统才启用的物料信息
							$this->searchArr = null;
							$this->searchArr ['ext2'] = $tempObj ['ext2'];
							
							if (is_array ( $this->list_d () )) {
								array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "该物料对应的k3编码已存在,请确认!" ) );
							} else {
								if ($this->add_d ( $tempObj )) {
									array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "导入成功" ) );
								} else {
									array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "导入失败,请确认相关信息是否正确!" ) );
								}
							}
						} else {
							if ($this->add_d ( $tempObj )) {
								array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "导入新物料成功" ) );
							} else {
								array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "导入失败,请确认新物料信息是否正确!" ) );
							}
						}
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
	 *
	 * 根据Excel信息更新物料
	 * @param $objArr
	 */
	function updateProByExcel($objArr) {
		try {
			$this->start_d ();
			set_time_limit ( 0 );
			$resultArr = array ();
			$proTypeDao = new model_stock_productinfo_producttype ();
			$dataDictDao = new model_system_datadict_datadict ();
			$commonDataDao = new model_common_otherdatas ();
			$dataDictOpt = $dataDictDao->getDatadictsByParentCodes ( "WLSTATUS" );
			
			$propertiesArr = array ("外购商品" => "WLSXWG", "原材料" => "WLSXWG", "包装物" => "WLSXWG", "成品" => "WLSXZZ", "半成品" => "WLSXZZ" );
			
			foreach ( $objArr as $key => $obj ) {
				if (! empty ( $obj ['0'] )) {
					$ext1StatusCode = "WLSTATUSKF"; //状态编码
					

					if (isset ( $dataDictOpt ['WLSTATUS'] )) { //物料状态
						foreach ( $dataDictOpt ['WLSTATUS'] as $key1 => $dataObj ) {
							if ($dataObj ['dataName'] == $obj [5]) {
								$ext1StatusCode = $dataObj ['dataCode'];
								break;
							}
						}
					}
					
					$purchUserCode = $commonDataDao->getUserID ( $obj [9] );
					$purchUserName = "";
					if ($purchUserCode != "") {
						$purchUserName = $obj [9];
					}
					
					$tempObj = array ("productCode" => $obj [0], //物料编码
"productName" => $obj [1], //物料名称
"ext2" => $obj [2], //k3编码
"unitName" => $obj [3], "pattern" => $obj [4], "ext1" => $ext1StatusCode, //开放状态,
"warranty" => $obj [6], //保修期
"arrivalPeriod" => $obj [7], //交货周期
"purchPeriod" => $obj [8], //采购周期
"purchUserCode" => $purchUserCode, //采购负责人code
"purchUserName" => $purchUserName, //采购负责人Name
"brand" => $obj [10], //品牌
"packageInfo" => $obj [11], //封装
"material" => $obj [12], //材料
"color" => $obj [13], //颜色
"leastPackNum" => $obj [14], //最小包装量
"leastOrderNum" => $obj [15], //最小订单量
"supplier" => $obj [16], //厂家信息
"remark" => $obj [17] );
					
					//判断物料编码对应信息是否存在,如果存在则更新
					$this->searchArr = null;
					$this->searchArr ['productCode'] = $tempObj ['productCode'];
					$searchResult = $this->list_d ();
					if (is_array ( $searchResult )) {
						$tempObj ['id'] = $searchResult [0] ['id'];
						if ($this->updateById ( $tempObj )) {
							array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "物料信息更新成功" ) );
						} else {
							array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "物料信息更新失败" ) );
						}
					} else {
						array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "导入失败,此物料编号系统不存在!" ) );
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
	 *
	 * 根据Excel信息更新物料成本
	 * @param $objArr
	 */
	function updatePriceByExcel($objArr) {
		try {
			$this->start_d ();
			set_time_limit ( 0 );
			$resultArr = array ();
			
			foreach ( $objArr as $key => $obj ) {
				if (! empty ( $obj ['0'] )) {
					//	print_r($obj);
					$this->searchArr = null;
					$this->searchArr ['nproductCode'] = $obj ['0'];
					$tempObj ['productCode'] = $obj ['0'];
					$searchResult = $this->listBySqlId ();
					if (is_array ( $searchResult )) {
						$tempObj ['id'] = $searchResult [0] ['id'];
						$tempObj ['priCost'] = $obj [1];
						if ($this->updateById ( $tempObj )) {
							array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "物料信息更新成功" ) );
						} else {
							array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "物料信息更新失败" ) );
						}
					} else {
						array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "导入失败,此物料编号系统不存在!" ) );
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
	 *
	 * 根据Excel信息更新物料K3编码
	 * @param $objArr
	 */
	function updateK3ByExcel($objArr) {
		try {
			$this->start_d ();
			set_time_limit ( 0 );
			$resultArr = array ();
			
			foreach ( $objArr as $key => $obj ) {
				if (! empty ( $obj ['0'] )) {
					//	print_r($obj);
					$this->searchArr = null;
					$this->searchArr ['productCode'] = $obj ['0'];
					$tempObj ['productCode'] = $obj ['0'];
					$searchResult = $this->listBySqlId ();
					if (is_array ( $searchResult )) {
						if (count ( $searchResult ) == 1) {
							$tempObj ['id'] = $searchResult [0] ['id'];
							$tempObj ['ext2'] = $obj [1];
//							print_r($tempObj);
							if ($this->updateById ( $tempObj )) {
								array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "物料信息更新成功" ) );
							} else {
								array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "物料信息更新失败" ) );
							}
						
						} else {
							array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "导入失败,此物料编号存在重复!" ) );
						}
					} else {
						array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "导入失败,此物料编号系统不存在!" ) );
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
	 *
	 * 根据K3编码获取物料信息
	 */
	function getProByExt2($proKCode) {
		$this->searchArr = array ("ext2" => $proKCode );
		$result = $this->list_d ();
		if (is_array ( $result )) {
			return $result [0];
		} else {
			return null;
		}
	
	}
	
	/**
	 * 根据产品类型id获取没有配件信息的物料
	 * @param $proTypeId
	 */
	function findNotAccessPro($proTypeId) {
		$sql = "select c.id from oa_stock_product_info c where not exists (select 1 from oa_stock_product_configuration p where p.hardWareId=c.id  and p.configType='proaccess')  and c.proTypeId='$proTypeId'";
		return $this->findSql ( $sql );
	
	}
	
	/**
	 * 根据产品类型id获取已经有配件信息的物料
	 * @param $proTypeId
	 */
	function findAccessPro($proTypeId) {
		$sql = "select c.id from oa_stock_product_info c where exists (select 1 from oa_stock_product_configuration p where p.hardWareId=c.id  and p.configType='proaccess')  and c.proTypeId='$proTypeId'";
		return $this->findSql ( $sql );
	
	}
	
	/**********************************临时物料信息处理*******************by LiuB*******************2011年6月29日10:57:11****************************************/
	/**
	 * 处理临时物料时新增临时物料至物料信息，同时将自定义清单物料转至产品清单
	 * bu LiuB
	 */
	function tempadd_d($productinfo) {
		try {
			$this->start_d ();
			$id = parent::add_d ( $productinfo );
			//新增配件信息
			if (is_array ( $productinfo ['configurations'] )) {
				$configurationDao = new model_stock_productinfo_configurationAdd ();
				foreach ( $productinfo ['configurations'] as $key => $value ) {
					if (! empty ( $value ['configName'] )) {
						$value ['hardWareId'] = $id;
						$configurationDao->add_d ( $value );
					}
				}
			}
			//获取原合同自定义清单的内容
			$type = $productinfo ['type'];
			$rows = $this->cusInfo ( $type, $productinfo ['tempId'] );
			unset ( $rows ['id'] );
			$rows ['productName'] = $productinfo ['productName'];
			$rows ['productModel'] = $productinfo ['pattern'];
			$rows ['productId'] = $id;
			$rows ['productNo'] = $productinfo ['productCode'];
			$rows ['warrantyPeriod'] = $productinfo ['warranty'];
			$rows ['unitName'] = $productinfo ['unitName'];
			$rows ['projArraDate'] = $rows ['projArraDT'];
			$rows ['license'] = $rows ['license'];
			
			//删除自定义清单内的物料信息
			if ($type == "oa_sale_order") {
				$tempProductDao = new model_projectmanagent_order_customizelist ();
				$tempProductDao->delete ( array ('id' => $productinfo ['tempId'] ) );
			} else if ($type == "oa_sale_service") {
				$customizelistDao = new model_engineering_serviceContract_customizelist ();
				$customizelistDao->delete ( array ('id' => $productinfo ['tempId'] ) );
			} else if ($type == "oa_sale_lease") {
				$customizelistDao = new model_contract_rental_customizelist ();
				$customizelistDao->delete ( array ('id' => $productinfo ['tempId'] ) );
			} else if ($type == "oa_sale_rdproject") {
				$customizelistDao = new model_rdproject_yxrdproject_customizelist ();
				$customizelistDao->delete ( array ('id' => $productinfo ['tempId'] ) );
			}
			
			//将自定义清单物料添加至产品清单
			if ($type == "oa_sale_order") {
				$orderequDao = new model_projectmanagent_order_orderequ ();
				$orderequDao->add_d ( $rows );
			
		//             $orderequDao->updateUniqueCode_d($rows['orderId']);
			} else if ($type == "oa_sale_service") {
				$serviceequDao = new model_engineering_serviceContract_serviceequ ();
				$serviceequDao->add_d ( $rows );
			} else if ($type == "oa_sale_lease") {
				$equDao = new model_contract_rental_tentalcontractequ ();
				$equDao->add_d ( $rows );
			} else if ($type == "oa_sale_rdproject") {
				$equDao = new model_rdproject_yxrdproject_rdprojectequ ();
				$equDao->add_d ( $rows );
			}
			$this->commit_d ();
			
			return $id;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	
	}
	
	/**
	 * 将自定义清单的物料写入产品清单
	 */
	function tempedit_d($productinfo) {
		try {
			$this->start_d ();
			
			//获取原合同自定义清单的内容
			$type = $productinfo ['type'];
			$rows = $this->cusInfo ( $type, $productinfo ['tempId'] );
			$productinfo ['orderId'] = $rows ['orderId'];
			//删除自定义清单内的物料信息
			if ($type == "oa_sale_order") {
				$tempProductDao = new model_projectmanagent_order_customizelist ();
				$tempProductDao->delete ( array ('id' => $productinfo ['tempId'] ) );
			} else if ($type == "oa_sale_service") {
				$customizelistDao = new model_engineering_serviceContract_customizelist ();
				$customizelistDao->delete ( array ('id' => $productinfo ['tempId'] ) );
			} else if ($type == "oa_sale_lease") {
				$customizelistDao = new model_contract_rental_customizelist ();
				$customizelistDao->delete ( array ('id' => $productinfo ['tempId'] ) );
			} else if ($type == "oa_sale_rdproject") {
				$customizelistDao = new model_rdproject_yxrdproject_customizelist ();
				$customizelistDao->delete ( array ('id' => $productinfo ['tempId'] ) );
			}
			//将自定义清单物料添加至产品清单
			if ($type == "oa_sale_order") {
				$orderequDao = new model_projectmanagent_order_orderequ ();
				$orderequDao->add_d ( $productinfo );
			
		//             $orderequDao->updateUniqueCode_d($rows['orderId']);
			} else if ($type == "oa_sale_service") {
				$serviceequDao = new model_engineering_serviceContract_serviceequ ();
				$serviceequDao->add_d ( $productinfo );
			} else if ($type == "oa_sale_lease") {
				$equDao = new model_contract_rental_tentalcontractequ ();
				$equDao->add_d ( $productinfo );
			} else if ($type == "oa_sale_rdproject") {
				$equDao = new model_rdproject_yxrdproject_rdprojectequ ();
				$equDao->add_d ( $productinfo );
			}
			
			$this->commit_d ();
			//				$this->rollBack ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	
	}
	/**
	 * 根据合同类型 获取四种合同自定义清单信息
	 * by LiuB
	 */
	function typeInfo($type, $id) {
		if ($type == "oa_sale_order") {
			$tempProductDao = new model_projectmanagent_order_customizelist ();
			$rows = $tempProductDao->get_d ( $id );
		} else if ($type == "oa_sale_service") {
			$customizelistDao = new model_engineering_serviceContract_customizelist ();
			$rows = $customizelistDao->get_d ( $id );
		
		} else if ($type == "oa_sale_lease") {
			$customizelistDao = new model_contract_rental_customizelist ();
			$rows = $customizelistDao->get_d ( $id );
		} else if ($type == "oa_sale_rdproject") {
			$customizelistDao = new model_rdproject_yxrdproject_customizelist ();
			$rows = $customizelistDao->get_d ( $id );
		
		}
		return $rows;
	}
	//根据合同类型获取四种合同 原自定义清单内容
	function cusInfo($type, $id) {
		if ($type == "oa_sale_order") {
			$tempProductDao = new model_projectmanagent_order_customizelist ();
			$rows = $tempProductDao->tempget_d ( $id );
		} else if ($type == "oa_sale_service") {
			$customizelistDao = new model_engineering_serviceContract_customizelist ();
			$rows = $customizelistDao->tempget_d ( $id );
		
		} else if ($type == "oa_sale_lease") {
			$customizelistDao = new model_contract_rental_customizelist ();
			$rows = $customizelistDao->tempget_d ( $id );
		} else if ($type == "oa_sale_rdproject") {
			$customizelistDao = new model_rdproject_yxrdproject_customizelist ();
			$rows = $customizelistDao->tempget_d ( $id );
		}
		return $rows;
	}
	/****************************************************************************************************************************/
	
	/**
	 * 获取产品过滤需要的数据
	 * by Liub
	 */
	function productPage($cont) {
		$arr = array ();
		foreach ( $cont as $k => $v ) {
			$sql = "select * from oa_stock_product_info where proTypeId in (select id from oa_stock_product_type where  lft >(select lft from oa_stock_product_type where proType = '$v' ) and rgt < (select  rgt from oa_stock_product_type where proType = '$v') and (rgt-lft=1))";
			$row = $this->_db->getArray ( $sql );
			foreach ( $row as $key => $val ) {
				array_push ( $arr, $val );
			}
		}
		return $arr;
	}
	
	/**
	 *
	 * 更新物料相关的业务对象信息
	 * @param  $productinfo
	 * @param  $relationArr
	 */
	function updateProductinfo($productinfo, $relationArr) {
		try {
			$this->start_d ();
			$this->edit_d ( $productinfo );
			include_once ("model/stock/productinfo/productinfoRelationTableArr.php");
			foreach ( $productRelationTableArr as $objArr ) {
				if (is_array ( $objArr )) {
					foreach ( $objArr as $key => $value ) {
						if (in_array ( $key, $relationArr )) {
							$sql = "update " . $key . " set ";
							foreach ( $value as $k => $v ) {
								if (! empty ( $v ) && ! empty ( $productinfo [$k] )) {
									$sql .= $v . "='" . $productinfo [$k] . "',";
								}
							}
							$sql = substr ( $sql, 0, - 1 );
							$sql .= " where " . $value ['id'] . " =" . $productinfo ['id'];
							if (! empty ( $productinfo ['updateStartDate'] )) {
								$sql .= " and createTime >= cast('" . $productinfo ['updateStartDate'] . "' as datetime)";
							}
							if (! empty ( $productinfo ['updateEndDate'] )) {
								$sql .= " and createTime <= cast('" . $productinfo ['updateEndDate'] . "' as datetime)";
							}
							if (isset ( $value ['condition'] )) { //额外条件
								$sql .= $value ['condition'];
							}
							//echo $sql;
							$this->query ( $sql );
						}
					}
				} else {
					throw new Exception ( "合并失败,配置文件存在问题." );
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
	 * 根据名称更新id及编码
	 * @param  $productinfo
	 * @param  $relationArr
	 */
	function updateBusProductinfoByName($productinfo, $relationArr) {
		try {
			$this->start_d ();
			$this->edit_d ( $productinfo );
			include_once ("model/stock/productinfo/productinfoRelationTableArr.php");
			$table = $this->tbl_name;
			foreach ( $productRelationTableArr as $objArr ) {
				if (is_array ( $objArr )) {
					foreach ( $objArr as $key => $value ) {
						if (in_array ( $key, $relationArr )) {
							if (! empty ( $value ['productName'] )) {
								$sql = "update $key left join $table  on $key." . $value ['productName'] . "=$table.productName set $key." . $value ['id'] . "=$table.id";
								if (! empty ( $value ['productCode'] )) {
									$sql .= ",$key." . $value ['productCode'] . "=$table.productCode";
								}
								$sql .= " where $key." . $value ['productName'] . " ='" . $productinfo ['productName'] . "'";
								if (! empty ( $productinfo ['updateStartDate'] )) {
									$sql .= " and $key.createTime >= cast('$key." . $productinfo ['updateStartDate'] . "' as datetime)";
								}
								if (! empty ( $productinfo ['updateEndDate'] )) {
									$sql .= " and $key.createTime <= cast('$key." . $productinfo ['updateEndDate'] . "' as datetime)";
								}
								
								if (isset ( $value ['condition'] )) { //额外条件
									$sql .= $value ['condition'];
								}
								
								//echo $sql;
								$this->query ( $sql );
							}
						}
					}
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
	 * 根据编码更新id及编码
	 * @param  $productinfo
	 * @param  $relationArr
	 */
	function updateBusProductinfoByCode($productinfo, $relationArr) {
		try {
			$this->start_d ();
			$this->edit_d ( $productinfo );
			include_once ("model/stock/productinfo/productinfoRelationTableArr.php");
			$table = $this->tbl_name;
			foreach ( $productRelationTableArr as $objArr ) {
				if (is_array ( $objArr )) {
					foreach ( $objArr as $key => $value ) {
						if (in_array ( $key, $relationArr )) {
							if (! empty ( $value ['productCode'] )) {
								$sql = "update $key left join $table  on $key." . $value ['productCode'] . "=$table.productCode set $key." . $value ['id'] . "=$table.id";
								if (! empty ( $value ['productName'] )) {
									$sql .= ",$key." . $value ['productName'] . "=$table.productName";
								}
								$sql .= " where $key." . $value ['productCode'] . " ='" . $productinfo ['productCode'] . "'";
								if (! empty ( $productinfo ['updateStartDate'] )) {
									$sql .= " and $key.createTime >= cast('$key." . $productinfo ['updateStartDate'] . "' as datetime)";
								}
								if (! empty ( $productinfo ['updateEndDate'] )) {
									$sql .= " and $key.createTime <= cast('$key." . $productinfo ['updateEndDate'] . "' as datetime)";
								}
								if (isset ( $value ['condition'] )) { //额外条件
									$sql .= $value ['condition'];
								}
								//echo $sql;
								$this->query ( $sql );
							}
						}
					}
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
	 * 判断一个物料是否已经被业务对象关联，如果已经被关联，返回关联信息
	 * @param  $id
	 */
	function isProductinfoRelated($id) {
		//进行删除校验，如果已经关联了业务对象，则不允许删除
		include_once ("model/stock/productinfo/productinfoRelationTableArr.php");
		$this->tbl_name = "oa_stock_product_info";
		$productinfo = $this->get_d ( $id );
		foreach ( $productRelationTableArr as $objArr ) {
			if (is_array ( $objArr )) {
				foreach ( $objArr as $key => $value ) {
					$this->tbl_name = $key;
					$num = $this->findCount ( array ($value ['id'] => $id ) );
					if ($num > 0) {
						throw new Exception ( "删除失败.物料【" . $productinfo ['productName'] . "】已经被" . $num . "条【" . $value [0] . "】关联." );
						break;
					}
				}
			}
		}
		return false;
	}
	
	/**
	 * 判断一个物料是否已经被业务对象关联，如果已经被关联，抛出关联信息异常，抛出所有
	 * @param unknown_type $id
	 */
	function productRelateMsg($id) {
		//进行删除校验，如果已经关联了业务对象，则不允许删除
		include_once ("model/stock/productinfo/productinfoRelationTableArr.php");
		$this->tbl_name = "oa_stock_product_info";
		$productinfo = $this->get_d ( $id );
		$msg = "";
		foreach ( $productRelationTableArr as $objArr ) {
			if (is_array ( $objArr )) {
				foreach ( $objArr as $key => $value ) {
					$this->tbl_name = $key;
					$num = $this->findCount ( array ($value ['id'] => $id ) );
					if ($num > 0) {
						$msg .= $num . "条【" . $value [0] . "】关联.</br>";
					}
				}
			}
		}
		if (empty ( $msg )) {
			$msg = "物料【" . $productinfo ['productName'] . "】没有被任何业务对象关联.";
		} else {
			$msg = "物料【" . $productinfo ['productName'] . "】已经被</br>" . $msg;
		}
		return $msg;
	}
	
	/**
	 *批量删除物料
	 */
	function deletes_d($ids) {
		try {
			$this->start_d ();
			$idArr = explode ( ",", $ids );
			$customerTable = $this->tbl_name;
			$logSettringDao = new model_syslog_setting_logsetting ();
			$configDao = new model_stock_productinfo_configurationAdd ();
			foreach ( $idArr as $id ) {
				$this->isProductinfoRelated ( $id );
				$this->tbl_name = $customerTable;
				
				//删除对象操作日志
				$logSettringDao->deleteObjLog ( $this->tbl_name, $this->get_d ( $id ) );
				
				$this->deleteByPk ( $id );
				
				//删除对应的配置 配件
				$configDao->deleteByHardWareId ( $id );
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
	 *
	 * 根据物料id检查是否存在关联的业务信息
	 * @param $id
	 */
	function checkRelatedResult($id) {
		include_once ("model/stock/productinfo/productinfoRelationTableArr.php");
		//		$this->tbl_name = "oa_stock_product_info";
		foreach ( $productRelationTableArr as $objArr ) {
			if (is_array ( $objArr )) {
				foreach ( $objArr as $key => $value ) {
					$this->tbl_name = $key;
					$num = $this->findCount ( array ($value ['id'] => $id ) );
					if ($num > 0) {
						return "0";
					}
				}
			}
		}
		return "1";
	}
	
	/**
	 * 设置关联清单的从表的主表id信息
	 */
	function setItemMainId($mainIdName, $mainIdValue, $iteminfoArr) {
		$resultArr = array ();
		foreach ( $iteminfoArr as $key => $value ) {
			if (trim ( $value ['configName'] ) != "") {
				$value [$mainIdName] = $mainIdValue;
				array_push ( $resultArr, $value );
			}
		}
		return $resultArr;
	}
	/**
	 * 判断合同物料保修期与基础物料信息保修期的大小
	 * by Liubo   2011年11月7日
	 */
	function compareWarranty($proCode, $orderWrr) {
		$sql = "select warranty from " . $this->tbl_name . " where productCode = $proCode";
		$warr = $this->_db->getArray ( $sql );
		foreach ( $warr as $k => $v ) {
			$warranty = $warr [0] [warranty];
		}
		return $warranty;
	}
	
	/**
	 *
	 * 产生某个类型底下的流水号
	 * @param $proTypeId
	 */
	function produceSerialCode($proTypeId) {
		$resultArr = $this->listBySql ( "select ifnull(max(productCode),'') as maxProductCode from " . $this->tbl_name . " where proTypeId='$proTypeId'" );
		if (! empty ( $resultArr [0] ['maxProductCode'] )) {
			$maxCode = $resultArr [0] ['maxProductCode'];
			return $maxCode + 1;
		} else {
			return "";
		}
	}
	
	/**
	 * 保存更新物料配件
	 * 
	 */
	function editAccess($productinfo){
		try {
			$this->start_d ();
			$accessDao=new model_stock_productinfo_configurationAdd();
		
			foreach ($productinfoAdd['accessItem'] as $k => $access ) {
				foreach ($productinfoAdd['productItem'] as $j => $productObj){
					if(isset($productObj['isDelTag'])){
					if($productinfoAdd['actType']=="add"){//增加更新配件
						$editCondition=array("configType"=>"proaccess","hardWareId"=>$productObj['productId'],"configId"=>$access['productId']);
						
						$lastConfig=$accessDao->find($editCondition);
						if(is_array($lastConfig)){
							$lastConfig['configNum']=$access['proNum'];
							$lastConfig['explains']=$access['remark'];
							$accessDao->updateById($lastConfig);
						}else{
							$accessObj=array(
								"configType"=>"proaccess",
								"hardWareId"=>$productObj['productId'],
								"configId"=>$access['productId'],
								"configCode"=>$access['productCode'],
								"configName"=>$access['productName'],
								"configPattern"=>$access['pattern'],
								"configNum"=>$access['proNum'],
								"explains"=>$access['remark'],
							);
							$accessDao->add_d($accessObj);						
						}
					}else{//删除配件
						$conditions=array("configType"=>"proaccess","configId"=>$access['productId'],"hardWareId"=>$productObj['productId']);
						$accessDao->delete($conditions);
					}		
					}
				}
			}
			$this->commit_d ();
			return true;
		} catch ( exception $e ) {
			$this->rollBack ();
			return false;
		}
	}
	
	/****************************** S 工程使用部分 **************************************/
	
	/**
	 * 工程启用
	 */
	function openEsmCanUse_d($id, $typeId) {
		//实例化物料类型
		$productTypeDao = new model_stock_productinfo_producttype ();
		try {
			$this->start_d ();
			
			//更新本物料启用状态
			$sql = "update " . $this->tbl_name . " set esmCanUse = 1 where id = $id";
			$this->_db->query ( $sql );
			
			//获取所有上级
			$typeParentIds = $productTypeDao->getParentIds_d ( $typeId );
			
			//将上级改成工程启用状态
			$productTypeDao->openEsmCanUse_d ( $typeParentIds );
			
			$this->commit_d ();
			return true;
		} catch ( exception $e ) {
			$this->rollBack ();
			return false;
		}
	}
	
	/**
	 * 工程关闭
	 */
	function closeEsmCanUse_d($id, $typeId) {
		//实例化物料类型
		$productTypeDao = new model_stock_productinfo_producttype ();
		try {
			$this->start_d ();
			
			//更新本物料启用状态
			$sql = "update " . $this->tbl_name . " set esmCanUse = 0 where id = $id";
			$this->_db->query ( $sql );
			
			//获取所有上级
			//			$typeParentIds = $productTypeDao->getParentIds_d($typeId);
			

			//将上级改成工程启用状态
			$productTypeDao->closeProType_d ( $typeId, $this );
			
			$this->commit_d ();
			return true;
		} catch ( exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

/****************************** E 工程使用部分 **************************************/
	/**
	 * 
	 * 更改提交状态
	 * @param $statusName
	 */
	function changeStatus($id,$statusName){
		$status=$this->statusDao->statusEtoK($statusName);
		$state=$this->state[$status];
		return $this->update ( array('id'=>$id), array ('status' => $status,'state'=>$state ) );
	}
	/**
	 * 
	 * 更改确认状态
	 * @param $id
	 * @param $stateId
	 */
	function changeState($id,$stateId){
		$state=$this->state[$stateId];
		if(!empty($state)){
			try {
				$this->start_d ();
				if($state=='已确认')
					$this->addProInfo($id);  //如果是确认物料信息，则提交数据到物料信息表
			    $this->update ( array('id'=>$id), array ('state'=>$state ) );
				$this->commit_d ();
				return true;
			} catch (Exception $e) {
				echo $e->getMessage();
				$this->rollBack ();
				return false;
			}
		}else{
			return false;
		}
		
	}
	/**
	 * 
	 * 更改提交状态和确认状态
	 */
	function changeCommit($id,$statusName,$stateId){
		try{
			$this->start_d ();
			$this->changeStatus($id,$statusName);
			$this->changeState($id,$stateId);
			$this->commit_d ();
			return true;
		}catch (Exception $e){
			$this->rollBack ();
			return false;
		}
	}
	/**
	 * 
	 * 确认后，提交数据到物料信息表
	 * @param $id
	 */
	function addProInfo($id){
		$obj=$this->get_d($id);
		$productinfo=new model_stock_productinfo_productinfo();
		$uploadFile = new model_file_uploadfile_management ();
		$configItem=array();
		$accessItem=array();
		if(is_array($obj['configurations']))
		foreach ($obj['configurations'] as $val){
				unset($val['id']);
				unset($val['hardWareId']);
			if($val['configType']=='proconfig'){			
				$configItem[]=$val;
			}
		if($val['configType']=='proaccess'){
				$accessItem[]=$val;
			}
		}
		unset($obj['configurations']);
		if(!empty($configItem))
			$obj['configItem']=$configItem;
		if(!empty($accessItem))
			$obj['accessItem']=$accessItem;
		/*	echo '<pre>';
			print_r($obj);
			exit;*/
		$id=$obj['id'];
		unset($obj['id']);
		//将数据添加到物料信息表
		$productinfoId=$productinfo->add_d($obj);
		if(!$productinfoId){
			throw new Exception ( "操作失败，编码已存在或未知错误！" );
			return false;
		}
		//关联附件
		$uploadFileRow=$uploadFile->findAll(array('serviceType'=>$this->tbl_name,'serviceId'=>$id));
		if(is_array($uploadFileRow)&&!empty($uploadFileRow))
		foreach ($uploadFileRow as $row){
			unset($row['id']);
			$row['serviceType']=$productinfo->tbl_name;
			$row['serviceId']=$productinfoId;
			if(!$uploadFile->add_d($row)){
				throw new Exception ( "操作失败，附件关联错误！" );
				return false;
			}
		}
		return true;
	}
	/**
	 * 
	 * 重写检查重复方法，检查新增物料表和物料信息表
	 * @param unknown_type $searchArr
	 * @param unknown_type $checkId
	 */
	function isRepeat($searchArr, $checkId=''){
		  if (parent::isRepeat ( $searchArr, $checkId )) {
				return true;
			}
			$productinfo_=new model_stock_productinfo_productinfo();  //物料信息表是否存在
			if ($productinfo_->isRepeat ( $searchArr, $checkId)) {
				
				return true;
		}

		return false;
	}
	
	/**
	 * 邮件发送
	 */
	function thisMail_d($object) {
		include (WEB_TOR . "model/common/mailConfig.php");
		$emailArr = isset ($mailUser['oa_stock_product_info_temp']) ? $mailUser['oa_stock_product_info_temp'] : array (
			'TO_ID'=>'',
			'TO_NAME'=>''
			);
			$nameStr = $emailArr['TO_NAME'];
			$addMsg = $_SESSION['USERNAME'] .'<br>【物料管理】<br>
				 物料编号 :【<font color="red">'.$object['productCode'].'</font>】<br>
				 物料名称 :【<font color="red">'.$object['productName'].'</font>】<br> 
		 		状态 :【<font color="red">'.$object['state'].'</font> 】<br>';
			$emailDao = new model_common_mail();
			$emailDao->mailClear('物料管理-【物料信息】', $emailArr['TO_ID'], $addMsg);
	}
}
?>
