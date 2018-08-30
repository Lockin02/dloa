<?php
/**
 * @author Administrator
 * @Date 2011年5月4日 21:40:04
 * @version 1.0
 * @description:收料通知单物料清单信息控制层
 */
class controller_purchase_arrival_equipment extends controller_base_action {

	function __construct() {
		$this->objName = "equipment";
		$this->objPath = "purchase_arrival";
		parent::__construct ();
	 }

	/*
	 * 跳转到收料通知单物料清单信息
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }

	/**
	 * 根据收料单id判断该收料单是否有质检打回的物料
	 */
	function c_getIsQualityBackByArrivalId() {
		$arrivalId = $_POST['arrivalId'];
		$objs = $this->service->findAll(array('arrivalId' => $arrivalId));
		$flag = 0;
		if (is_array($objs)) {
			foreach ($objs as $key => $val) {
				if ($val['isQualityBack'] > 0) {
					$flag = 1;
					break;
				}
			}
		}
		echo $flag;
	}

    /**
     * 获取分页数据转成Json
     */
    function c_pageJson() {
        $service = $this->service;

        $service->getParam ( $_REQUEST );
        //$service->getParam ( $_POST ); //设置前台获取的参数信息

        //$service->asc = false;
        $rows = $service->page_d ();
        //数据加入安全码
        $rows = $this->sconfig->md5Rows ( $rows );
        //获取质检合格率
        if(is_array($rows)){
            $ualityereportequitemDao=new model_produce_quality_qualityereportequitem();
            $qualityapplyitemDao=new model_produce_quality_qualityapplyitem();
            foreach($rows as $key=>$val){
                $applyItemRow=$qualityapplyitemDao->getApplyItem_d($val['id']);
                if(is_array($applyItemRow)&&$applyItemRow['status']==0){
                    $rows[$key]['qualitedRate']='100%';
                }else{
                    $rateRow=$ualityereportequitemDao->getQualifiedRate_d($val['id'],'ZJSQYDSL');
                    if(is_array($rateRow)){
                        $rows[$key]['qualitedRate']=$rateRow[0]['qualitedRate']."%";
                    }else{
                        $rows[$key]['qualitedRate']='';
                    }
                }
            }
        }
        $arr = array ();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode ( $arr );
    }
 }
?>