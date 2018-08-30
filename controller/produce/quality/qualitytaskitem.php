<?php
/**
 * @author Administrator
 * @Date 2013年3月7日 星期四 15:09:03
 * @version 1.0
 * @description:交检任务单清单控制层
 */
class controller_produce_quality_qualitytaskitem extends controller_base_action {

	function __construct() {
		$this->objName = "qualitytaskitem";
		$this->objPath = "produce_quality";
		parent::__construct ();
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_listJsonForReport() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ('select_forreport');
        foreach ($rows as $k => $row){
            if($row['remark'] == 'NULL'){
                $rows[$k]['remark'] = '';
            }
        }
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * 跳转到交检任务单清单列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增交检任务单清单页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * 跳转到编辑交检任务单清单页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
	}

	/**
	 * 跳转到查看交检任务单清单页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}	/**
	*
	* 获取交检任务清单subgrid数据
	*/
	function c_pageItemJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
        if(is_array($rows)){
            $ualityereportequitemDao=new model_produce_quality_qualityereportequitem();
            $qualityapplyitemDao=new model_produce_quality_qualityapplyitem();
            foreach($rows as $key=>$val){
                $applyItemRow=$qualityapplyitemDao->getApplyItem_d($val['objItemId']);
                if(is_array($applyItemRow)&&$applyItemRow['status']==0){
                    $qualitedRate = bcdiv($rows[$key]['standardNum'],$rows[$key]['realCheckNum'],2) * 100;
                    $rows[$key]['qualitedRate']=number_format($qualitedRate,2)."%";
                }else{
                    $rateRow=$ualityereportequitemDao->getQualifiedRate_d($val['objItemId'],'ZJSQYDSL');
                    if(is_array($rateRow)){
                        $rows[$key]['qualitedRate']=$rateRow[0]['qualitedRate']."%";
                    }else{
                        $rateRow=$ualityereportequitemDao->getQualifiedRate_d($val['objItemId'],'ZJSQYDTH');
                        if(is_array($rateRow)){
                            $rows[$key]['qualitedRate']=$rateRow[0]['qualitedRate']."%";
                        }else{
                            $rows[$key]['qualitedRate']='';
                        }
                    }
                }
            }
        }
		$arr = array ();
		$arr ['collection'] = $rows;
		echo util_jsonUtil::encode ( $arr );
	}/**
	*
	* 获取生产清单editgrid数据
	*/
	function c_editItemJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
        if(is_array($rows)){
            $ualityereportequitemDao=new model_produce_quality_qualityereportequitem();
            $qualityapplyitemDao=new model_produce_quality_qualityapplyitem();
            foreach($rows as $key=>$val){
                $applyItemRow=$qualityapplyitemDao->getApplyItem_d($val['objItemId']);
                if(is_array($applyItemRow)&&$applyItemRow['status']==0){
                    $qualitedRate = bcdiv($rows[$key]['standardNum'],$rows[$key]['realCheckNum'],2) * 100;
                    $rows[$key]['qualitedRate']=number_format($qualitedRate,2)."%";
                }else{
                    $rateRow=$ualityereportequitemDao->getQualifiedRate_d($val['objItemId'],'ZJSQYDSL');
                    if(is_array($rateRow)){
                        $rows[$key]['qualitedRate']=$rateRow[0]['qualitedRate']."%";
                    }else{
                        $rateRow=$ualityereportequitemDao->getQualifiedRate_d($val['objItemId'],'ZJSQYDTH');
                        if(is_array($rateRow)){
                            $rows[$key]['qualitedRate']=$rateRow[0]['qualitedRate']."%";
                        }else{
                            $rows[$key]['qualitedRate']='';
                        }
                    }
                }
            }
        }
		//		$arr = array ();
		//		$arr ['collection'] = $rows;
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 *
	 * 检验任务分配数量
	 */
	function c_checkAssignNum(){
		$service = $this->service;
		echo $service->checkAssignNum($_POST['id'],$_POST['applyItemId'],$_POST['assignNum']);

	}
}
?>