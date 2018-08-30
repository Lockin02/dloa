<?php

/**
 * @author Show
 * @Date 2011年11月25日 星期五 9:38:59
 * @version 1.0
 */
class controller_engineering_baseinfo_esmNotice extends controller_base_action
{

    function __construct()
    {
        $this->objName = "esmNotice";
        $this->objPath = "engineering_baseinfo";
        parent::__construct();
    }

    /**
     * 获取分页数据转成Json
     */
    function c_pageJson()
    {
        $service = $this->service;

        $service->getParam($_REQUEST);
        $rows = $service->page_d();
        
        foreach ($rows as $k => $v) {
            $rows[$k]['content'] = mb_substr($v['content'], 0, 20, "gbk") == $v['content'] ?
                $v['content'] : mb_substr($v['content'], 0, 20, "gbk") . '...';
        }

        $arr = array();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 显示列表
     */
    function c_list()
    {
        $this->assign('manage', '0');
        $this->assign('t', isset($_GET['t']) ? $_GET['t'] : "0");
        $this->view("list");
    }

    /**
     * 获取所有数据返回json
     */
    function c_listJson()
    {
        // 加载个人权限
        $rangeDao = new model_engineering_officeinfo_range();
        $officeIds = $rangeDao->getOfficeIds_d();

        $sql = "SELECT * FROM " . $this->service->tbl_name . " c WHERE 1 ";

        // 如果有权限，则匹配权限过滤条件
        if ($officeIds) {
            $officeIdArr = explode(',', $officeIds);
            $sql .= " AND (c.officeIds = '' ";
            foreach ($officeIdArr as $v) {
                $sql .= " OR find_in_set(" . $v . ", c.officeIds)";
            }
            $sql .= ")";
        } else {
            $sql .= " AND (c.officeIds = '') ";
        }
        if ($_REQUEST['period'] == 'lastThreeMonth') {
            $sql .= " AND DATEDIFF('" . day_date . "', noticeDate) <= 90 ";
        }

        $sql .= " ORDER BY noticeDate DESC";

        $rows = $this->service->_db->getArray($sql);

        foreach ($rows as $k => $v) {
            $rows[$k]['content'] = mb_substr($v['content'], 0, 20, "gbk") == $v['content'] ?
                $v['content'] : mb_substr($v['content'], 0, 20, "gbk") . '...';
        }

        //数据加入安全码
        echo util_jsonUtil::encode($rows);
    }

    /**
     * 新增
     */
    function c_toAdd()
    {
        $this->assign('noticeDate', day_date);
        $this->showDatadicts(array('category' => 'GCTZLX')); // 通知类型
        $this->view('add');
    }

    /**
     * 编辑
     */
    function c_toEdit()
    {
        $object = $this->service->get_d($_GET['id']);
        $this->assignFunc($object);
        $this->assign('files', $this->service->getFilesByObjId($_GET['id'], true, $this->service->tbl_name));
        $this->showDatadicts(array('category' => 'GCTZLX'), $object['category']); // 通知类型
        $this->view('edit');
    }

    /**
     * 查看
     */
    function c_toView()
    {
        $object = $this->service->get_d($_GET['id']);
        $this->assignFunc($object);
        $this->assign('files', $this->service->getFilesByObjId($_GET['id'], false, $this->service->tbl_name));
        $this->view('view');
    }

    /**
     * 更新是否有附件
     */
    function c_updateHashFile()
    {
        echo $this->service->updateHasFile_d($_POST['id']) ? 1 : 0;
    }
}