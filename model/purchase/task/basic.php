<?php

class model_purchase_task_basic extends model_base{

	public static $pageArr=array();

	//״̬λ
	private $state;

	public $statusDao; //״̬��

	function __construct() {
		$this->tbl_name = "oa_purch_task_basic";
		$this->sql_map = "purchase/task/basicSql.php";
		parent :: __construct();

		$this->state = array(
			0 => array(
				"stateEName" => "begin",
				"stateCName" => "������",
				"stateVal" => "0"
			),
			1 => array(
				"stateEName" => "execute",
				"stateCName" => "ִ����",
				"stateVal" => "1"
			),
			2 => array(
				"stateEName" => "Locking",
				"stateCName" => "����",
				"stateVal" => "2"
			),
			3 => array(
				"stateEName" => "end",
				"stateCName" => "���",
				"stateVal" => "3"
			),
			4 => array(
				"stateEName" => "close",
				"stateCName" => "�ر�",
				"stateVal" => "4"
			),
			5 => array(
				"stateEName" => "change",
				"stateCName" => "�����",
				"stateVal" => "5"
			)
		);

		$this->statusDao = new model_common_status();
		$this->statusDao->status = array(
			0 => array(
				"statusEName" => "begin",
				"statusCName" => "������",
				"key" => "0"
			),
			1 => array(
				"statusEName" => "execute",
				"statusCName" => "ִ����",
				"key" => "1"
			),
			2 => array(
				"statusEName" => "Locking",
				"statusCName" => "����",
				"key" => "2"
			),
			3 => array(
				"statusEName" => "end",
				"statusCName" => "���",
				"key" => "3"
			),
			4 => array(
				"statusEName" => "close",
				"statusCName" => "�ر�",
				"key" => "4"
			),
			5 => array(
				"statusEName" => "change",
				"statusCName" => "�����",
				"key" => "5"
			)
		);

		//���ó�ʼ�����������
		parent::setObjAss();
	}

    //��˾Ȩ�޴��� TODO
    protected $_isSetCompany = 1; # �����Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������
    protected $_isSetMyList = 0; # �����б����Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������
/*****************************************ҳ��ģ����ʾ��ʼ********************************************/

