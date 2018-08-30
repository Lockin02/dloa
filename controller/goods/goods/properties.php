<?php

/**
 * @author huangzf
 * @Date 2012��3��1�� 20:09:22
 * @version 1.0
 * @description:��Ʒ��������(����)���Ʋ�
 */

class controller_goods_goods_properties extends controller_base_action
{

    function __construct() {
        $this->objName = "properties";
        $this->objPath = "goods_goods";
        parent::__construct();
    }

    /**
     * ѡ���б�
     */
    function c_pageSelect() {
        $this->view('list-select');
    }

    /**
     * ������������Ϣ�б�
     */
    function c_toProInfoTypePage() {
        $this->assign('productUpdate', $this->service->this_limit ['����']);
        $this->view("list");
    }

    /**
     *
     * ��Ʒ���ñ༭
     */
    function c_toEditConfig() {
        $this->assign("goodsId", $_GET ['goodsId']);
        $this->view("config-edit");
    }

    /**
     *
     * ��Ʒ���ñ༭
     */
    function c_toPreView() {
        $goodsId = isset ($_GET ['goodsId']) ? $_GET ['goodsId'] : "";
        $service = $this->service;
        $goodsDao = new model_goods_goods_goodsbaseinfo ();
        $goodsObj = $goodsDao->get_d($goodsId);
        $this->assign("goodsName", $goodsObj ['goodsName']);
        $this->assign("goodsId", $goodsId);
        $this->assign("description", $goodsObj ['description']);
        $rows = $service->getProByGoodsId($goodsId);
        $this->assign("propertiesInfo", $service->showPropertiesForChoose($rows));
        $this->view("config-view");
    }

    /**
     * �����ȡ���ڲ�Ʒ���ñ༭
     */
    function c_HWtoPreView($id) {
        $goodsId = isset ($id) ? $id : "";
        $service = $this->service;
        $goodsDao = new model_goods_goods_goodsbaseinfo ();
        $goodsObj = $goodsDao->get_d($goodsId);
        $goodsObj['rows'] = $service->getProByGoodsId($goodsId);
        return util_jsonUtil:: iconvGB2UTFArr($goodsObj);
    }

    /**
     * ajax ��ȡ ��Ʒ��Ϣ
     */
    function c_ajaxGetGoods() {
        $goodsId = isset ($_GET ['goodsId']) ? $_GET ['goodsId'] : exit;
        $rows = $this->service->getProByGoodsId($goodsId);
        echo util_jsonUtil::iconvGB2UTF($this->service->showPropertiesForChoose($rows));
    }

    /**
     * ��ͬ��Ӳ�Ʒ����
     */
    function c_toChoose() {
        $goodsId = isset ($_GET ['goodsId']) ? $_GET ['goodsId'] : "";
        $productInfoId = isset ($_GET ['productInfoId']) ? $_GET ['productInfoId'] : "";
        $service = $this->service;
        $goodsDao = new model_goods_goods_goodsbaseinfo ();
        $goodsObj = $goodsDao->get_d($goodsId);
        $this->assign("goodsName", $goodsObj ['goodsName']);
        $this->assign("goodsId", $goodsId);
        $this->assign("productInfoId", $productInfoId);
        $rows = $service->getProByGoodsId($goodsId);
        $this->assign("propertiesInfo", $service->showPropertiesForChoose($rows));
        $this->view("config-choose");
    }

