<?php
    /*
	 *�ʲ�����������ϸ����Ʋ�
	 *@linzx
	 */
class controller_asset_disposal_sellitem extends controller_base_action {
		function __construct() {
		$this->objName = "sellitem";
		$this->objPath = "asset_disposal";
		parent::__construct ();
	}

    /*
	 * ��ת���ʲ�����������ϸ��
	 *
	 */
    function c_page() {
      $this->view('list');
    }

    /*
	 *ͨ��select_card��ȡ�ʲ���Ƭ����ֶ�
	 *��ҪΪ����ʾ	����״̬
	 */
	function c_sellCardJson(){
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ


		//$service->asc = false;
		$rows = $service->pageBySqlId ("select_assetcard");
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

}
?>
