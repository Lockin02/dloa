<?php
/**
 * @author Administrator
 * @Date 2011��5��9�� 15:19:15
 * @version 1.0
 * @description:����������ѵ�ƻ� Model��
 */
 class model_projectmanagent_borrow_trainingplan  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_borrow_trainingplan";
		$this->sql_map = "projectmanagent/borrow/trainingplanSql.php";
		parent::__construct ();
	}


    /**
	 * ��Ⱦ�鿴ҳ���ڴӱ�
	 */
	function initTableView($object){

		$str = "";
		$i = 0;
		foreach($object as $key => $val ){
			$i ++ ;
				$str .=<<<EOT
					<tr>
						<td width="5%">$i</td>
						<td>$val[beginDT]</td>
						<td>$val[endDT]</td>
						<td>$val[traNum]</td>
						<td>$val[adress]</td>
						<td>$val[content]</td>
						<td>$val[trainer]</td>



					</tr>
EOT;
		}
		return $str;
	}

     /**
      * ��Ⱦ�༭�ӱ�
      */
      function initTableEdit($rows) {
		$i = 0;
		$str = "";
		if ($rows) {
			foreach ($rows as $val) {
				$i++;
				$str .=<<<EOT
					<tr>
					 	<td nowrap align="center" width="5%">$i
					 	</td>
					 	<td nowrap align="center">
					 		<input type="text" class="txtshort" name="borrow[trainingplan][$i][beginDT]" id="TraDT$i" size="10" onfocus="WdatePicker()" value="$val[beginDT]"/>
					 	</td>
					 	<td nowrap align="center">
					 		<input type="text" class="txtshort" name="borrow[trainingplan][$i][endDT]" id="TraEndDT$i" size="10" onfocus="WdatePicker()" value="$val[endDT]" />
					 	</td>
					 	<td align="center">
					 		<input type="text" class="txtshort" name="borrow[trainingplan][$i][traNum]" value="$val[traNum]" size="8" maxlength="40"/>
					    </td>
					 	<td nowrap align="center">
					 		<textarea name="borrow[trainingplan][$i][adress]" rows="3" style="width: 100%">$val[adress]</textarea>
					 	</td>
					 	<td nowrap align="center">
					 		<textarea name="borrow[trainingplan][$i][content]" rows="3" style="width: 100%">$val[content]</textarea>
					 	</td>
					 	<td nowrap align="center">
					 		<textarea name="borrow[trainingplan][$i][trainer]" rows="3" style="width: 100%">$val[trainer]</textarea>
					 	</td>
					 	<td nowrap align="center">
					 		<img src="images/closeDiv.gif" onclick="mydel(this,mytra.id)" title="ɾ����">
					 	</td>
					 </tr>
EOT;
			}
		}
		return array($i,$str);
	}
/*******************************ҳ����ʾ��*********************************/

	/**
	 * ������Ŀid��ȡ��Ʒ�б�
	 */
	function getDetail_d($borrowId){
		$this->searchArr['borrowId'] = $borrowId;
		return $this->list_d();
	}
 }
?>