	/**
	 * @desription ��ʾ�ҵĴ������б�
	 * @param $rows		�ɹ���������
	 * @date 2010-12-23 ����04:07:16
	 */
	function showMyWaitlist_s($rows){
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$i++;
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				if($val['feedback']==1){
					$taskNumb="<font color='blue'>".$val['taskNumb']."</font>";
				}else{
					$taskNumb=$val['taskNumb'];
				}
				$str .=<<<EOT
	<tr class="$classCss"  title="˫���鿴�ɹ�����" ondblclick="parent.location='index1.php?model=purchase_task_basic&action=read&id=$val[id]&contNumber=$val[applyNumb]&skey=$val[skey_]'">
		<td  name="tdth01">
			<p class="childImg">
            <image src="images/expanded.gif" />$i
        	</p>
        </td>
        <td name="tdth02">
            <p class="checkChildAll"> $taskNumb </p>
        </td>
        <td name="tdth03">
            $val[sendTime]
        </td>
        <td name="tdth04">
            $val[dateHope]
        </td>
        <td name="tdth05">
            $val[createName]
        </td>
        <td name="tdth06">
            $val[stateC]
        </td>
        <td width="47%" class="tdChange td_table" >
			<table class="shrinkTable main_table_nested" width="100%" border="0" cellspacing="1" cellpadding="0">
EOT;

				foreach ($val['childArr'] as $chdKey => $chdVal){
					$str.=<<<EOT
					<tr align="center">
	        			<td  align="left" name="tdth08">
				        	<input type="hidden" class="hidden" value="$chdVal[id]"/>
				        	$chdVal[productNumb]<img src="images/icon/view.gif" title="�鿴������ϸ��Ϣ" onclick="viewProduct('$chdVal[productId]');"/>
				        	 <br> $chdVal[productName]
				        </td>
				        <td width="20%" name="tdth09">
				        	$chdVal[purchTypeCName]
				        </td>
				        <td width="12%" name="tdth10">
				            $chdVal[amountAll]
				        </td>
				        <td width="12%" name="tdth11">
				            $chdVal[amountIssued]
				        </td>
				        <td width="12%" name="tdth12">
				           $chdVal[amountNotIssued]
				        </td>
				        <td width="19%" name="tdth13">
				            $chdVal[dateHope]
				        </td>
	        		</tr>
EOT;
				}
	        	$str.=<<<EOT
        	</table>
        	<div class="readThisTable"><����չ�����Ͼ�����Ϣ></div>
        </td>
        <td name="tdth14">
        	<a target="_parent" href="?model=purchase_task_basic&action=read&id=$val[id]&skey=$val[skey_]">�鿴</a> |
			<a href="?model=purchase_task_basic&action=stateBegin&sid=$val[id]">����</a> |
			<a target="_parent" href="?model=purchase_task_basic&action=toFeedBack&id=$val[id]&skey=$val[skey_]">����</a>
		</td>
    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";
		}
		return $str;
	}

	/**
	 * @desription ��ʾδִ�вɹ������б�
	 * @param $rows		�ɹ���������
	 * @date 2010-12-23 ����04:07:16
	 */
	function showWaitlist_s($rows){
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$i++;
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				$str .=<<<EOT
	<tr class="$classCss"  title="˫���鿴�ɹ�����" ondblclick="location='index1.php?model=purchase_task_basic&action=read&id=$val[id]&contNumber=$val[applyNumb]&skey=$val[skey_]'">
		<td  name="tdth01" >
			<p class="childImg">
            <image src="images/expanded.gif" />$i
        	</p>
        </td>
        <td  name="tdth02">
            <p class="checkChildAll"> $val[taskNumb] </p>
        </td>
        <td  name="tdth03">
            $val[sendTime]
        </td>
        <td  name="tdth04">
            $val[dateHope]
        </td>
        <td  name="tdth05">
            $val[createName]
        </td>
        <td  name="tdth06">
            $val[sendName]
        </td>
        <td  name="tdth07">
            $val[stateC]
        </td>
        <td width="47%" class="tdChange td_table" >
			<table class="shrinkTable main_table_nested" width="100%" border="0" cellspacing="1" cellpadding="0">
EOT;

				foreach ($val['childArr'] as $chdKey => $chdVal){
					$str.=<<<EOT
					<tr align="center">
	        			<td  align="left"  name="tdth08">
				        	<input type="hidden" class="hidden" value="$chdVal[id]"/>
				        	$chdVal[productNumb] <br> $chdVal[productName]
				        </td>
				        <td width="20%"  name="tdth09">
				        	$chdVal[purchTypeCName]
				        </td>
				        <td width="12%"  name="tdth10">
				            $chdVal[amountAll]
				        </td>
				        <td width="12%"  name="tdth11">
				            $chdVal[amountNotIssued]
				        </td>
				        <td width="19%"  name="tdth12">
				            $chdVal[dateHope]
				        </td>
	        		</tr>
EOT;
				}
	        	$str.=<<<EOT
        	</table>
        	<div class="readThisTable"><����չ�����Ͼ�����Ϣ></div>
        </td>
        <td  name="tdth13">
			<select class="myExecuteTask">
				<option>��ѡ�����</option>
				<option value="view">�鿴</option>
				<option value="change">���·���</option>
			</select>
			<input type="hidden" value='$val[id]'/>
			<input type="hidden" value='$val[applyNumb]' />
			<input type="hidden" id="check$val[id]" value='$val[skey_]' />
			<!--
        	<a  href="?model=purchase_task_basic&action=read&id=$val[id]">�鿴</a> |
			<a href="?model=purchase_task_basic&action=stateBegin&sid=$val[id]">����</a> -->
		</td>
    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";
		}
		return $str;
	}

	/**
	 * @desription ��ʾִ�����б�
	 * @param $rows		�ɹ���������
	 * @date 2010-12-23 ����05:00:53
	 */
	function showExecutionlist_s ($rows) {
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$j=0;
				$i++;
				$orderEquDao=new model_purchase_contract_equipment();
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				$str .=<<<EOT
			<tr class="$classCss" title="˫���鿴�ɹ�����" ondblclick="location='index1.php?model=purchase_task_basic&action=read&id=$val[id]&contNumber=$val[applyNumb]&skey=$val[skey_]'">
				<td  name="tdth01" width="3%">
					<p class="childImg">
		            <image src="images/expanded.gif" />$i
		        	</p>
		        </td>
		        <td name="tdth02" width="9%">
		            <p class="checkChildAll"><input id="allCheckbox$i" type="checkbox"> $val[taskNumb] </p>
		        </td>
		        <td name="tdth03" width="7%">
		            $val[sendTime]
		        </td>
		        <td name="tdth04" width="7%">
		            $val[dateHope]
		        </td>
		        <td name="tdth05" width="4%">
		            $val[createName]
		        </td>
		        <td name="tdth06" width="4%">
		            $val[sendName]
		        </td>
		        <!--
		        <td name="tdth07">
		            $val[stateC]
		        </td>-->
		        <td width="40%" class="tdChange td_table" >
					<table class="shrinkTable main_table_nested" width="100%" border="0" cellspacing="1" cellpadding="0">
EOT;
				foreach ($val['childArr'] as $chdKey => $chdVal){

					if( $chdVal['amountNotIssued'] == null || $chdVal['amountNotIssued']==0 || $chdVal['amountNotIssued']==""|| $chdVal['amountNotIssued']<0 ){

						$checkBoxStr =<<<EOT
				        	$chdVal[productNumb] <br> $chdVal[productName]
EOT;
					}else{
						$j++;
						$checkBoxStr =<<<EOT
							<input type="checkbox" class="checkChild">
				        	<input type="hidden" class="hidden" value="$chdVal[id]"/>
				        	$chdVal[productNumb] <br> $chdVal[productName]
EOT;
					}
					$priceData=$orderEquDao->getHistoryInfo_d( $chdVal['productNumb'],$val['createTime']);//��ȡ������ʷ�۸�
						if(empty($priceData['applyPrice'])){
							$priceData['applyPrice'] = "��";
							$priceLink =<<<EOT
							$priceData[applyPrice]
EOT;
						}
						else{
							$priceData['applyPrice'] = sprintf("%.3f",$priceData['applyPrice']);	//������λС��
							$priceLink =<<<EOT
							$priceData[applyPrice]<img src="images/icon/view.gif" title="�鿴��������" onclick="showPrice('$chdVal[productNumb]','$val[createTime]');"/>
EOT;
						}
					//ѯ������	$chdVal[amountIssued]
					$str.=<<<EOT
						<tr align="center">
		        			<td  align="left"  name="tdth08" width="20%">
					        	$checkBoxStr
					        </td>
					        <td width="12%" name="tdth09">
					        	$chdVal[purchTypeCName]
					        </td>
					        <td width="8%" name="tdth10">
					            $chdVal[amountAll]
					        </td>
					        <td width="9%" name="tdth12">
					          $chdVal[amountNotIssued]
					        </td>
					        <td width="12%" name="tdth13">
					            $chdVal[dateHope]
					        </td>
					        <td width="14%" name="tdth11">
					          $priceLink
					        </td>
		        		</tr>
EOT;
				}
				$val['instruction'] = htmlspecialchars($val['instruction']);	//��һЩԤ������ַ�ת��Ϊ HTML ʵ�� 
	        	$str.=<<<EOT
			        	</table>
			        	<div class="readThisTable"><����չ�����Ͼ�����Ϣ></div>
			        </td>
			        <td width="13%">
	            		$val[instruction]
	        		</td>
			        <td  name="tdth14" width="11%">

						<SCRIPT language=JavaScript>
							if($j==0){
								jQuery("#allCheckbox$i").hide();
							}
						</SCRIPT>
						<select class="myExecuteTask">
							<option>��ѡ�����</option>
							<option value="view">�鿴</option>
							<option value="change">���</option>
							<option value="finish">���</option>
							<option value="close">�ر�</option>
						</select>
						<input type="hidden" value='$val[id]' />
						<input type="hidden" value='$val[applyNumb]' />
						<input type="hidden" id="check$val[id]" value='$val[skey_]' />
					</td>
			    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";
		}
		return $str;
	}
	/**
	 * @desription ��ʾ�ҵ�ִ�����б�
	 * @param $rows		�ɹ���������
	 * @date 2010-12-23 ����05:00:53
	 */
	function showMyExecutionlist_s ($rows) {
		$str = "";
		$i = 0;
		if ($rows) {
			$orderEquDao=new model_purchase_contract_equipment();
			foreach ($rows as $key => $val) {
				$j=0;
				$i++;
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				if($val['feedback']==1){
					$taskNumb="<font color='blue'>".$val['taskNumb']."</font>";
				}else{
					$taskNumb=$val['taskNumb'];
				}
				$str .=<<<EOT
			<tr class="$classCss"  title="˫���鿴�ɹ�����" ondblclick="parent.location='index1.php?model=purchase_task_basic&action=read&id=$val[id]&contNumber=$val[applyNumb]&skey=$val[skey_]'">
				<td   name="tdth01" width="3%">
					<p class="childImg">
		            <image src="images/expanded.gif" />$i
		        	</p>
		        </td>
		        <td name="tdth02" width="9%">
		            <p class="checkChildAll"><input id="allCheckbox$i" type="checkbox"> $taskNumb </p>
		        </td>
		        <td name="tdth03" width="7%">
		            $val[sendTime]
		        </td>
		        <td name="tdth04" width="7%">
		            $val[dateHope]
		        </td>
		        <td name="tdth05" width="4%">
		            $val[createName]
		        </td>
		        <td name="tdth06" width="4%">
		            $val[stateC]
		        </td>
		        <td width="40%" class="tdChange td_table" >
					<table class="shrinkTable main_table_nested" width="100%" border="0" cellspacing="1" cellpadding="0">
EOT;
				foreach ($val['childArr'] as $chdKey => $chdVal){

					if( $chdVal['amountAll']-$chdVal['contractAmount']<0||$chdVal['amountAll']-$chdVal['contractAmount']==0 ){
						if($chdVal['amountNotIssued']!=$chdVal['amountAll']){//����ɫ���������Ƿ����ѯ��
							$checkBoxStr =<<<EOT
					        	<font color="blue">$chdVal[productNumb]<img src="images/icon/view.gif" title="�鿴������ϸ��Ϣ" onclick="viewProduct('$chdVal[productId]');"/> <br> $chdVal[productName]</font>
EOT;
						}else{
							$checkBoxStr =<<<EOT
					        	$chdVal[productNumb]<img src="images/icon/view.gif" title="�鿴������ϸ��Ϣ" onclick="viewProduct('$chdVal[productId]');"/> <br> $chdVal[productName]
EOT;
						}
					}else{
						$j++;
						if($chdVal['amountNotIssued']!=$chdVal['amountAll']){//����ɫ���������Ƿ����ѯ��
						$checkBoxStr =<<<EOT
							<input type="checkbox" class="checkChild">
				        	<input type="hidden" class="hidden" value="$chdVal[id]"/>
				        	<font color="blue">$chdVal[productNumb]<img src="images/icon/view.gif" title="�鿴������ϸ��Ϣ" onclick="viewProduct('$chdVal[productId]');"/> <br> $chdVal[productName]</font>
EOT;
						}else{
						$checkBoxStr =<<<EOT
							<input type="checkbox" class="checkChild">
				        	<input type="hidden" class="hidden" value="$chdVal[id]"/>
				        	$chdVal[productNumb]<img src="images/icon/view.gif" title="�鿴������ϸ��Ϣ" onclick="viewProduct('$chdVal[productId]');"/> <br> $chdVal[productName]
EOT;
						}
					}
						$priceData=$orderEquDao->getHistoryInfo_d( $chdVal['productNumb'],$val['createTime']);//��ȡ������ʷ�۸�
						if(empty($priceData['applyPrice'])){
							$priceData['applyPrice'] = "��";
							$priceLink =<<<EOT
							$priceData[applyPrice]
EOT;
						}
						else{
							$priceData['applyPrice'] = sprintf("%.3f",$priceData['applyPrice']);	//������λС��
							$priceLink =<<<EOT
							$priceData[applyPrice]<img src="images/icon/view.gif" title="�鿴��������" onclick="showPrice('$chdVal[productNumb]','$val[createTime]');"/>
EOT;
						}
					//ѯ������	$chdVal[amountIssued]
					$str.=<<<EOT
						<tr align="center">
		        			<td  align="left"   name="tdth08" width="20%">
					        	$checkBoxStr
					        </td>
					        <td width="12%"   name="tdth09">
					        	$chdVal[purchTypeCName]
					        </td>
					        <td width="8%"   name="tdth10">
					            $chdVal[amountAll]
					        </td>
					        <td width="9%"   name="tdth12">
					           $chdVal[amountNotIssued]
					        </td>
					        <td width="12%"   name="tdth13">
					            $chdVal[dateHope]
					        </td>
					        <td width="14%"   name="tdth11">	
					           $priceLink	
					        </td>
		        		</tr>
EOT;
				}
				$val['instruction'] = htmlspecialchars($val['instruction']);	//��һЩԤ������ַ�ת��Ϊ HTML ʵ�� 
	        	$str.=<<<EOT
			        	</table>
			        	<div class="readThisTable"><����չ�����Ͼ�����Ϣ></div>
			        </td>
		        	<td width="13%">
	            		$val[instruction]
	        		</td>
			        <td  name="tdth14" width="11%">

						<SCRIPT language=JavaScript>
							if($j==0){
								jQuery("#allCheckbox$i").hide();
							}
						</SCRIPT>
						<select class="myExecuteTask">
							<option>��ѡ�����</option>
							<option value="view">�鿴</option>
							<option value="export">��������</option>
							<option value="finish">���</option>
							<option value="close">�ر�</option>
							<option value="feedback">������</option>
						</select>
						<input type="hidden" value='$val[id]' />
						<input type="hidden" value='$val[applyNumb]' />
						<input type="hidden" id="check$val[id]" value='$val[skey_]' />
					</td>
			    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";
		}
		return $str;
	}

	/**��ʾ�ҵ��ѹر��б�
	*author can
	*2011-1-5
	 * @param $rows		�ɹ���������
	*/
		function showMyCloselist($rows){
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$i++;
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
							$str .=<<<EOT
				<tr class="$classCss" align="center" title="˫���鿴�ɹ�����"  ondblclick="parent.location='index1.php?model=purchase_task_basic&action=read&id=$val[id]&contNumber=$val[applyNumb]&skey=$val[skey_]'">
					<td   name="tdth01">
						<p class="childImg">
			            <image src="images/expanded.gif" />$i
			        	</p>
			        </td>
			        <td align="left"  name="tdth02">
			            <p class="checkChildAll">$val[taskNumb] </p>
			        </td>
			       <td   name="tdth03">
			            $val[sendTime]
			        </td>
			        <td   name="tdth04">
			            $val[dateHope]
			        </td>
			        <td   name="tdth05">
			            $val[createName]
			        </td>
			        <td   name="tdth06">
			            $val[stateC]
			        </td>
			        <td width="47%" class="tdChange td_table" >
						<table class="shrinkTable main_table_nested" width="100%" border="0" cellspacing="1" cellpadding="0">
EOT;
				foreach ($val['childArr'] as $chdKey => $chdVal){
//					if( !isset( $chdVal["amountAll"] )||$chdVal["amountAll"]==0 ||$chdVal["amountAll"]=="" ){
//						$chdVal["amountAll"] = 0;
//						continue;
//					}
					$str.=<<<EOT
					<tr align="center">
	        			<td  align="left"  name="tdth08">
				        	<input type="hidden" class="hidden" value="$chdVal[id]"/>
				        	$chdVal[productNumb] <br> $chdVal[productName]
				        </td>
				        <td width="20%"  name="tdth09">
				        	$chdVal[purchTypeCName]
				        </td>
				        <td width="12%"  name="tdth10">
				            $chdVal[amountAll]
				        </td>
				        <td width="12%"  name="tdth11">
				            $chdVal[amountIssued]
				        </td>
				        <td width="12%"  name="tdth12">
				           $chdVal[amountNotIssued]
				        </td>
				        <td width="19%"  name="tdth13">
				          	$chdVal[dateHope]
				        </td>
	        		</tr>
EOT;
				}
	        	$str.=<<<EOT
			        	</table>
			        	<div class="readThisTable"><����չ�����Ͼ�����Ϣ></div>
			        </td>
			        <td  name="tdth14">
						<a target="_parent" href="?model=purchase_task_basic&action=read&id=$val[id]&skey=$val[skey_]">�鿴</a>��
						<img src="images/icon/view.gif" title="������������" onclick="startTask($val[id]);"/>
					</td>
			    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";
		}
		return $str;
	}

	/**
	 * @exclude ��ʾ���֪ͨ���б�
	 * @author ouyang
	 * @param $rows		�ɹ���������
	 * @param	$showpage
	 * @return
	 * @version 2010-8-17 ����08:51:37
	 */
	function showChangeNotice($rows, $showpage){
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$i++;
				$iClass = $i%2+1;
				$stateName=$this->service->stateToVal(  $val['state'] );
				$str .=<<<EOT
				<tr class="TableLine$iClass" align="center">
					<td  >
						<p class="childImg">
			            <image src="images/expanded.gif" />$i
			        	</p>
			        </td>
			        <td align="left" >
			            $val[taskNumb]
			        </td>
			        <td  >
			            $val[createName]
			        </td>
			        <td  >
			            $stateName
			        </td>
			        <td width="50%" class="tdChange" >
						<table width="100%"  class="shrinkTable">
EOT;
				foreach ($val['childArr'] as $chdKey => $chdVal){

					if( !isset( $chdVal["amountAll"] )||$chdVal["amountAll"]==0 ||$chdVal["amountAll"]=="" ){
						$chdVal["amountAll"] = 0;
						continue;
					}

					if( isset( $chdVal['amountIssued']) && $chdVal['amountIssued']!="" ){
						$amountOk = $chdVal['amountAll'] - $chdVal['amountIssued'];
					}else{
						$amountOk = $chdVal['amountAll'];
					}

					$str.=<<<EOT
					<tr align="center">
	        			<td  align="left" >
				        	<input type="hidden" class="hidden" value="$chdVal[id]"/>
				        	$chdVal[productNumb] <br> $chdVal[productName]
				        </td>
				        <td width="15%" >
				            $chdVal[purchTypeCName]
				        </td>
				        <td width="12%">
				            $chdVal[amountAll]
				        </td>
				        <td width="12%">
				            $amountOk
				        </td>
				        <td width="20%">
				            $chdVal[dateHope]
				        </td>
	        		</tr>
EOT;
				}
	        	$str.=<<<EOT
			        	</table>
			        	<div class="readThisTable"><����չ�����Ͼ�����Ϣ></div>
			        </td>
			        <td >
						<select onchange=" selectButton( this , '$val[id]', '$val[taskNumb]' ) " >
						    <option value="value1">��ѡ��</option>
						    <option value="readReceive">�鿴����</option>
					  	</select>
					</td>
			    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";}
		return $str . '<tr><td colspan="15" class="pageTd" style="text-align:center;">' . $showpage->show(7) . '</td></tr>';
	}

	/**
	 * ��ʾִ�����б�
	 * @param $rows		�ɹ���������
	 */
	function showExecutionlist($rows){
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$i++;
				 $iClass = $i%2+1;
				$str .=<<<EOT
				<tr class="TableLine$iClass" align="center"  title="˫���鿴�ɹ�����" ondblclick="location='index1.php?model=purchase_task_basic&action=read&id=$val[id]&contNumber=$val[applyNumb]&skey=$val[skey_]'">
					<td    name="tdth01">
						<p class="childImg">
			            <image src="images/expanded.gif" />$i
			        	</p>
			        </td>
			        <td align="left"   name="tdth02">
			            <p class="checkChildAll">$val[taskNumb] </p>
			        </td>
			        <td    name="tdth03">
			            $val[sendTime]
			        </td>
			        <td    name="tdth04">
			            $val[dateHope]
			        </td>
			        <td    name="tdth05">
			            $val[createName]
			        </td>
			        <td    name="tdth06">
			            $val[sendName]
			        </td>
			        <td width="42%" class="tdChange td_table" >
						<table width="100%"  class="shrinkTable main_table_nested">
EOT;
				if(is_array($val['childArr'])){
				foreach ($val['childArr'] as $chdKey => $chdVal){
//					if( !isset( $chdVal["amountAll"] )||$chdVal["amountAll"]==0 ||$chdVal["amountAll"]=="" ){
//						$chdVal["amountAll"] = 0;
//						continue;
//					}
					$str.=<<<EOT
					<tr align="center">
	        			<td  align="left"   name="tdth07">
				        	<input type="hidden" class="hidden" value="$chdVal[id]"/>
				        	$chdVal[productNumb] <br> $chdVal[productName]
				        </td>
				        <td width="20%"   name="tdth08">
				        	$chdVal[purchTypeCName]
				        </td>
				        <td width="12%"   name="tdth09">
				            $chdVal[amountAll]
				        </td>
				        <td width="12%"   name="tdth10">
				            $chdVal[amountIssued]
				        </td>
				        <td width="12%"   name="tdth11">
				            $chdVal[amountNotIssued]
				        </td>
				        <td width="19%"   name="tdth12">
				          	$chdVal[dateHope]
				        </td>
	        		</tr>
EOT;
				}

				}
	        	$str.=<<<EOT
			        	</table>
			        	<div class="readThisTable"><����չ�����Ͼ�����Ϣ></div>
			        </td>
			        <td   name="tdth13">
						<a  href="?model=purchase_task_basic&action=read&id=$val[id]&skey=$val[skey_]">�鿴</a>
					</td>
			    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";}
		return $str;
	}

	/**
	 * �ҷ���Ĵ����ղɹ������б�
	 * @param $rows		�ɹ���������
	 * @param $showpage
	 */
	function showWaitlist($rows, $showpage){
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$i++;
				 $iClass = $i%2+1;
							$str .=<<<EOT
				<tr class="TableLine$iClass" align="center">
					<td  >
						<p class="childImg">
			            <image src="images/expanded.gif" />$i
			        	</p>
			        </td>
			        <td align="left" >
			            <p class="checkChildAll">$val[taskNumb] </p>
			        </td>
			        <td  >
			            $val[sendTime]
			        </td>
			        <td  >
			            $val[dateHope]
			        </td>
			        <td  >
			            $val[createName]
			        </td>
			        <td  >
			            $val[sendName]
			        </td>
			        <td width="42%" class="tdChange td_table" >
						<table width="100%"  class="shrinkTable main_table_nested">
EOT;
				foreach ($val['childArr'] as $chdKey => $chdVal){
//					if( !isset( $chdVal["amountAll"] )||$chdVal["amountAll"]==0 ||$chdVal["amountAll"]=="" ){
//						$chdVal["amountAll"] = 0;
//						continue;
//					}
					$str.=<<<EOT
					<tr align="center">
	        			<td  align="left" >
				        	<input type="hidden" class="hidden" value="$chdVal[id]"/>
				        	$chdVal[productNumb] <br> $chdVal[productName]
				        </td>
				        <td width="20%">
				        	$chdVal[purchTypeCName]
				        </td>
				        <td width="12%">
				            $chdVal[amountAll]
				        </td>
				        <td width="12%">
				            $chdVal[amountNotIssued]
				        </td>
				        <td width="16%">
				          	$chdVal[dateHope]
				        </td>
	        		</tr>
EOT;
				}
	        	$str.=<<<EOT
			        	</table>
			        	<div class="readThisTable"><����չ�����Ͼ�����Ϣ></div>
			        </td>
			        <td >
						<a target="_parent" href="?model=purchase_task_basic&action=read&id=$val[id]">�鿴</a>
					</td>
			    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";
		}
		return $str;
	}

	/**����ִ���е������б�
	*author can
	*2011-3-23
	 * @param $rows		�ɹ���������
	 * @param $showpage
	*/

	function showAllotExecutionlist($rows, $showpage){
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$i++;
				 $iClass = $i%2+1;
				$str .=<<<EOT
				<tr class="TableLine$iClass" align="center"  title="˫���鿴�ɹ�����" ondblclick="location='index1.php?model=purchase_task_basic&action=read&id=$val[id]&contNumber=$val[applyNumb]&skey=$val[skey_]'">
					<td>
						<p class="childImg">
			            <image src="images/expanded.gif" />$i
			        	</p>
			        </td>
			        <td align="left" >
			            <p class="checkChildAll">$val[taskNumb] </p>
			        </td>
			        <td  >
			            $val[sendTime]
			        </td>
			        <td  >
			            $val[dateHope]
			        </td>
			        <td  >
			            $val[createName]
			        </td>
			        <td  >
			            $val[sendName]
			        </td>
			        <td width="50%" class="tdChange td_table" >
						<table width="100%"  class="shrinkTable main_table_nested">
EOT;
				foreach ($val['childArr'] as $chdKey => $chdVal){
//					if( !isset( $chdVal["amountAll"] )||$chdVal["amountAll"]==0 ||$chdVal["amountAll"]=="" ){
//						$chdVal["amountAll"] = 0;
//						continue;
//					}
					$str.=<<<EOT
					<tr align="center">
	        			<td  align="left" >
				        	<input type="hidden" class="hidden" value="$chdVal[id]"/>
				        	$chdVal[productNumb] <br> $chdVal[productName]
				        </td>
				        <td width="20%">
				        	$chdVal[purchTypeCName]
				        </td>
				        <td width="8%">
				            $chdVal[amountAll]
				        </td>
				        <td width="8%">
				            $chdVal[amountIssued]
				        </td>
				        <td width="8%">
				            $chdVal[contractAmount]
				        </td>
				        <td width="15%">
				          	$chdVal[dateHope]
				        </td>
	        		</tr>
EOT;
				}
	        	$str.=<<<EOT
			        	</table>
			        	<div class="readThisTable"><����չ�����Ͼ�����Ϣ></div>
			        </td>
			        <td >
						<a   href="?model=purchase_task_basic&action=read&id=$val[id]&skey=$val[skey_]">�鿴</a>&nbsp;
					<!--	<a target="_parent" href="?model=purchase_task_basic&action=toChange&id=$val[id]&numb=$val[taskNumb]">���</a>-->
					</td>
			    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";
		}
		return $str;
	}
		/**
	 * �鿴�ɹ�����ʾ�б�
	 * @param $listEqu		�ɹ�������������
	 */
	function showTaskRead($listEqu){
		$planDao=new model_purchase_plan_basic();
		$applyDao=new model_asset_purchase_apply_apply();
		$str="";
		$i = 0;
		if($listEqu){
			foreach ($listEqu as $key => $val) {
				$planRows=$planDao->get_d($val['planId']);
				if($val['purchType']==""){      //�жϲɹ������Ƿ�Ϊ��
					$val['purchType']=$planRows['purchType'];
				}
				switch($val['purchType']){
					case "oa_sale_order":$objModel='model_projectmanagent_order_order';$objName='order';break;
					case "oa_sale_lease":$objModel='model_contract_rental_rentalcontract';$objName='rentalcontract';break;
					case "oa_sale_service":$objModel='model_engineering_serviceContract_serviceContract';$objName='serviceContract';break;
					case "oa_sale_rdproject":$objModel='model_rdproject_yxrdproject_rdproject';$objName='rdproject';break;
					case "oa_borrow_borrow":$objModel='model_projectmanagent_borrow_borrow';$objName='borrow';break;
					case "oa_present_present":$objModel='model_projectmanagent_present_present';$objName='present';break;
					case "stock":$objModel='model_stock_fillup_fillup';$objName='fillup';break;
					case "HTLX-XSHT":$objModel='model_contract_contract_contract';$objName='contract';break;
					case "HTLX-ZLHT":$objModel='model_contract_contract_contract';$objName='contract';break;
					case "HTLX-FWHT":$objModel='model_contract_contract_contract';$objName='contract';break;
					case "HTLX-YFHT":$objModel='model_contract_contract_contract';$objName='contract';break;
					default:$objModel='';$objName='';break;
				}
					$securityDao=new model_common_securityUtil($objName);
					if($planRows['sourceID']!=""&&$objModel!=''){
						$sourceDao=new $objModel();
						$sourceRow=$sourceDao->find(array ("id" =>$planRows['sourceID'] ) );
						$skey=$securityDao->md5Row($sourceRow,'id',$objModel);
					}
				//���ݲ�ͬ�Ĳɹ����ͣ��鿴��ͬ��Դ����Ϣ
				switch($val['purchType']){
					case "oa_sale_order":$souceNum='<a target="_bank" href="index1.php?model=projectmanagent_order_order&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="�鿴��ͬ��Ϣ">'.$planRows[sourceNumb].'</a>';break;
					case "oa_sale_lease":$souceNum='<a target="_bank" href="index1.php?model=contract_rental_rentalcontract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="�鿴��ͬ��Ϣ">'.$planRows[sourceNumb].'</a>';break;
					case "oa_sale_service":$souceNum='<a target="_bank" href="index1.php?model=engineering_serviceContract_serviceContract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="�鿴��ͬ��Ϣ">'.$planRows[sourceNumb].'</a>';break;
					case "oa_sale_rdproject":$souceNum='<a target="_bank" href="index1.php?model=rdproject_yxrdproject_rdproject&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="�鿴��ͬ��Ϣ">'.$planRows[sourceNumb].'</a>';break;
					case "stock":$souceNum='<a target="_bank" href="index1.php?model=stock_fillup_fillup&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="�鿴������Ϣ">'.$planRows[sourceNumb].'</a>';break;
					case "oa_borrow_borrow":$souceNum='<a target="_bank" href="index1.php?model=projectmanagent_borrow_borrow&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="�鿴��������Ϣ">'.$planRows[sourceNumb].'</a>';break;
					case "oa_present_present":$souceNum='<a target="_bank" href="index1.php?model=projectmanagent_present_present&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="�鿴������Ϣ">'.$planRows[sourceNumb].'</a>';break;
					case "HTLX-XSHT":$souceNum='<a target="_bank" href="index1.php?model=contract_contract_contract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="�鿴��ͬ��Ϣ">'.$planRows[sourceNumb].'</a>';break;
					case "HTLX-ZLHT":$souceNum='<a target="_bank" href="index1.php?model=contract_contract_contract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="�鿴��ͬ��Ϣ">'.$planRows[sourceNumb].'</a>';break;
					case "HTLX-FWHT":$souceNum='<a target="_bank" href="index1.php?model=contract_contract_contract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="�鿴��ͬ��Ϣ">'.$planRows[sourceNumb].'</a>';break;
					case "HTLX-YFHT":$souceNum='<a target="_bank" href="index1.php?model=contract_contract_contract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="�鿴��ͬ��Ϣ">'.$planRows[sourceNumb].'</a>';break;
					default:$souceNum=$planRows[sourceNumb];break;
				}
				if($val['purchType']=="oa_asset_purchase_apply"){
					$applyRow=$applyDao->get_d($val['applyId']);
					$souceNum='';
					$planRows['rObjCode']='';
					$planRows['projectName']='';
					$planRows['sendName']=$applyRow['applicantName'];
					$planRows['department']=$applyRow['applyDetName'];

				}

				$i++;
				$str.=<<<EOT
					<tr height="28" align="center">
						<td>
							$i
						</td>
						<td>
							$val[productNumb] <br/> $val[productName]
						</td>
						<td>
							$val[pattem]
						</td>
						<td>
							$val[unitName]
						</td>
						<td>
							$val[purchTypeCName]
						</td>
						<td>
							$val[qualityName]
						</td>
						<td>
							$val[amountAll]
						</td>
						<td>
							$val[contractAmount]
						</td>
						<td>
							$val[dateHope]
						</td>
						<td>
							$souceNum
						</td>
						<td>
							$planRows[contractName]
						</td>
						<td>
							$planRows[projectName]
						</td>
						<td>
							$planRows[sendName]
						</td>
						<td>
							$planRows[department]
						</td>
						<td>
							<textarea rows="2" cols="23" class="textarea_read_blue" readOnly>$val[remark]</textarea>
						</td>
					</tr>
EOT;
			}
		}
		return $str;
	}

	/**
	 * �鿴�ɹ�����ʾ�б�
	 * @param $listEqu		�ɹ�������������
	 */
	function showRead($listEqu){
		$planDao=new model_purchase_plan_basic();
		$applyDao=new model_asset_purchase_apply_apply();
		$str="";
		$i = 0;
		if($listEqu){
			foreach ($listEqu as $key => $val) {
				$planRows=$planDao->get_d($val['planId']);
				if($val['purchType']==""){      //�жϲɹ������Ƿ�Ϊ��
					$val['purchType']=$planRows['purchType'];
				}
				switch($val['purchType']){
					case "oa_sale_order":$objModel='model_projectmanagent_order_order';$objName='order';break;
					case "oa_sale_lease":$objModel='model_contract_rental_rentalcontract';$objName='rentalcontract';break;
					case "oa_sale_service":$objModel='model_engineering_serviceContract_serviceContract';$objName='serviceContract';break;
					case "oa_sale_rdproject":$objModel='model_rdproject_yxrdproject_rdproject';$objName='rdproject';break;
					case "oa_borrow_borrow":$objModel='model_projectmanagent_borrow_borrow';$objName='borrow';break;
					case "oa_present_present":$objModel='model_projectmanagent_present_present';$objName='present';break;
					case "stock":$objModel='model_stock_fillup_fillup';$objName='fillup';break;
					case "HTLX-XSHT":$objModel='model_contract_contract_contract';$objName='contract';break;
					case "HTLX-ZLHT":$objModel='model_contract_contract_contract';$objName='contract';break;
					case "HTLX-FWHT":$objModel='model_contract_contract_contract';$objName='contract';break;
					case "HTLX-YFHT":$objModel='model_contract_contract_contract';$objName='contract';break;
					default:$objModel='';$objName='';break;
				}
					$securityDao=new model_common_securityUtil($objName);
					if($planRows['sourceID']!=""&&$objModel!=''){
						$sourceDao=new $objModel();
						$sourceRow=$sourceDao->find(array ("id" =>$planRows['sourceID'] ) );
						$skey=$securityDao->md5Row($sourceRow,'id',$objModel);
					}
				//���ݲ�ͬ�Ĳɹ����ͣ��鿴��ͬ��Դ����Ϣ
				switch($val['purchType']){
					case "oa_sale_order":$souceNum='<a target="_bank" href="index1.php?model=projectmanagent_order_order&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="�鿴��ͬ��Ϣ">'.$planRows[sourceNumb].'</a>';break;
					case "oa_sale_lease":$souceNum='<a target="_bank" href="index1.php?model=contract_rental_rentalcontract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="�鿴��ͬ��Ϣ">'.$planRows[sourceNumb].'</a>';break;
					case "oa_sale_service":$souceNum='<a target="_bank" href="index1.php?model=engineering_serviceContract_serviceContract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="�鿴��ͬ��Ϣ">'.$planRows[sourceNumb].'</a>';break;
					case "oa_sale_rdproject":$souceNum='<a target="_bank" href="index1.php?model=rdproject_yxrdproject_rdproject&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="�鿴��ͬ��Ϣ">'.$planRows[sourceNumb].'</a>';break;
					case "stock":$souceNum='<a target="_bank" href="index1.php?model=stock_fillup_fillup&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="�鿴������Ϣ">'.$planRows[sourceNumb].'</a>';break;
					case "oa_borrow_borrow":$souceNum='<a target="_bank" href="index1.php?model=projectmanagent_borrow_borrow&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="�鿴��������Ϣ">'.$planRows[sourceNumb].'</a>';break;
					case "oa_present_present":$souceNum='<a target="_bank" href="index1.php?model=projectmanagent_present_present&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="�鿴������Ϣ">'.$planRows[sourceNumb].'</a>';break;
					case "HTLX-XSHT":$souceNum='<a target="_bank" href="index1.php?model=contract_contract_contract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="�鿴��ͬ��Ϣ">'.$planRows[sourceNumb].'</a>';break;
					case "HTLX-ZLHT":$souceNum='<a target="_bank" href="index1.php?model=contract_contract_contract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="�鿴��ͬ��Ϣ">'.$planRows[sourceNumb].'</a>';break;
					case "HTLX-FWHT":$souceNum='<a target="_bank" href="index1.php?model=contract_contract_contract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="�鿴��ͬ��Ϣ">'.$planRows[sourceNumb].'</a>';break;
					case "HTLX-YFHT":$souceNum='<a target="_bank" href="index1.php?model=contract_contract_contract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="�鿴��ͬ��Ϣ">'.$planRows[sourceNumb].'</a>';break;
					default:$souceNum=$planRows[sourceNumb];break;
				}
				if($val['purchType']=="oa_asset_purchase_apply"){
					$applyRow=$applyDao->get_d($val['applyId']);
					$souceNum='';
					$planRows['rObjCode']='';
					$planRows['projectName']='';
					$planRows['sendName']=$applyRow['applicantName'];
					$planRows['department']=$applyRow['applyDetName'];

				}

				$i++;
				$str.=<<<EOT
					<tr height="28" align="center">
						<td>
							$i
						</td>
						<td>
							$val[productNumb] <br/> $val[productName]
						</td>
						<td>
							$val[pattem]
						</td>
						<td>
							$val[unitName]
						</td>
						<td>
							$val[purchTypeCName]
						</td>
						<td>
							$val[amountAll]
						</td>
						<td>
							$val[contractAmount]
						</td>
						<td>
							$val[dateHope]
						</td>
						<td>
							$souceNum
						</td>
						<td>
							$planRows[rObjCode]
						</td>
						<td>
							$planRows[contractName]
						</td>
						<td>
							$planRows[projectName]
						</td>
						<td>
							$planRows[sendName]
						</td>
						<td>
							$planRows[department]
						</td>
						<td>
							<textarea rows="2" cols="23" class="textarea_read_blue" readOnly>$val[remark]</textarea>
						</td>
					</tr>
EOT;
			}
		}
		return $str;
	}

	/**
	 * �����ɹ�����ʾ�б�
	 * @param $listEqu		�ɹ�������������
	 */
	function showFeedbackList($listEqu){
		$planDao=new model_purchase_plan_basic();
		$str="";
		$i = 0;
		if($listEqu){
			foreach ($listEqu as $key => $val) {
				$planRows=$planDao->get_d($val['planId']);
				if($val['purchType']==""){      //�жϲɹ������Ƿ�Ϊ��
					$val['purchType']=$planRows['purchType'];
				}

				$i++;
				$str.=<<<EOT
					<tr height="28" align="center">
						<td>
							$i
						</td>
						<td>
							$val[productNumb] <br/> $val[productName]
							<input type="hidden" name="basic[equment][$i][productNumb]" value="$val[productNumb]" />
							<input type="hidden" name="basic[equment][$i][productName]" value="$val[productName]" />
							<input type="hidden" name="basic[equment][$i][purchType]" value="$val[purchType]" />
							<input type="hidden" name="basic[equment][$i][planId]" value="$val[planId]" />
							<input type="hidden" name="basic[equment][$i][sourceID]" value="$planRows[sourceID]" />
						</td>
						<td>
							$val[pattem]
							<input type="hidden" name="basic[equment][$i][pattem]" value="$val[pattem]" />
						</td>
						<td>
							$val[unitName]
							<input type="hidden" name="basic[equment][$i][unitName]" value="$val[unitName]" />
						</td>
						<td>
							$val[purchTypeCName]
							<input type="hidden" name="basic[equment][$i][purchTypeCName]" value="$val[purchTypeCName]" />
						</td>
						<td>
							$val[amountAll]
							<input type="hidden" name="basic[equment][$i][amountAll]" value="$val[amountAll]" />
						</td>
						<td>
							$val[dateHope]
							<input type="hidden" name="basic[equment][$i][dateHope]" value="$val[dateHope]" />
						</td>
						<td>
							$planRows[sourceNumb]
							<input type="hidden" name="basic[equment][$i][sourceNumb]" value="$planRows[sourceNumb]" />
						</td>
						<td>
							$planRows[contractName]
							<input type="hidden" name="basic[equment][$i][contractName]" value="$planRows[contractName]" />
						</td>
						<td>
							$planRows[projectName]
							<input type="hidden" name="basic[equment][$i][projectName]" value="$planRows[projectName]" />
						</td>
						<td>
							$planRows[sendName]
							<input type="hidden" name="basic[equment][$i][sendName]" value="$planRows[sendName]" />
							<input type="hidden" name="basic[equment][$i][planNumb]" value="$planRows[planNumb]" />
						</td>
					</tr>
EOT;
			}
		}
		return $str;
	}

	/**
	 * �½��ɹ����뵥ʾ�б�
	 * @param $listEqu		�ɹ�������������
	 */
	function newApply($listEqu){
		$str="";
		$i = $m = 0;
		if($listEqu){
			foreach ($listEqu as $key => $val) {
				$i++;

				$str.=<<<EOT
				<tr height="28" align="center">
					<td>
						<p class="childImg">
							<image src="images/expanded.gif" />$i
						</p>
					</td>
					<td>
						$val[productNumb]/$val[productName]
					</td>
					<td>
						<p class="allAmount">$val[allAmount]</p>
					</td>
					<td width="75%">
						<table class="shrinkTable" width="100%" border="0" cellspacing="1" cellpadding="0">
EOT;
				foreach ($val['childArr'] as $chdKey => $chdVal){
					$nowTime=date("Y-m-d H:i:s");
					$deviceNumb = "paequ-".date("YmdHis").rand(10,99);
					if( !isset( $chdVal["amountNotIssued"] )||$chdVal["amountNotIssued"]==0 ||$chdVal["amountNotIssued"]=="" ){
						$chdVal["amountNotIssued"] = 0;
						continue;
					}
					//������һ�д�����ڵ�941��
					//<input type="hidden" name="basic[equment][$m][deviceIsUse]" value="1" />
					$str.=<<<EOT
				<tr align="center">
						<td>
							$chdVal[basicNumb]
						</td>
						<td width="14%">
							$chdVal[purchTypeCName]
						</td>
						<td  width="12%">
							<input type="text" name="basic[equment][$m][amountAll]" id="amountAll$m" value="$chdVal[amountNotIssued]" size=6 class="taskAmount">
							<input type="hidden" name="amountAll" value="$chdVal[amountNotIssued]" />


							<input type="hidden" name="basic[equment][$m][applyEquOnlyId]" value="$deviceNumb" />
							<input type="hidden" name="basic[equment][$m][deviceNumb]" value="$deviceNumb" />
							<input type="hidden" name="basic[equment][$m][basicVersionNumb]" value="1" />
							<input type="hidden" name="basic[equment][$m][objectsNumb]" value="$chdVal[objectsNumb]" />
							<input type="hidden" name="basic[equment][$m][typeTabName]" value="$chdVal[typeTabName]" />
							<input type="hidden" name="basic[equment][$m][typeTabId]" value="$chdVal[typeTabId]" />
							<input type="hidden" name="basic[equment][$m][typeEquTabName]" value="$chdVal[typeEquTabName]" />
							<input type="hidden" name="basic[equment][$m][typeEquTabId]" value="$chdVal[typeEquTabId]" />
							<input type="hidden" name="basic[equment][$m][productName]" value="$chdVal[productName]" />
							<input type="hidden" name="basic[equment][$m][productId]" value="$chdVal[productId]" />
							<input type="hidden" name="basic[equment][$m][productNumb]" value="$chdVal[productNumb]" />
							<input type="hidden" name="basic[equment][$m][amountIssued]" value="0" />
							<input type="hidden" name="basic[equment][$m][dateIssued]" value="$nowTime" />
							<input type="hidden" name="basic[equment][$m][planId]" value="$chdVal[planId]" />
							<input type="hidden" name="basic[equment][$m][plantNumb]" value="$chdVal[plantNumb]" />
							<input type="hidden" name="basic[equment][$m][planEquId]" value="$chdVal[planEquId]" />
							<input type="hidden" name="basic[equment][$m][planEquNumb]" value="$chdVal[planEquNumb]" />

							<input type="hidden" name="basic[equment][$m][taskId]" value="$chdVal[basicId]" />
							<input type="hidden" name="basic[equment][$m][taskNumb]" value="$chdVal[basicNumb]" />
							<input type="hidden" name="basic[equment][$m][taskEquId]" value="$chdVal[id]" />
							<input type="hidden" name="basic[equment][$m][taskEquNumb]" value="$chdVal[deviceNumb]" />
						</td>
						<td width="14%">
							&nbsp;<input type="text" id="hopeTime$m" name="basic[equment][$m][dateHope]" size="9" maxlength="12" class="BigInput" value=""  onfocus="WdatePicker()"  readonly />
						</td>
						<td width="12%">
							&nbsp;<input type="text" id="applyPrice$m" name="basic[equment][$m][applyPrice]" size="9" maxlength="12" value=""/>
						</td>
						<td width="20%">
							<textarea rows="2" cols="18" name="basic[equment][$m][remark]" id="remark$m"></textarea>
						</td>
					</tr>
EOT;
					++$m;
				}
				$str.=<<<EOT
        	</table>
        	<div class="readThisTable"><����չ�����Ͼ�����Ϣ></div>
        </td>
    </tr>
EOT;
			}
		}
		return $str;
	}

	/**
	 * @exclude ����ɹ������б�
	 * @author ouyang
	 * @param $listEqu		�ɹ�������������
	 * @return
	 * @version 2010-8-17 ����04:50:46
	 */
	function showChange($listEqu){
		$str="";
		$i = $m = 0;
		if($listEqu){
			foreach ($listEqu as $key => $val) {
				$i++;
				if(empty($val['originalId'])){
					$str.='<input type="hidden" name="basic[equment]['.$i.'][oldId]" value="'.$val['id'].'" />';
				}else{
					$str.='<input type="hidden" name="basic[equment]['.$i.'][oldId]" value="'.$val['originalId'].'" />';
				}

				$str.=<<<EOT
					<tr height="28" align="center">
						<td>
							$i
						</td>
						<td>
							$val[productNumb]<br>$val[productName]
							<input type="hidden" size="10" id="productNumb$i" name="basic[equment][$i][productNumb]" value="$val[productNumb]"/>
							<input type="hidden" size="10" id="productName$i" name="basic[equment][$i][productName]" value="$val[productName]"/>
							<input type="hidden" size="10" id="productId$i" name="basic[equment][$i][productId]" value="$val[productId]"/>
							<input type="hidden" size="10" id="planId$i" name="basic[equment][$i][planId]" value="$val[planId]"/>
							<input type="hidden" size="10" id="planId$i" name="basic[equment][$i][purchType]" value="$val[purchType]"/>
						</td>
						<td>
							$val[pattem]
							<input type="hidden" id="pattem$i" name="basic[equment][$i][pattem]" value="$val[pattem]"/>
						</td>
						<td>
							$val[unitName]
							<input type="hidden" id="unitName$i" name="basic[equment][$i][unitName]" value="$val[unitName]"/>
						</td>
						<td>
							$val[purchTypeCName]
						</td>
						<td>
							$val[amountAll]
						</td>
						<td>
							$val[amountIssued]
							<input type="hidden" id="amountIssued$i" name="basic[equment][$i][amountIssued]" value="$val[amountIssued]"/>
						</td>
						<td>
							<input type="text" size="6" id="amountAll$i" name="basic[equment][$i][amountAll]" value="$val[amountAll]" class="taskAmount" />

							<input type="hidden" name="basic[equment][$i][maxAmount]"  value="$val[amountAll]" />
							<input type="hidden"   value="$val[amountIssued]" />
							<!--
							<input type="hidden" name="basic[equment][$i][id]"  value="$val[id]" /> -->
						</td>
						<td>
							&nbsp;<input type="text" id="dateHope$i" name="basic[equment][$i][dateHope]" size="9" maxlength="12" class="BigInput" value="$val[dateHope]"  onfocus="WdatePicker()"  readonly />
						</td>
						<td>
							<input id="remark$i" class="txtmiddle" name="basic[equment][$i][remark]" value="$val[remark]"></input>
						</td>
						<td>
							<input type="checkbox" class="" name="basic[equment][$i][isClose]" ></input>
						</td>
			    	</tr>
EOT;
			}
		}
		return $str;
	}

	/**
	 * @exclude ������ղ鿴�б�
	 * @author ouyang
	 * @param $listEqu		�ɹ�������������
	 * @return
	 * @version 2010-8-17 ����09:58:46
	 */
	function showChangeRead($listEqu){
		$str="";
		$i = 0;
		if($listEqu){
			foreach ($listEqu as $key => $val) {
				$i++;
				$str.=<<<EOT
					<tr height="28" align="center">
						<td>
							$i
						</td>
						<td>
							$val[productNumb] <br/> $val[productName]
						</td>
						<td>
							$val[objectsNumb]
						</td>
						<td>
							$val[purchTypeCName]
						</td>
						<td>
							$val[amountAll]
						</td>
						<td>
							$val[amountIssued]
						</td>
						<td>
							$val[dateIssued]
						</td>
						<td>
							$val[dateHope]
						</td>
						<td>
							<textarea rows="2" cols="23" readOnly>$val[remark]</textarea>
						</td>
					</tr>
EOT;
			}
		}
		return $str;
	}


