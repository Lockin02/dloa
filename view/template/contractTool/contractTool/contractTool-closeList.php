<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="GBK"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>关闭合同</title>
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
            <input type="hidden" name="action" value="closeContract"/>
            合同编号：<input class="input_text search" id="contractCode" name="contractCode"
                        value="<?php echo $contractCode; ?>"/>
            合同名称：<input class="input_text search" id="contractName" name="contractName"
                        value="<?php echo $contractName; ?>"/>
            客户名称：<input class="input_text search" id="customerName" name="customerName"
                        value="<?php echo $customerName; ?>"/>
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
                <th id="contractDate_Col">合同建立时间</th>
                <th id="contractCode_Col">合同号</th>
                <th id="contractName_Col">合同名称</th>
                <th id="customerName_Col">客户名称</th>
                <th id="contractMoney_Col">合同金额</th>
                <th id="invoiceMoney_Col">已开票金额</th>
                <th id="incomeMoney_Col">已到款金额</th>
                <th id="outstockDate_Col">交付完成日期</th>
                <th id="signDate_Col">纸质合同回收日期</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($rows):
                $i = 0;
                foreach ($rows as $k => $v) {
                    $i++;
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td id="contractDate_Col<?php echo $k; ?>"><?php echo $v['ExaDTOne']; ?></td>
                        <td id="contractCode_Col<?php echo $k; ?>">
                            <?php echo
                                '<span style="cursor:pointer;color:#3399ff" title="点击查看源单" ' .
                                'onclick="showOpenWin(\'?model=contract_contract_contract&action=toViewTab&id=' . $v['id'] . '\')">' . $v['contractCode'] . '</span>';
                            ?>
                        </td>
                        <td id="contractName_Col<?php echo $k; ?>"><?php echo $v['contractName']; ?></td>
                        <td id="customerName_Col<?php echo $k; ?>"><?php echo $v['customerName']; ?></td>
                        <td id="contractMoney_Col<?php echo $k; ?>"><?php echo number_format($v['contractMoney']); ?></td>
                        <td id="invoiceMoney_Col<?php echo $k; ?>"><?php echo number_format($v['invoiceMoney']); ?></td>
                        <td id="incomeMoney_Col<?php echo $k; ?>"><?php echo number_format($v['incomeMoney']); ?></td>
                        <td id="outstockDate_Col<?php echo $k; ?>"><?php echo $v['outstockDate'] ?></td>
                        <td id="signDate_Col<?php echo $k; ?>"><?php echo $v['signDate'] ?></td>


                    </tr>
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
        $('#finishStatus').val($("#finishStatusVal").val());
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
            data: { "customCode": 'closeContract', "rows": customJson },
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
            customCode: 'closeContract',
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
		window.open("?model=contractTool_contractTool_contractTool&action=exportCloseContract&colName=" + colName
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