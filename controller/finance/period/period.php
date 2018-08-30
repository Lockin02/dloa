<?php

/**
 * @author Show
 * @Date 2011��5��21�� ������ 14:47:06
 * @version 1.0
 * @description:�������ڼ����Ʋ�
 */
class controller_finance_period_period extends controller_base_action
{

    function __construct() {
        $this->objName = "period";
        $this->objPath = "finance_period";
        parent::__construct();
    }

    /**
     * ��ȡȨ��
     */
    function c_getLimits() {
        $limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
        echo $this->service->this_limit[$limitName];
    }

    /**
     * ��ת���������ڼ��
     */
    function c_page() {
        $this->display('list');
    }

    /**
     * ���õ�ǰ�����
     */
    function c_toCreatePeriod() {
        $this->assign('year', date('Y'));
        $this->assign('sysMonth', date('m') * 1);

        $branchDao = new model_deptuser_branch_branch();
        $branchDao->sort = "ComCard";
        $branchDao->asc = false;
        $branchOptions = $branchDao->list_d('select_for_editgrid');
        $firstBusinessBelongOpt = $businessBelongOpts = '';$num = 0;

        if(!empty($branchOptions) && is_array($branchOptions)){
            foreach ($branchOptions as $branch){
                if($num == 0){
                    $firstBusinessBelongOpt = $branch['name'];
                    $num += 1;
                }
                $businessBelongOpts .= '<option value="'.$branch['value'].'">'.$branch['name'].'</option>';
            }
        }
        $this->assign('firstBusinessBelongOpt', $firstBusinessBelongOpt);
        $this->assign('businessBelongOpts', $businessBelongOpts);
        $this->display('createperiod');
    }

    /**
     * ���ò�������������
     */
    function c_createPeriod() {
        if ($this->service->createPeriod_d($_POST[$this->objName])) {
            msg('���óɹ�');
        } else {
            msg('����ʧ��');
        }
    }

    /**
     * ����Ƿ��Ѵ��ڱ���Ĳ�������
     */
    function c_isExistPeriod() {
        $year = isset($_REQUEST['year'])? $_REQUEST['year'] : '';
        $businessBelong = isset($_REQUEST['businessBelong'])? $_REQUEST['businessBelong'] : '';
        echo $this->service->isExistPeriod_d($year,$businessBelong) ? 1 : 0;
    }

    /**
     * ����Ƿ��ڲ�������
     */
    function c_isFirst() {
    	$type = isset($_POST['type']) ? $_POST['type'] : 'stock';
        echo $this->service->isFirst_d($_POST['year'], $_POST['month'], $type) ? 1 : 0;
    }

    /**
     * ����Ƿ��ڲ������� ��ʱ��
     */
    function c_isFirstDate() {
    	$type = isset($_POST['type']) ? $_POST['type'] : 'stock';
        echo $this->service->isFirstDate_d($_POST['thisDate'],$type) ? 1 : 0;
    }

    /**
     * ���ʱ���Ƿ���ڵ�ǰ�������� ��ʱ��
     */
    function c_isLaterPeriod() {
    	$type = isset($_POST['type']) ? $_POST['type'] : 'stock';
        echo $this->service->isLaterPeriod_d($_POST['thisDate'],$type) ? 0 : 1;
    }

    /**
     * ���ʹ���
     */
    function c_checkout() {
    	$type = isset($_POST['type']) ? $_POST['type'] : 'stock';
        $businessBelong = isset($_POST['businessBelong']) ? $_POST['businessBelong'] : '';
        echo $this->service->checkout_d($_POST['id'],$type,$businessBelong) ? 1 : 0;
    }

    /**
     * �����ʹ���
     */
    function c_uncheckout() {
    	$type = isset($_POST['type']) ? $_POST['type'] : 'stock';
        $businessBelong = isset($_POST['businessBelong']) ? $_POST['businessBelong'] : '';
        echo $this->service->uncheckout_d($_POST['id'],$type,$businessBelong) ? 1 : 0;
    }

    /**
     *  ���˹���
     */
    function c_close() {
    	$type = isset($_POST['type']) ? $_POST['type'] : 'stock';
        $businessBelong = isset($_POST['businessBelong']) ? $_POST['businessBelong'] : '';
        echo $this->service->close_d($_POST['id'],$type,$businessBelong) ? 1 : 0;
    }

    /**
     *  ������
     */
    function c_unclose() {
    	$type = isset($_POST['type']) ? $_POST['type'] : 'stock';
        $businessBelong = isset($_POST['businessBelong']) ? $_POST['businessBelong'] : '';
        echo $this->service->unclose_d($_POST['id'],$type,$businessBelong) ? 1 : 0;
    }

    /**
     * ����Ƿ��ڹ���״̬��
     */
    function c_isClosed() {
    	$type = isset($_POST['type']) ? $_POST['type'] : 'stock';
        $businessBelong = isset($_POST['businessBelong']) ? $_POST['businessBelong'] : '';
        echo $this->service->isClosed_d($type,$businessBelong) ? 1 : 0;
    }

    /**
     * ��ȡ����ϵͳ�еĲ�������
     */
    function c_getAllPeriod() {
        $this->service->_isSetCompany = 0;
        $_POST['businessBelong'] = $_SESSION['USER_COM'];// �����û�������˾����ȡ��Ӧ������
        $this->service->getParam($_POST);
        echo util_jsonUtil::encode($this->service->list_d('selectSelect'));
    }

    /**
     * ��ȡ���5�ܲ�����
     */
    function c_getNextOneYearPeriod() {
    	$type = isset($_POST['type']) ? $_POST['type'] : 'stock';
        echo util_jsonUtil::encode($this->service->getNextOneYearPeriod_d($type));
    }

    /**
     * ��ȡ��ǰ������
     */
    function c_getNowPeriod() {
    	$type = isset($_POST['type']) ? $_POST['type'] : 'stock';
        echo util_jsonUtil::encode($this->service->rtThisPeriod_d(1,$type));
    }
}