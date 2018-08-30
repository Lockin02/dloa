<?php

/**
 *
 * 资产采购任务model
 * @author fengxw
 *
 */
class model_asset_purchase_task_task extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_purchase_task";
		$this->sql_map = "asset/purchase/task/taskSql.php";
		parent::__construct ();
	}

	/**
	 * 新建保存资产采购任务及明细单
	 */

	function add_d($object){
		try{
			$this->start_d();
			if(!is_array($object['taskItem'])){
				msg ( '请填写好资产采购任务明细单的信息！' );
				throw new Exception('资产采购任务信息不完整，保存失败！');
			}
			$codeDao=new model_common_codeRule();//生成业务编号
			$object['formCode'] = $codeDao->purchTaskCode("oa_purch_task_basic",$object['sendId']);
			$id=parent::add_d($object,true);
			//保存明细单
			$taskItemDao=new model_asset_purchase_task_taskItem();
			$applyEquDao=new model_asset_purchase_apply_applyItem();
			foreach($object['taskItem'] as $val){
				$val['parentId']=$id;
				$val['taskCode']=$object['formCode'];
				$taskItemDao->add_d($val);
				//更新采购计划设备的已下达数量
				$applyEquDao->updateAmountIssued($val['applyEquId'],$val['taskAmount']);
			}

			//发送邮件通知采购负责人
			$emailArr=array();
			$emailArr['issend'] = 'y';
			$emailArr['TO_ID']=$object['purcherId'];
			$emailArr['TO_NAME']=$object['purcherName'];
			if(is_array($object ['taskItem'])){
				$j=0;
				$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>物料名称</b></td><td><b>规格型号</b></td><td><b>单位</b></td><td><b>任务数量</b></td><td><b>采购需求编号</b></td><td><b>希望完成时间</b></td><td><b>备注</b></td></tr>";
				foreach($object ['taskItem'] as $key => $equ ){
					$j++;
					$productName=$equ['productName'];
					$pattem=$equ ['pattem'];
					$unitName=$equ ['unitName'];
					$taskAmount=$equ ['taskAmount'];
					$applyCode=$equ ['applyCode'];
					$dateHope=$equ['dateHope'];
					$remark=$equ['remark']." ";
					$addmsg .=<<<EOT
					<tr align="center" >
							<td>$j</td>
							<td>$productName</td>
							<td>$pattem</td>
							<td>$unitName</td>
							<td>$taskAmount</td>
							<td>$applyCode</td>
							<td>$dateHope</td>
							<td>$remark </td>
						</tr>
EOT;
					}
					$addmsg.="</table>";
			}
			$emailDao = new model_common_mail();
			$emailInfo = $emailDao->purchTaskEmail($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'oa_purch_task_basic',',采购任务单据号为：<font color=red><b>'.$object["formCode"].'</b></font>','',$emailArr['TO_ID'],$addmsg,1);

			$this->commit_d();
			return $id;

		}catch(Exception $e){
			$this->rollBack();
			return $id;
		}
	}

	/**
	 * 修改保存
	 * @see model_base::edit_d()
	 */
	function edit_d($object) {
		try {
			$this->start_d ();
			if (is_array ( $object ['taskItem'] )) {
				$id = parent::edit_d ( $object, true );
				$taskItemDao=new model_asset_purchase_task_taskItem();
				$mainArr=array("parentId"=>$object ['id']);
				$itemsArr=util_arrayUtil::setArrayFn($mainArr,$object ['taskItem']);
				$itemsObj = $taskItemDao->saveDelBatch ( $itemsArr );
				$this->commit_d ();
				return $object ['id'];
			} else {
				throw new Exception ( "单据信息不完整，请确认！" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}


}


?>
