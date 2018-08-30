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
							<th>实际项目结束日期</th>
							<th>验收条款</th>
							<th>预计验收时间</th>
							<th>实际验收时间</th>
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
									<td><?php echo $v['contractCode'];?></td>
									<td><?php echo$v['realEndDate'];?></td>
									<td><?php echo $v['clause'];?></td>
									<td><?php if($v['checkDate']!='0000-00-00'){
												echo $v['checkDate'];
											  }?></td>
									<td><?php echo $v['realCheckDate'];?></td>
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