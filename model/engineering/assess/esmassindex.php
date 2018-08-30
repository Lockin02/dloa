<?php
/**
 * @author Show
 * @Date 2012��11��27�� ���ڶ� 11:40:15
 * @version 1.0
 * @description:����ָ��� Model��
 */
class model_engineering_assess_esmassindex extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_ass_index";
		$this->sql_map = "engineering/assess/esmassindexSql.php";
		parent :: __construct();
	}

	/**
	 * ��Ӷ���
	 */
	function add_d($object) {
		//ѡ��
		$esmassoptionArr = $object['esmassoption'];
		unset($object['esmassoption']);

		try{
			$this->start_d();
			//����
			$newId = parent::add_d($object);

			//��ϸ����
			$esmassoptionDao = new model_engineering_assess_esmassoption();
			$esmassoptionArr = util_arrayUtil::setArrayFn(array('mainId' => $newId),$esmassoptionArr);
			$esmassoptionDao->saveDelBatch($esmassoptionArr);

			//ѡ�����ݹ���
			$actOptionArr = $esmassoptionDao->findAll(array('mainId' => $newId));
			//��������
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
	 * �༭����
	 */
	function edit_d($object) {
		//ѡ��
		$esmassoptionArr = $object['esmassoption'];
		unset($object['esmassoption']);

		try{
			$this->start_d();
			//����
			parent::edit_d($object);

			//��ϸ����
			$esmassoptionDao = new model_engineering_assess_esmassoption();
			$esmassoptionArr = util_arrayUtil::setArrayFn(array('mainId' => $object['id']),$esmassoptionArr);
			$esmassoptionDao->saveDelBatch($esmassoptionArr);

			//ѡ�����ݹ���
			$actOptionArr = $esmassoptionDao->findAll(array('mainId' => $object['id']));
			//��������
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
	 * ����ָ��id��ȡָ������
	 */
	function getIndexs_d($ids){
		$this->searchArr = array('ids' => $ids);
		$rs = $this->list_d();
		return $rs;
	}

	/**
	 * ��̨��Ⱦָ��
	 */
	function initEdit_d($objects,$needIndexIds,$useIndexIds = null){
		$str = "";
		if($objects){
			//����ѡ��
			$needIndexsArr = explode(',',$needIndexIds);
			//ʹ�õ�ָ��
			$useIndexIds =  explode(',',$useIndexIds);

			//ѡ�����ʵ����
			$optionsDao = new model_engineering_assess_esmassoption();

			foreach($objects as $key => $val){
	            $trClass = $key%2 == 0? 'tr_odd' : 'tr_even';

				if(in_array($val['id'],$needIndexsArr)){
					$isNeed = '<span class="blue">��</span>';
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
					$isNeed = '��';
					$disabled = '';
					$indexNameClass = "txtmiddle";
					$readonly = "";
					$isUse = 0;
				}
				$str.=<<<EOT
					<tr id="tr$key" class="$trClass">
						<td valign="top"><img src="images/removeline.png" onclick="removeIndex($key,this)" title="ɾ����"/></td>
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

                //��ϸѡ��ִ���
				$optionsArr = $optionsDao->findAll(array('mainId' => $val['id']));
				foreach($optionsArr as $k => $v){
					//id �ַ���
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
									<img src="images/add_item.png" onclick="addOption($key,$k)" title="�����"/>
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
									<img src="images/removeline.png" onclick="removeOption($key,$k,this)" title="ɾ����"/>
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