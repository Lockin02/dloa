<?php
/**
 *
 * �ɹ�������ϸ����Ʋ���
 * @author fengxw
 *
 */
class controller_asset_purchase_apply_applyItem extends controller_base_action {

	function __construct() {
		$this->objName = "applyItem";
		$this->objPath = "asset_purchase_apply";
		parent::__construct ();
	}

	/*
	 * ��ת���ɹ�������ϸ��
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }

	/**
	 * �������ɹ�������Ϣ
	 */
	function c_delPageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		//��ȡ�ɹ�������ϸ��Ϣ
		$rows=$service->getItemByApplyId($_POST['applyId'],'1','0');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ���˼�ɾ���ɹ�������Ϣ
	 */
	function c_IsDelPageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		//��ȡ�ɹ�������ϸ��Ϣ
		$rows=$service->getDelItemByApplyId($_POST['applyId'],'0');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * �����´�������Ϊnull��0�����ݷ���json
	 */
	function c_issuedListJson() {
		$service = $this->service;
		$service->getParam ( $_POST );
		$rows = $service->findIssuedAmount ($_POST['applyId']);
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * ���˼�ɾ����־�����ݷ���json
	 */
	function c_DelListJson() {
		$service = $this->service;
		$rows = $service->findIsDel ($_GET['applyId']);
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_preListJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		if( is_array($rows)&&count($rows) ){//ҳ����ʾ���������Ƹ�Ϊ������������ƣ�zengzx��
			foreach( $rows as $key=>$val ){
				$rows[$key]['productName']=$val['inputProductName'];
			}
		}
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_purchListJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
	
	/**
	 * �༭ʱ��ȡ�������ݷ���json
	 */
	function c_editListJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
}