    /**
     * ��ͬ��Ӳ�Ʒ���� �� �������
     */
    function c_toChooseStep() {
        $cacheId = isset ($_GET ['cacheId']) ? $_GET ['cacheId'] : "";
        $notEquSlt = isset ($_GET ['notEquSlt']) ? $_GET ['notEquSlt'] : "";
        $isMoney = isset ($_GET ['isMoney']) ? $_GET ['isMoney'] : "0";
        $isSale = isset ($_GET ['isSale']) ? $_GET ['isSale'] : "0";
        $isCon = isset ($_GET ['isCon']) ? $_GET ['isCon'] : "0";
        $exeDeptName = isset ($_GET ['exeDeptName']) ? $_GET ['exeDeptName'] : "";
        $exeDeptCode = isset ($_GET ['exeDeptCode']) ? $_GET ['exeDeptCode'] : "";
        $typeId = isset ($_GET ['typeId']) ? $_GET ['typeId'] : "";
        $this->assign('typeId', $typeId);
        $this->assign('notEquSlt', $notEquSlt);
        $this->assign('isMoney', $isMoney);
        $this->assign('isSale', $isSale);
        $this->assign('isCon', $isCon);
        $this->assign("exeDeptCode", $exeDeptCode);
        $this->assign("exeDeptName", $exeDeptName);
        $this->assign('cacheId', $cacheId);
        $goodsId = isset ($_GET ['goodsId']) ? $_GET ['goodsId'] : "";
        $goodsDao = new model_goods_goods_goodsbaseinfo ();
        $goodsObj = $goodsDao->get_d($goodsId);
        $this->assign("description", $goodsObj ['description']);

        //����д���cacheId,�����༭����
        if (empty ($cacheId)) {
            $number = isset ($_GET ['number']) ? $_GET ['number'] : "";
            $productInfoId = isset ($_GET ['productInfoId']) ? $_GET ['productInfoId'] : "";
            $service = $this->service;
            $this->assign("goodsName", $goodsObj ['goodsName']);
            $this->assign("goodsId", $goodsId);
            $this->assign("productInfoId", $productInfoId);
            $rows = $service->getProByGoodsId($goodsId);
            $this->assign("propertiesInfo", $service->showPropertiesForChoose($rows, $number));
            $this->assign('goodsValue', '');

            $this->assignFunc($_GET);
        } else {
            $this->assignFunc($_GET);
            $goodsCacheDao = new model_goods_goods_goodscache ();
            $this->assign("propertiesInfo", $goodsCacheDao->getGoodsCache_d($_GET ['cacheId']));
            $rs = $goodsCacheDao->find(array('id' => $_GET ['cacheId']), null, 'goodsValue');
            $this->assignFunc($rs);
        }

        $this->view("config-choosestep");
    }

    /**
     * �����ͬ��Ӳ�Ʒ���� �� �������
     */
    function c_HWtoChooseStep($idArr) {
        $cacheId = isset ($idArr ['cacheId']) ? $idArr ['cacheId'] : "";
        $isMoney = isset ($idArr ['isMoney']) ? $idArr ['isMoney'] : "0";
        $isSale = isset ($idArr ['isSale']) ? $idArr ['isSale'] : "0";

        $this->assign('isMoney', $isMoney);
        $this->assign('isSale', $isSale);

        $this->assign('cacheId', $cacheId);

        //����д���cacheId,�����༭����
        if (empty ($cacheId)) {
            $goodsId = isset ($idArr ['goodsId']) ? $idArr ['goodsId'] : "";
            $service = $this->service;
            $goodsDao = new model_goods_goods_goodsbaseinfo ();
            $objArr['goodsObj'] = $goodsDao->get_d($goodsId);
            $rows = $service->getProByGoodsId($goodsId);
            $objArr['rows'] = $rows;
        } else {
            $goodsCacheDao = new model_goods_goods_goodscache ();
            $objArr['goodsCache'] = $goodsCacheDao->getGoodsCache_d($idArr ['cacheId']);
            $rs = $goodsCacheDao->find(array('id' => $idArr ['cacheId']), null, 'goodsValue');
            $objArr['rs'] = $rs;
        }
        return util_jsonUtil:: iconvGB2UTFArr($objArr);
    }

    /**
     * ��ͬ��Ӳ�Ʒ���� - �����ý����ٴ�ѡ��
     */
    function c_toChooseAgain() {
        $this->assignFunc($_GET);
        $goodsCacheDao = new model_goods_goods_goodscache ();
        $this->assign("propertiesInfo", $goodsCacheDao->getGoodsCache_d($_GET ['cacheId']));
        $rs = $goodsCacheDao->find(array('id' => $_GET ['cacheId']), null, 'goodsValue');
        $this->assignFunc($rs);

        $this->view("config-chooseagain");
    }

