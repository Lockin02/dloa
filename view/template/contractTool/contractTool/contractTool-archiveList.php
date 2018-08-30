<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="GBK"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>待归档合同</title>
    <meta name="description"
          content="Sticky Table Headers Revisited: Creating functional and flexible sticky table headers"/>
    <meta name="keywords" content="Sticky Table Headers Revisited"/>
    <meta name="author" content="Codrops"/>
    <link rel="stylesheet" type="text/css" href="css/newstyle/normalize.css"/>
    <link rel="stylesheet" type="text/css" href="css/newstyle/demo.css"/>
    <link rel="stylesheet" type="text/css" href="css/newstyle/component.css"/>
    <!--[if IE]>
    <script src="js/html5.js"></script>
    <![endif]-->
</head>
<body>
<div class="container">
    <div class="searchArea">
        <form method="get">
            <input type="hidden" name="model" value="contractTool_contractTool_contractTool"/>
            <input type="hidden" name="action" value="contractArchive"/>
            合同编号：<input class="input_text search" id="contractCode" name="contractCode"
                        value="<?php echo $contractCode; ?>"/>
            合同名称：<input class="input_text search" id="contractName" name="contractName"
                        value="<?php echo $contractName; ?>"/>
            客户名称：<input class="input_text search" id="customerName" name="customerName"
                        value="<?php echo $customerName; ?>"/>
            <input type="hidden" id="signStatusArrVal" value="<?php echo $signStatusArr; ?>"/>
            归档状态:<select class="input_select search" id="signStatusArr" name="signStatusArr">
                <option value="">所有</option>
                <option value="0,2">未归档</option>
                <option value="1">已归档</option>
            </select>
            <input type="submit" class="input_button" value="搜索"/>
            <input type="button" class="input_button" value="清空" onclick="$('.search').val('');"/>
            <input type="submit" class="input_button" value="重置" onclick="$('.search').val('');"/>
            <input type="button" class="input_button" value="导出" onclick="exportExcel();"/>
        </form>
    </div>
    <div class="component">
        <?php echo $page; ?>
        <table class="stickyheader">
            <thead>
            <tr>
                <th width="4%">序号</th>
                <th id="contractCode_Col">合同号</th>
                <th id="contractName_Col">合同名称</th>
                <th id="ExaDTOne_Col">预计纸质合同回收日期</th>
                <!-- 							<th>纸质合同回收日期推送</th> -->
                <th id="isAcquiringDate_Col">纸质合同签收时间</th>
                <th id="changeTime_Col">归档时间</th>
                <th id="differentReason_Col">OA合同信息与纸质合同不一致原因</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($rows):
                $i = 0;
                foreach ($rows as $k => $v) {
                    $i++;
                    if ($v['isSigned'] == 0 && $v['isArchiveOutDate'] == 1) {
                        ?>
                        <tr>
                            <td style="color:#FF4040"><?php echo $i; ?></td>
                            <td id="contractCode_Col<?php echo $k; ?>">
                                <?php echo
                                    '<span style="color:#EF4040;cursor:pointer;" title="点击查看源单" ' .
                                    'onclick="showModalWin(\'?model=contractTool_contractTool_contractTool&action=viewContract&id=' . $v['id'] . '\')">' . $v['contractCode'] . '</span>';
                                ?>
                            </td>
                            <td style="color:#FF4040"
                                id="contractName_Col<?php echo $k; ?>"><?php echo $v['contractName']; ?></td>
                            <td style="color:#FF4040"
                                id="ExaDTOne_Col<?php echo $k; ?>"><?php if ($v['ExaDTOne'] != '0000-00-00' && $v['ExaDTOne']) {
                                    echo date("Y-m-d", strtotime("+3 month", strtotime($v['ExaDTOne'])));
                                }?></td>
                            <!--  	<td><?php echo $v['signPush']?></td>-->
                            <td style="color:#FF4040"
                                id="isAcquiringDate_Col<?php echo $k; ?>"><?php if ($v['isAcquiringDate'] != '0000-00-00') {
                                    echo $v['isAcquiringDate'];
                                }?></td>
                            <td style="color:#FF4040"
                                id="changeTime_Col<?php echo $k; ?>"><?php echo $v['changeTime']; ?></td>
                            <td style="color:#FF4040"
                                id="differentReason_Col<?php echo $k; ?>"><?php echo $v['differentReason'] ?></td>
                        </tr>
                    <?php
                    } else {
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td id="contractCode_Col<?php echo $k; ?>">
                                <?php echo
                                    '<span style="cursor:pointer;color:#3399ff" title="点击查看源单" ' .
                                    'onclick="showModalWin(\'?model=contractTool_contractTool_contractTool&action=viewContract&id=' . $v['id'] . '\')">' . $v['contractCode'] . '</span>';
                                ?>
                            </td>
                            <td id="contractName_Col<?php echo $k; ?>"><?php echo $v['contractName']; ?></td>
                            <td id="ExaDTOne_Col<?php echo $k; ?>"><?php if ($v['ExaDTOne'] != '0000-00-00' && $v['ExaDTOne']) {
                                    echo date("Y-m-d", strtotime("+3 month", strtotime($v['ExaDTOne'])));
                                }?></td>
                            <!--  	<td><?php echo $v['signPush']?></td>-->
                            <td id="isAcquiringDate_Col<?php echo $k; ?>"><?php if ($v['isAcquiringDate'] != '0000-00-00') {
                                    echo $v['isAcquiringDate'];
                                }?></td>
                            <td id="changeTime_Col<?php echo $k; ?>"><?php echo $v['changeTime']; ?></td>
                            <td id="differentReason_Col<?php echo $k; ?>"><?php echo $v['differentReason'] ?></td>
                        </tr>
                    <?php } ?>
                <?php
                }
            else: ?>
                <tr>
                    <td colspan="20" style="text-align:center;">-- 暂无查询数据 --</td>
                </tr>
            <?php
            endif; ?>
            </tbody>
        </table>
        <?php echo $page2; ?>
    </div>
