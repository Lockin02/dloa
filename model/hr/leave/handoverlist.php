<?php
/**
 * @author Administrator
 * @Date 2012-08-09 15:38:12
 * @version 1.0
 * @description:��ְ�����嵥��ϸ Model��
 */
 class model_hr_leave_handoverlist  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_handover_list";
		$this->sql_map = "hr/leave/handoverlistSql.php";
		parent::__construct ();
	}

	/**
	 * ��ְ�����嵥��������
	 */
	function restart_d($arr){
		try {
			$this->start_d();
			if(is_array($arr['formwork'])){
				foreach($arr['formwork'] as $k => $v){
					if($v['restart'] == "on"){
						$updateSql = "update oa_hr_handover_list set affstate = '0' where id='".$v['id']."'";
						$this->query($updateSql);

						//�����ʼ�֪ͨ����������ȷ��
						$emailDao = new model_common_mail ();
						$mailContent = '<span style="color:blue">'.$arr['deptName'].'</span>���ŵ�<span style="color:blue">'.$arr['userName'].'</span>��ְ��������<span style="color:blue">'.$v['items'].'</span>����ȷ�ϣ��뵽����OA·������ȷ�ϣ�</br>������--->���˰칫--->��������--->������--->��ְ����ȷ��';
						$emailDao->mailClear("��ְ������������",$v['recipientId'], $mailContent);
					}
				}
			}
			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}
	/*
	 * ��ְ�����嵥�޸�
	 */
	function alterHand_d( $object ) {
		try {
			$this->start_d();
			$handoverId = $object['id'];
			$obj = ($object['formwork']);
			$handoverMemberDao = new model_hr_leave_handoverMember();

			foreach ($obj as $k => $v) {

				$recipientName=explode(",",$v['recipientName']);
				$recipientId=explode(",",$v['recipientId']);

				if ($v['isDelTag'] == '1') { //������ݿⲻ�������ݾ�ɾ��
					$this->delete( array('id'=>$v['id']) );
					continue;

				}else if ($v['id'] == '') { //������ݲ��������ݿ������
					$v['handoverId'] = $handoverId;
					$listId = $this->add_d( $v ,true );

					//��ӳɹ����ʼ�֪ͨ������
					if ($listId) {
						$this->alterHandMail_d($object ,$v);
					}
					foreach($recipientId as $mKey =>$mVal){
						if($mVal!=""){
							$newArr=array('handoverId' => $handoverId,
										  'parentId' => $listId,
										  'recipientId' => $mVal,
										  'recipientName' => $recipientName[$mKey],
										  'affstate' => 0
									);
							$handoverMemberDao->add_d($newArr,true);
						}
					}
					continue;

				}else { //������ݴ������ݿ�͸���
					if (!$v['isKey']) {
						$v['isKey'] = '';
					}
					if (!$v['mailAffirm']) {
						$v['mailAffirm'] = '';
					}
					$objArr = $this->get_d($v['id']);
					//�޸Ľ����˷��ʼ�֪ͨ
					if ($objArr['recipientId'] != $v['recipientId']) {
						$this->alterHandMail_d($object ,$v);
					}
					$this->updateById ( $v ,true );
					$handoverMemberDao->delete( array('parentId'=>$v['id']) );
					foreach($recipientId as $mKey =>$mVal){
						if($mVal!=""){
							$newArr=array('handoverId' => $handoverId,
										  'parentId' => $v['id'],
										  'recipientId' => $mVal,
										  'recipientName' => $recipientName[$mKey],
										  'affstate' => 0
									);
							$handoverMemberDao->add_d($newArr,true);
						}
					}
				}
			}
			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/*
	 * ��ְ�����嵥�޸Ľ����˷��ʼ�֪ͨ
	 */
	 function alterHandMail_d($obj ,$listArr) {
	 	$emailDao = new model_common_mail ();
		$mailContent = '���ã�<br><span style="color:blue">'.$obj['deptName'].'</span>���ŵ�<span style="color:blue">'.$obj['userName'].'</span>��ְ��������<span style="color:blue">'.$listArr['items'].'</span>�ȴ�ȷ�ϣ��뵽����OA·������ȷ��<br>������--->���˰칫--->��������--->������--->��ְ����ȷ��';
		$emailDao->mailClear("��ְ���ӵ�ȷ��" ,$listArr['recipientId'] ,$mailContent);
	 }

	 /*
	 * ������ְ��ID�ж�ȷ�ϵ������Ƿ��Ѿ�ȷ����ϣ�����������ǰȷ�ϵģ�
	 */
	 function isAffirmAll_d($leaveId){
	 	$handoverDao=new model_hr_leave_handover();
	 	$handoverObj = $handoverDao->find(array ('leaveId' => $leaveId));
	 	$num = $this->findCount(array ('handoverId' => $handoverObj['id'] ,'affstate' => '0'));
	 	return $num;
	 }
 }
?>