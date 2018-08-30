<?php

/**
 * @author Administrator
 * @Date 2012年11月8日 11:04:46
 * @version 1.0
 * @description:收货通知单控制层
 */
class controller_stock_withdraw_withdraw extends controller_base_action
{

    function __construct() {
        $this->objName = "withdraw";
        $this->objPath = "stock_withdraw";
        parent::__construct();
    }

    /**
     * 跳转到收货通知单列表
     */
    function c_page() {
        $this->view('list');
    }

    /**
     * 跳转到换货单据查看页面-收货通知单列表
     */
    function c_pageForExchange() {
        $this->assign('docId', $_GET['id']);
        $this->view('listforexchange');
    }

    /**
     * 跳转到新增页面
     */
    function c_toAdd() {
        $contId = $_GET['id'];
        $docType = $_GET['docType'];
        $service = $this->service;
        //根据类型获取策略类名
        $relatedStrategy = $service->relatedStrategyArr[$docType];
        $cont = $service->getDocInfo($contId, new $relatedStrategy());
        $equIds = isset($_GET['equIds']) ? $_GET['equIds'] : null;
        $equIdArr = explode(',', $equIds);
        $detail = array();
        //传入明细id
        if (!empty($equIds)) {
            foreach ($equIdArr as $key => $val) {
                foreach ($cont['equ'] as $k => $v) {
                    if ($v['id'] == $val) {
                        $detail[] = $v;
                    }
                }
            }
        }
        //换货需求下达收货通知单时显示退货明细
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
     * 新增对象操作
     */
    function c_add($isAddInfo = false) {
        $this->checkSubmit();
        if ($this->service->add_d($_POST [$this->objName], $isAddInfo)) {
            msgRf($_POST ["msg"] ? $_POST ["msg"] : '添加成功！');
        }
    }

    /**
     * 跳转到编辑收货通知单页面
     */
    function c_toEdit() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign("itemsList", $this->service->showItemAtEdit($obj['items']));
        $this->assign("itemscount", count($obj ['items']));
        $this->view('edit');
    }

    /**
     * 跳转到查看收货通知单页面
     */
    function c_toView() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign('docType', '换货');
        $this->view('view');
    }

    /**
     * 发货计划列表从表
     */
    function c_equJson() {
        $outplanEqu = new model_stock_withdraw_equ();
        $outplanEqu->searchArr['mainId'] = $_POST['mainId'];
        $outplanEqu->searchArr['isDel'] = 0;
        $rows = $outplanEqu->list_d();
        $arr = array();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $outplanEqu->count ? $outplanEqu->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $outplanEqu->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 初始化对象
     */
    function c_init() {
        $this->permCheck(); //安全校验
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
     * 获取所有数据返回json
     */
    function c_listJson() {
        $service = $this->service;
        $service->getParam($_REQUEST);
        print_R($_REQUEST);
        $rows = $service->list_d();
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        echo util_jsonUtil::encode($rows);
    }

    /**
     * ajax 更新赔偿状态
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