<?php
/**
 * @author Administrator
 * @Date 2012-08-09 09:35:57
 * @version 1.0
 * @description:离职清单模板 Model层
 */
 header("Content-type: text/html; charset=gb2312");
 class model_hr_leave_formwork  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_leave_handover_formwork";
		$this->sql_map = "hr/leave/formworkSql.php";
		parent::__construct ();
	}
	/**
	 * 处理离职交接详细
	 */
    function fromworkInfo_d($fromworkInfo,$count=null){
         foreach($fromworkInfo as $k => $v){
         	$i = $k +$count+1;
         	$j=$i-1;
         	$items = $v['items'];
         	$recipientName = $v['recipientName'];
         	$recipientId = $v['recipientId'];
         	$advanceAffirm[$j] = $v['advanceAffirm'] ? 'checked':'';
         	$sort[$j] = $v['sort'];
         	$mailAffirm[$j] = $v['mailAffirm'] ? 'checked':'';
         	$sendPremise[$j] = $v['sendPremise'];
             $list .= "<tr class='clearClass'><td> <img align='absmiddle' src='images/removeline.png' onclick='delItem(this);' title='删除行' /></td><td>
             		     <input type ='hidden' class='rimless_textB' readonly name='handover[formwork][$j][items]' value='{$items}'/>$items
             		  </td>
             		  <td><input type ='txt' id='recipientName$i' class='rimless_textB' readonly name='handover[formwork][$j][recipientName]' value='{$recipientName}' title='双击选择人员'>
             		  	  <input type ='hidden' id='recipientId$i' class='txt' readonly name='handover[formwork][$j][recipientId]' value='{$recipientId}'></td>
             		  <td></td><td></td><td><input id='isKey$i' type='checkbox' name='handover[formwork][$j][isKey]' $advanceAffirm[$j]/></td>
					  <td><input type ='txt' class='rimless_textB' name='handover[formwork][$j][sort]' style='width:20px' value='{$sort[$j]}'/></td>
             		  <td><input type ='checkbox' name='handover[formwork][$j][mailAffirm]' $mailAffirm[$j] /></td>
             		  <td><input type ='txt' class='rimless_textB' name='handover[formwork][$j][sendPremise]' style='width:50px' value='{$sendPremise[$j]}'/></td>
		</tr>";
         }
           return $list;
    }
 }
?>