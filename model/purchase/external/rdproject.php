<?php
header("Content-type: text/html; charset=gb2312");

//����ӿ�
include_once WEB_TOR . 'model/purchase/external/planBasic.php';

/**
 * @description: �з��ɹ����model
 * @date 2010-12-17 ����04:28:25
 * @author oyzx
 * @version V1.0
 */
class model_purchase_external_rdproject extends planBasic {

	private $externalDao; //�ⲿ����dao�ӿ�
	private $externalEquDao; //�ⲿ�����豸dao�ӿ�

	function __construct() {
		$this->externalDao = new model_rdproject_project_rdproject();
		$this->externalEquDao = new model_rdproject_project_rdproject();

		//���ó�ʼ�����������
		$this->objass = new model_common_objass();
	}

	/***************************************************************************************************
	 * ------------------------------����Ϊҳ��ģ����ʾ���÷���------------------------------------------*
	 **************************************************************************************************/

	/**
	 * @desription ��Ӳɹ��ƻ�-��ʾ�б�
	 * @param tags
	 * @date 2010-12-17 ����05:17:09
	 */
	function showAddList($equs,$mainRows) {
		$str.=<<<EOT
					<input type="hidden" name="basic[objAssId]" value="$mainRows[id]" />
					<input type="hidden" name="basic[objAssName]" value="$mainRows[projectName]" />
					<input type="hidden" name="basic[objAssType]" value="rdproject" />
					<input type="hidden" name="basic[objAssCode]" value="$mainRows[projectCode]" />
					<input type="hidden" name="basic[equObjAssType]" value="rdproject_equ" />
					</td>
EOT;
		return $str;
	}

	/***************************************************************************************************
	 * ------------------------------���½ӿڷ���,�ɹ�����ģ�����---------------------------------------*
	 **************************************************************************************************/

	/**
	 * @desription ͨ���з���Ŀ��Id��ȡ��Ʒ����
	 * @param tags
	 * @date 2010-12-17 ����05:05:57
	 */
	function getItemsByParentId ($parentId) {
		return true;
	}

	/**
	 * ���ݲɹ����͵ĵ���ID����ȡ����Ϣ
	 *
	 * @param $id
	 * @return return_type
	 */
	function getInfoList ($id) {
		$mainRows=$this->externalDao->get_d($id);
		return $mainRows;
	}

	/**
	 * ���ݲ�ͬ�����Ͳɹ��ƻ�������ҵ����
	 *
	 * @param $paramArr
	 */
	function dealInfoAtPlan ($paramArr){
		//TODO:
		return true;
	}

	/**�������´�ɹ�����
	*author can
	*2011-3-22
	*/
	function updateAmountIssued($id,$issuedNum,$lastIssueNum){
		return true;
	}

	/**
	 * �´�ɹ��ƻ���ҳ��ԭ��ת
	 *
	 * @param $id	�ɹ����뱣��ID
	 */
	function toShowPage ($id,$type=null) {
			if($type){
				if($id){
					msg('����ɹ�');
				}else{
					msg('������Ϣ��������û�����ϻ�����Ϊ0���´�ʧ��');
				}
			}else{		//��ͳһ�ӿ�����ɹ��ƻ������תҳ��
				if($id){
					msgGo('����ɹ�','index1.php?model=purchase_external_external&action=toRdprojectAdd');
				}else{
					msgGo('������Ϣ��������û�����ϻ�����Ϊ0���´�ʧ��','index1.php?model=purchase_external_external&action=toRdprojectAdd');
				}
			}
	}

}
?>
