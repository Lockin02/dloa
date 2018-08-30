<?php

/**
 * @author Show
 * @Date 2010��12��29�� ������ 19:31:43
 * @version 1.0
 * @description:������ϵ������Ʋ� ֻ�й����ͷ���,���޸Ĳ���
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
     * ��ת��������ϵ����
     */
    function c_page()
    {
        $this->display('list');
    }

    /**
     * ��������
     * ��isHookΪ1ʱ,�����ݽ���������������
     * ��isHookΪ0ʱ,�����ݽ����ݹ���ز���
     */
    function c_hookAdd()
    {
        $rs = $this->service->hookAdd_d($_POST);
        if ($rs) {
            msgRf('�����ɹ�');
        } else {
            msgRf('����ʧ��');
        }
    }

    /**
     * ��д�б�pageJson
     */
    function c_pageJson()
    {
        $service = $this->service;
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->asc = true;
        $rows = $service->pageBySqlId('hook_list');
        //URL����
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
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->asc = true;
        $rows = $service->pageBySqlId('detail_list');
        //URL����
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        $arr ['totalSize'] = $service->count;
        $arr ['page'] = $service->page;
        $arr ['pageSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ��������ʾ�б�
     */
    function c_toUnhook()
    {
        $ids = $this->service->getIds_d($_GET['invPurId']);
        $this->assign('ids', $ids);
        $this->assign('hookMainId', $_GET['invPurId']);
        $this->display('list-unhook');
    }

    /**
     * ����������
     */
    function c_unHook()
    {
        echo $this->service->unhook_d($_POST['id']) ? 1 : 0;
    }

    /**
     * ���ݱ�ֱ�ӽ��з���������
     */
    function c_unHookByInv()
    {
        echo $this->service->unhookByInv_d($_POST['invPurId']) ? 1 : 0;
    }

    /**
     * �ݹ����
     */
    function c_releaseAdd()
    {
        // ����
    }

    /**
     * �鿴��������
     */
    function c_init()
    {
        $rows = $this->service->get_d($_GET ['id']);
        foreach ($rows as $key => $val) {
            if ($key == 'shareType') {
                if ($val == 'forNumber') {
                    $val = '����������';
                } else {
                    $val = '��������';
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