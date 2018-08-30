<?php

/**
 * @author Show
 * @Date 2010年12月29日 星期三 19:31:43
 * @version 1.0
 * @description:钩稽关系主表控制层 只有钩稽和反钩,无修改操作
 */
class controller_finance_related_baseinfo extends controller_base_action
{

    function __construct()
    {
        $this->objName = "baseinfo";
        $this->objPath = "finance_related";
        parent::__construct();
    }

    /*
     * 跳转到钩稽关系主表
     */
    function c_page()
    {
        $this->display('list');
    }

    /**
     * 钩稽操作
     * 当isHook为1时,对数据进行正常钩稽操作
     * 当isHook为0时,对数据进行暂估冲回操作
     */
    function c_hookAdd()
    {
        $rs = $this->service->hookAdd_d($_POST);
        if ($rs) {
            msgRf('钩稽成功');
        } else {
            msgRf('钩稽失败');
        }
    }

    /**
     * 重写列表pageJson
     */
    function c_pageJson()
    {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息
        $service->asc = true;
        $rows = $service->pageBySqlId('hook_list');
        //URL过滤
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        $arr ['totalSize'] = $service->count;
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    function c_pageJsonRelated()
    {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息
        $service->asc = true;
        $rows = $service->pageBySqlId('detail_list');
        //URL过滤
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        $arr ['totalSize'] = $service->count;
        $arr ['page'] = $service->page;
        $arr ['pageSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 反钩稽显示列表
     */
    function c_toUnhook()
    {
        $ids = $this->service->getIds_d($_GET['invPurId']);
        $this->assign('ids', $ids);
        $this->assign('hookMainId', $_GET['invPurId']);
        $this->display('list-unhook');
    }

    /**
     * 反钩稽操作
     */
    function c_unHook()
    {
        echo $this->service->unhook_d($_POST['id']) ? 1 : 0;
    }

    /**
     * 根据表单直接进行反钩稽操作
     */
    function c_unHookByInv()
    {
        echo $this->service->unhookByInv_d($_POST['invPurId']) ? 1 : 0;
    }

    /**
     * 暂估冲回
     */
    function c_releaseAdd()
    {
        // 丢弃
    }

    /**
     * 查看钩稽详情
     */
    function c_init()
    {
        $rows = $this->service->get_d($_GET ['id']);
        foreach ($rows as $key => $val) {
            if ($key == 'shareType') {
                if ($val == 'forNumber') {
                    $val = '按数量分配';
                } else {
                    $val = '按金额分配';
                }
            }
            $this->assign($key, $val);
        }
        if (isset ($_GET ['perm']) && $_GET ['perm'] == 'view') {
            $this->display('view');
        } else {
            $this->display('edit');
        }
    }
}