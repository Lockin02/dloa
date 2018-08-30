<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="GBK" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>查看合同</title>
		<meta name="description" content="Sticky Table Headers Revisited: Creating functional and flexible sticky table headers" />
		<meta name="keywords" content="Sticky Table Headers Revisited" />
		<meta name="author" content="Codrops" />
		<link rel="stylesheet" type="text/css" href="css/newstyle/normalize.css" />
		<link rel="stylesheet" type="text/css" href="css/newstyle/demo.css" />
		<link rel="stylesheet" type="text/css" href="css/newstyle/view.css" />
		<!--[if IE]>
  		<script src="js/html5.js"></script>
		<![endif]-->
	</head>
	<body>
	<div class="newcomponent">
		<div class="newcomponent">
		<table>
			<tr class ="trtitle">
				<td colspan="4">
					基础信息
				</td>
			</tr>
			<tr class ="line">
				<td colspan="4">
					<div style="width:100%;height:1px; background:#E0E0E0;"></div>
				</td>
			</tr>
			<tr>
			    <td class="right">合同号 </td>
			    <td class = "form_text"><?php echo $rows['contractCode'];?></td>
			</tr>
			<tr>
			    <td class="right">合同名称 </td>
				<td class = "form_text"><?php echo $rows['contractName'];?></td>
			    <td class="right">客户名称 </td>
				<td class = "form_text"><?php echo $rows['customerName'];?></td>
			</tr>
			<tr>
				<td class="right">合同金额</td>
				<td class = "form_text"><?php echo $rows['contractMoney'];?></td>
				<td class="right">扣款/坏账</td>
				<td class = "form_text"><?php echo $rows['badMoney'];?></td>

			</tr>
			<tr>
				<td class="right">累计开票金额</td>
				<td class = "form_text"><?php echo $rows['invoiceMoney'];?></td>
				<td class="right">剩下开票金额</td>
				<td class = "form_text"><?php echo $rows['surplusInvoiceMoney'];?></td>
			</tr>
			<tr>
				<td class="right">累计到款金额</td>
				<td class = "form_text"><?php echo $rows['incomeMoney'];?></td>
				<td class="right">剩下到款金额</td>
				<td class = "form_text"><?php echo $rows['surincomeMoney'];?></td>
			</tr>
			<tr>
				<td class="right">发货状态</td>
				<td class = "form_text"><?php echo $rows['DeliveryStatusName'];?></td>
				<td class="right">总项目进度</td>
				<td class = "form_text"><?php echo $rows['projectProcessAll'];?> </td>
			</tr>
			<tr>
				<td class="right">合同负责人</td>
				<td class = "form_text"><?php echo $rows['prinvipalName'];?></td>
				<td class="right">归属区域</td>
				<td class = "form_text"><?php echo $rows['areaName'].'（'.$rows['areaPrincipal'].'）';?> </td>
			</tr>
		</table>
		</div>
		<div class="newcomponent">
		<table>
			<tr class="trtitle">
				<td colspan="8">
					项目信息
				</td>
			</tr>
			<tr>
				<td colspan="8">
					<div style="width:100%;height:1px; background:#E0E0E0;"></div>
				</td>
			<tr>
			<tr>
				<th>产品线</th>
				<th>项目编号</th>
				<th>项目经理</th>
				<th>项目状态</th>
				<th>预计/实际开始时间</th>
				<th>预计/实际结束时间</th>
				<th>项目进度</th>
				<th>决算进度</th>
			</tr>
			<?php
				if($rows['project']):
					foreach($rows['project'] as $k => $v){
		    ?>
			<tr>
				<td ><?php echo $rows['project'][$k]['productLineName'];?></td>
				<td ><?php echo $rows['project'][$k]['projectCode'];?></td>
				<td ><?php echo $rows['project'][$k]['managerName'];?></td>
				<td ><?php echo $rows['project'][$k]['statusName'];?></td>
				<td ><?php echo $rows['project'][$k]['planBeginDate'];?></td>
				<td ><?php echo $rows['project'][$k]['planEndDate'];?></td>
				<td ><?php echo $rows['project'][$k]['projectProcess'];?></td>
				<td ><?php echo $rows['project'][$k]['feeAllProcess'];?></td>
			</tr>
			<?php }else: ?>
			<tr>
				<td colspan="20" style="text-align:center;">-- 暂无查询数据 --</td>
			</tr>
			<?php endif;?>
		</table>
		</div>
		<div class="newcomponent">
		<table>
			<tr class="trtitle">
				<td colspan="6">
					发货信息
				</td>
			</tr>
			<tr>
				<td colspan="6">
					<div style="width:100%;height:1px; background:#E0E0E0;"></div>
				</td>
			<tr>
			<tr>
				<th>物料编号</th>
				<th>物料名称</th>
				<th>需求数量</th>
				<th>已执行数量</th>
				<th>已退库数量</th>
				<th>实际执行数量</th>
			</tr>
			<?php
				if($rows['equ']):
					foreach($rows['equ'] as $k => $v){
		    ?>
			<tr>
				<td ><?php echo $rows['equ'][$k]['productCode'];?></td>
				<td ><?php echo $rows['equ'][$k]['productName'];?></td>
				<td ><?php echo $rows['equ'][$k]['number'];?></td>
				<td ><?php echo $rows['equ'][$k]['executedNum'];?></td>
				<td ><?php echo $rows['equ'][$k]['backNum'];?></td>
				<td ><?php echo $rows['equ'][$k]['actNum'];?></td>
			</tr>
			<?php }else: ?>
			<tr>
				<td colspan="20" style="text-align:center;">-- 暂无查询数据 --</td>
			</tr>
			<?php endif;?>
			<tr>
			    <td style="color:#2E9AFE;font-weight:bold">发货完成日期 </td>
			    <td ><?php echo $rows['outstockDate'];?></td>
			</tr>
		</table>
		</div>
		<div class="newcomponent">
		<table>
			<tr class="trtitle">
				<td colspan ="6">
					验收信息
				</td>
			</tr>
			<tr>
				<td colspan="6">
					<div style="width:100%;height:1px; background:#E0E0E0;"></div>
				</td>
			<tr>
			<tr>
				<th>验收条款</th>
				<th>预计验收时间</th>
				<th>验收文档</th>
				<th>验收状态</th>
				<th>实际验收时间</th>
				<th>备注</th>
			</tr>
			<?php
				if($rows['checkAccept']):
					foreach($rows['checkAccept'] as $k => $v){
		    ?>
			<tr>
				<td ><?php echo $rows['checkAccept'][$k]['clause'];?></td>
				<td ><?php echo $rows['checkAccept'][$k]['checkDate'];?></td>
				<td ><?php echo $rows['checkAccept'][$k]['file'];?></td>
				<td ><?php echo $rows['checkAccept'][$k]['checkStatus'];?></td>
				<td ><?php echo $rows['checkAccept'][$k]['realCheckDate'];?></td>
				<td ><?php echo $rows['checkAccept'][$k]['reason'];?></td>
			</tr>
			<?php }else: ?>
			<tr>
				<td colspan="20" style="text-align:center;">-- 暂无查询数据 --</td>
			</tr>
			<?php endif;?>
			<tr>
			    <td style="color:#2E9AFE;font-weight:bold" colspan="6">详细验收条款</td>
			</tr>
			<tr>
			   <td colspan="6"><?php foreach($rows['checkAccept'] as $k => $v){
			    	   if(!empty($v['clauseInfo']))
			    	    echo $v['clauseInfo']."<br/>";
			    }?></td>
			</tr>
		</table>
		<table>
			<tr class="trtitle">
				<td colspan="6">
					财务信息
				</td>
			</tr>
			<tr>
				<td colspan="7">
					<div style="width:100%;height:1px; background:#E0E0E0;"></div>
				</td>
			<tr>
			<tr>
				<th>付款条件</th>
				<th>付款百分比</th>
				<th>财务T日</th>
				<th>已开票金额</th>
				<th>剩余应开金额</th>
				<th>已到款金额</th>
				<th>剩余到款金额</th>
			</tr>
			<?php
				if($rows['receiptplan']):
					foreach($rows['receiptplan'] as $k => $v){
		    ?>
			<tr>
				<td ><?php echo $rows['receiptplan'][$k]['paymentterm'];?></td>
				<td ><?php echo $rows['receiptplan'][$k]['paymentPer'].'%';?></td>
				<td ><?php echo $rows['receiptplan'][$k]['Tday'];?></td>
				<td ><?php echo $rows['receiptplan'][$k]['invoiceMoneyHtml'];?></td>
				<td ><?php echo bcsub($rows['receiptplan'][$k]['money'], $rows['receiptplan'][$k]['invoiceMoney'],2);?></td>
				<td ><?php echo $rows['receiptplan'][$k]['incomMoneyHtml'];?></td>
				<td ><?php echo bcsub($rows['receiptplan'][$k]['money'], $rows['receiptplan'][$k]['incomMoney'],2);?></td>
			</tr>
			<?php }else: ?>
			<tr>
				<td colspan="20" style="text-align:center;">-- 暂无查询数据 --</td>
			</tr>
			<?php endif;?>
			<tr>
			    <td colspan="6" style="color:#2E9AFE;font-weight:bold">详细收款条款</td>
			</tr>
			<tr>
			    <td colspan="6"><?php foreach($rows['receiptplan'] as $k => $v){
			    	   if(!empty($v['paymenttermInfo']))
			    	    echo $v['paymenttermInfo']."<br/>";
			    }?></td>
			</tr>
		</table>
		</div>
		<div class="newcomponent">

		<table>
			<td colspan="20" style="text-align:center;"><input type="button" class="closebutton" value="关闭" onclick = "closeFun();"/></td>
		</table>
		</div>
		</div>
		<script src="js/jquery/jquery-1.6.2.min.js"></script>
		<script src="js/jquery/jquery.ba-throttle-debounce.min.js"></script>
		<script src="js/jquery/jquery.stickyheader.js"></script>
		<!-- 核心组件 -->
		<script type="text/javascript" src="js/common/businesspage.js"></script>
		<script type="text/javascript" src="js/jquery/woo.js"></script>
		<script type="text/javascript" src="js/jquery/component.js"></script>
		<script type="text/javascript" src="js/jquery/dump.js"></script>

		<!-- 弹窗组件 -->
		<script type="text/javascript" src="js/thickbox.js"></script>
	</body>
</html>