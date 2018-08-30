<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="GBK"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>工程项目界面</title>
		<link rel="stylesheet" type="text/css" href="css/newstyle/normalize.css"/>
		<link rel="stylesheet" type="text/css" href="css/newstyle/demo.css"/>
		<link rel="stylesheet" type="text/css" href="css/newstyle/component.css"/>
		<!--[if IE]>
  		<script src="js/html5.js"></script>
		<![endif]-->
	</head>
	<body style="overflow-x:hidden;overflow-y:auto">
		<div class="container">
			<div class="searchArea">
				<form method="get">
					<input type="hidden" name="model" value="engineering_project_esmproject"/>
					<input type="hidden" name="action" value="test"/>
					项目编号：<input class="input_text search" id="projectCodeSearch" name="projectCodeSearch" value="<?php echo $projectCodeSearch;?>"/>
					项目名称：<input class="input_text search" id="projectSearch" name="projectSearch" value="<?php echo $projectSearch;?>"/>
					项目经理：<input class="input_text search" id="managerName" name="managerName" value="<?php echo $managerName;?>"/>
					产 品 线：<select class="input_select search" id="productLine" name="productLine">
							<option value="">所有</option>
                            <?php echo $productLineArr;?>
						</select>
						<input type="submit" class="input_button" value="搜索"/>
						<input type="button" class="input_button" value="清空" onclick="$('.search').val('');"/>
					<hr/>
					所属区域：<input class="input_text search" id="officeName" name="officeName" value="<?php echo $officeName;?>"/>
					项目省份：<input class="input_text search" id="province" name="province" value="<?php echo $province;?>"/>
					项目状态：<select class="input_select search" id="status" name="status">
                            <option value="">所有</option>
							<?php echo $statusArr;?>
						</select>
				</form>
			</div>
			<div class="component">
				<?php echo $page;?>
				<table class="stickyheader" style="display:none;">
					<thead>
						<tr>
							<th width="4%">序号</th>
							<th id="projectCode_Col">项目编号</th>
							<th id="projectName_Col">项目名称</th>
							<th id="statusName_Col">项目状态</th>
							<th id="productLineName_Col">产品线</th>
							<th id="officeName_Col">所属区域</th>
							<th id="province_Col">省份</th>
							<th id="managerName_Col">项目经理</th>
							<th id="projectProcess_Col">项目进度</th>
							<th id="planBeginDate_Col">预计开始日期</th>
							<th id="planEndDate_Col">预计结束日期</th>
							<th id="actBeginDate_Col">实际开始日期</th>
							<th id="actEndDate_Col">实际结束日期</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if($rows):
								foreach($rows as $k => $v){ ?>
								<tr>
									<td><?php echo $k+1;?></td>
									<td id="projectCode_Col<?php echo $k;?>"><a href=""><?php echo $v['projectCode'];?></a></td>
									<td id="projectName_Col<?php echo $k;?>"><?php echo $v['projectName'];?></td>
									<td id="statusName_Col<?php echo $k;?>"><?php echo $v['statusName'];?></td>
									<td id="productLineName_Col<?php echo $k;?>"><?php echo $v['productLineName'];?></td>
									<td id="officeName_Col<?php echo $k;?>"><?php echo $v['officeName'];?></td>
									<td id="province_Col<?php echo $k;?>"><?php echo $v['province'];?></td>
									<td id="managerName_Col<?php echo $k;?>"><?php echo $v['managerName'];?></td>
									<td id="projectProcess_Col<?php echo $k;?>"><?php echo $v['projectProcess'];?> %</td>
									<td id="planBeginDate_Col<?php echo $k;?>"><?php echo $v['planBeginDate'];?></td>
									<td id="planEndDate_Col<?php echo $k;?>"><?php echo $v['planEndDate'];?></td>
									<td id="actBeginDate_Col<?php echo $k;?>"><?php echo $v['actBeginDate'];?></td>
									<td id="actEndDate_Col<?php echo $k;?>"><?php echo $v['actEndDate'];?></td>
								</tr>
						<?php	}
							else: ?>
								<tr>
									<td colspan="20" style="text-align:center;">-- 暂无查询数据 --</td>
								</tr>
						<?php
							endif;?>
					</tbody>
				</table>
				<?php echo $page2;?>
			</div>
		</div><!-- /container -->
		<script src="js/jquery/jquery-1.6.2.min.js"></script>
        <script src="js/jquery/jquery.scrolltotop.js"></script>
        <script>
            $(function(){
                //初始化自定义表格
                var customJson = {};//当前自定义表格
                $("table.stickyheader th").each(function(){
                    if($(this).attr("id")){
                        customJson[$(this).attr("id")] = {
                            'colName' : $(this).attr("id"),
                            'colWidth' : $(this).width() == 0 ? 100 : $(this).width(),
                            'colShow': 1,
                            'colText':$(this).text()
                        };
                    }
                });

                var isLoad = false;//动态表格数据缓存
                //初始化自定义表格
                $.ajax({
                    type: "POST",
                    url: "?model=system_gridcustom_gridcustom&action=initCustomList",
                    data: { "customCode" : 'newesmproject' , "rows" : customJson },
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
                    customCode : 'newesmproject',
                    notOpacity : true,
                    customJson : customJson
                });
            });
        </script>
		<script src="js/common/businesspage.js"></script>
		<script src="js/jquery/jquery.ba-throttle-debounce.min.js"></script>
		<script src="js/jquery/jquery.stickyheader.js"></script>
	</body>
</html>