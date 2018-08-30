<?php
/**
 * @author Administrator
 * @Date 2013��3��7�� ������ 15:09:03
 * @version 1.0
 * @description:���������嵥���Ʋ�
 */
class controller_produce_quality_qualitytaskitem extends controller_base_action {

	function __construct() {
		$this->objName = "qualitytaskitem";
		$this->objPath = "produce_quality";
		parent::__construct ();
	}

	/**
	 * ��ȡ�������ݷ���json
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
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * ��ת�����������嵥�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת���������������嵥ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * ��ת���༭���������嵥ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
	}

	/**
	 * ��ת���鿴���������嵥ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}	/**
	*
	* ��ȡ���������嵥subgrid����
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
	* ��ȡ�����嵥editgrid����
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
	 * ���������������
	 */
	function c_checkAssignNum(){
		$service = $this->service;
		echo $service->checkAssignNum($_POST['id'],$_POST['applyItemId'],$_POST['assignNum']);

	}
}
?>