</div>
<!-- /container -->
<script src="js/jquery/jquery-1.6.2.min.js"></script>
<script src="js/jquery/jquery.scrolltotop.js"></script>
<!-- 核心组件 -->
<script type="text/javascript" src="js/common/businesspage.js"></script>
<script type="text/javascript" src="js/jquery/woo.js"></script>
<script type="text/javascript" src="js/jquery/component.js"></script>
<script type="text/javascript" src="js/jquery/dump.js"></script>

<!-- 弹窗组件 -->
<script type="text/javascript" src="js/thickbox.js"></script>
<script>
    $(function () {
        $('#signStatusArr').val($("#signStatusArrVal").val());
        //初始化自定义表格
        var customJson = {};//当前自定义表格
        $("table.stickyheader th").each(function (i) {
            if ($(this).attr("id")) {
                customJson[$(this).attr("id")] = {
                    'colName': $(this).attr("id"),
                    'colWidth': $(this).width(),
                    'isShow': !$(this).is(":hidden"),
                    'colText': $(this).text()
                };
            }
        });

        var isLoad = false;//动态表格数据缓存
        //初始化自定义表格
        $.ajax({
            type: "POST",
            url: "?model=system_gridcustom_gridcustom&action=initCustomList",
            data: { "customCode": 'archiveListCode', "rows": customJson },
            async: false,
            success: function (data) {
                if (data != 'false') {
                    var rows = eval("(" + data + ")");
                    for (i in rows) {
                        if (rows[i].isShow == "1") {
                            $("#" + i + "_Col").show().width(rows[i].colWidth);
                            $("table.stickyheader td[id^='" + i + "']").show();
                        } else {
                            $("#" + i + "_Col").hide();
                            $("table.stickyheader td[id^='" + i + "']").hide();
                        }
                    }
                }
                isLoad = true;
            }
        });
        if (isLoad == true)  $("table.stickyheader").show();

        //回到顶部
        $.scrolltotop({
            className: 'totop',
            offsetx: 30,
            offsety: 30
        });

        //自定义表格
        $.scrolltotop({
            titleName: '自定义表格',
            className: 'customgrid',
            offsetx: 30,
            offsety: 80,
            customGrid: true,
            customCode: 'archiveListCode',
            notOpacity: true,
            customJson: customJson
        });
        //返回工作窗
        $.scrolltotop({
            titleName: '返回工作窗',
            className: 'customgrid2',
            offsetx: 30,
            offsety: 130,
            notOpacity: true,
            click: function () {
                if (parent.parent.openTab) {
                    var openTab = parent.parent.openTab;
                }
                openTab('index1.php?model=contractTool_contractTool_mytable&action=toMytable', '工作窗');
            }
        });
    })
    //导出
    function exportExcel(){
		var colName = "";
		$(".stickyheader").children("thead").children("tr")
		.children("th").each(function() {
			if ($(this).css("display") != "none" && $(this).html() != "序号") {
				colName += $(this).html() + ",";
			}
		})
		window.open("?model=contractTool_contractTool_contractTool&action=exportArchiveContract&colName=" + colName
					+ "&signStatusArr=" + $("#signStatusArr").val()
					+ "&contractCode=" + $("#contractCode").val()
					+ "&contractName=" + $("#contractName").val()
					+ "&customerName=" + $("#customerName").val()
					);
    }
</script>
<script src="js/jquery/jquery.ba-throttle-debounce.min.js"></script>
<script src="js/jquery/jquery.stickyheader.js"></script>
</body>
</html>