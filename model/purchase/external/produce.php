<?php
header("Content-type: text/html; charset=gb2312");

//����ӿ�
include_once WEB_TOR . 'model/purchase/external/planBasic.php';

/**
 * @description: �����ɹ�model
 * @date 2010-12-17 ����04:28:25
 * @author oyzx
 * @version V1.0
 */
class model_purchase_external_produce extends planBasic {

	private $externalDao; //�ⲿ����dao�ӿ�
	private $externalEquDao; //�ⲿ�����豸dao�ӿ�

	function __construct() {

		//���ó�ʼ�����������
		$this->objass = new model_common_objass();
	}

	/***************************************************************************************************
	 * ------------------------------����Ϊҳ��ģ����ʾ���÷���------------------------------------------*
	 **************************************************************************************************/

	/**
	 * @desription ��Ӳɹ��ƻ�-��ʾ�б�
	 * @param $equs
	 * @param $mainRows
	 * @date 2010-12-17 ����05:17:09
	 */
	function showAddList($equs,$mainRows) {
		//TODO:׷�����󱣴浽�ɹ��ƻ����������Ϣ��������ģ����ɺ���������
		$str.=<<<EOT
					<input type="hidden" name="basic[objAssId]" value="" />
					<input type="hidden" name="basic[objAssName]" value="�����ɹ�" />
					<input type="hidden" name="basic[objAssType]" value="produce" />
					<input type="hidden" name="basic[objAssCode]" value="" />
					<input type="hidden" name="basic[equObjAssType]" value="produce"/>
					</td>
EOT;
		return $str;
	}

	/***************************************************************************************************
	 * ------------------------------���½ӿڷ���,�ɹ�����ģ�����---------------------------------------*
	 **************************************************************************************************/

	/**
	 * @desription ͨ���������뵥Id��ȡ��Ʒ����
	 * @param $parentId
	 * @date 2010-12-17 ����05:05:57
	 */
	function getItemsByParentId ($parentId) {
		//TODO:ͨ���������뵥Id��ȡ��Ʒ����
		return true;
	}

	/**
	 * ���ݲɹ����͵�ID����ȡ����Ϣ
	 *
	 * @param $id
	 * @return
	 */
	function getInfoList ($id) {
		//TODO:Ŀǰû����������Ϣ�����Ժ���
		return true;
	}

	/**
	 * ���ݲ�ͬ�����Ͳɹ��ƻ�������ҵ����
	 *
	 * @param $paramArr
	 */
	function dealInfoAtPlan ($paramArr){
		//TODO:���������Ĳɹ��ƻ����ʱ��ҵ����������ʱ�����ڴ����
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
					msgGo('����ɹ�');
				}else{
					msgGo('������Ϣ��������û�����ϻ�����Ϊ0���´�ʧ��');
				}
			}
	}
}
?>
