<?php

/**
 * Class controller_contract_counting_counting
 */
class controller_contract_counting_counting extends controller_base_action
{

    function __construct()
    {
        $this->objName = "counting";
        $this->objPath = "contract_counting";
        parent:: __construct();
    }

    /**
     * ��ת�����������б�
     */
    function c_page()
    {
        $this->view('list');
    }

    /**
     * ��д��ȡ��ҳ����ת��Json
     */
    function c_pageJson() {
        $service = $this->service;//chkResult
        if(isset($_REQUEST['chkResult'])){
            $chkResult = $_REQUEST['chkResult'];
            unset($_REQUEST['chkResult']);
            switch ($chkResult){
                case 'correct':
                    $_REQUEST['countingAndBuildChkCorrect'] = '��ȷ';
                    break;
                case 'wrong':
                    $_REQUEST['countingAndBuildChk'] = '����';
                    break;
            }
        }

        $service->getParam ( $_REQUEST );
        $service->searchArr['isDel'] = 0;
        //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

        //$service->asc = false;
        $rows = $service->page_d ();

        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows ( $rows );
        $_SESSION['countingSearchArr'] = $service->searchArr;
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
     * ����ҳ��
     */
    function c_toUpdate()
    {
        $this->assign('year', date('Y'));
        $this->view('update');
    }

    /**
     * ���·���
     */
    function c_update()
    {
        if(isset($_POST['resetField']) && $_POST['resetField'] != ''){
            echo $this->service->resetRecord_d($_POST['resetField'],$_POST['contractCode'], $_POST['year'], $_POST['month'], $_POST['projectCode']);
        }else{
            echo $this->service->update_d($_POST['contractCode'], $_POST['year'], $_POST['month'], $_POST['projectCode']);
        }
    }

    /**
     * ��������
     */
    function c_export() {
        set_time_limit(0); // ���ò���ʱ
        $this->service->getParam($_REQUEST);
        if(isset($_SESSION['countingSearchArr']) && is_array($_SESSION['countingSearchArr'])){
            foreach ($_SESSION['countingSearchArr'] as $k => $v){
                if(!isset($this->service->searchArr[$k])){
                    $this->service->searchArr[$k] = $v;
                }
            }
        }
        $rows = $this->service->list_d();

        //��չ���ݴ���
        if ($rows) {
            foreach ($rows as $k => $v) {
                $rows[$k]['projectRate'] = bcmul($v['projectRate'], 100, 7);
                $rows[$k]['productRate'] = bcmul($v['productRate'], 100, 7);
            }
            $colCode = $_GET['colCode'];
            $colName = $_GET['colName'];
            $head = array_combine(explode(',', $colCode), explode(',', $colName));
            model_finance_common_financeExcelUtil::export07ExcelUtil($head, $rows, '��ͬ���ʱ�', array(
                'projectRate', 'productRate'
            ));
        } else {
            echo util_jsonUtil::iconvGB2UTF('û�в�ѯ���������');
        }
    }

    /**
     * ɾ����Ŀ���� (ɾ���Ժ�ͬΪ��λ����������)
     */
    function c_delProject(){
        $id = isset($_POST['id'])? $_POST['id'] : '';

        // ɾ��ͳ�Ʊ�����
        $result = $this->service->update("contractId = {$id}",array("isDel"=>1));

        if($result){
            echo "ok";
        }else{
            echo "fail";
        }
    }

    /**
     * �����Ŀ��������Ϊ��ȷ (����Ժ�ͬΪ��λ����������)
     */
    function c_setProjectIsTrue(){
        $id = isset($_POST['id'])? $_POST['id'] : '';

        // ɾ��ͳ�Ʊ�����
        $result = $this->service->update("contractId = {$id}",array("isTrue"=>1));

        if($result){
            echo "ok";
        }else{
            echo "fail";
        }
    }
}