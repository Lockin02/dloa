<?php

/**
 * @author Administrator
 * @Date 2012年3月11日 15:15:40
 * @version 1.0
 * @description:产品分类信息控制层
 */
class controller_yxlicense_license_category extends controller_base_action
{

    function __construct() {
        $this->objName = "category";
        $this->objPath = "yxlicense_license";
        parent::__construct();
    }

    /*
     * 跳转到产品分类信息列表
     */
    function c_page() {
        $this->assign('isUse', $_GET['isUse']);
        $this->assign('id', $_GET['id']);
        $this->assign('name', $_GET['name']);
        $this->view('list');
    }

    /**
     * 跳转到新增license分类信息页面
     */
    function c_toAdd() {
        //判断是否已经含有填写表格类的分类，有的话不能新增
        if ($this->service->find(array('licenseId' => $_GET['id'], 'showType' => '5'), null, 'id')) {
            msg('填写表格只能单独存在,不能继续添加新的分类');
        } else {
            $this->assign('licenseId', $_GET['id']);
            $this->view('add');
        }
    }

    //新增
    function c_add() {
        if ($this->service->add_d($_POST[$this->objName])) {
            msg("添加成功");
        } else {
            msg("添加失败");
        }
    }

    /**
     * 跳转到编辑license分类信息页面
     */
    function c_toEdit() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign('showTypeHidden', $obj['showType']);
        $this->assign('typeHidden', $obj['type']);        //勾选/文本类型进行初始化显示
        $this->view('edit');
    }

    /**
     * 跳转到查看license分类信息页面
     */
    function c_toView() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        switch ($obj['showType']) {
            case 1 :
                $this->assign('showType', '列表显示');
                break;
            case 2 :
                $this->assign('showType', '分组显示');
                break;
            case 3 :
                $this->assign('showType', '表单显示');
                break;
            case 4 :
                $this->assign('showType', '直接输入');
                break;
            case 5 :
                $this->assign('showType', '填写表格');
                break;
        }

        switch ($obj['isHideTitle']) {
            case 0 :
                $this->assign('isHideTitle', '否');
                break;
            case 1 :
                $this->assign('isHideTitle', '是');
                break;
        }
        //文本显示类型（勾选/文本）
        switch ($obj['type']) {
            case 1 :
                $this->assign('type', '勾选');
                break;
            case 2 :
                $this->assign('type', '文本');
                break;
        }
        $this->view('view');
    }

    /**
     * 根据licenseId产品类型信息
     */
    function c_getTreeData() {
        $service = $this->service;
        $isSale = isset ($_GET ['isSale']) ? $_GET ['isSale'] : 0;
        if ($isSale == '1') {
            $service->searchArr ['mySearch'] = "sql: and ( c.id='11' or c.licenseId='11')";
        }
        $service->sort = " c.orderNum";
        $service->asc = false;
        $rows = $service->listBySqlId('select_treeinfo');
        echo util_jsonUtil::encode($rows);
    }

    /**
     * 获取分页数据转成Json
     */
    function c_pageJson() {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $service->sort = " c.orderNum";
        $service->asc = false;
        $rows = $service->page_d();

        $arr = array();
        $arr ['collection'] = $rows;
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     *
     * 重新调整产品树的左右节点
     */
    function c_ajustNode() {
        echo $this->service->createTreeLRValue();
    }

    /**
     * 跳转到预览页面
     */
    function c_preview() {
        $this->assign('name', $_GET['licenseName']);
        $this->assign('id', $_GET['id']);
        $this->view('preview');
    }
}