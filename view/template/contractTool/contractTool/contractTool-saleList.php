<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="GBK" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>销售列表</title>
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
			<div class="searchArea">
				<form method="get">
					<input type="hidden" name="model" value="contractTool_contractTool_contractTool"/>
					<input type="hidden" name="action" value="saleList"/>
					合同编号：<input class="input_text search" id="contractCode" name="contractCode" value="<?php echo $contractCode;?>"/>
					合同名称：<input class="input_text search" id="contractName" name="contractName" value="<?php echo $contractName;?>"/>
					客户名称：<input class="input_text search" id="customerName" name="customerName" value="<?php echo $customerName;?>"/>
					<input type="submit" class="input_button" value="搜索"/>
					<input type="button" class="input_button" value="清空" onclick="$('.search').val('');"/>
					<input type="submit" class="input_button" value="重置" onclick="$('.search').val('');"/>
				</form>
			</div>
			<div class="component">
			<?php echo $page;?>
				<table  class="stickyheader">
					<thead>
						<tr>
						    <th width="4%">序号</th>
							<th id="contractCode_Col">合同号</th>
							<th id="contractName_Col">合同名称</th>
							<th id="customerName_Col">客户名称</th>
							<th id="stateName_Col">合同状态</th>
							<th id="deliveryDate_Col">预计交付日期</th>
							<th id="projectProcess_Col">交付状态</th>
							<th id="checkDate_Col">预计验收时间</th>
							<th id="checkStatus_Col">验收状态</th>
							<th id="surplusInvoiceMoney_Col">未开票开票金额</th>
							<th id="surincomeMoney_Col">未到款到款金额</th>
							<th id="changeTime_Col">预计归档日期</th>
							<th id="signStatus_Col">合同归档</th>
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
									<td id="contractCode_Col<?php echo $k;?>">
									  <?php echo
									 '<span style="color:#2ECCFA;cursor:pointer;" title="点击查看源单" ' .
									 'onclick="showModalWin(\'?model=contractTool_contractTool_contractTool&action=viewContract&id='.$v['id'].'\')">'.$v['contractCode'].'</span>';
                                      ?>
                                    </td>
                                    <td id="contractName_Col<?php echo $k;?>"><?php echo $v['contractName'];?></td>
									<td id="customerName_Col<?php echo $k;?>"><?php echo $v['customerName'];?></td>
									<td id="stateName_Col<?php echo $k;?>"><?php echo $v['stateName'];?></td>
									<td id="deliveryDate_Col<?php echo $k;?>"><?php if($v['deliveryDate']!='0000-00-00'){
												echo $v['deliveryDate'];
											  }?></td>
									<td id="projectProcess_Col<?php echo $k;?>"><?php
// 									foreach ($rows[$k]['view']['project'] as $key => $val){
// 										 echo "工程项目：".$val['projectCode']." 进度:".$val['projectProcess']."<br/>";
// 									 }
// 									foreach ($rows[$k]['view']['receiptplan'] as $key => $val){
// 										 echo "发货计划：".$val['projectCode']." 进度:".$val['projectProcess']."<br/>";
									 //}
									 ?>
									</td>
									<td id="checkDate_Col<?php echo $k;?>">
									<?php if($v['checkDate']!='0000-00-00'){
											echo $v['checkDate'];
										  }?></td>
									<td id="checkStatus_Col<?php echo $k;?>"><?php echo $v['checkStatus'];?></td>
									<td id="surplusInvoiceMoney_Col<?php echo $k;?>"><?php echo number_format($v['view']['surplusInvoiceMoney'],2);?></td>
									<td id="surincomeMoney_Col<?php echo $k;?>"><?php echo number_format($v['view']['surincomeMoney'],2);?></td>
									<td id="changeTime_Col<?php echo $k;?>"><?php echo $v['changeTime'];?></td>
									<td id="signStatus_Col<?php echo $k;?>"><?php  if($v['signStatus'] == 1){
										echo "已归档";
									}else{
										echo "未归档";
									}?></td>
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
		<script src="js/jquery/jquery.scrolltotop.js"></script>
		<!-- 核心组件 -->
		<script type="text/javascript" src="js/common/businesspage.js"></script>
		<script type="text/javascript" src="js/jquery/woo.js"></script>
		<script type="text/javascript" src="js/jquery/component.js"></script>
		<script type="text/javascript" src="js/jquery/dump.js"></script>

		<!-- 弹窗组件 -->
		<script type="text/javascript" src="js/thickbox.js"></script>
		<script>
			$(function(){
				//初始化自定义表格
                var customJson = {};//当前自定义表格
                $("table.stickyheader th").each(function(i){
                    if($(this).attr("id")){
                        customJson[$(this).attr("id")] = {
                            'colName' : $(this).attr("id"),
                            'colWidth' : $(this).width(),
                            'isShow':!$(this).is(":hidden"),
                            'colText':$(this).text()
                        };
                    }
                });

                var isLoad = false;//动态表格数据缓存
                //初始化自定义表格
                $.ajax({
                    type: "POST",
                    url: "?model=system_gridcustom_gridcustom&action=initCustomList",
                    data: { "customCode" : 'saleListCode' , "rows" : customJson },
                    async : false,
                    success : function(data){
                        if(data!='false'){
                            var rows = eval("(" + data + ")");
                            for(i in rows){
                                if(rows[i].isShow == "1"){
                                    $("#"+ i + "_Col").show().width(rows[i].colWidth);
                                    $("table.stickyheader td[id^='"+i+"']").show();
                                }else{
                                    $("#"+ i + "_Col").hide();
                                    $("table.stickyheader td[id^='"+i+"']").hide();
                                }
                            }
                        }
                        isLoad = true;
                    }
                });
                if(isLoad == true)  $("table.stickyheader").show();

                //回到顶部
                $.scrolltotop({
                    className: 'totop',
                    offsetx : 30,
                    offsety : 30
                });

                //自定义表格
                $.scrolltotop({
                    titleName : '自定义表格',
                    className: 'customgrid',
                    offsetx : 30,
                    offsety : 80,
                    customGrid : true,
                    customCode : 'saleListCode',
                    notOpacity : true,
                    customJson : customJson
               });
			})
	</script>
	</body>
</html>