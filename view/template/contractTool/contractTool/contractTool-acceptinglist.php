<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="GBK" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>验收合同</title>
		<meta name="description" content="Sticky Table Headers Revisited: Creating functional and flexible sticky table headers" />
		<meta name="keywords" content="Sticky Table Headers Revisited" />
		<meta name="author" content="Codrops" />
		<link rel="stylesheet" type="text/css" href="css/newstyle/normalize.css" />
		<link rel="stylesheet" type="text/css" href="css/newstyle/demo.css" />
		<link rel="stylesheet" type="text/css" href="css/newstyle/component.css" />
		<!--[if IE]>
  		<script src="js/html5.js"></script>
		<![endif]-->
	</head>
	<body>
		<div class="searchArea">
				<form method="get">
					<input type="hidden" name="model" value="contractTool_contractTool_contractTool"/>
					<input type="hidden" name="action" value="buildContract"/>
					合同编号：<input class="input_text search" id="contractCode" name="contractCode" value="<?php echo $contractCode;?>"/>
					合同名称：<input class="input_text search" id="contractName" name="contractName" value="<?php echo $contractName;?>"/>
					客户名称：<input class="input_text search" id="customerName" name="customerName" value="<?php echo $customerName;?>"/>
					<input type="submit" class="input_button" value="搜索"/>
					<input type="button" class="input_button" value="清空" onclick="$('.search').val('');"/>
				</form>
			</div>
		<div class="container" >
			<div class="component">
			    <?php echo $page;?>
				<table  class="stickyheader">
					<thead>
						<tr>
						    <th>序号</th>
							<th>合同建立时间</th>
							<th>合同号</th>
							<th>客户名称</th>
							<th>合同金额</th>
							<th>期望交付日期</th>
							<th>预计纸质合同<br>回收日期</th>
							<th>验收条款</th>
							<th>收款条款</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if($rows):
							     $i = 0;
								foreach($rows as $k => $v){
					                $i ++;
					    ?>
								<tr>
								    <td><?php echo $i;?></td>
									<td><?php echo $v['ExaDTOne'];?></td>
									<td>
									  <?php echo
									 '<span style="color:#2ECCFA;cursor:pointer;" title="点击查看源单" ' .
									 'onclick="showOpenWin(\'?model=contract_contract_contract&action=toViewTab&id='.$v['id'].'\')">'.$v['contractCode'].'</span>';
                                      ?>
                                    </td>
									<td><?php echo $v['customerName'];?></td>
									<td><?php echo $v['contractMoney'];?></td>
									<td><?php echo $v['deliveryDate'];?></td>
									<td><?php echo date("Y-m-d",strtotime("+1 month",strtotime($v['ExaDTOne'])));?></td>
									<td><?php echo $v[''];?></td>
									<td><?php echo $v[''];?></td>
									<td></td>
								</tr>
						<?php	}
							else: ?>
								<tr>
									<td colspan="20" style="text-align:center;">-- 暂无查询数据 --</td>
								</tr>
						<?php
							endif; ?>
					</tbody>
				</table>
				<?php echo $page2;?>
			</div>
		</div><!-- /container -->
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