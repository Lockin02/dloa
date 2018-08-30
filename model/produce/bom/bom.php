<?php
/**
 * @author Administrator
 * @Date 2011年12月30日 11:45:00
 * @version 1.0
 * @description:BOM表 Model层 注:同一物料不能同时出现在同一BOM的父项物料与子项物料中
 */
class model_produce_bom_bom extends model_base {
	
	function __construct() {
		$this->tbl_name = "oa_produce_bom";
		$this->sql_map = "produce/bom/bomSql.php";
		parent::__construct ();
	}
	/**
	 *
	 * 编辑页面从表显示模板
	 * @param  $rows
	 */
	function showItemAtEdit($rows) {
		if ($rows) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			$datadictDao = new model_system_datadict_datadict ();
			foreach ( $rows as $key => $val ) {
				$seNum = $i + 1;
				$propertiesName = $datadictDao->getDataNameByCode ( $val ['properties'] );
				$str .= <<<EOT
				<tr align="center" >
								<td>
			                        <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行" />
			                    </td>
                               <td>
                                    $seNum
                                </td>
                                <td>
                                    <input type="text" name="bom[items][$i][productCode]" id="productCode$i" class="txtshort" value="{$val['productCode']}" />
                                    <input type="hidden" name="bom[items][$i][id]" id="id$i" value="{$val['id']}"  />
                                    <input type="hidden" name="bom[items][$i][productId]" id="productId$i" value="{$val['productId']}"  />
                                </td>
                                <td>
                                    <input type="text" name="bom[items][$i][productName]" id="productName$i" class="txt" value="{$val['productName']}" />
                                </td>
                                <td>
                                    <input type="text" name="bom[items][$i][pattern]" id="pattern$i" class="readOnlyTxtShort" value="{$val['pattern']}" />
                                </td>
                                <td>
                                    <input type="text"  id="propertiesName$i" class="readOnlyTxtShort" value="$propertiesName" />
                                    <input type="hidden" name="bom[items][$i][properties]" id="properties$i"  value="{$val['properties']}" />
                                </td>
                                <td>
                                    <input type="text" name="bom[items][$i][unitName]" id="unitName$i" class="readOnlyTxtShort" value="{$val['unitName']}" />
                                </td>
                                <td>
                                    <input type="text" name="bom[items][$i][useNum]" id="useNum$i" class="txtshort" value="{$val['useNum']}" />
                                </td>
		                </tr>
EOT;
				$i ++;
			}
			return $str;
		}
	}
	
	/**
	 *
	 * 查看页面从表显示模板
	 * @param  $rows
	 */
	function showItemAtView($rows) {
		if ($rows) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			$datadictDao = new model_system_datadict_datadict ();
			foreach ( $rows as $key => $val ) {
				$seNum = $i + 1;
				$propertiesName = $datadictDao->getDataNameByCode ( $val ['properties'] );
				$str .= <<<EOT
						<tr align="center" >
                               <td>
                                    $seNum
                               </td>
                               <td>
                                    $val[productCode]

                               </td>
                               <td>
									$val[productName]
                               </td>
                               <td>
                                    $val[pattern]
                               </td>
                               <td>
                                    $propertiesName

                               </td>
                               <td>
									$val[unitName]
                               </td>
                               <td>
                                    $val[useNum]
                               </td>
		                </tr>
EOT;
				$i ++;
			}
			return $str;
		}
	}
	
	/*--------------------------------------------业务操作--------------------------------------------*/
	
	/**
	 * 新增保存
	 * @see model_base::add_d()
	 */
	function add_d($object) {
		try {
			$this->start_d ();
			if (is_array ( $object ['items'] )) {
				$codeDao = new model_common_codeRule ();
				$object ['docCode'] = $codeDao->stockCode ( "oa_produce_bom", "BOM" );
				$id = parent::add_d ( $object, true );
				
				$bomitemDao = new model_produce_bom_bomitem ();
				$itemsArr = util_arrayUtil::setItemMainId ( "mainId", $id, $object ['items'] );
				
				//判断bom材料是否重复
				$codeKeyArr = array ();
				foreach ( $itemsArr as $key => $value ) {
					if (! isset ( $value ['isDelTag'] )) {
						if (! isset ( $codeKeyArr [$value ['productCode']] )) {
							$codeKeyArr [$value ['productCode']] = $value ['productCode'];
						} else {
							throw new Exception ( "bom材料存在重复,请重新设置!" );
						}
					}
				}
				
				$itemsObj = $bomitemDao->saveDelBatch ( $itemsArr );
				$this->commit_d ();
				return $id;
			} else {
				throw new Exception ( "单据信息不完整，请确认！" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			
			//			return null;
			throw $e;
		}
	}
	
	/**
	 * 修改保存
	 * @see model_base::edit_d()
	 */
	function edit_d($object) {
		try {
			$this->start_d ();
			if (is_array ( $object ['items'] )) {
				$editResult = parent::edit_d ( $object, true );
				
				$bomitemDao = new model_produce_bom_bomitem ();
				$itemsArr = util_arrayUtil::setItemMainId ( "mainId", $object ['id'], $object ['items'] );
				
				//判断bom材料是否重复
				$codeKeyArr = array ();
				foreach ( $itemsArr as $key => $value ) {
					if (! isset ( $value ['isDelTag'] )) {
						if (! isset ( $codeKeyArr [$value ['productCode']] )) {
							$codeKeyArr [$value ['productCode']] = $value ['productCode'];
						} else {
							throw new Exception ( "bom材料存在重复,请重新设置!" );
						}
					}
				}
				$itemsObj = $bomitemDao->saveDelBatch ( $itemsArr );
				
				$this->commit_d ();
				return $editResult;
			} else {
				throw new Exception ( "单据信息不完整，请确认！" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			//			return null;
			throw $e;
		}
	}
	
	/**
	 * 通过id获取详细信息
	 * @see model_base::get_d()
	 */
	function get_d($id) {
		$object = parent::get_d ( $id );
		$bomitemDao = new model_produce_bom_bomitem ();
		$bomitemDao->searchArr ['mainId'] = $id;
		$object ['items'] = $bomitemDao->listBySqlId ();
		return $object;
	
	}
	
	/**
	 * 
	 * 启用物料BOM设置
	 * @param  $id
	 * @param  $productCode
	 */
	function actUseStatus_d($id, $productId) {
		try {
			$this->start_d ();
			$this->query ( "update " . $this->tbl_name . " set useStatus='1' where productId=" . $productId );
			$object = array ("id" => $id, "useStatus" => "0" );
			$this->updateById ( $object );
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			throw $e;
		}
	}
}
?>