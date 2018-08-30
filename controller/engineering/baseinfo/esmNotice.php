<?php

/**
 * @author Show
 * @Date 2011��11��25�� ������ 9:38:59
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
     * ��ȡ��ҳ����ת��Json
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
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ��ʾ�б�
     */
    function c_list()
    {
        $this->assign('manage', '0');
        $this->assign('t', isset($_GET['t']) ? $_GET['t'] : "0");
        $this->view("list");
    }

    /**
     * ��ȡ�������ݷ���json
     */
    function c_listJson()
    {
        // ���ظ���Ȩ��
        $rangeDao = new model_engineering_officeinfo_range();
        $officeIds = $rangeDao->getOfficeIds_d();

        $sql = "SELECT * FROM " . $this->service->tbl_name . " c WHERE 1 ";

        // �����Ȩ�ޣ���ƥ��Ȩ�޹�������
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

        //���ݼ��밲ȫ��
        echo util_jsonUtil::encode($rows);
    }

    /**
     * ����
     */
    function c_toAdd()
    {
        $this->assign('noticeDate', day_date);
        $this->showDatadicts(array('category' => 'GCTZLX')); // ֪ͨ����
        $this->view('add');
    }

    /**
     * �༭
     */
    function c_toEdit()
    {
        $object = $this->service->get_d($_GET['id']);
        $this->assignFunc($object);
        $this->assign('files', $this->service->getFilesByObjId($_GET['id'], true, $this->service->tbl_name));
        $this->showDatadicts(array('category' => 'GCTZLX'), $object['category']); // ֪ͨ����
        $this->view('edit');
    }

    /**
     * �鿴
     */
    function c_toView()
    {
        $object = $this->service->get_d($_GET['id']);
        $this->assignFunc($object);
        $this->assign('files', $this->service->getFilesByObjId($_GET['id'], false, $this->service->tbl_name));
        $this->view('view');
    }

    /**
     * �����Ƿ��и���
     */
    function c_updateHashFile()
    {
        echo $this->service->updateHasFile_d($_POST['id']) ? 1 : 0;
    }
}