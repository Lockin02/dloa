<?php
/**
 * @author Administrator
 * @Date 2011��6��2�� 10:30:25
 * @version 1.0
 * @description:���������� Model��
 */
 class model_produce_protask_protask  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_protask";
		$this->sql_map = "produce/protask/protaskSql.php";
		parent::__construct ();
		$this->relatedStrategyArr = array (//��ͬ���ͳ������������,������Ҫ���������׷��
					"oa_sale_order" => "model_produce_protask_strategy_salesprotask", //���۷���
					"oa_sale_lease" => "model_produce_protask_strategy_rentalprotask", //���޳���
					"oa_sale_service" => "model_produce_protask_strategy_serviceprotask", //�����ͬ����
					"oa_sale_rdproject" => "model_produce_protask_strategy_rdprojectprotask", //�з���ͬ����
					"oa_borrow_borrow" => "model_produce_protask_strategy_borrowprotask", //���÷���
					"oa_present_present" => "model_produce_protask_strategy_presentprotask", //���÷���
				);
	}
	/*===================================ҳ��ģ��======================================*/
	/**
	 * @description ���������б���ʾģ��
	 * @param $rows
	 */
	function showList($rows,taskstrategy $istrategy){
		$istrategy->showList($rows);
	}

	/**
	 * @description ������������ʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemAdd($rows,taskstrategy $istrategy) {
		return $istrategy->showItemAdd($rows);
	}

	/**
	 * @description �޸���������ʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemEdit($rows,taskstrategy $istrategy) {
		return $istrategy->showItemEdit($rows);
	}

	/**
	 * @description �鿴��������ʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemView($rows,taskstrategy $istrategy) {
		return $istrategy->showItemView($rows);
	}

	/**
	 * �鿴���ҵ����Ϣ
	 * @param $paramArr
	 */
	function viewRelInfo($paramArr =false,$skey,taskstrategy $istrategy) {
		return $istrategy->viewRelInfo($paramArr,$skey);
	}

	/**
	 * ���ƻ�ȡԴ�����ݷ���
	 */
	 function getDocInfo( $id, taskstrategy $istrategy ){
		$rows = $istrategy->getDocInfo($id);
		return $rows;
	 }

	/**
	 * ������������ʱԴ����ҵ����
	 * @param $istorageapply ���Խӿ�
	 * @param  $paramArr ����������� :$relDocId->��������id;$relDocCode->�������ݱ���;
	 * @param  $relItemArr �ӱ��嵥��Ϣ
	 */
	function ctDealRelInfoAtAdd(taskstrategy $istrategy, $paramArr = false, $relItemArr = false) {
		return $istrategy->dealRelInfoAtAdd($paramArr, $relItemArr);
	}
	/**
	 * �޸���������ʱԴ����ҵ����
	 * @param $istorageapply ���Խӿ�
	 * @param  $paramArr ����������� :$relDocId->��������id;$relDocCode->�������ݱ���;
	 * @param  $relItemArr �ӱ��嵥��Ϣ
	 */
	function ctDealRelInfoAtEdit(taskstrategy $istrategy, $paramArr = false, $relItemArr = false) {
		return $istrategy->dealRelInfoAtEdit($paramArr, $relItemArr);
	}

	/**
	 * ��Ӷ���
	 */
	 function add_d( $object ){
		try{
			$this->start_d();
			$id = parent::add_d( $object, true );
			$relDocType = isset ($object['relDocType']) ? $object['relDocType'] : null;
			if ($relDocType) { //���ڷ����ƻ�����
				$protaskStrategy = $this->relatedStrategyArr[$relDocType];
				if ($protaskStrategy) {
						$paramArr = array (//�����������
							'mainId' => $id,
							'relDocId' =>  $object['relDocId'],
							'relDocCode' => $object['relDocCode'],
							'relDocType' => $object['relDocType'],
						); //...���Լ���׷��
					if (is_array($object['productsdetail'])) {
						$relItemArr = $object['productsdetail']; //�����嵥��Ϣ
						$protaskequ= new model_produce_protask_protaskequ();
						$mainIdArr = array( 'mainId' => $paramArr['docId'] );
						$protaskequ->createBatch( $relItemArr,$paramArr );
					} else {
						throw new Exception("������Ϣ����������ȷ��!");
					}
					//ͳһѡ����ԣ�������Ե�ҵ����
					$storageproId = $this->ctDealRelInfoAtAdd(new $protaskStrategy (), $paramArr, $relItemArr);
				} else {
					throw new Exception("�����ͷ���������δ���ţ�����ϵ������Ա!");
				}
			} else {
				throw new Exception("������Ϣ����������ȷ��!");
			}
			/*end:�����������ҵ����,ֻ����ѱ�Ҫ�������չ��򴫵����԰�װ����,�Ժ�����Թ̶��Ĵ���*/

			$this->commit_d();
			return $id;
		}catch( Exception $e ){
			$this->rollBack();
			return null;
		}
	 }



	/**
	 * �༭����
	 */
	 function edit_d( $object ){
		try{
			$this->start_d();
			$id = parent::edit_d( $object, true );
			$relDocType = isset ($object['relDocType']) ? $object['relDocType'] : null;
			if ($relDocType) { //���ڷ����ƻ�����
				$protaskStrategy = $this->relatedStrategyArr[$relDocType];
				if ($protaskStrategy) {
						$paramArr = array (//�����������
							'mainId' => $object['id'],
							'relDocId' => $object['relDocId'],
							'relDocCode' => $object['relDocCode'],
							'relDocType' => $object['relDocType'],
						); //...���Լ���׷��
					if (is_array($object['productsdetail'])) {
						$relItemArr = $object['productsdetail']; //�����嵥��Ϣ
						foreach( $relItemArr as $key => $val ){
							unset($relItemArr[$key]['issuedProNum']);
						}
						$prod = new model_produce_protask_protaskequ();
						$mainIdArr = array( 'mainId' => $object['id'] );
						$prod->delete($mainIdArr);
						$prod->createBatch( $relItemArr,$paramArr );
					} else {
						throw new Exception("������Ϣ����������ȷ��!");
					}
					//ͳһѡ����ԣ�������Ե�ҵ����
					$relItemArr = $object['productsdetail']; //�����嵥��Ϣ
					$storageproId = $this->ctDealRelInfoAtEdit(new $protaskStrategy (), $paramArr, $relItemArr);
				} else {
					throw new Exception("�����ͳ���������δ���ţ�����ϵ������Ա!");
				}
			} else {
				throw new Exception("������Ϣ����������ȷ��!");
			}
			/*end:�����������ҵ����,ֻ����ѱ�Ҫ�������չ��򴫵����԰�װ����,�Ժ�����Թ̶��Ĵ���*/

			$this->commit_d();
			return $id;
		}catch( Exception $e ){
			$this->rollBack();
			return null;
		}
	 }


	/**
	 * ����id��������뵥������Ϣ
	 */
	function get_d($id) {
		$protaskInfo = parent :: get_d($id);
		$itemDao = new model_produce_protask_protaskequ();
		$searchArr = array(
			'mainId' => $id,
		 );
		$protaskInfo['details'] = $itemDao -> findAll( $searchArr );
		return $protaskInfo;
	}
 }
?>