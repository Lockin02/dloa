<?php
/**
 * @author Show
 * @Date 2012年11月27日 星期二 11:40:15
 * @version 1.0
 * @description:考核指标表 Model层
 */
class model_engineering_assess_esmassindex extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_ass_index";
		$this->sql_map = "engineering/assess/esmassindexSql.php";
		parent :: __construct();
	}

	/**
	 * 添加对象
	 */
	function add_d($object) {
		//选项
		$esmassoptionArr = $object['esmassoption'];
		unset($object['esmassoption']);

		try{
			$this->start_d();
			//新增
			$newId = parent::add_d($object);

			//明细处理
			$esmassoptionDao = new model_engineering_assess_esmassoption();
			$esmassoptionArr = util_arrayUtil::setArrayFn(array('mainId' => $newId),$esmassoptionArr);
			$esmassoptionDao->saveDelBatch($esmassoptionArr);

			//选项内容构建
			$actOptionArr = $esmassoptionDao->findAll(array('mainId' => $newId));
			//缓存数组
			foreach($actOptionArr as $key => $val){
				$object['detail'] .= $val['name'] . "<span class=\"blue\"> ( ".$val['score'] ." )</span> ; ";
			}
			$object['id'] = $newId;
			parent::edit_d($object);

			$this->commit_d();
			return $newId;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 编辑对象
	 */
	function edit_d($object) {
		//选项
		$esmassoptionArr = $object['esmassoption'];
		unset($object['esmassoption']);

		try{
			$this->start_d();
			//新增
			parent::edit_d($object);

			//明细处理
			$esmassoptionDao = new model_engineering_assess_esmassoption();
			$esmassoptionArr = util_arrayUtil::setArrayFn(array('mainId' => $object['id']),$esmassoptionArr);
			$esmassoptionDao->saveDelBatch($esmassoptionArr);

			//选项内容构建
			$actOptionArr = $esmassoptionDao->findAll(array('mainId' => $object['id']));
			//缓存数组
			foreach($actOptionArr as $key => $val){
				$object['detail'] .= $val['name'] . "<span class=\"blue\"> ( ".$val['score'] ." )</span> ; ";
			}
			parent::edit_d($object);

			$this->commit_d();
			return $object['id'];
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 根据指标id获取指标数组
	 */
	function getIndexs_d($ids){
		$this->searchArr = array('ids' => $ids);
		$rs = $this->list_d();
		return $rs;
	}

	/**
	 * 后台渲染指标
	 */
	function initEdit_d($objects,$needIndexIds,$useIndexIds = null){
		$str = "";
		if($objects){
			//必填选项
			$needIndexsArr = explode(',',$needIndexIds);
			//使用的指标
			$useIndexIds =  explode(',',$useIndexIds);

			//选项对象实例化
			$optionsDao = new model_engineering_assess_esmassoption();

			foreach($objects as $key => $val){
	            $trClass = $key%2 == 0? 'tr_odd' : 'tr_even';

				if(in_array($val['id'],$needIndexsArr)){
					$isNeed = '<span class="blue">是</span>';
					$disabled = 'disabled="disabled"';
					$indexNameClass = "readOnlyTxtMiddle";
					$readonly = "readonly='readonly'";
					$checked = 'checked="checked"';
					$isUse = 1;
				}else{
					if(in_array($val['id'],$useIndexIds)){
						$checked = 'checked="checked"';
					}else{
						$checked = '';
					}
					$isNeed = '否';
					$disabled = '';
					$indexNameClass = "txtmiddle";
					$readonly = "";
					$isUse = 0;
				}
				$str.=<<<EOT
					<tr id="tr$key" class="$trClass">
						<td valign="top"><img src="images/removeline.png" onclick="removeIndex($key,this)" title="删除行"/></td>
						<td valign="top">
							<input type="text" class="$indexNameClass" name="esmasspro[esmassproindex][$key][indexName]" id="indexName$key" value="$val[name]" onblur="indexSet($key);" $readonly/>
							<input type="hidden" name="esmasspro[esmassproindex][$key][indexId]" id="indexId$key" value="$val[id]"/>
						</td>
						<td valign="top">
							<input type="text" class="readOnlyTxtMiddle" name="esmasspro[esmassproindex][$key][upperLimit]" id="upperLimit$key" value="$val[upperLimit]" readonly="readonly"/>
						</td>
						<td valign="top">
							<input type="text" class="readOnlyTxtMiddle" name="esmasspro[esmassproindex][$key][lowerLimit]" id="lowerLimit$key" value="$val[lowerLimit]" readonly="readonly"/>
						</td>
						<td valign="top">$isNeed</td>
						<td valign="top">
							<input type="checkbox" id="chk$key" $checked $disabled onclick="checkVal($key)" score="$val[upperLimit]" indexName="$val[name]" indexId="$val[id]"/>
							<input type="hidden" name="esmasspro[esmassproindex][$key][isUse]" id="isUse$key" value="$isUse"/>
						</td>
	                    <td valign="top" id="innerTr_$key" colspan="3" style="text-align:left">
	                    	<span id="span_$key" ondblclick="showEditInfo($key);">$val[detail]</span>
	                        <table class="form_in_table" id="table_$key" style="display:none">
EOT;

                //详细选项部分处理
				$optionsArr = $optionsDao->findAll(array('mainId' => $val['id']));
				foreach($optionsArr as $k => $v){
					//id 字符串
					$countI = $key . "_" . $k;
					if($k == 0){
		                $str .=<<<EOT
		                    <tr id="option_$countI">
		                        <td width="35%">
		                            <input type="text" name="esmasspro[esmassproindex][$key][options][$k][optionName]" id="optionName_$countI" value="$v[name]" class="txtmiddle" onblur="optionSet($key,$k);"/>
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
		                    <tr id="option_$countI">
		                        <td width="35%">
		                            <input type="text" name="esmasspro[esmassproindex][$key][options][$k][optionName]" id="optionName_$countI" value="$v[name]" class="txtmiddle" onblur="optionSet($key,$k);"/>
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
}
?>