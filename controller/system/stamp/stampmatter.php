<?php
/**
 * @author show
 * @Date 2013��12��5�� 11:48:32
 * @version 1.0
 * @description:����ʹ���������ÿ��Ʋ�
 */
class controller_system_stamp_stampmatter extends controller_base_action {

	function __construct() {
		$this->objName = "stampmatter";
		$this->objPath = "system_stamp";
		parent :: __construct();
	}

	/**
	 * ��ת������ʹ�����������б�
	 */
	function c_page() {
		$this->view('list');
	}

    /**
     * ��д��ȡ��ҳ����ת��Json����
     */
    function c_pageJson() {
        $service = $this->service;

        $service->getParam ( $_REQUEST );
        //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

        //$service->asc = false;
        $rows = $service->page_d ();

        // ��ȡ��Ӧ�ĸ����������
        $datadictDao = new model_system_datadict_datadict();
        $typeInfo = $datadictDao->getDataDictList_d('GZLB');//�������

        foreach($rows as $k => $row){
            $rows[$k]['stamp_cName'] = $typeInfo[$row['stamp_cId']];
        }

        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows ( $rows );
        $arr = array ();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode ( $arr );
    }

	/**
	 * ��ת����������ʹ����������ҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭����ʹ����������ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
        $stamp_cId_Option = $this->showDatadicts ( array ('categoryId' => 'GZLB'),$obj['stamp_cId'],true,null,true);
        $this->show->assign( 'stamp_cId_option', $stamp_cId_Option );

		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * ��ת���鿴����ʹ����������ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('needAudit',$this->service->getNeedAudit($obj['needAudit']));
		$this->assign('status',$this->service->getIsStatus($obj['status']));
		$this->view('view');
	}
	
	/**
	 * ��񷽷�
	 */
	function c_jsonSelect(){
		$service = $this->service;
		$rows = null;
	
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->pageBySqlId();

        $datadictDao = new model_system_datadict_datadict();
        $typeInfo = $datadictDao->getDataDictList_d('GZLB');//�������
        foreach($rows as $k => $row){
            $rows[$k]['stamp_cName'] = $typeInfo[$row['stamp_cId']];
        }

		$rows = $this->sconfig->md5Rows ( $rows );
	
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	
}