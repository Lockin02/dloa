<?php

/**
 * @author Show
 * @Date 2010年12月29日 星期三 19:31:43
 * @version 1.0
 * @description:钩稽关系主表 Model层 只有钩稽和反钩,无修改操作
 */
class model_finance_related_baseinfo extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_finance_related";
        $this->sql_map = "finance/related/baseinfoSql.php";
        parent::__construct();
    }

    /**
     * 钩稽操作
     */
    function hookAdd_d($object)
    {
        $relateDetailDao = new model_finance_related_detail();
        try {
            $this->start_d();
            //添加钩稽主表
            $newId = $this->add_d(array(
                'years' => date('Y'),
                'status' => 'CGFPZT-YGJ',
                'supplierId' => $object['supplierId'],
                'supplierName' => $object['supplierName'],
                'shareType' => $object['shareType']), true);

            //添加钩稽从表,修改被钩稽条目内容，修改采购入库单条目内容
            if (isset($object['purchType']) && $object['purchType'] == 'assets' && isset($object['checkCards'])) {
                $relateDetailDao->addInvpurDetail_d($object['invpurdetail'], $newId, $object['storage'], 0, $object['shareType'], $object['checkCards']);
            } else {
                $relateDetailDao->addInvpurDetail_d($object['invpurdetail'], $newId, $object['storage'], 0, $object['shareType']);
            }

            $this->commit_d();
//			$this->rollBack();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 根据采购发票获取钩稽主表id串
     */
    function getIds_d($invPurId)
    {
        $relateDetailDao = new model_finance_related_detail();
        $rows = $relateDetailDao->getRelatedIds_d($invPurId);

        $issetArr = array();
        foreach ($rows as $key => $val) {
            if (!in_array($val['relatedId'], $issetArr)) {
                array_push($issetArr, $val['relatedId']);
            }
        }
        $ids = implode($issetArr, ',');
        return $ids;
    }


    /********************反钩稽部分***************************/

    /**
     * 反钩稽
     */
    function unhook_d($id)
    {
        $relateDetailDao = new model_finance_related_detail();
        try {
            $this->start_d();
            //处理钩稽采购发票
            $relateDetailDao->unhookInvPur_d($id);

            //处理钩稽采购入库单
            $relateDetailDao->unhookStorage_d($id);

            //删除钩稽记录主表
            $this->deletes_d($id);

            $this->commit_d();
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
        return true;
    }

    /**
     * 根据采购发票id进行反钩稽
     */
    function unhookByInv_d($invPurId)
    {
        //获取钩稽序号
        $id = $this->getIds_d($invPurId);
        $rs = $this->unhook_d($id);
        if (!$rs) {
            return false;
        }
        return true;
    }

    /********************其他函数**********************************/
    /**
     * 重写get_d
     */
    function get_d($id)
    {
        $relateDetailDao = new model_finance_related_detail();
        $relateRows = $relateDetailDao->findAll(array('relatedId' => $id, 'hookObj' => 'storage'), null, 'productId,productNo,productName,amount,number,firstAmount,firstPrice,price,hookMainId,hookObjCode');
        $relateRows = $relateDetailDao->showDetailInInit($relateRows);
        $rows = $this->find(array("id" => $id));
        $rows['detailRows'] = $relateRows;
        return $rows;
    }

    /**********************暂估冲回********************************/
    function releaseAdd_d($object)
    {
        // 丢弃
    }
}