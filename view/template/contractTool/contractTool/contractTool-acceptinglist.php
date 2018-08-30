<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="GBK" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>���պ�ͬ</title>
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
					��ͬ��ţ�<input class="input_text search" id="contractCode" name="contractCode" value="<?php echo $contractCode;?>"/>
					��ͬ���ƣ�<input class="input_text search" id="contractName" name="contractName" value="<?php echo $contractName;?>"/>
					�ͻ����ƣ�<input class="input_text search" id="customerName" name="customerName" value="<?php echo $customerName;?>"/>
					<input type="submit" class="input_button" value="����"/>
					<input type="button" class="input_button" value="���" onclick="$('.search').val('');"/>
				</form>
			</div>
		<div class="container" >
			<div class="component">
			    <?php echo $page;?>
				<table  class="stickyheader">
					<thead>
						<tr>
						    <th>���</th>
							<th>��ͬ����ʱ��</th>
							<th>��ͬ��</th>
							<th>�ͻ�����</th>
							<th>��ͬ���</th>
							<th>������������</th>
							<th>Ԥ��ֽ�ʺ�ͬ<br>��������</th>
							<th>��������</th>
							<th>�տ�����</th>
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
									 '<span style="color:#2ECCFA;cursor:pointer;" title="����鿴Դ��" ' .
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