/*****************************************ҳ��ģ����ʾ����********************************************/


/*****************************************��ʾ�ָ���********************************************/

	/**
	 * ͨ��value����״̬
	 */
	function stateToVal($stateVal){
		$returnVal = false;
		foreach( $this->state as $key => $val ){
			if( $val['stateVal']== $stateVal ){
				$returnVal = $val['stateCName'];
			}
		}
		//TODO:����쳣����
		return $returnVal;
	}

	/**
	 * ͨ��״̬����value
	 */
	function stateToSta($stateSta){
		$returnVal = false;
		foreach( $this->state as $key => $val ){
			if( $val['stateEName']== $stateSta ){
				$returnVal = $val['stateVal'];
			}
		}
		//TODO:����쳣����
		return $returnVal;
	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * �ɹ�����-�豸��ҳ��ʾ�б�
	 */
	function pageList_d(){
		$rows = $this->pageBySqlId("list_page");
		$i = 0;
		if($rows){
			$equipment = new model_purchase_task_equipment();
			$planDao = new model_purchase_plan_basic();
			//���ýӿ�������齫��ȡһ��objAss��������
			$interfObj = new model_common_interface_obj();
			foreach($rows as $key => $val){
				$equipment->resetParam();
				$searchArr = array (
					//"basicId" => $val['id']
					"basicNumb" => $val['taskNumb'],
//					"deviceIsUse" => "1",
					"status" => $equipment->statusToSta("execution")
				);
				$equipment->__SET('groupBy', "p.id");
				$equipment->__SET('sort', "p.id");
				$equipment->__SET('searchArr', $searchArr);
				$chiRows = $equipment->listBySqlId("equipment_list_plan");
				if(is_array($chiRows)){
					foreach ($chiRows as $chdKey => $chdVal){
						//���زɹ���������
						//$chiRows[$chdKey]['purchTypeCName'] = $planDao->purchTypeToVal( $chdVal['typeTabName'] );
						//$chiRows[$chdKey]['purchTypeCName'] = '���۲ɹ�';
						$chiRows[$chdKey]['purchTypeCName'] = $interfObj->typeKToC( $chdVal['ePurchType'] );		//��������

						if( !isset( $chdVal["amountAll"]) ||$chdVal["amountAll"]==""){
							$chiRows[$chdKey]["amountAll"] =  0;
						}

						if( !isset( $chdVal["amountIssued"]) ||$chdVal["amountIssued"]==""){
							$chiRows[$chdKey]["amountNotIssued"] =  $chiRows[$chdKey]["amountAll"];
						}else{
							$chiRows[$chdKey]["amountNotIssued"] =  $chiRows[$chdKey]["amountAll"] -  $chdVal["amountIssued"];
						}
					}
				}
				$rows[$i]['stateCName'] = $this->stateToVal( $rows[$i]['state'] );
				$rows[$i]['childArr']=$chiRows;
				++$i;
			}
			$rows = $planDao->purchTypArrayCName($rows);
			return $rows;
		}
		else {
			return false;
		}
	}

	/**
	 * �ɹ��豸-�����б�
	 */
	function listEqu_d(){
		$rows = $this->pageBySqlId("list_page");
		$i = 0;
		if($rows){
			$equipment = new model_purchase_task_equipment();
			foreach($rows as $key => $val){
				$equipment->resetParam();
				$searchArr = array (
					//"stockTaskId" => $val['id']
					"basicNumb" => $val['taskNumb'],
					"deviceIsUse" => "1",
					//"status" => $equipment->statusToSta("execution")
				);
				$equipment->__SET('groupBy', "p.id");
				$equipment->__SET('sort', "p.id");
				$equipment->__SET('searchArr', $searchArr);
				$chiRows = $equipment->listBySqlId("equipment_list");
				$rows[$i]['childArr']=$chiRows;
				++$i;
			}
			return $rows;
		}
		else {
			return false;
		}
	}

	/**
	 * @exclude ͨ��Id���زɹ������б�
	 * @author ouyang
	 * @param	$id �ɹ�����ID
	 * @return
	 * @version 2010-8-18 ����09:44:16
	 */
	function readTaskByBasicId_d ($id) {
		$searchArr = array (
					'id' => $id
				);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId('list_page');
		$i = 0;
		if($rows){
			//���òɹ�����
			$planDao = new model_purchase_plan_basic();
			$equipment = new model_purchase_task_equipment();
			foreach($rows as $key => $val){
				$equipment->resetParam();
				$searchArr = array (
					'basicId' => $val['id']
					//'status' => $equipment->statusToSta('execution')
				);
				$equipment->__SET('sort', 'p.productId');
				$equipment->__SET('searchArr', $searchArr);
				$chiRows = $equipment->listBySqlId('equipment_list');
				$chiRows = $planDao->purchTypArrayCName($chiRows);
				$rows[$i]['childArr']=$chiRows;
				++$i;
			}
			return $rows;
		}
		else {
			return false;
		}
	}

	/**
	 * ��ȡ�ɹ�������ϸ��Ϣ
	 * @param	$id �ɹ�����ID
	 */
	function readTask_d($id){
        $this->_isSetCompany =0;//�Ƿ񵥾��Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������
		$searchArr = array (
					"id" => $id
				);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("list_page");
		$i = 0;
		if($rows){
			$equipment = new model_purchase_task_equipment();
			$planDao = new model_purchase_plan_basic();
			$planEquDao=new model_purchase_plan_equipment();
			$interfObj = new model_common_interface_obj();
			foreach($rows as $key => $val){
				$equipment->resetParam();
				$searchArr = array (
					"basicId" => $val['id'],
					"basicNumb" => $val['taskNumb']
//					"deviceIsUse" => "1",
					 //"status" => $equipment->statusToSta("execution")
				);
				$equipment->__SET('groupBy', "p.id");
				$equipment->__SET('sort', "p.productId");
				$equipment->__SET('searchArr', $searchArr);
				$chiRows = $equipment->listBySqlId("equipment_list");
				if(is_array($chiRows)){
					foreach ($chiRows as $keyChild => $valChild){
						 //��ȡ�ɹ��ƻ����豸��Ϣ
					    $planEquRows=$planEquDao->get_d($valChild['planEquId']);
						//���زɹ���������
						$chiRows[$keyChild]['purchTypeCName'] = $interfObj->typeKToC( $valChild['purchType'] );
					}
				}
				$rows[$i]['childArr']=$chiRows;
				++$i;
			}
			return $rows;
		}
		else {
			return false;
		}
	}

	/**
	 * @exclude �رղɹ�����
	 * @author ouyang
	 * @param	$numb �ɹ�������
	 * @return
	 * @version 2010-8-10 ����04:30:12
	 */
	function close_d($numb){
		$equDao = new model_purchase_task_equipment();
//		$equDao->closeTaskEqu_d($numb);
		$condition = array(
					"taskNumb" => $numb
				);
		$obj = array(
					"state" => $this->stateToSta('close'),
					"dateFact" => date("Y-m-d"),
					"ExaStatus" => "�ر�"
				);
		return parent::update ( $condition, $obj );
	}

	/**
	 * @exclude ���������ɹ�����
	 * @param	$�� �ɹ�����ID
	 */
	function startTask_d($id){
		$condition = array(
					"id" => $id
				);
		$obj = array(
					"state" => $this->stateToSta('execute')
				);
		return parent::update ( $condition, $obj );
	}

	/**
	 * @exclude ��ɲɹ��������������
	 * @author ouyang
	 * @param �ɹ�����Id
	 * @return 0:�쳣��1:���,2���������
	 * @version 2010-8-10 ����04:26:05
	 */
	function end_d ($id) {
		 $numb = $this->findNumbById_d($id);
		$equDao = new model_purchase_task_equipment();
		if( $equDao->endTaskByBasicId_d($numb) ){
			$obj = array(
					"id" => $id,
					"state" => $this->stateToSta('end'),
					"dateFact" => date("Y-m-d")
			);
			if( parent::updateById($obj) ){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 2;
		}
	}

	/**
	 * �����ɹ�����״̬��״̬Ϊ���
	 */
	function updateData_d() {
		try {
			$this->start_d ();
			$searchArr ['state'] = $this->stateToSta('execute');
			$this->searchArr=$searchArr;
			$rows = $this->listBySqlId ( 'list_page' );
			if ($rows) {
				foreach ( $rows as $key =>$val) {
					$this->end_d($val['id']);
				}
			}
			$this->commit_d ();
			return 1;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return 0;
		}
	}

	/**
	 * @exclude ͨ��Id�ұ��
	 * @author ouyang
	 * @param $id ҵ��Id
	 * @return ҵ����
	 * @version 2010-8-10 ����07:06:51
	 */
	function findNumbById_d ($id) {
		$searchArr = array (
					"id" => $id
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("list_page");
		if($rows){
			return $rows['0']['taskNumb'];
		}else{
			return false;
		}
	}

	/**
	 * @exclude ͨ���ɹ������Ų�ѯ״̬
	 * @author ouyang
	 * @param	$basicNumb �ɹ�������
	 * @return
	 * @version 2010-8-10 ����11:46:07
	 */
	function getStateByNumb ($basicNumb) {
		$searchArr = array (
					"taskNumbMy" => $basicNumb,
//					"isUse" => "1"
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("list_page");
		if($rows){
			return $rows['0']['state'];
		}else{
			return false;
		}
	}



	/**
	 * @exclude �����ȡ�ɹ��ƻ���ϸ��Ϣ
	 * @author ouyang
	 * @param	$id  �ɹ�����ID
	 * @param	$basicNumb �ɹ�������
	 * @return
	 * @version 2010-8-17 ����06:26:56
	 */
	function toChange_d($id,$numb){
		$searchArr = array (
					'id' => $id
				);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId('list_page');
		$i = 0;
		if($rows){
			//���òɹ�����
			$planDao = new model_purchase_plan_basic();
			$rows = $planDao->purchTypArrayCName($rows);
			$equDao = new model_purchase_task_equipment();
			$interfObj = new model_common_interface_obj();
			foreach($rows as $key => $val){
				$equDao->resetParam();
				$searchArr = array (
					//'basicId' => $val['id']
					'basicNumb' => $val['taskNumb'],
					'deviceIsUse' => '1',
					'status' => $equDao->statusToSta('execution')
				);
				$equDao->__SET('searchArr', $searchArr);
				$chiRows = $equDao->listBySqlId('equipment_list_plan');
				foreach($chiRows as $chdKey => $chdVal){
					//$chiRows[$chdKey]['purchTypeCName'] = $planDao->purchTypeToVal( $chdVal['typeTabName'] );
					//$chiRows[$chdKey]['purchTypeCName'] = '���۲ɹ�';
					$chiRows[$chdKey]['purchTypeCName'] = $interfObj->typeKToC( $chdVal['ePurchType'] );		//��������
				}
				$rows[$i]['childArr']=$chiRows;
				++$i;
			}
			return $rows;
		}
		else {
			return false;
		}
	}

	/**
	 * ��������ӷ���
	 *author can
	 *2011-1-12
	 */
	function change_d($obj) {
		$this->start_d ();
			$taskEquDao = new model_purchase_task_equipment();
		$changeLogDao = new model_common_changeLog ( 'purchasetask',false );
		//�����¼,�õ��������ʱ������id
		$tempObjId = $changeLogDao->addLog ( $obj );

		$oldTask=parent::get_d($obj['oldId']);
		$obj['id']=$obj['oldId'];
		$obj['isTemp']=1;

		$emailArr = $obj['email'];
		unset($obj['email']);
		$flag=parent::edit_d ( $obj );

		$supDao = new model_purchase_plan_equipment();	//ʵ��������
		$planDao=new model_purchase_plan_basic();
		$applyEquDao=new model_asset_purchase_apply_applyItem();
		$applyDao=new model_asset_purchase_apply_apply();
		foreach($obj['equment'] as $key => $val){
			$oldEqu=$taskEquDao->get_d( $val ['oldId'] );

			//�ж������Ƿ����
			$amountJudge = ( $oldEqu['amountAll']>=$val['amountAll'] && $oldEqu['contractAmount']<=$val['amountAll'] );
			if( !$amountJudge ){
				throw new Exception('��������������С�����´�������');
			}
				$val['id']= $val ['oldId'];
				$taskEquDao->edit_d ( $val );

			//�������
			if($oldEqu['amountAll']!=$val['amountAll']&&$val['isClose']=="on" ){
				if($oldEqu['purchType']=='oa_asset_purchase_apply'){
					//���²ɹ�����״̬Ϊִ����
					$applyDao->updateField('id='.$oldEqu['applyId'],'purchState','0');
					//�����ϼ��´�����
					$applyEquDao->updateAmountIssued ( $oldEqu ['applyEquId'], 0,$oldEqu['amountAll']-$val['amountAll'] );
				}else{
					//���²ɹ�����״̬Ϊִ����
					$planDao->updateField('id='.$val['planId'],'state','0');
					//�����ϼ��´�����
					$supDao->updateAmountIssued ( $oldEqu ['planEquId'], 0,$oldEqu['amountAll']-$val['amountAll'] );
					$supDao->updateField('id='.$oldEqu['planEquId'],'isTask','0');//�������ϵ�״̬Ϊ���´�����
				}
			}
		}

		//�����ʼ� ,������Ϊ�ύʱ�ŷ���
		if( $emailArr['issend'] == 'y'&&!empty($emailArr['TO_ID'])){
			$emailDao = new model_common_mail();
			$emailInfo = $emailDao->batchEmail($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],$this->tbl_name,'���',$obj['taskNumb'],$emailArr['TO_ID'],$addmsg=null,1);
		}
		$this->commit_d ();
		return $flag;
	}

	/**
	 * @exclude ���ձ��
	 * @author ouyang
	 * @param	$id  �ɹ�����ID
	 * @param	$numb �ɹ�������
	 * @version 2010-8-18 ����10:14:37
	 */
	function receiveChange_d ($id,$numb) {
		try {
			$this->start_d ();
			$oldCondition = array(
				'taskNumb' => $numb,
				'isUse' => '1'
			);
			$oldObject = array(
				'state' => $this->stateToSta('close')
			);
			$this->update($oldCondition,$oldObject);

			$equDao = new model_purchase_task_equipment();
			$oldConditionEqu = array(
				'basicNumb' => $numb,
				'deviceIsUse' => '1'
			);
			$oldObjectEqu = array(
				'deviceIsUse' => '0'
			);
			$equDao->update($oldConditionEqu,$oldObjectEqu);

			$newCondition = array(
				'taskNumb' => $numb,
				'state' => $this->stateToSta('change')
			);
			$newObject = array(
				'state' => $this->stateToSta('execute'),
				'isUse' => '1'
			);
			$this->update($newCondition,$newObject);

			$newConditionEqu = array(
				'basicId' => $id
			);
			$newObjectEqu = array(
				'deviceIsUse' => '1'
			);
			$equDao->update($newConditionEqu,$newObjectEqu);

			//TODO:�ɹ��ƻ�������� ���º�ͬ�´�����
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * @exclude �˻ر��
	 * @author ouyang
	 * @param	$id  �ɹ�����ID
	 * @param	$numb �ɹ�������
	 * @version 2010-8-18 ����10:14:52
	 */
	function returnChange_d ($id,$numb) {
		try {
			$this->start_d ();
			$oldCondition = array(
				'taskNumb' => $numb,
				'isUse' => '1'
			);
			$oldObject = array(
				'state' => $this->stateToSta('execute')
			);
			$this->update($oldCondition,$oldObject);

			$newCondition = array(
				'taskNumb' => $numb,
				'state' => $this->stateToSta('change')
			);
			$newObject = array(
				'state' => $this->stateToSta('close'),
				'isUse' => '0'
			);
			$this->update($newCondition,$newObject);

			//TODO:�ɹ��ƻ�����ܾ� ���º�ͬ�´�����
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}
	/**
	 * @desription �ɹ�����-�豸��ҳ��ʾ�б�(���������ô�д)
	 * @param tags
	 * @date 2010-12-23 ����02:31:45
	 */
	function PageTask_d(){
		$rows = $this->pageBySqlId("list_page");
		if( is_array( $rows ) ){
			$equipment = new model_purchase_task_equipment();
			$rows = $equipment->getTaskAsEqu_d( $rows );
			foreach($rows as $key => $val){
				$rows[$key]['stateC'] = $this->statusDao->statusKtoC( $rows[$key]['state'] );  //״̬����
			}
			return $rows;
		}else{
			return false;
		}
	}

	/**
	 * ��д�����ɹ�����
	 */
	function add_d($object) {
		try {
			$this->start_d ();
			$object['state'] = $this->statusDao->statusEtoK('begin');
			$object['objCode'] = $this->objass->codeC( "purch_task" );
			$codeDao=new model_common_codeRule();
			$object['taskNumb'] = $codeDao->purchTaskCode($this->tbl_name,$object['sendUserId']);
			$object['isTemp'] = 0;
			$taskId = parent::add_d ( $object,true );

			$equipmentDao = new model_purchase_task_equipment();
			$applyEquDao=new model_asset_purchase_apply_applyItem();
			$applyDao=new model_asset_purchase_apply_apply();
			$emailEquArr=array();//�´������������������ȵ�����
			$planIds=array();
			$assetIds=array();//�̶��ʲ�����ID
			foreach( $object['equment'] as $key => $val ){
//				if($val['amountAll']!==$val['planAmonut']){//�ж��Ƿ�ȫ���´�����
//					array_push($emailEquArr,$object['equment'][$key]);
//				}
				$val['objCode'] = $this->objass->codeC( 'purch_task_equ' );
				$val['basicNumb'] = $object["taskNumb"];
				$val['basicId'] = $taskId;
				$val['status'] = $equipmentDao->statusDao->statusEtoK('execution');
				$equId=$equipmentDao->add_d( $val );
				if($val['purchType']!="oa_asset_purchase_apply"){//�����Ƿ��ɹ̶��ʲ������Ĳɹ�����
					//�����ܹ��������ݣ���������豸�嵥idȥ���ܹ��������Ҫ���µ�����Ϊ�գ������update�����������Ʋ�Ϊ�յ����ݽ�����������
					$this->objass->saveModelObjs("purch",array("planEquId"=>$val['planEquId']),array("taskCode"=>$object['taskNumb'],"taskId"=>$taskId,"taskEquId"=>$equId));
					//���²ɹ��ƻ��豸�����´�����
					$planEquDao=new model_purchase_plan_equipment();
					$planEquDao->updateAmountIssued($val['planEquId'],$val['amountAll']);
					$planEquDao->updateField('id='.$val['planEquId'],'isTask','1');//�������ϵ�״̬Ϊ���´�����
					array_push($planIds,$val['planId']);//�ɹ�����id
				}else{
					//���²ɹ��ƻ��豸�����´�����
					$applyEquDao->updateAmountIssued($val['applyEquId'],$val['amountAll']);
					$applyDao->updatePurchState_d($val['applyId']);

				}
			}
			$planIdArr=array_unique($planIds);
			if(!empty($planIdArr)){//����ɹ�������������´���ɣ������״̬Ϊ���ѹرա�
				$planArrStr=implode(',',$planIdArr);
				$planDao=new model_purchase_plan_basic();
				$planDao->updateData_d($planArrStr);
			}

			//�����ʼ�֪ͨ�ɹ�������
			$emailArr=array();
			$emailArr['issend'] = 'y';
			$emailArr['TO_ID']=$object['sendUserId'];
			$emailArr['TO_NAME']=$object['sendName'];
			if(is_array($object ['equment'])){
				$j=0;
				$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>���</b></td><td><b>���ϱ���</b></td><td><b>��������</b></td><td><b>����ͺ�</b></td><td><b>��λ</b></td><td><b>��������</b></td><td><b>�ɹ�������</b></td><td><b>�ɹ�����</b></td><td><b>Դ����� </b></td><td><b>ϣ�����ʱ��</b></td><td><b>��ע</b></td></tr>";
				foreach($object ['equment'] as $key => $equ ){
					$j++;
					$productNumb=$equ['productNumb'];
					$productName=$equ['productName'];
					$pattem=$equ ['pattem'];
					$unitName=$equ ['unitName'];
					$amountAll=$equ ['amountAll'];
					$applyNum=$equ ['applyNum'];
					$purchTypeCName=$equ['purchTypeCName'];
					$sourceNum=$equ['sourceNum'];
					$dateHope=$equ['dateHope'];
					$remark=$equ['remark']." ";
					$addmsg .=<<<EOT
					<tr align="center" >
							<td>$j</td>
							<td>$productNumb</td>
							<td>$productName</td>
							<td>$pattem</td>
							<td>$unitName</td>
							<td>$amountAll</td>
							<td>$applyNum</td>
							<td>$purchTypeCName</td>
							<td>$sourceNum</td>
							<td>$dateHope</td>
							<td>$remark </td>
						</tr>
EOT;
					}
					$addmsg.="</table>";
			}
			$emailDao = new model_common_mail();
			$emailInfo = $emailDao->purchTaskEmail($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],$this->tbl_name,',�ɹ����񵥾ݺ�Ϊ��<font color=red><b>'.$object["taskNumb"].'</b></font>','',$emailArr['TO_ID'],$addmsg,1);
            //�ٷ���һ���ʼ��ʼ���������
            if(is_array($object ['equment']) && $object ['equment'][0]['sendUserId'] != '') {
                $emailArr['TO_ID2'] = $object ['equment'][0]['sendUserId'];
                $emailInfo2 = $emailDao->purchTaskEmail($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, ',�ɹ����񵥾ݺ�Ϊ��<font color=red><b>' . $object["taskNumb"] . '</b></font>', '', $emailArr['TO_ID2'], $addmsg, 1);
            }

		/*	if(!empty($emailEquArr)){
				//�����ʼ�֪ͨ�ɹ�������
				$emailArr=array();
				$emailSendIdArr=array();
				$emailArr['SENDID']="";
				$emailArr['SENDNAME']="";
					$k=0;
					$equaddmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>���</b></td><td><b>��������</b></td><td><b>����ͺ�</b></td><td><b>��λ</b></td><td><b>�ɹ���������</b></td><td><b>�´���������</b></td><td><b>�ɹ�������</b></td><td><b>�ɹ�����</b></td><td><b>Դ����� </b></td><td><b>ϣ�����ʱ��</b></td><td><b>��ע</b></td></tr>";
					foreach($emailEquArr as $key => $equ ){
						array_push($emailSendIdArr,$equ['sendUserId']);
						$k++;
						$productName=$equ['productName'];
						$pattem=$equ ['pattem'];
						$unitName=$equ ['unitName'];
						$planAmount=$equ ['planAmonut'];
						$amountAll=$equ ['amountAll'];
						$applyNum=$equ ['applyNum'];
						$purchTypeCName=$equ['purchTypeCName'];
						$sourceNum=$equ['sourceNum'];
						$dateHope=$equ['dateHope'];
						$remark=$equ['remark']." ";
						$equaddmsg .=<<<EOT
						<tr align="center" >
								<td>$k</td>
								<td>$productName</td>
								<td>$pattem</td>
								<td>$unitName</td>
								<td>$planAmount</td>
								<td>$amountAll</td>
								<td>$applyNum</td>
								<td>$purchTypeCName</td>
								<td>$sourceNum</td>
								<td>$dateHope</td>
								<td>$remark </td>
							</tr>
EOT;
						}
						$equaddmsg.="</table>";
				$emailArr['SENDID']=implode(',',$emailSendIdArr);
				$emailInfo = $emailDao->purchTaskFeedbackEmail('y',$_SESSION['USERNAME'],$_SESSION['EMAIL'],'purchPlanFeedback','','',$emailArr['SENDID'],$equaddmsg,1);

			}*/
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			throw $e;
			return null;
		}
	}

	/**
	 * @desription ���ղɹ�����
	 * @param	$id  �ɹ�����ID
	 * @date 2010-12-23 ����04:28:54
	 */
	function stateBegin_d($id){
		$obj = array(
					"id" => $id,
					"state" => $this->statusDao->statusEtoK("execute"),
					"dateReceive" => date("Y-m-d"),
					"updateTime" => date ( "Y-m-d H:i:s" )
				);
		return parent::edit_d($obj,true);
	}

	/**��ȡ�ɹ�������
	 * @author Administrator
	 *
	 */
	 function getApplyMan_d($equRows){
	 	$applyArr=array();
	 	if(is_array($equRows)){
			$planDao=new model_purchase_plan_basic();
			$interfObj = new model_common_interface_obj();
//			$orderContractDao = new model_projectmanagent_order_order();
	 		foreach($equRows as $key=>$val){
	 			if($val['planId']){
	 				$sendIdSource=array();
	 				$sendNameSource=array();
					$planRows=$planDao->get_d($val['planId']);
					$applyArr['sendId'][$key]=$planRows['sendUserId'];
					$applyArr['sendName'][$key]=$planRows['sendName'];
					if($planRows['purchType']=="oa_sale_order"||$planRows['purchType']=="oa_sale_lease"||$planRows['purchType']=="oa_sale_service"||$planRows['purchType']=="oa_sale_rdproject"){
						$supDaoName = $interfObj->typeKToObj( $planRows['purchType'] );  //��ȡ�ɹ����Ͷ�������
						$supDao = new $supDaoName();	//ʵ��������
						$sourceRow=$orderContractDao->getCusinfoByorder($planRows['purchType'],$planRows['sourceID']);
						array_push($sendIdSource,$sourceRow['prinvipalId']);
						array_push($sendNameSource,$sourceRow['prinvipalName']);
					}else if($planRows['purchType']=="oa_borrow_borrow"||$planRows['purchType']=="oa_present_present"){
						$supDaoName = $interfObj->typeKToObj( $planRows['purchType'] );  //��ȡ�ɹ����Ͷ�������
						$supDao = new $supDaoName();	//ʵ��������
						$sourceRow=$supDao->getInfoList($planRows['sourceID']);
						array_push($sendIdSource,$sourceRow['createId']);
						array_push($sendNameSource,$sourceRow['createName']);
					}
					if($planRows['purchType']=="HTLX-XSHT"||$planRows['purchType']=="HTLX-ZLHT"||$planRows['purchType']=="HTLX-FWHT"||$planRows['purchType']=="HTLX-YFHT"){
						$supDaoName = $interfObj->typeKToObj( $planRows['purchType'] );  //��ȡ�ɹ����Ͷ�������
						$supDao = new $supDaoName();	//ʵ��������
						$sourceRow=$supDao->getInfoList($planRows['sourceID']);
						array_push($sendIdSource,$sourceRow['prinvipalId']);
						array_push($sendNameSource,$sourceRow['prinvipalName']);
					}
	 			}
	 		}
	 		if(!empty($sendIdSource)){
	 			foreach($sendIdSource as $sKey=>$sVal){
					array_push($applyArr['sendId'],$sVal);
					array_push($applyArr['sendName'],$sendNameSource[$sKey]);
	 			}
	 		}
	 	}
	 	return $applyArr;
	 }

	 /**�ɹ�������
	 * @author Administrator
	 *
	 */
	 function feedback_d($object){
		try {
			$this->start_d ();
			//���²ɹ�����Ϊ�ѷ���
			$flag=$this->updateField('id='.$object['id'],'feedback','1');
			//�����ʼ�
			$emailArr=array();
			$emailArr['issend'] = 'y';
			$emailArr['TO_ID']=$object['email']['TO_ID'];
			$emailArr['TO_NAME']=$object['email']['TO_NAME'];
			if(is_array($object ['equment'])){
				$interfObj = new model_common_interface_obj();
				$j=0;
				$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>���</b></td><td><b>��������</b></td><td><b>����ͺ�</b></td><td><b>��λ</b></td><td><b>��������</b></td><td><b>ϣ�����ʱ��</b></td><td><b>�ɹ�������</b></td><td><b>�ɹ�����</b></td><td><b>Դ����� </b></td><td><b>Դ������ </b></td><td><b>��Ŀ���� </b></td><td><b>����/ʹ����</b></td><td><b>����ԭ��</b></td></tr>";
				foreach($object ['equment'] as $key => $equ ){
					$j++;
					$productName=$equ['productName'];
					$pattem=$equ ['pattem'];
					$unitName=$equ ['unitName'];
					$amountAll=$equ ['amountAll'];
					$applyNum=$equ ['planNumb'];
					$purchTypeCName=$equ['purchTypeCName'];
					$sourceNum=$equ['sourceNumb'];
					$dateHope=$equ['dateHope'];
					$contractName=$equ['contractName'];
					$projectName=$equ['projectName'];
					$sendName=$equ['sendName'];
					if($equ['purchType']=="oa_sale_order"||$equ['purchType']=="oa_sale_lease"||$equ['purchType']=="oa_sale_service"||$equ['purchType']=="oa_sale_rdproject"){
						$supDaoName = $interfObj->typeKToObj( $equ['purchType'] );  //��ȡ�ɹ����Ͷ�������
						$supDao = new $supDaoName();	//ʵ��������
						$sourceRow=$orderContractDao->getCusinfoByorder($equ['purchType'],$equ['sourceID']);
						$sendName=$sourceRow['prinvipalName'];
					}else if($equ['purchType']=="oa_borrow_borrow"||$equ['purchType']=="oa_present_present"){
						$supDaoName = $interfObj->typeKToObj( $equ['purchType'] );  //��ȡ�ɹ����Ͷ�������
						$supDao = new $supDaoName();	//ʵ��������
						$sourceRow=$supDao->getInfoList($equ['sourceID']);
						$sendName=$sourceRow['createName'];
					}
					if($equ['purchType']=="HTLX-XSHT"||$equ['purchType']=="HTLX-ZLHT"||$equ['purchType']=="HTLX-FWHT"||$equ['purchType']=="HTLX-YFHT"){
						$supDaoName = $interfObj->typeKToObj( $equ['purchType'] );  //��ȡ�ɹ����Ͷ�������
						$supDao = new $supDaoName();	//ʵ��������
						$sourceRow=$supDao->getInfoList($equ['sourceID']);
						$sendName=$sourceRow['prinvipalName'];
					}
					$applyReason='';
					if($equ['planId']>0){
						$applyReason=$this->get_table_fields('oa_purch_plan_basic','id='.$equ['planId'],'applyReason');
					}
					$addmsg .=<<<EOT
					<tr align="center" >
							<td>$j</td>
							<td>$productName</td>
							<td>$pattem</td>
							<td>$unitName</td>
							<td>$amountAll</td>
							<td>$dateHope</td>
							<td>$applyNum</td>
							<td>$purchTypeCName</td>
							<td>$sourceNum</td>
							<td>$contractName</td>
							<td>$projectName</td>
							<td>$sendName</td>
							<td align='left'>$applyReason</td>
					</tr>
EOT;
					}
					$addmsg.="</table><br/>";
					$addmsg.="�������ݣ�<br/>     ";
					$addmsg.="<font color='blue'>".$object['feedbackRemark']."</font>";
			}
			$emailDao = new model_common_mail();
			$emailInfo = $emailDao->purchTaskFeedbackEmail($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'purchTaskFeedback','','',$emailArr['TO_ID'],$addmsg,1);
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	 }

}
?>
