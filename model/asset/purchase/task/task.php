<?php

/**
 *
 * �ʲ��ɹ�����model
 * @author fengxw
 *
 */
class model_asset_purchase_task_task extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_purchase_task";
		$this->sql_map = "asset/purchase/task/taskSql.php";
		parent::__construct ();
	}

	/**
	 * �½������ʲ��ɹ�������ϸ��
	 */

	function add_d($object){
		try{
			$this->start_d();
			if(!is_array($object['taskItem'])){
				msg ( '����д���ʲ��ɹ�������ϸ������Ϣ��' );
				throw new Exception('�ʲ��ɹ�������Ϣ������������ʧ�ܣ�');
			}
			$codeDao=new model_common_codeRule();//����ҵ����
			$object['formCode'] = $codeDao->purchTaskCode("oa_purch_task_basic",$object['sendId']);
			$id=parent::add_d($object,true);
			//������ϸ��
			$taskItemDao=new model_asset_purchase_task_taskItem();
			$applyEquDao=new model_asset_purchase_apply_applyItem();
			foreach($object['taskItem'] as $val){
				$val['parentId']=$id;
				$val['taskCode']=$object['formCode'];
				$taskItemDao->add_d($val);
				//���²ɹ��ƻ��豸�����´�����
				$applyEquDao->updateAmountIssued($val['applyEquId'],$val['taskAmount']);
			}

			//�����ʼ�֪ͨ�ɹ�������
			$emailArr=array();
			$emailArr['issend'] = 'y';
			$emailArr['TO_ID']=$object['purcherId'];
			$emailArr['TO_NAME']=$object['purcherName'];
			if(is_array($object ['taskItem'])){
				$j=0;
				$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>���</b></td><td><b>��������</b></td><td><b>����ͺ�</b></td><td><b>��λ</b></td><td><b>��������</b></td><td><b>�ɹ�������</b></td><td><b>ϣ�����ʱ��</b></td><td><b>��ע</b></td></tr>";
				foreach($object ['taskItem'] as $key => $equ ){
					$j++;
					$productName=$equ['productName'];
					$pattem=$equ ['pattem'];
					$unitName=$equ ['unitName'];
					$taskAmount=$equ ['taskAmount'];
					$applyCode=$equ ['applyCode'];
					$dateHope=$equ['dateHope'];
					$remark=$equ['remark']." ";
					$addmsg .=<<<EOT
					<tr align="center" >
							<td>$j</td>
							<td>$productName</td>
							<td>$pattem</td>
							<td>$unitName</td>
							<td>$taskAmount</td>
							<td>$applyCode</td>
							<td>$dateHope</td>
							<td>$remark </td>
						</tr>
EOT;
					}
					$addmsg.="</table>";
			}
			$emailDao = new model_common_mail();
			$emailInfo = $emailDao->purchTaskEmail($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'oa_purch_task_basic',',�ɹ����񵥾ݺ�Ϊ��<font color=red><b>'.$object["formCode"].'</b></font>','',$emailArr['TO_ID'],$addmsg,1);

			$this->commit_d();
			return $id;

		}catch(Exception $e){
			$this->rollBack();
			return $id;
		}
	}

	/**
	 * �޸ı���
	 * @see model_base::edit_d()
	 */
	function edit_d($object) {
		try {
			$this->start_d ();
			if (is_array ( $object ['taskItem'] )) {
				$id = parent::edit_d ( $object, true );
				$taskItemDao=new model_asset_purchase_task_taskItem();
				$mainArr=array("parentId"=>$object ['id']);
				$itemsArr=util_arrayUtil::setArrayFn($mainArr,$object ['taskItem']);
				$itemsObj = $taskItemDao->saveDelBatch ( $itemsArr );
				$this->commit_d ();
				return $object ['id'];
			} else {
				throw new Exception ( "������Ϣ����������ȷ�ϣ�" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}


}


?>
