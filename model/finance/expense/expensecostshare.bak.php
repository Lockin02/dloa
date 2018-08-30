<?php

/**
 * @author Show
 * @Date 2016年1月27日 星期三 11:07:46
 * @version 1.0
 * @description:报销申请分摊明细(部门报销) Model层
 */
class model_finance_expense_expensecostshare extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_finance_costshare";
        $this->sql_map = "finance/expense/expensecostshareSql.php";
        parent:: __construct();
    }

    /**
     * 根据传入的对象数组自动进行新增，修改，删除(主要用于解决主从表中对从表对象的批量操作)
     * 判断规则：
     * 1.如果id为空且isDelTag属性为1（这种情况属于如界面上添加后删除情况,后台啥都不做）
     * 2.如果id为空，则新增
     * 3.如果isDelTag属性为1，则删除
     * 4.否则修改
     * @param Array $objs
     * @return array|bool
     * @throws Exception
     */
    function saveDelBatch($objs) {
        try {
            $returnObjs = array();
            $datadictDao = new model_system_datadict_datadict();
            // 所属板块
            $moduleArr = $datadictDao->getDataDictList_d('HTBK', array('isUse' => 0));
            foreach ($objs as $key => $val) {
            	$allDelTag = isset($val ['isDelTag']) ? $val ['isDelTag'] : NULL;
            	//具体明细处理
            	foreach ($val['expenseinv'] as $k => $v){
            		if($allDelTag == 1 && !empty($v ['ID'])){//删除全部
            			$this->deletes($v ['ID']);
            		}else{
            			$isDelTag = isset($v ['isDelTag']) ? $v ['isDelTag'] : NULL;
            			if ((empty ($v ['ID']) && $isDelTag == 1)) {
            			
            			} else if (empty ($v ['ID'])) {
            				//只添加有效的数据
            				if (!empty($v['CostMoney'])) {
            					if(isset($moduleArr[$v['module']])){//所属板块数据字典处理
            						$v['moduleName'] = $moduleArr[$v['module']];
            					}
            					//再新增费用类型部分
            					$v['MainType'] = $val['MainType'];
            					$v['MainTypeId'] = $val['MainTypeId'];
            					$v['CostType'] = $val['CostType'];
            					$v['CostTypeID'] = $val['CostTypeID'];
            					$v['Remark'] = $val['Remark'];
            					$v['BillNo'] = $val['BillNo'];
            					parent::add_d($v);
            					array_push($returnObjs, $v);
            				}
            			} else if ($isDelTag == 1) {
            				//先删除费用
            				$this->deletes($v ['ID']);
            			} else {
            				//修改部分
            				if (empty($v['CostMoney'])) {
            					//先删除费用
            					$this->deletes($v ['ID']);
            				} else {
            					//具体明细处理
            					if(isset($moduleArr[$v['module']])){//所属板块数据字典处理
            						$v['moduleName'] = $moduleArr[$v['module']];
            					}
            					//再新增费用类型部分
            					$v['Remark'] = $val['Remark'];
            					parent::edit_d($v);
            					array_push($returnObjs, $v);
            				}
            			}
            		}
            	}
            }
            return $returnObjs;
        } catch (Exception $e) {
            echo $e->getMessage();
            throw $e;
        }
    }

    /**
     * 根据主键修改记录
     * @param $object
     * @return mixed
     */
    function updateById($object) {
        $condition = array("ID" => $object ['ID']);
        return $this->update($condition, $object);
    }

    /**
     * 获取对应明细
     * @param $BillNo
     * @return bool
     */
    function getBillDetail_d($BillNo) {
        $this->searchArr = array(
            'BillNo' => $BillNo
        );
        $this->asc = false;
        $expensecostshare = $this->list_d();
        
        //分摊明细处理
        $moduleArr = array();
        foreach ($expensecostshare as $v){
        	if(array_key_exists($v['CostTypeID'],$moduleArr)){
        		array_push($moduleArr[$v['CostTypeID']]['detail'], array(
        		'ID' => $v['ID'],
        		'CostMoney' => $v['CostMoney'],
        		'module' => $v['module'],
        		'moduleName' => $v['moduleName']
        		));
        	}else{
        		$v['detail']['0'] = array(
        				'ID' => $v['ID'],
        				'CostMoney' => $v['CostMoney'],
        				'module' => $v['module'],
        				'moduleName' => $v['moduleName']
        		);
        		unset($v['ID']);
        		unset($v['CostMoney']);
        		unset($v['module']);
        		unset($v['moduleName']);
        		$moduleArr[$v['CostTypeID']] = $v;
        	}
        }
        return $moduleArr;
    }

    /**
     * 渲染对应明细
     * @param $BillDetail
     * @return string
     */
    function initBillDetailView_d($BillDetail) {
        if ($BillDetail) {
            $str = "<tbody>";
            $allCostMoney = 0;
            foreach ($BillDetail as $val) {
            	$str .= <<<EOT
            				<tr>
				            	<td valign="top" class="form_text_right">
				                	$val[MainType]
				                </td>
				                <td valign="top" class="form_text_right">
				                	$val[CostType]
				                </td>
								<td valign="top" colspan="2" class="innerTd">
				            		<table class="form_in_table">
				                    	<tbody>
EOT;
            	foreach ($val['detail'] as $k => $v){
            		$allCostMoney = bcadd($allCostMoney, $v[CostMoney]);
            		$str .= <<<EOT
            				<tr>
				                <td style="width:146px;text-align:right;" valign="top">
				                    <span class="formatMoney" >$v[CostMoney]</span>
				                </td>
			                    <td valign="top" class="form_text_right" style="width:90px;">
	                            	$v[moduleName]
			                    </td>
			            	</tr>
EOT;
            	}
            	$str .= <<<EOT
            							</tbody>
            						</table>
            					</td>
            					<td valign="top" class="form_text_right">$val[Remark]</td>
			            	</tr>
EOT;
            }
            $str .= <<<EOT
            	        </tbody>
            	        <tr class="tr_count">
	                        <td class="form_text_right">合计</td>
	                        <td></td>
	                        <td><input type="text" class="readOnlyTxtCount formatMoney" style="width:146px;text-align:right;" id="allCostshareMoney" value="$allCostMoney" readonly="readonly"/>
	                        </td>
	                        <td><input type="text" class="readOnlyTxtCount" style="width:90px" readonly="readonly"/>
	                        </td>
	                        <td colspan="2" align="left"></td>
                    	</tr>
EOT;
            return $str;
        }
    }

    /**
     * 渲染对应明细
     * @param $BillDetail
     * @return string
     */
    function initBillDetailViewEdit_d($BillDetail) {
        if ($BillDetail) {
        	$str = "<tbody>";
        	$allCostMoney = 0;
        	foreach ($BillDetail as $val) {
        		$str .= <<<EOT
            				<tr>
				            	<td valign="top" class="form_text_right">
				                	$val[MainType]
				                	<input type="hidden" id="MainType$val[MainTypeId]" value="$val[MainTypeId]"/>
				                </td>
				                <td valign="top" class="form_text_right">
				                	<img src="images/changeedit.gif" id="imgDetail$val[CostTypeID]" onclick="changeDetailCostShare('$val[CostTypeID]','$val[CostType]','$val[MainTypeId]','$val[MainType]')" title="修改分摊信息"/>
				                	$val[CostType]
				                </td>
								<td valign="top" colspan="2" class="innerTd">
				            		<table class="form_in_table">
				                    	<tbody>
EOT;
        		foreach ($val['detail'] as $k => $v){
        			$allCostMoney = bcadd($allCostMoney, $v[CostMoney]);
        			$str .= <<<EOT
            				<tr>
				                <td style="width:146px;text-align:right;" valign="top">
				                    <span class="formatMoney" >$v[CostMoney]</span>
				                </td>
			                    <td valign="top" class="form_text_right" style="width:90px;">
	                            	$v[moduleName]
			                    </td>
			            	</tr>
EOT;
        		}
        		$str .= <<<EOT
            							</tbody>
            						</table>
            					</td>
            					<td valign="top" class="form_text_right">$val[Remark]</td>
			            	</tr>
EOT;
        	}
        	$str .= <<<EOT
            	        </tbody>
            	        <tr class="tr_count">
	                        <td class="form_text_right">合计</td>
	                        <td></td>
	                        <td><input type="text" class="readOnlyTxtCount formatMoney" style="width:146px;text-align:right;" id="allCostshareMoney" value="$allCostMoney" readonly="readonly"/>
	                        </td>
	                        <td><input type="text" class="readOnlyTxtCount" style="width:90px" readonly="readonly"/>
	                        </td>
	                        <td colspan="2" align="left"></td>
                    	</tr>
EOT;
        	return $str;
        }
    }
    
    /**
     * 编辑相应明细
     * @param $object
     * @return boolean
     */
    function editDetail_d($object){
    	$appArr = array(
    			'BillNo' => $object['BillNo'],
    			'MainType' => $object['MainTypeName'],
    			'MainTypeId' => $object['MainType'],
    			'CostType' => $object['CostType'],
    			'CostTypeID' => $object['CostTypeID'],
    			'Remark' => $object['RemarkCostshare']
    	);
    	$object['expensecostshare'] = util_arrayUtil::setArrayFn($appArr, $object['expensecostshare']);
    	$datadictDao = new model_system_datadict_datadict();
    	// 所属板块
    	$moduleArr = $datadictDao->getDataDictList_d('HTBK', array('isUse' => 0));
    	try {
    		$this->start_d();
    	
    		//具体明细处理
    		foreach ($object['expensecostshare'] as $k => $v){
    			if ((empty ($v ['ID']) && $v ['isDelTag'] == 1)) {
    					
    			} else if (empty ($v ['ID'])) {
    				//只添加有效的数据
    				if (!empty($v['CostMoney'])) {
    					if(isset($moduleArr[$v['module']])){//所属板块数据字典处理
    						$v['moduleName'] = $moduleArr[$v['module']];
    					}
    					parent::add_d($v);
    				}
    			} else if ($v ['isDelTag'] == 1) {
    				//先删除费用
    				$this->deletes($v ['ID']);
    			} else {
    				//修改部分
    				if (empty($v['CostMoney'])) {
    					//先删除费用
    					$this->deletes($v ['ID']);
    				} else {
    					//具体明细处理
    					if(isset($moduleArr[$v['module']])){//所属板块数据字典处理
    						$v['moduleName'] = $moduleArr[$v['module']];
    					}
    					parent::edit_d($v);
    				}
    			}
    		}
    	
    		$this->commit_d();
    		return true;
    	} catch (Exception $e) {
    		$this->rollBack();
    		return false;
    	}
    }
}