    /**
     * ��ͬ��Ӳ�Ʒ���� - �����ý����ٴ�ѡ��
     */
    function c_toChooseView() {
        $this->assignFunc($_GET);
        if (!isset ($_GET ['goodsName'])) {
            $this->assign("goodsName", "");
        }
        $goodsCacheDao = new model_goods_goods_goodscache ();
        $this->assign("propertiesInfo", $goodsCacheDao->getGoodsCache_d($_GET ['cacheId']));

        $this->view("config-chooseview");
    }

    /**
     * �����Ʒ����
     */
    function c_toChooseChange() {
        $cacheId = isset ($_GET ['cacheId']) ? $_GET ['cacheId'] : "";

        $this->assign('cacheId', $cacheId);

        //����д���cacheId,�����༭����
        if (empty ($cacheId)) {
            $goodsId = isset ($_GET ['goodsId']) ? $_GET ['goodsId'] : "";
            $number = isset ($_GET ['number']) ? $_GET ['number'] : "";
            $productInfoId = isset ($_GET ['productInfoId']) ? $_GET ['productInfoId'] : "";
            $service = $this->service;
            $goodsDao = new model_goods_goods_goodsbaseinfo ();
            $goodsObj = $goodsDao->get_d($goodsId);
            $this->assign("goodsName", $goodsObj ['goodsName']);
            $this->assign("goodsId", $goodsId);
            $this->assign("productInfoId", $productInfoId);
            $rows = $service->getProByGoodsId($goodsId);
            $this->assign("propertiesInfo", $service->showPropertiesForChoose($rows, $number));
            $this->assign('goodsValue', '');

            $this->assignFunc($_GET);
        } else {
            $this->assignFunc($_GET);

            $goodsCacheDao = new model_goods_goods_goodscache ();
            $this->assign("propertiesInfo", $goodsCacheDao->getGoodsCache_d($_GET ['cacheId']));
            $rs = $goodsCacheDao->find(array('id' => $_GET ['cacheId']), null, 'goodsValue');
            $this->assignFunc($rs);
        }

        $this->view("config-change");
    }

    /**
     * ��ת����Ʒ��������(����)�б�
     */
    function c_page() {
        $this->assign("goodsId", $_GET ['goodsId']);
        $this->view('list');
    }

    /**
     * ��ת��������Ʒ��������(����)ҳ��
     */
    function c_toAdd() {
        $this->view('add');
    }

