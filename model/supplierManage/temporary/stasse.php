<?php
/**
 * @description: ��Ӧ����ʱ��������Ϣ
 * @date 2010-11-10 ����02:07:59
 * @author oyzx
 * @version V1.0
 */
class model_supplierManage_temporary_stasse extends model_base{

	function __construct(){
		$this->tbl_name = "oa_supp_asse_temp";
		$this->sql_map = "supplierManage/temporary/stasseSql.php";
		parent::__construct ();
	}

	/***************************************************************************************************
	 * ------------------------------����Ϊҳ��ģ����ʾ���÷���------------------------------------------*
	 **************************************************************************************************/

/*****************************************�����ǹ��ڹ�Ӧ��ע��ҳ�������********************************************/

	/**
	 * @desription ��ʾ����
	 * @param tags
	 * @date 2010-11-11 ����09:52:24
	 */
	function add_s ( $arr ) {
		$parentId = isset( $_GET['parentId'] )?$_GET['parentId']:null;
		$str = "";
		$strOption = $this->xiala_s();
		if( is_array($arr) ){
			$arrCount = count( $arr );

			$dataArr3=end($arr);
			//ԭ����ѭ����$i<($arrCount-1);Ҳû��$dataArr3�������;
			//���ѭ������˼�ǣ��ֱ�Ѽ�������ѡ���ֵ����������������顣
			for($i=0;$i< ($arrCount);$i+=3 ){
				$i2=$i+1;
				$dataArr=$arr[$i];
				$dataArr2=$arr[$i+1];
				$dataArr3=$arr[$i+2];
				$str .=<<<EOT
		<tr>
			<td class="form_text_left">$dataArr[dataName]</td>
			<td class="form_text_right">
				<select class="select" id="$dataArr[dataCode]" name="temporary[typeCode][$dataArr[dataCode]]">
					$strOption
				</select>
			</td>
			<td class="form_text_left">$dataArr2[dataName]</td>
			<td class="form_text_right">
				<select class="select" id="$dataArr2[dataCode]" name="temporary[typeCode][$dataArr2[dataCode]]">
					$strOption
				</select>
			</td>
			<td class="form_text_left">$dataArr3[dataName]</td>
			<td class="form_text_right" colspan="3">
				<select class="select" id="$dataArr3[dataCode]" name="temporary[typeCode][$dataArr3[dataCode]]">
					$strOption
				</select>
			</td>
		</tr>

EOT;
			}
		}
		return $str;
	}

	/**
	 * @desription ����
	 * @param tags
	 * @date 2010-11-11 ����10:30:27
	 */
	function xiala_s () {
		return <<<EOT
		<option value="��">��</option>
		<option value="��">��</option>
		<option value="һ��">һ��</option>
		<option value="��">��</option>
EOT;
	}

/*****************************************�����ǹ��ڹ�Ӧ��ע��ҳ�������********************************************/



	/**
	 * @desription ��ʾ�鿴���������б�
	 * @param tags
	 * @date 2010-11-24 ����10:39:42
	 */
	function showAsseList ($arr) {
		$str = "";
		if($arr){
			$str .=<<<EOT
				<tr>
EOT;
			foreach($arr as $key=>$val){
				$opinion = $val[opinion];
				$str .=<<<EOT
					<td>$val[typeCode]</td>
EOT;
			}
		}else{
			return $str = "<tr><td colspan='4'>�����������</td></tr>";
		}
		$str .=<<<EOT
			<td>$opinion</td>
			</tr>
EOT;
		return $str;
	}

/**#######################################�����ǹ��ڹ�Ӧ��ע��ҳ������۵ı༭#######################################*/
	/**
	 * @desription ���ڱ༭����ҳ����ʾ
	 * @param tags
	 * @return return_type
	 * @date 2010-11-25 ����08:26:07
	 */
	 function addAsse_s ( $arr ) {
		$parentId = isset( $_GET['parentId'] )?$_GET['parentId']:null;
		$str = "";
		if( is_array($arr) ){
			$i = 0;
			//ͨ��ѭ�������۵�������ʾ����
			foreach( $arr as $key => $val ){
				$typeCodeVal = $this->editAsseSelect_s( $val['typeCode'] );
				if( $i%2 == 0 ){
					$str .=<<<EOT
					<tr>
						<td class="form_text_left">$val[typeNameC]</td>
						<td class="form_text_right">
							<select id="$val[typeName]" name="typeCode[$i][typeCode]">
								$typeCodeVal
							</select>
							<input type="hidden" name="typeCode[$i][id]" value="$val[id]" />
							<input type="hidden" name="typeCode[$i][opinion]" value="$val[opinion]" />
							<input type="hidden" name="typeCode[$i][parentId]" value="$val[parentId]" />
						</td>
EOT;
				}else{
					$str .=<<<EOT
						<td class="form_text_left">$val[typeNameC]</td>
						<td class="form_text_right">
							<select id="$val[typeName]" name="typeCode[$i][typeCode]">
								$typeCodeVal
							</select>
							<input type="hidden" name="typeCode[$i][id]" value="$val[id]" />
							<input type="hidden" name="typeCode[$i][opinion]" value="$val[opinion]" />
							<input type="hidden" name="typeCode[$i][parentId]" value="$val[parentId]" />
						</td>
					</tr>
EOT;
				}
				++$i;
			}
			if( $i%2 != 0 ){
				$str .=<<<EOT
					<td class="form_text_left"></td>
					<td class="form_text_right"></td>
				</tr>
EOT;
			}
		}
		return $str;
	}

