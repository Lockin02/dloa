<?php

/**
 * @author Show
 * @Date 2012��12��1�� ������ 9:53:08
 * @version 1.0
 * @description:��Ŀ����ָ�� Model��
 */
class model_engineering_assess_esmasspro extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_project_assess";
		$this->sql_map = "engineering/assess/esmassproSql.php";
		parent :: __construct();
	}

	/********************* ��ɾ�Ĳ� ***************/

	/**
	 * ��Ӷ���
	 */
	function add_d($object) {
//		echo "<pre>";print_r($object);die();

		//ָ���ȡ
		$exmassproindexArr = $object['esmassproindex'];
		unset($object['esmassproindex']);

		try{
			$this->start_d();

			//����
			$newId = parent::add_d($object);

			//ʵ������Ŀ����ָ��
			$esmassproindexDao = new model_engineering_assess_esmassproindex();
			//������ϸ���ô���
			//�������ݸ���
			$addArr = array(
				'assessId' => $newId
			);
			$exmassproindexArr = util_arrayUtil::setArrayFn($addArr,$exmassproindexArr);
			//���������ϸ
			$esmassproindexDao->saveDelBatch($exmassproindexArr);

			$this->commit_d();
			return $newId;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}
	/**
	 * ���������޸Ķ���
	 */
	function edit_d($object) {
//		echo "<pre>";print_r($object);die();

		//ָ���ȡ
		$exmassproindexArr = $object['esmassproindex'];
		unset($object['esmassproindex']);

		try{
			$this->start_d();

			//�༭����
			parent::edit_d($object);

			//ʵ������Ŀ����ָ��
			$esmassproindexDao = new model_engineering_assess_esmassproindex();
			//������ϸ���ô���
			//�������ݸ���
			$addArr = array(
				'assessId' => $object['id']
			);
			$exmassproindexArr = util_arrayUtil::setArrayFn($addArr,$exmassproindexArr);
			//���������ϸ
			$esmassproindexDao->saveDelBatch($exmassproindexArr);

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/************************ ����ҵ���� ****************/

	//��ȡ��Ŀ��Ϣ
	function getPorjectInfo_d($projectId){
		$esmprojectDao = new model_engineering_project_esmproject();
		$esmprojectArr = $esmprojectDao->find(array('id'=>$projectId));
		$esmprojectArr['projectId'] = $esmprojectArr['id'];
		unset($esmprojectArr['id']);
		return $esmprojectArr;
	}

	/**
	 * ��̨��Ⱦָ��
	 */
	function initEdit_d($object){
		$str = "";

		//��ȡָ��
		$esmassproindexDao = new model_engineering_assess_esmassproindex();
		$esmassproindexArr = $esmassproindexDao->findAll(array('assessId' => $object['id']));
		if($esmassproindexArr){
			//����ѡ��
			$needIndexsArr = explode(',',$object['needIndexIds']);

			//ѡ�����ʵ����
			$optionsDao = new model_engineering_assess_esmassprooption();

			foreach($esmassproindexArr as $key => $val){
	            $trClass = $key%2 == 0? 'tr_odd' : 'tr_even';

				if(in_array($val['indexId'],$needIndexsArr)){
					$isNeed = '<span class="blue">��</span>';
					$disabled = 'disabled="disabled"';
					$indexNameClass = "readOnlyTxtMiddle";
					$readonly = "readonly='readonly'";
				}else{
					$isNeed = '��';
					$disabled = '';
					$indexNameClass = "txtmiddle";
					$readonly = "";
				}
				$str.=<<<EOT
					<tr id="tr$val[id]" class="$trClass">
						<td valign="top"><img src="images/removeline.png" onclick="removeIndex($key,this)" title="ɾ����"/></td>
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

                //��ϸѡ��ִ���
				$optionsArr = $optionsDao->findAll(array('detailId' => $val['id']));
				foreach($optionsArr as $k => $v){
					//id �ַ���
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
									<img src="images/add_item.png" onclick="addOption($key,$k)" title="�����"/>
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

	//�鿴ҳ��
	function initView_d($object){
		$str = "";

		//��ȡָ��
		$esmassproindexDao = new model_engineering_assess_esmassproindex();
		$esmassproindexArr = $esmassproindexDao->findAll(array('assessId' => $object['id']));
		if($esmassproindexArr){
			//����ѡ��
			$needIndexsArr = explode(',',$object['needIndexIds']);

			//ѡ�����ʵ����
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