    /**
     * ��ת���༭��Ʒ��������(����)ҳ��
     */
    function c_toEdit() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('edit');
    }

    /**
     * ��ת���鿴��Ʒ��������(����)ҳ��
     */
    function c_toView() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('view');
    }

    /**
     * ��ת���鿴��Ʒ��������(����)ҳ��
     */
    function c_toViewTip() {
        $propertyItmDao = new model_goods_goods_propertiesitem ();
        $propertyItem = $propertyItmDao->get_d($_GET ['id']);
        $this->assign("remark", stripslashes(util_jsonUtil::iconvGB2UTF($propertyItem ['remark'])));
        $this->view('config-tip');
    }

    /**
     *
     * ����parentId��productId��ȡ��Ʒ��������
     */
    function c_getTreeData() {
        $service = $this->service;
        if (isset ($_GET['goodsId'])) {
            $service->searchArr['mainId'] = $_GET['goodsId'];
        }

        $service->searchArr ['parentId'] = isset ($_POST ['id']) ? $_POST ['id'] : -1;
        $service->sort = " c.orderNum";
        $service->asc = false;
        $rows = $service->listBySqlId('select_treeinfo');
        echo util_jsonUtil::encode($rows);
    }

    /**
     * ��ȡ��ҳ����ת��Json
     */
    function c_pageJson() {
        $service = $this->service;
        $service->getParam($_REQUEST);
        if (isset ($service->searchArr ['parentId'])) {
            $node = $service->get_d($service->searchArr ['parentId']);
            unset ($service->searchArr ['parentId']);
            $service->searchArr ['dlft'] = $node ['lft'];
            $service->searchArr ['xrgt'] = $node ['rgt'];
            $rows = $service->page_d("select_default");
        } else {
            $rows = $service->page_d();
        }

        $arr = array();
        $arr ['collection'] = $rows;
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ��ת����д��ע��Ϣ
     */
    function c_toEditRemark() {
        $this->assign("rowNum", $_GET ['rowNum']);

        if (empty ($_GET ['rkey'])) {
            if (!empty ($_GET ['id'])) {
                $propertiesItemDao = new model_goods_goods_propertiesitem ();
                $obj = $propertiesItemDao->get_d($_GET ['id']);
            } else {
                $obj = array();
            }
            $rkey = "tempRemark" . rand(1, 1000);
            $_SESSION [$rkey] = $obj ['remark'];
            $this->assign("rkey", $rkey);
        } else {
            $this->assign("rkey", $_GET ['rkey']);
        }
        $this->view("config-remark");
    }

    /**
     *
     * ��ת�����ù���������
     */
    function c_toSetAssItem() {
        $service = $this->service;
        $goodsId = isset ($_GET ['goodsId']) ? $_GET ['goodsId'] : "";
        $this->assign("goodsId", $goodsId);
        $assItems = $service->getProBeforeOrderNum($goodsId, $_GET ['orderNum']);
        $this->assign("rowNum", $_GET ['rowNum']);
        $this->assign("assitem", $_GET ['assitem']);
        $this->assign("assItemIdStr", $_GET ['assItemIdStr']);
        $this->assign("assitemTipStr", $_GET ['assitemTipStr']);
        $this->assign("assItems", $service->showItemsAtAss($assItems));
        $this->view("config-assitem");
    }

    /**
     * �޸Ķ���
     */
    function c_edit($isEditInfo = false) {
        $object = $_POST [$this->objName];
        if ($this->service->edit_d($object, $isEditInfo)) {
            msgGo('�༭�ɹ���', '?model=goods_goods_properties&action=toEdit&id=' . $object ['id']);
        }
    }

    /**
     * ������ʱ������
     */
    function c_saveTempRemark() {
        $_SESSION [$_POST ['rkey']] = util_jsonUtil::iconvUTF2GB(stripslashes($_POST ['remark'])); //ȥ����б��
        echo $_POST ['rkey'];
    }

    /**
     * ��ȡ��ʱ������
     */
    function c_getTempRemark() {
        echo util_jsonUtil::iconvGB2UTF(stripslashes($_SESSION [$_POST ['rkey']]));
    }

    //ѡ���Ʒ����֮����ת������ȷ��ҳ��
    function c_toMatConfirm() {
        $this->assign("goodsId", $_GET['goodsId']);
        $this->assign("goodsName", $_GET['goodsName']);
        $this->assign("goodsValue", $_GET['goodsValue']);
        $this->assign("isEncrypt", $_GET['isEncrypt']);
        $this->assign("number", $_GET['number']);
        $this->assign("price", $_GET['price']);
        $this->assign("money", $_GET['money']);
        $this->assign("warrantyPeriod", $_GET['warrantyPeriod']);
        $this->assign("cacheId", $_GET['cacheId']);
        $this->assign("isMoney", $_GET['isMoney']);
        $this->assign("isSale", $_GET['isSale']);
        $this->assign("typeId", $_GET['typeId']);
		$this->assign('rowNum', isset($_GET['rowNum']) ? $_GET['rowNum'] : 0);
		$this->assign('componentId', isset($_GET['componentId']) ? $_GET['componentId'] : '');
        $this->assign("exeDeptName", $_GET['exeDeptName']);
        $this->assign("exeDeptCode", $_GET['exeDeptCode']);
        $this->assign("auditDeptCode", $_GET['auditDeptCode']);
        $this->assign("auditDeptName", $_GET['auditDeptName']);

        $this->view("config-matConfirm");
    }
}