<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="GBK" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>�ｨ�еĺ�ͬ</title>
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
						    <th>���</th>
							<th>��ͬ���</th>
							<th>ʵ����Ŀ��������</th>
							<th>��������</th>
							<th>Ԥ������ʱ��</th>
							<th>ʵ������ʱ��</th>
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
									<td colspan="20" style="text-align:center;">-- ���޲�ѯ���� --</td>
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
		<!-- ������� -->
		<script type="text/javascript" src="js/common/businesspage.js"></script>
		<script type="text/javascript" src="js/jquery/woo.js"></script>
		<script type="text/javascript" src="js/jquery/component.js"></script>
		<script type="text/javascript" src="js/jquery/dump.js"></script>

		<!-- ������� -->
		<script type="text/javascript" src="js/thickbox.js"></script>
	</body>
</html>