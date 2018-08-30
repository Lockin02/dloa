<?php
/**
 * @author Administrator
 * @Date 2012-08-09 13:56:00
 * @version 1.0
 * @description:��ְ�����嵥 Model��
 */
class model_hr_leave_handover  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_leave_handover";
		$this->sql_map = "hr/leave/handoverSql.php";
		parent::__construct ();
	}

	/**
	 * ��дadd_d����
	 */
	function add_d($object) {
		try {
			$this->start_d();
			//���������ֵ��ֶ�
			$datadictDao = new model_system_datadict_datadict();
			//����������Ϣ
			$newId = parent :: add_d($object, true);
			//����ӱ���Ϣ
			//ģ��
			if (!empty ($object['formwork'])) {
				$Dao = new model_hr_leave_handoverlist();
				$handoverMemberDao=new model_hr_leave_handoverMember();
				$mailStr="";
				$mailArr=array();
				foreach($object['formwork'] as $key=>$val){
					if(!empty($val['items'])){
						$val['handoverId']=$newId;
						$listId=$Dao->add_d($val);
						if(!empty($val['recipientId'])){
							$emailStr.=$val['recipientId'].",";
							$recipientName=explode(",",$val['recipientName']);
							$recipientId=explode(",",$val['recipientId']);
							//ѭ����̸�ߣ��ֱ����ӱ�
							foreach($recipientId as $mKey =>$mVal){
								if($mVal!=""){
									array_push($mailArr,$mVal);
									$newArr=array("handoverId"=>$newId,'parentId'=>$listId);
									$itemsArr=array();
									$interviewerArray = array();
									$interviewerArray=array("recipientId"=>$mVal,"recipientName"=>$recipientName[$mKey]);
									$itemsArr=array_merge($newArr,$interviewerArray);
									$handoverMemberDao->add_d($itemsArr,true);
								}
							}
						}
					}
				}
				if(!empty($mailArr)){//�����ʼ�֪ͨȷ����
					$newMailArr=array_unique($mailArr);
					$mailStr=implode(',',$newMailArr);
					$this->mailToConfirm($newId,$mailStr);
				}

				$this->departureTransferMail_d($object); //���ʼ�֪ͨԱ��
			}
			$this->commit_d();
			return $newId;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ������ְ���뵥ID�������ʼ�֪ͨԱ����������ְ�����嵥����ܰ����
	 */
	function departureTransferMail_d( $object ) {
		$emailDao = new model_common_mail();
		$leaveDao = new model_hr_leave_leave();
		$leaveObj = $leaveDao->get_d( $object['leaveId'] );
		$mailContent = "���ã�<br>"
				."������ְ����������ͨ������ְ����Ϊ��<span style='color:blue'>".$leaveObj['comfirmQuitDate']
				."</span>������ָ�����幤���������Ͻ�������,����<span style='color:blue'>".$leaveObj['comfirmQuitDate']."</span>ǰ���±��������������ɹ������ӡ�<br>"
				."��ϸ��Ϣ���£�<br>";
		if (!empty ($object['formwork']) && is_array($object['formwork'])) {
			$mailContent .= '<table border="1px" cellspacing="0px" style="text-align:center"><tr style="background-color:#fff999"><td width="600px">�������豸��������</td><td width="300px">������</td></tr>';
			foreach ($object['formwork'] as $key => $val) {
				$mailContent .= '<tr><td>'.$val['items'].'</td><td>'.$val['recipientName'].'</td></tr>';
			}
			$mailContent .= '</table><br><br>';
		}
		$mailContent .= "<span style='color:blue'>���İ칫���߽���<span style='color:red'>".$leaveObj['comfirmQuitDate']."</span>�رգ�����ܰ���ѣ�Ϊʹ��˳������������������������¹������Ա���ز��ż�ʱ�����������豸��<br>"
					."<br>1�����������豨���ķ��ã�������ʽ��ְǰ��ʵ��д����������Ҫ���ṩ��ӦƱ�ݣ�<br>"
					."<br>2�������н��ù�˾���ã�������ʽ��ְǰ��ϵ�������Թ黹��<br>"
					."<br>3�������н��ù�˾�豸����ο��豸����ͬ�·������嵥������ʽ��ְǰ���Թ黹��<br>"
					."<br>4��������������ִ�����Ļ��Ե�ר������������ʽ��ְǰ��д�������¹�����־���Ա����Ϊ�����㵱�¸���н�ꣻ��������������־Ϊ׼��©��Ľ����ٽ��м��㷢�ţ� <br>"
					."<br><br>ȫ���������Ӽ���ְ����������Ϻ󣬿ɲ���ÿ��15�ջ��µ׵���ְ���㣬����������Դ��������ְ���ʣ���ʱ�����ͽ�����ϸ�����������䣬���뼰ʱ���ġ�<br>"
					."<br><br>������������У��������ʣ���ӭ��ʱ��ϵ������Դ��0756-3639105<br>"
					."<br><br>��л���ڹ�˾��Ŭ��������������ף�����Ҹ����������⣡<br>"
					."<br><br><br>�麣���Ͷ����Ƽ��ɷ����޹�˾</span>";
		$emailDao->mailGeneral("��ְ��������֪ͨ" ,$leaveObj['userAccount'] ,$mailContent);
	}

	/**
	 * ������ְ���뵥ID��������ְ�����嵥�������ˣ��������ʼ�
	 */
	 function mailByLeaveId($leaveId){
		//�����Ƿ������ְ�����嵥
		$row=$this->getnfo_d($leaveId);
		if(is_array($row)){
			//��ȡ�����嵥��Ϣ
		     $Dao = new model_hr_leave_handoverlist();
		     $Dao->searchArr['handoverId'] = $row['id'];
		     $fromworkInfo = $Dao->list_d ("select_default");
		     if(is_array($fromworkInfo)){
				$mailStr="";
				$mailArr=array();
				foreach($fromworkInfo as $key=>$val){
					if(!empty($val['recipientId'])){
							$emailStr.=$val['recipientId'].",";
							$recipientName=explode(",",$val['recipientName']);
							$recipientId=explode(",",$val['recipientId']);
							//ѭ����̸�ߣ��ֱ����ӱ�
							foreach($recipientId as $mKey =>$mVal){
								if($mVal!=""){
									array_push($mailArr,$mVal);
								}
							}
					}
				}
				if(!empty($mailArr)){//�����ʼ�֪ͨȷ����
					$newMailArr=array_unique($mailArr);
					$mailStr=implode(',',$newMailArr);
					$this->mailToConfirm($row['id'],$mailStr);
				}

		     }
		}
	 }

	/**
	 * �����ʼ�֪ͨȷ���˽���ȷ��
	 */
	 function mailToConfirm($id,$mailIds){
		//�����ʼ�֪ͨԱ������ȷ��
		$emailDao = new model_common_mail ();
		$leaveObj=$this->get_d($id);
		$msg="���ã���".$leaveObj['deptName']."-".$leaveObj['userName']."������ְ�����嵥�ѷ���ȷ�ϣ�����<font color='blue''>".$leaveObj['quitDate']."</font>ǰ��������Ľ��������ȷ�ϣ���Ա������ط��á��豸���ã��뷢���嵥֪ͨԱ����ʱ�黹��������ʧ������OAȷ��ʱ��д��Ӧ�۳��OAȷ��·�������˰칫-��������-������-��ְ����ȷ��.";
		$emailDao->mailClear("��ְ������ȷ��",$mailIds,$msg);
	 }

	/**
	 * ��ȡ���е�ǰ��¼��Ϊȷ���˵Ľ����嵥ID
	 */
	function affirmUserInfo($userId){
		$sql = "select handoverId from oa_hr_handover_member where recipientId = '".$userId."' and affstate=0 group by handoverId";
		$arr = $this->_db->getArray($sql);

		if(is_array($arr)){
			foreach ($arr as $k => $v){
				$ids .= $v['handoverId'].",";
			}
			$ids = rtrim($ids, ',');
		}

		return $ids;
	}
	/**
	 * ��ְ�����嵥ȷ�ϲ���
	 */
	function affirmCon_d($arr){
		try {
			$this->start_d();
			if(is_array($arr['formwork'])){
				foreach($arr['formwork'] as $k => $v){
					if($v['affstate']=="0"){
						$updateSql = "update oa_hr_handover_list set handoverCondition = '".$v['handoverCondition']."',lose = '".$v['lose']."',deduct = '".$v['deduct']."',remark = '".$v['remark']."',affstate = '1',affTime = now() where id='".$v['id']."'";
						$this->query($updateSql);
					}
				}
			}
			if(is_array($arr['formwork'])){

				$handoverlistDao = new model_hr_leave_handoverlist();
			    $handoverlistRow = $handoverlistDao->findAll ( array('handoverId' => $arr['id']) );
				foreach($handoverlistRow as $k => $v) {
					//�ж��¼��Ƿ��������з���ǰ������
					if(!$v['affstate'] && $v['mailAffirm'] == 'on' && trim($v['sendPremise']) != ''){
						$a = trim($v['sendPremise']);
						$sendPremise = str_replace('��',',',$a);//����Ƿ�Ϊ���ķ���
						if($handoverlistDao->findCount("affstate='1' and handoverId=".$arr['id']." and sort in(".$sendPremise.")") == count(explode("," ,$sendPremise))) {
							//����ǰ��ȫ����ɣ������ʼ�֪ͨ��һ�����������
							$emailDao_1 = new model_common_mail ();
							$mailContent = '<span style="color:blue">'.$arr['deptName'].'</span>���ŵ�<span style="color:blue">'.$arr['userName'].'</span>��ְ��������<span style="color:blue">'.$v['items'].'</span>��ȷ�ϣ��뵽����OA·������ȷ�ϣ�</br>������--->���˰칫--->��������--->������--->��ְ����ȷ��';
							$emailDao_1->mailClear("��ְ������Ϣȷ��!",$v['recipientId'], $mailContent);
						}
					}
				}

				//�ж��Ƿ������һ��ȷ����Ϣ,������ְ���Ľ���ȷ��״̬
				$handOverListDao=new model_hr_leave_handoverlist();
				if($handOverListDao->findCount("affstate<>'1' and handoverId=".$arr['id']." and isKey='on'")=="0"){
					$leaveDao=new model_hr_leave_leave();
					$leaveDao->updateById(array("id"=>$arr['leaveId'],"handoverCstatus"=>"YQR"));
					//�����ʼ�֪ͨԱ������ȷ��
					$emailDao = new model_common_mail ();
					$leaveObj=$leaveDao->get_d($arr['leaveId']);
					$emailDao->mailClear("��ְ������Ϣȷ��!",$leaveObj['userAccount'], "������ְ�����嵥�Ѿ�ȷ����ϣ�������½OA�鿴��ȷ�ϡ�");
				}
			}
			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ����ȷ����ְ�����嵥ȷ�ϲ���
	 */
	function startAffirmPro_d($arr){
		try {
			$this->start_d();
			$updateSql = "update oa_leave_handover set staffAffCon = '".$arr['staffAffCon']."',staffAffDT = '".$arr['staffAffDT']."',staffConRemark = '".$arr['staffConRemark']."' where id='".$arr['handoverId']."'";
			$this->query($updateSql);
			//ͬʱ������ְ���뵥��ȷ��״̬
			$leaveDao=new model_hr_leave_leave();
			$leaveDao->updateById(array("id"=>$arr['leaveId'],"userSelfCstatus"=>"YQR"));
			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ���ݽ��ӵ�ID �鿴�Ƿ�ȷ�����
	 */
	function getLeaveInfo_d($handoverId){
		$sql = "select count(*) as num from oa_hr_handover_list where handoverId = '".$handoverId."' and affstate=0 and isKey='0'";
		$arr = $this->_db->getArray($sql);
		if($arr[0]['num'] == '0'){
			return "0";
		}else{
			return "1";
		}
	}

	/**
	 * ���ݽ��ӵ�ID �鿴�Ƿ�ȷ�����
	 */
	function isDone_d($handoverId){
		//�ж��Ƿ������һ��ȷ����Ϣ,������ְ���Ľ���ȷ��״̬
		$handOverListDao=new model_hr_leave_handoverlist();
		if($handOverListDao->findCount("affstate<>'1' and handoverId=".$handoverId." and isKey='on'")=="0"){
	     	return  1;
		}else{
	     	return  0;
		}
	}

	/**
	 * ������ְ������ϸ
	 */
	function fromworkInfo_d($fromworkInfo){
		$num = count($fromworkInfo);
		$listA = "<table class='tableA' id='formtable'><tr><td width='30'>����<input type='hidden' id='num' value='{$num}'></td><td>�������豸��������</td><td>������</td><td>��ʧ����</td><td >���</td><td width='75'>�Ƿ����ȷ��</td><td width='30'>����</td><td width='75'>�Ƿ����ʼ�</td><td width='50'>����ǰ��</td></tr>";
		foreach($fromworkInfo as $k => $v){
			$i = $k + 1;
			$items = $v['items'];
			$recipientName = $v['recipientName'];
			$recipientId = $v['recipientId'];
			$list .= "<tr><td> <img align='absmiddle' src='images/removeline.png' onclick='delItem(this);' title='ɾ����' /></td><td>
             		     <input type ='hidden' class='rimless_textB' readonly name='handover[formwork][$k][items]' value='{$items}'/>$items
             		  </td>
             		  <td><input type ='txt' id='recipientName$i' class='rimless_textB' readonly name='handover[formwork][$k][recipientName]' value='{$recipientName}' title='˫��ѡ����Ա'>
             		  	  <input type ='hidden' id='recipientId$i' class='txt' readonly name='handover[formwork][$k][recipientId]' value='{$recipientId}'></td>
             		  <td></td><td ></td><td ><input type ='checkbox' id='isKey$i'  readonly name='handover[formwork][$k][isKey]' checked></td>
             		  <td><input type ='txt' class='rimless_textB' name='handover[formwork][$k][sort]' style='width:20px'/></td>
             		  <td><input type ='checkbox' name='handover[formwork][$k][mailAffirm]' checked /></td>
             		  <td><input type ='txt' class='rimless_textB' name='handover[formwork][$k][sendPremise]' /></td>
             		 </tr>";
		}
		$list.="<tr  id='appendHtml'></tr></table>";
		return $listA.$list;
	}

	/**
	 * �༭��ְ������ϸ
	 */
	function editHandoverList_d($fromworkInfo){
		$num = count($fromworkInfo);
		$listA = "<tr><td>����<input type='hidden' id='num' value='{$num}'></td><td>�������豸��������</td><td>������</td><td>��ʧ����</td><td >�ۿ���</td><td>�Ƿ����ȷ��</td></tr>";
		foreach($fromworkInfo as $k => $v){
			$i = $k + 1;
			$items = $v['items'];
			$recipientName = $v['recipientName'];
			$recipientId = $v['recipientId'];
			if( $v['affstate']==1){
				if($v['isKey'] == "on"){
					$isKey = "<span style='color:red'>��</span>";
				}else{
					$isKey = "<span style='color:blue'>��</span>";
				}
				$list .= "<tr><td></td><td>
             		     $items
             		  </td>
             		  <td>{$recipientName}</td>
             		  <td></td>
             		  <td ></td>
             		  <td >$isKey</td></tr>";

			}else{
				$list .= "<tr><td> <img align='absmiddle' src='images/removeline.png' onclick='delItem(this);' title='ɾ����' /></td><td>
             		     <input type ='hidden' class='rimless_textB' readonly name='handover[formwork][$k][items]' value='{$items}'/>$items
             		  </td>
             		  <td><input type ='txt' id='recipientName$i' class='rimless_textB' readonly name='handover[formwork][$k][recipientName]' value='{$recipientName}' title='˫��ѡ����Ա'>
             		  	  <input type ='hidden' id='recipientId$i' class='txt' readonly name='handover[formwork][$k][recipientId]' value='{$recipientId}'></td>
             		  <td></td><td ></td><td ><input type ='checkbox' id='isKey$i'  readonly name='handover[formwork][$k][isKey]' checked></td></tr>";
			}
		}
		$list.="<tr  id='appendHtml'></tr>";
		return $listA.$list;
	}

	//�鿴
	function fromworkInfo_view($fromworkInfo){
		$num = count($fromworkInfo);
		$listA = "<table class='tableA'><tr bgcolor='#ECECFF'><td  width='25'>���<input type='hidden' id='num' value='{$num}'></td><td>�������豸��������</td><td width='100'>������</td><td>��ʧ����</td><td  width='80'>���</td><td>��ע</td><td width='100'>�Ƿ������ǰȷ��</td><td width='50'>ȷ��״̬<td width='25'>����</td><td width='75'>�Ƿ����ʼ�</td><td width='50'>����ǰ��</td></tr>";
		foreach($fromworkInfo as $k => $v){
			$i = $k + 1;
			$items = $v['items'];
			$recipientName = $v['recipientName'];
			$handoverCondition = $v['handoverCondition'];
			$lose = $v['lose'];
			$remark = $v['remark'];
			$sort = $v['sort'];
			$sendPremise = $v['sendPremise'];
			if($v['affstate'] == "1"){
				$affstate = "<span style='color:blue'>��</span>";
			}else{
				$affstate = "<span style='color:red'>��</span>";
			}
			if($v['isKey'] == "on"){
				$isKey = "<span style='color:red'>��</span>";
			}else{
				$isKey = "<span style='color:blue'>��</span>";
			}
			if($v['deduct']==0){
				$deduct = '';
			}else{
				$deduct = $v['deduct'];
			}
			if ($v['mailAffirm'] == 'on') {
				$mailAffirm = "<span style='color:red'>��</span>";
			} else{
				$mailAffirm = "<span style='color:blue'>��</span>";
			}
			$list .= "<tr><td>$i</td><td>$items</td><td>$recipientName</td><td>$lose</td><td>$deduct</td><td  align='left'>$remark</td><td>$isKey</td><td>$affstate</td><td>$sort</td><td>$mailAffirm</td><td>$sendPremise</td></tr>";
		}
		$listB =  "<tr><td colspan='11' style='text-align:left'><span style='color:blue;'>�ر�˵��</span>���Ͷ���ϵ����ְ������ֹ������������ְ���������󣬲��ٴ����κα������ݣ���ְ�����嵥������ȷ�Ϻ��Ͷ���ϵ�����ڼ���Ͷ������Ȩ��������ѽ��壬��ְ��Ա���������Ͷ������ɾ��׾��뱾��˾�޹أ����ҹ�˾ǩ���ı���Э����Ȼ��Ч������Υ����׷��������Ρ�</td></tr></table>";
		return $listA.$list.$listB;
	}

	/*
	 * ��ӡҳ��
	 * û�����򡢷���ǰ�ᡢ�Ƿ���
	 */
	function fromworkInfo_print($fromworkInfo){
		$num = count($fromworkInfo);
		$listA = "<table class='tableA'><tr bgcolor='#ECECFF'><td  width='25'>���<input type='hidden' id='num' value='{$num}'></td><td>�������豸��������</td><td width='100'>������</td><td>��ʧ����</td><td  width='80'>���</td><td>��ע</td><td width='50'>�Ƿ������ǰȷ��</td><td width='50'>ȷ��״̬</tr>";
		foreach($fromworkInfo as $k => $v){
			$i = $k + 1;
			$items = $v['items'];
			$recipientName = $v['recipientName'];
			$handoverCondition = $v['handoverCondition'];
			$lose = $v['lose'];
			$remark = $v['remark'];
			$sort = $v['sort'];
			$sendPremise = $v['sendPremise'];
			if($v['affstate'] == "1"){
				$affstate = "<span style='color:blue'>��</span>";
			}else{
				$affstate = "<span style='color:red'>��</span>";
			}
			if($v['isKey'] == "on"){
				$isKey = "<span style='color:red'>��</span>";
			}else{
				$isKey = "<span style='color:blue'>��</span>";
			}
			if($v['deduct']==0){
				$deduct = '';
			}else{
				$deduct = $v['deduct'];
			}
			if ($v['mailAffirm'] == 'on') {
				$mailAffirm = "<span style='color:red'>��</span>";
			} else{
				$mailAffirm = "<span style='color:blue'>��</span>";
			}
			$list .= "<tr><td>$i</td><td>$items</td><td>$recipientName</td><td>$lose</td><td>$deduct</td><td  align='left'>$remark</td><td>$isKey</td><td>$affstate</td></tr>";
		}
		$listB =  "<tr><td colspan='11' style='text-align:left'><span style='color:blue;'>�ر�˵��</span>���Ͷ���ϵ����ְ������ֹ������������ְ���������󣬲��ٴ����κα������ݣ���ְ�����嵥������ȷ�Ϻ��Ͷ���ϵ�����ڼ���Ͷ������Ȩ��������ѽ��壬��ְ��Ա���������Ͷ������ɾ��׾��뱾��˾�޹أ����ҹ�˾ǩ���ı���Э����Ȼ��Ч������Υ����׷��������Ρ�</td></tr></table>";
		return $listA.$list.$listB;
	}

	/**
	 * �����û��˺Ż�ȡ��Ϣ
	 */
	function getnfo_d($leaveId){
		$this->searchArr = array ('leaveId' => $leaveId );
		$personnelRow= $this->listBySqlId ( "select_default" );
		return $personnelRow['0'];
	}

}
?>