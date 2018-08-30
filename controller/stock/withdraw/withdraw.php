<?php

/**
 * @author Administrator
 * @Date 2012��11��8�� 11:04:46
 * @version 1.0
 * @description:�ջ�֪ͨ�����Ʋ�
 */
class controller_stock_withdraw_withdraw extends controller_base_action
{

    function __construct() {
        $this->objName = "withdraw";
        $this->objPath = "stock_withdraw";
        parent::__construct();
    }

    /**
     * ��ת���ջ�֪ͨ���б�
     */
    function c_page() {
        $this->view('list');
    }

    /**
     * ��ת���������ݲ鿴ҳ��-�ջ�֪ͨ���б�
     */
    function c_pageForExchange() {
        $this->assign('docId', $_GET['id']);
        $this->view('listforexchange');
    }

    /**
     * ��ת������ҳ��
     */
    function c_toAdd() {
        $contId = $_GET['id'];
        $docType = $_GET['docType'];
        $service = $this->service;
        //�������ͻ�ȡ��������
        $relatedStrategy = $service->relatedStrategyArr[$docType];
        $cont = $service->getDocInfo($contId, new $relatedStrategy());
        $equIds = isset($_GET['equIds']) ? $_GET['equIds'] : null;
        $equIdArr = explode(',', $equIds);
        $detail = array();
        //������ϸid
        if (!empty($equIds)) {
            foreach ($equIdArr as $key => $val) {
                foreach ($cont['equ'] as $k => $v) {
                    if ($v['id'] == $val) {
                        $detail[] = $v;
                    }
                }
            }
        }
        //���������´��ջ�֪ͨ��ʱ��ʾ�˻���ϸ
        $equArrStr = '';
        if($docType == "oa_contract_exchangeapply"){
            $equIds = '';
        	$exchangebackequDao = new model_projectmanagent_exchange_exchangebackequ();
        	$avilableEquArr = $exchangebackequDao->getEquIdByExchangeId_d($contId);
            if($avilableEquArr){
                foreach ($avilableEquArr as $k => $v){
                    if($v['remainNum']>0){
                        $equIds .= ($equIds == '')? $v['id'] : ','.$v['id'];
                        $equArrStr .= ($equArrStr == '')? $v['id'].":".$v['remainNum'] : ",".$v['id'].":".$v['remainNum'];
                    }
                }
            }
        }
        $paramArr['docId'] = $contId;
        foreach ($cont as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign('equIdArr', $equIds);
        $this->assign('equArrStr', $equArrStr);
        $this->showDatadicts(array('type' => 'FHXZ'));
        $this->assign('docId', $cont['id']);
        $this->assign('pageAction', 'add');
        $this->view('add', true);
    }

    /**
     * �����������
     */
    function c_add($isAddInfo = false) {
        $this->checkSubmit();
        if ($this->service->add_d($_POST [$this->objName], $isAddInfo)) {
            msgRf($_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���');
        }
    }

    /**
     * ��ת���༭�ջ�֪ͨ��ҳ��
     */
    function c_toEdit() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign("itemsList", $this->service->showItemAtEdit($obj['items']));
        $this->assign("itemscount", count($obj ['items']));
        $this->view('edit');
    }

    /**
     * ��ת���鿴�ջ�֪ͨ��ҳ��
     */
    function c_toView() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign('docType', '����');
        $this->view('view');
    }

    /**
     * �����ƻ��б�ӱ�
     */
    function c_equJson() {
        $outplanEqu = new model_stock_withdraw_equ();
        $outplanEqu->searchArr['mainId'] = $_POST['mainId'];
        $outplanEqu->searchArr['isDel'] = 0;
        $rows = $outplanEqu->list_d();
        $arr = array();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $outplanEqu->count ? $outplanEqu->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $outplanEqu->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ��ʼ������
     */
    function c_init() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        if (isset ($_GET ['perm']) && $_GET ['perm'] == 'view') {
            $this->view('view');
        } else {
            $this->view('edit');
        }
    }

    /**
     * ��ȡ�������ݷ���json
     */
    function c_listJson() {
        $service = $this->service;
        $service->getParam($_REQUEST);
        print_R($_REQUEST);
        $rows = $service->list_d();
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        echo util_jsonUtil::encode($rows);
    }

    /**
     * ajax �����⳥״̬
     */
    function c_ajaxState() {
        try {
            $this->service->updateDocStatus_d($_POST ['id'], $_POST['state']);
            echo 1;
        } catch (Exception $e) {
            echo 0;
        }
    }
}