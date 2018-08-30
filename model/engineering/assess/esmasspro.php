<?php

/**
 * @author Show
 * @Date 2012年12月1日 星期六 9:53:08
 * @version 1.0
 * @description:项目考核指标 Model层
 */
class model_engineering_assess_esmasspro extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_project_assess";
		$this->sql_map = "engineering/assess/esmassproSql.php";
		parent :: __construct();
	}

	/********************* 增删改查 ***************/

	/**
	 * 添加对象
	 */
	function add_d($object) {
//		echo "<pre>";print_r($object);die();

		//指标获取
		$exmassproindexArr = $object['esmassproindex'];
		unset($object['esmassproindex']);

		try{
			$this->start_d();

			//新增
			$newId = parent::add_d($object);

			//实例化项目考核指标
			$esmassproindexDao = new model_engineering_assess_esmassproindex();
			//报销明细费用处理
			//关联数据复制
			$addArr = array(
				'assessId' => $newId
			);
			$exmassproindexArr = util_arrayUtil::setArrayFn($addArr,$exmassproindexArr);
			//插入费用明细
			$esmassproindexDao->saveDelBatch($exmassproindexArr);

			$this->commit_d();
			return $newId;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}
	/**
	 * 根据主键修改对象
	 */
	function edit_d($object) {
//		echo "<pre>";print_r($object);die();

		//指标获取
		$exmassproindexArr = $object['esmassproindex'];
		unset($object['esmassproindex']);

		try{
			$this->start_d();

			//编辑方法
			parent::edit_d($object);

			//实例化项目考核指标
			$esmassproindexDao = new model_engineering_assess_esmassproindex();
			//报销明细费用处理
			//关联数据复制
			$addArr = array(
				'assessId' => $object['id']
			);
			$exmassproindexArr = util_arrayUtil::setArrayFn($addArr,$exmassproindexArr);
			//插入费用明细
			$esmassproindexDao->saveDelBatch($exmassproindexArr);

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/************************ 其他业务处理 ****************/

	//获取项目信息
	function getPorjectInfo_d($projectId){
		$esmprojectDao = new model_engineering_project_esmproject();
		$esmprojectArr = $esmprojectDao->find(array('id'=>$projectId));
		$esmprojectArr['projectId'] = $esmprojectArr['id'];
		unset($esmprojectArr['id']);
		return $esmprojectArr;
	}

	/**
	 * 后台渲染指标
	 */
	function initEdit_d($object){
		$str = "";

		//获取指标
		$esmassproindexDao = new model_engineering_assess_esmassproindex();
		$esmassproindexArr = $esmassproindexDao->findAll(array('assessId' => $object['id']));
		if($esmassproindexArr){
			//必填选项
			$needIndexsArr = explode(',',$object['needIndexIds']);

			//选项对象实例化
			$optionsDao = new model_engineering_assess_esmassprooption();

			foreach($esmassproindexArr as $key => $val){
	            $trClass = $key%2 == 0? 'tr_odd' : 'tr_even';

				if(in_array($val['indexId'],$needIndexsArr)){
					$isNeed = '<span class="blue">是</span>';
					$disabled = 'disabled="disabled"';
					$indexNameClass = "readOnlyTxtMiddle";
					$readonly = "readonly='readonly'";
				}else{
					$isNeed = '否';
					$disabled = '';
					$indexNameClass = "txtmiddle";
					$readonly = "";
				}
				$str.=<<<EOT
					<tr id="tr$val[id]" class="$trClass">
						<td valign="top"><img src="images/removeline.png" onclick="removeIndex($key,this)" title="删除行"/></td>
						<td valign="top">
							<input type="text" class="$indexNameClass" name="esmasspro[esmassproindex][$key][indexName]" id="indexName$key" value="$val[indexName]" onblur="indexSet($key);" $readonly/>
							<input type="hidden" name="esmasspro[esmassproindex][$key][indexId]" id="indexId$key" value="$val[indexId]"/>
							<input type="hidden" name="esmasspro[esmassproindex][$key][id]" value="$val[id]"/>
						</td>
						<td valign="top">
							<input type="text" class="readOnlyTxtMiddle" name="esmasspro[esmassproindex][$key][upperLimit]" id="upperLimit$key" value="$val[upperLimit]" readonly="readonly"/>
						</td>
						<td valign="top">
							<input type="text" class="readOnlyTxtMiddle" name="esmasspro[esmassproindex][$key][lowerLimit]" id="lowerLimit$key" value="$val[lowerLimit]" readonly="readonly"/>
						</td>
						<td valign="top">$isNeed</td>
						<td valign="top">
							<input type="checkbox" id="chk$key" checked="checked" $disabled onclick="checkVal($key)" score="$val[upperLimit]" indexName="$val[indexName]" indexId="$val[id]"/>
							<input type="hidden" name="esmasspro[esmassproindex][$key][isUse]" id="isUse$key" value="1"/>
						</td>
	                    <td valign="top" id="innerTr_$key" colspan="3" style="text-align:left">
	                    	<span id="span_$key" ondblclick="showEditInfo($key);">$val[detail]</span>
	                        <table class="form_in_table" id="table_$key" style="display:none">
EOT;

                //详细选项部分处理
				$optionsArr = $optionsDao->findAll(array('detailId' => $val['id']));
				foreach($optionsArr as $k => $v){
					//id 字符串
					$countI = $key . "_" . $k;
					if($k == 0){
		                $str .=<<<EOT
		                    <tr>
		                        <td width="35%">
		                            <input type="text" name="esmasspro[esmassproindex][$key][options][$k][optionName]" id="optionName_$countI" value="$v[optionName]" class="txtmiddle" onblur="optionSet($key,$k);"/>
								<input type="hidden" name="esmasspro[esmassproindex][$key][options][$k][id]" value="$v[id]"/>
		                        </td>
		                        <td width="35%">
		                            <input type="text" name="esmasspro[esmassproindex][$key][options][$k][score]" id="score_$countI" value="$v[score]" class="txtshort" onblur="scoreSet($key,$k);"/>
		                        </td>
		                        <td width="30%">
									<img src="images/add_item.png" onclick="addOption($key,$k)" title="添加行"/>
		                        </td>
							</tr>
EOT;
					}else{
		                $str .=<<<EOT
		                    <tr>
		                        <td width="35%">
		                            <input type="text" name="esmasspro[esmassproindex][$key][options][$k][optionName]" id="optionName_$countI" value="$v[optionName]" class="txtmiddle" onblur="optionSet($key,$k);"/>
									<input type="hidden" name="esmasspro[esmassproindex][$key][options][$k][id]" value="$v[id]"/>
		                        </td>
		                        <td width="35%">
		                            <input type="text" name="esmasspro[esmassproindex][$key][options][$k][score]" id="score_$countI" value="$v[score]" class="txtshort" onblur="scoreSet($key,$k);"/>
		                        </td>
		                        <td width="30%">
									<img src="images/removeline.png" onclick="removeOption($key,$k,this)" title="删除行"/>
		                        </td>
							</tr>
EOT;
					}
				}

	            $str .=<<<EOT
	                        </table>
	                    </td>
	                </tr>
EOT;
			}
		}
		return $str;
	}

	//查看页面
	function initView_d($object){
		$str = "";

		//获取指标
		$esmassproindexDao = new model_engineering_assess_esmassproindex();
		$esmassproindexArr = $esmassproindexDao->findAll(array('assessId' => $object['id']));
		if($esmassproindexArr){
			//必填选项
			$needIndexsArr = explode(',',$object['needIndexIds']);

			//选项对象实例化
			$optionsDao = new model_engineering_assess_esmassprooption();

			foreach($esmassproindexArr as $key => $val){
	            $trClass = $key%2 == 0? 'tr_odd' : 'tr_even';
				$str.=<<<EOT
					<tr id="tr$val[id]" class="$trClass">
						<td valign="top">$val[indexName]
						</td>
						<td valign="top">$val[upperLimit]
						</td>
						<td valign="top">$val[lowerLimit]
						</td>
	                    <td valign="top" style="text-align:left">$val[detail]
	                    </td>
	                </tr>
EOT;
			}
		}
		return $str;
	}
}
?>