	/**
	 * @desription ���ڱ༭���۵�������
	 * @param tags
	 * @date 2010-11-25 ����07:35:47
	 */
	function editAsseSelect_s ( $val ) {
		$sel0 = "";
		$sel1 = "";
		$sel2 = "";
		$sel3 = "";
		switch($val){
			case '��':$sel0="selected";break;
			case '��':$sel1="selected";break;
			case 'һ��':$sel2="selected";break;
			case '��':$sel3="selected";break;
		}

		$str =<<<EOT
			   <option value='��' $sel0>��</option>
			   <option value='��' $sel1>��</option>
			   <option value='һ��' $sel2>һ��</option>
			   <option value='��' $sel3>��</option>
EOT;
		return $str;

	}

		/**
	 * @desription �༭��Ӧ�����۵����ݼ�������
	 * @param tags
	 * @date 2010-11-26 ����11:12:44
	 */
	function stsList_d () {
		$arr = $this->list_d();
		$datadictDao = new model_system_datadict_datadict ();
//		echo "<pre>";
//		print_r($arr);
		foreach( $arr as $key => $val ){
			$arr[$key]['typeNameC'] = $datadictDao->getDataNameByCode( $val['typeName'] );
		}
		return $arr;
	}



/**#######################################�����ǹ��ڹ�Ӧ��ע��ҳ������۵ı༭#######################################*/



	/***************************************************************************************************
	 * ------------------------------���½ӿڷ���,�ɹ�����ģ�����---------------------------------------*
	 **************************************************************************************************/
	 /**
	 * @desription �����б����ݷ���
	 * @param tags
	 * @date 2010-11-17 ����04:52:28
	 */
	function suppApprovalPage_d () {
		$this->groupBy = "c.parentId";
		$rows = $this->pageBySqlId('select_Approval');
		if( is_array($rows) ){

		}
		return $rows;
	}

	function rpApprovalNo_s($rows){
		if($rows){
			$i = 0;
			$str = "";
			foreach($rows as $key => $val){
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				$i++;
				$str.=<<<EOT
					<tr class="$classCss" pjId="$val[id]">
						<td>
							$i
						</td>
						<td>
							$val[suppName]
						</td>
						<td>
							$val[busiCode]
						</td>
						<td>
							$val[products]
						</td>
						<td>
							$val[fax]
						</td>
						<td>
							$val[address]
						</td>
						<td>
							$val[ExaStatus]
						</td>
						<td>
							$val[createTime]
						</td>
						<td>
							<a href='controller/rdproject/project/ewf_index.php?actTo=ewfExam&taskId=$val[wTask]&spid=$val[pId]&billId=$val[id]'>����</a> |
							<a href='javascript:showOpenWin("?model=rdproject_project_rdproject&action=rpRead&pjId=$val[id]")'>�鿴</a>
						</td>
					</tr>
EOT;
			}
		}else {
			$str = '<tr><td colspan="50">������ؼ�¼</td></tr>';
		}
		return $str;
	}



}
?>
