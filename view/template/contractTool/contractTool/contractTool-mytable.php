<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="GBK" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>��ҳ</title>
		<meta name="description" content="Sticky Table Headers Revisited: Creating functional and flexible sticky table headers" />
		<meta name="keywords" content="Sticky Table Headers Revisited" />
		<meta name="author" content="Codrops" />
		<link rel="stylesheet" type="text/css" href="css/newstyle/normalize.css" />
		<link rel="stylesheet" type="text/css" href="css/newstyle/demo.css" />
		<link rel="stylesheet" type="text/css" href="css/newstyle/component.css" />
		<!--[if IE]>
  		<script src="../js/html5.js"></script>
		<![endif)-->
		<style>
			.box_nav {
				line-height: 10em;
				margin: 0 auto;
				padding: 0;
				width: 96%;
				max-width: 1200px;
				overflow: hidden;
			}
			.box_table td,.box_table th {
				align: center;
				padding: 0;
				align : middle;
			}
			.box {
				background-color: #6699CC;
				margin: 5px 2px 5px 2px;
				padding: 2px;
				float: left;
				-webkit-border-radius: 1px;
				-moz-border-radius: 1px;
				border-radius: 1px;
			    text-align:left;
    			color: #fff;
				font-size: 1.8em;
				text-align:left;
				width : 95%;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="box_nav" align="center">
				<table class="box_table">
					<tr>
						<td rowspan="2" width="20%">
							<div class="box" style="height:280px;font-size:150%;">
								&nbsp;�������ͬ
								<?php if($build!='true'){echo '(***)';}
									  else{
								?>
								<span style="color:#2ECCFA;cursor:pointer;"
								title="����鿴Դ��"
								onclick="conRrl(null,'0');"><?php echo "(".$numArr['build'].")";}?></span>
								>>
							</div>
						</td>
						<td width="20%">
							<div class="box" style="font-size:150%;">
								&nbsp;��������ͬ
								<?php if($delivery!='true'){echo '(***)';}
									  else{
								?>
								<span style="color:#2ECCFA;cursor:pointer;"
								title="����鿴Դ��"
								onclick="deliveryRrl(null,2);"><?php echo "(".$numArr['delivery'].")";}?></span>
								>>
							</div>
						</td>
						<td width="20%">
							<div class="box" style="font-size:150%;">
								&nbsp;�����պ�ͬ
								<?php if($waiting!='true'){echo '(***)';}
									  else{
								?>
								<span style="color:#2ECCFA;cursor:pointer;"
								title="����鿴Դ��"
								onclick="waitRrl('','1');"><?php echo "(".$numArr['wait'].")";}?></span>
								>>
							</div>
						</td>
						<td width="20%">
							<div class="box" style="font-size:150%;">
								&nbsp;���տ��ͬ
								<?php if($invoice!='true'){echo '(***)';}
									  else{
								?>
								<span style="color:#2ECCFA;cursor:pointer;"
								title="����鿴Դ��"
								onclick="invoiceRrl(null,'0');"><?php echo "(".$numArr['invoice'].")";}?></span>
								>>
							</div>
						</td>
						<td width="20%" rowspan="2">
							<div class="box" style="height:280px;font-size:150%;">
								&nbsp;�ѹرպ�ͬ
								<?php if($close!='true'){echo '(***)';}
									  else{
								?>
								<span style="color:#2ECCFA;cursor:pointer;"
								title="����鿴Դ��"
								onclick="closeRrl();"><?php echo "(".$numArr['close'].")";}?></span>
								>>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<div class="box" style="width:98.2%;font-size:150%;">
								&nbsp;���鵵��ͬ
								<?php if($archive!='true'){echo '(***)';}
									  else{
								?>
								<span style="color:#2ECCFA;cursor:pointer;"
								title="����鿴Դ��"
								onclick="archiveRrl();"><?php echo "(".$numArr['text'].")";}?></span>
								>>
							</div>
						</td>
					</tr>
				</table>
			</div>
		</div><!-- /container -->
		<div class="container">
			<div class="component">
				<table class="stickyheader">
					<thead>
						<tr>
							<th  colspan="3">���º�ͬ��Ϣͳ��</th>
							<th  colspan="3">ȫ���ͬ��Ϣͳ��</th>
						</tr>
					</thead>
					<tbody>
						<tr <?php if($build!='true'){echo "style='display:none;'";}?>>
						    <td class="user-name" colspan="3">�����ۼ���ǩ��ͬ�� �� <span style="color:blue;cursor:pointer;"  onclick="conRrl('month');"><?php echo $numArr['thisMonth']?></span></td>
						    <td class="user-name" colspan="3">ȫ���ۼ���ǩ��ͬ�� �� <span style="color:blue;cursor:pointer;"  onclick="conRrl('year');"><?php echo $numArr['thisYear']?></span></td>
						</tr>
						<tr	<?php if($delivery!='true'){echo "style='display:none;'";}?>>
						    <td class="user-name">
						     ����  Ӧ������ͬ <span style="color:blue;cursor:pointer;" onclick="deliveryRrl('month');"><?php echo $numArr['shouldDeliveryM']?></span>��&nbsp;&nbsp;&nbsp;&nbsp;
						    </td><td class="user-name">
						     ����ɽ�����ͬ<span style="color:blue;cursor:pointer;" onclick="deliveryRrl('month',4);"><?php echo $numArr['hasDeliveryM']?></span>��&nbsp;&nbsp;&nbsp;&nbsp;
						     </td><td class="user-name">
						     δ��ɽ�����ͬ <span style="color:blue;cursor:pointer;" onclick="deliveryRrl('month',2);"><?php echo $numArr['noDeliveryM']?></span>��</td>
						    <td class="user-name">
						     ȫ��  Ӧ������ͬ <span style="color:blue;cursor:pointer;" onclick="deliveryRrl('year');"><?php echo $numArr['shouldDelivery']?></span>��&nbsp;&nbsp;&nbsp;&nbsp;
						    </td><td class="user-name">
						     ����ɽ�����ͬ<span style="color:blue;cursor:pointer;" onclick="deliveryRrl('year',4);"><?php echo $numArr['hasDelivery']?></span>��&nbsp;&nbsp;&nbsp;&nbsp;
						    </td><td class="user-name">
						     δ��ɽ�����ͬ <span style="color:blue;cursor:pointer;" onclick="deliveryRrl('year',2);"><?php echo $numArr['noDelivery']?></span>��</td>
						</tr>
						<tr <?php if($waiting!='true'){echo "style='display:none;'";}?>>
						    <td class="user-name">
						     ����  Ӧ���պ�ͬ <span style="color:blue;cursor:pointer;" onclick="waitRrl('month');"><?php echo $numArr['shouldcheckM']?></span>��&nbsp;&nbsp;&nbsp;&nbsp;
						    </td><td class="user-name">
						     ��������պ�ͬ<span style="color:blue;cursor:pointer;" onclick="waitRrl('month','������');"><?php echo $numArr['hasCheckM']?></span>��&nbsp;&nbsp;&nbsp;&nbsp;
						    </td><td class="user-name">
						     δ������պ�ͬ <span style="color:blue;cursor:pointer;" onclick="waitRrl('month','δ����');"><?php echo $numArr['noCheckM']?></span>��</td>
						    <td class="user-name">
						     ȫ��  Ӧ���պ�ͬ <span style="color:blue;cursor:pointer;" onclick="waitRrl('year');"><?php echo $numArr['shouldcheck']?></span>��&nbsp;&nbsp;&nbsp;&nbsp;
						    </td><td class="user-name">
						     ��������պ�ͬ<span style="color:blue;cursor:pointer;" onclick="waitRrl('year','������');"><?php echo $numArr['hasCheck']?></span>��&nbsp;&nbsp;&nbsp;&nbsp;
						    </td><td class="user-name">
						     δ������պ�ͬ <span style="color:blue;cursor:pointer;" onclick="waitRrl('year','δ����');"><?php echo $numArr['noCheck']?></span>��</td>
						</tr>
						<tr <?php if($invoice!='true'){echo "style='display:none;'";}?>>
						    <td class="user-name">
						     ����  Ӧ�տ��ͬ <span style="color:blue;cursor:pointer;" onclick="invoiceRrl('month')"><?php echo $numArr['shouldPayM']?></span>��&nbsp;&nbsp;&nbsp;&nbsp;
						    </td><td class="user-name">
						     ������տ��ͬ<span style="color:blue;cursor:pointer;" onclick="invoiceRrl('month',1)"><?php echo $numArr['hasPayM']?></span>��&nbsp;&nbsp;&nbsp;&nbsp;
						    </td><td class="user-name">
						     δ����տ��ͬ <span style="color:blue;cursor:pointer;" onclick="invoiceRrl('month',0)"><?php echo $numArr['noPayM']?></span>��</td>
						    <td class="user-name">
						     ȫ��  Ӧ�տ��ͬ <span style="color:blue;cursor:pointer;" onclick="invoiceRrl('year')"><?php echo $numArr['shouldPayY']?></span>��&nbsp;&nbsp;&nbsp;&nbsp;
						    </td><td class="user-name">
						     ������տ��ͬ<span style="color:blue;cursor:pointer;" onclick="invoiceRrl('year',1)"><?php echo $numArr['hasPayY']?></span>��&nbsp;&nbsp;&nbsp;&nbsp;
						    </td><td class="user-name">
						     δ����տ��ͬ <span style="color:blue;cursor:pointer;" onclick="invoiceRrl('year',0)"><?php echo $numArr['noPayY']?></span>��</td>
						    <td class="user-name">
						</tr>
						<tr <?php if($archive!='true'){echo "style='display:none;'";}?>>
						    <td class="user-name">
						     ����  Ӧ�鵵��ͬ <span style="color:blue;cursor:pointer;" onclick="archiveRrl('month','0,1,2');"><?php echo $numArr['shouldSignM']?></span>��&nbsp;&nbsp;&nbsp;&nbsp;
						    </td><td class="user-name">
						     ����ɹ鵵��ͬ<span style="color:blue;cursor:pointer;" onclick="archiveRrl('month','1');"><?php echo $numArr['hasSignM']?></span>��&nbsp;&nbsp;&nbsp;&nbsp;
						    </td><td class="user-name">
						     δ��ɹ鵵��ͬ<span style="color:blue;cursor:pointer;" onclick="archiveRrl('month','0,2');"><?php echo $numArr['noSignM']?></span>��</td>
						    <td class="user-name">
						     ȫ��  Ӧ�鵵��ͬ <span style="color:blue;cursor:pointer;" onclick="archiveRrl('year','0,1,2');"><?php echo $numArr['shouldSignY']?></span>��&nbsp;&nbsp;&nbsp;&nbsp;
						    </td><td class="user-name">
						     ����ɹ鵵��ͬ<span style="color:blue;cursor:pointer;" onclick="archiveRrl('year','1');"><?php echo $numArr['hasSignY']?></span>��&nbsp;&nbsp;&nbsp;&nbsp;
						    </td><td class="user-name">
						     δ��ɹ鵵��ͬ<span style="color:blue;cursor:pointer;" onclick="archiveRrl('year','0,2');"><?php echo $numArr['noSignY']?></span>��</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div><!-- /container -->
<!--		<div class="container">
			<div class="component">
				<table class="stickyheader">
					<thead>
						<tr>
							<th colspan="3">��������</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td <?php if($waiting!='true'){echo "style='display:none;'";}?>>
								��ͬ�����գ�<span style="color:blue;cursor:pointer;" onclick="toWaitTask();"><?php echo $numArr['waitAcceptTask'];?></span>��</td>
							</td>
							<td <?php if($invoice!='true'){echo "style='display:none;'";}?>>
								��Ʊ�տ��ȷ�ϣ�<span style="color:blue;cursor:pointer;" onclick="toInvoiceTask();"><?php echo $numArr['invoiceContractTask'];;?></span>��</td>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
 -->
		</div>
		<script src="js/jquery/jquery-1.6.2.min.js"></script>
		<script src="js/jquery/jquery.ba-throttle-debounce.min.js"></script>
		<script src="js/jquery/jquery.stickyheader.js"></script>
		<!-- ������� -->
		<script type="text/javascript" src="js/common/businesspage.js"></script>
		<script type="text/javascript" src="view/template/contractTool/contractTool/js/contractTool-mytable.js"></script>
	</body>
</html>