<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="GBK"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>������Ŀ����</title>
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
					��Ŀ��ţ�<input class="input_text search" id="projectCodeSearch" name="projectCodeSearch" value="<?php echo $projectCodeSearch;?>"/>
					��Ŀ���ƣ�<input class="input_text search" id="projectSearch" name="projectSearch" value="<?php echo $projectSearch;?>"/>
					��Ŀ����<input class="input_text search" id="managerName" name="managerName" value="<?php echo $managerName;?>"/>
					�� Ʒ �ߣ�<select class="input_select search" id="productLine" name="productLine">
							<option value="">����</option>
                            <?php echo $productLineArr;?>
						</select>
						<input type="submit" class="input_button" value="����"/>
						<input type="button" class="input_button" value="���" onclick="$('.search').val('');"/>
					<hr/>
					��������<input class="input_text search" id="officeName" name="officeName" value="<?php echo $officeName;?>"/>
					��Ŀʡ�ݣ�<input class="input_text search" id="province" name="province" value="<?php echo $province;?>"/>
					��Ŀ״̬��<select class="input_select search" id="status" name="status">
                            <option value="">����</option>
							<?php echo $statusArr;?>
						</select>
				</form>
			</div>
			<div class="component">
				<?php echo $page;?>
				<table class="stickyheader" style="display:none;">
					<thead>
						<tr>
							<th width="4%">���</th>
							<th id="projectCode_Col">��Ŀ���</th>
							<th id="projectName_Col">��Ŀ����</th>
							<th id="statusName_Col">��Ŀ״̬</th>
							<th id="productLineName_Col">��Ʒ��</th>
							<th id="officeName_Col">��������</th>
							<th id="province_Col">ʡ��</th>
							<th id="managerName_Col">��Ŀ����</th>
							<th id="projectProcess_Col">��Ŀ����</th>
							<th id="planBeginDate_Col">Ԥ�ƿ�ʼ����</th>
							<th id="planEndDate_Col">Ԥ�ƽ�������</th>
							<th id="actBeginDate_Col">ʵ�ʿ�ʼ����</th>
							<th id="actEndDate_Col">ʵ�ʽ�������</th>
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
									<td colspan="20" style="text-align:center;">-- ���޲�ѯ���� --</td>
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
                //��ʼ���Զ�����
                var customJson = {};//��ǰ�Զ�����
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

                var isLoad = false;//��̬������ݻ���
                //��ʼ���Զ�����
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

                //�ص�����
                $.scrolltotop({
                    className: 'totop',
                    offsetx : 30,
                    offsety : 30
                });

                //�Զ�����
                $.scrolltotop({
                    titleName : '�Զ�����',
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