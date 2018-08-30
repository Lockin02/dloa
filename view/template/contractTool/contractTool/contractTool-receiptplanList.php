<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="GBK" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>筹建中的合同</title>
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
		<div class="container" >
			<div class="component">
			   <?php echo $page;?>
				<table  class="stickyheader">
					<thead>
						<tr>
						    <th>序号</th>
							<th>合同编号</th>
							<th>付款条件</th>
							<th>付款百分比</th>
							<th>财务T日</th>
							<th>付款金额</th>
							<th>到款金额</th>
							<th>扣款金额</th>
							<th>是否完成</th>
							<th>是否确认</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if($rows):
							     $i = 0;
								foreach($rows as $k => $v){
					                $i ++;
					    ?>
					    <?php
					    	if($v['isfinance'] == 1){
						?>
					    		<tr style="color:#B0B0B0">
					    		<td>-</td>
					    		<?php
					    	}else{
						?>
					    		<tr style="color:black">
					    		<td><?php echo $i;?></td>
					    		<?php
					    	}
					    ?>

									<td><?php echo $v['contractCode'];?></td>
									<td><?php echo $v['paymentterm'];?></td>
									<td><?php echo $v['paymentPer'].'%';?></td>
									<td><?php echo $v['Tday'];?></td>
									<td class="formatMoney"><?php echo $v['money'];?></td>
									<td class="formatMoney"><?php echo $v['incomMoney'];?></td>
									<td class="formatMoney"><?php echo $v['deductMoney'];?></td>
									<td><?php if($v['isDel']=='1'){
												echo '已删除';
											  }else if($v['isCom']=='0'){
											  	echo "未完成";
											  }else if($v['isCom']=='1'){
												echo "已完成";
											  }
									?></td>
									<td><?php if($v['Tday'] != '' && $v['Tday'] != '0000-00-00'){
								   	  	  		echo "已确认";
								   	  	  	  }else{
								   	  	  	  	echo "未确认";
								   	  	  	  }
									?></td>
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