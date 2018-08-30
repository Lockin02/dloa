<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="GBK"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>开票/到款合同</title>
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
            <input type="hidden" name="action" value="invoiceContract"/>
            合同编号：<input class="input_text search" id="contractCode" name="contractCode"
                        value="<?php echo $contractCode; ?>"/>
            合同名称：<input class="input_text search" id="contractName" name="contractName"
                        value="<?php echo $contractName; ?>"/>
            客户名称：<input class="input_text search" id="customerName" name="customerName"
                        value="<?php echo $customerName; ?>"/>
            <input type="hidden" id="finishStatusVal" value="<?php echo $finishStatus; ?>"/>
            完成状态 :<select class="input_select search" id="finishStatus" name="finishStatus">
                <option value="">所有</option>
                <option value="0">未完成</option>
                <option value="1">已完成</option>
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
                <th id="contractMoney_Col">合同金额</th>
                <th id="paymentterm_Col">收款条款</th>
                <th id="paymentPer_Col">比例</th>
                <th id="money_Col">金额</th>
                <th id="Tday_Col">T日确认</th>
                <th id="actEndDate_Col">合同完成时间</th>
                <th id="invoiceMoney_Col">开票金额</th>
                <th id="incomMoney_Col">到款金额</th>
                <th id="getInvoiceMoney_Col">剩余未开票金额</th>
                <th id="getMoney_Col">应收账款余额</th>
                <th id="deductMoney_Col">扣款</th>
                <th id="invoiceDate_Col">最近一次开票日期</th>
                <th id="receiptDate_Col">最近一次收款日期</th>
                <th id="conType_Col">合同文本</th>

            </tr>
            </thead>
            <tbody>
            <?php
            if ($rows):
                $i = 0;
                foreach ($rows as $k => $v) {
                    $i++;
                    if ($v['isFinishMoney'] == 0 && $v['isMoneyOutDate'] == 1) {
                        ?>
                        <tr>
                            <td style="color:#FF4040"><?php echo $i; ?></td>
                            <td style="color:#FF4040"
                                id="contractCode_Col<?php echo $k; ?>"><?php echo '<span style="color:#FF4040;cursor:pointer;" title="点击查看源单" ' .
                                    'onclick="showOpenWin(\'?model=contract_contract_contract&action=toViewTab&id=' . $v['contractId'] . '\')">' . $v['contractCode'] . '</span>';?></td>
                            <td style="color:#FF4040"
                                id="contractName_Col<?php echo $k; ?>"><?php echo $v['contractName'] ?></td>
                            <td style="color:#FF4040"
                                id="contractMoney_Col<?php echo $k; ?>"><?php echo number_format($v['contractMoney']) ?></td>
                            <td style="color:#FF4040"
                                id="paymentterm_Col<?php echo $k; ?>"><?php echo $v['paymentterm']; ?></td>
                            <td style="color:#FF4040"
                                id="paymentPer_Col<?php echo $k; ?>"><?php echo $v['paymentPer'] . "%"; ?></td>
                            <td style="color:#FF4040"
                                id="money_Col<?php echo $k; ?>"><?php echo $v['money']; ?></td>
                            <td style="color:#FF4040" id="Tday_Col<?php echo $k; ?>"><?php echo $v['Tday']; ?></td>
                            <td style="color:#FF4040" id="actEndDate_Col<?php echo $k; ?>"><?php echo $v['actEndDate']; ?></td>
                            <td style="color:#FF4040"
                                id="invoiceMoney_Col<?php echo $k; ?>"><?php echo number_format($v['invoiceMoney']); ?></td>
                            <td style="color:#FF4040"
                                id="incomMoney_Col<?php echo $k; ?>"><?php echo number_format($v['incomMoney']); ?></td>
                            <td style="color:#FF4040"
                                id="getInvoiceMoney_Col<?php echo $k; ?>"><?php $getInvoiceMoney = $v['money'] - $v['invoiceMoney'];
                                echo number_format($getInvoiceMoney);
                                ?></td>
                            <td style="color:#FF4040"
                                id="getMoney_Col<?php echo $k; ?>"><?php $getMoney = $v['money'] - $v['incomMoney'];
                                echo number_format($getMoney);
                                ?></td>
                            <td style="color:#FF4040"
                                id="deductMoney_Col<?php echo $k; ?>"><?php echo number_format($v['deductMoney']); ?></td>
                            <td style="color:#FF4040"
                                id="invoiceDate_Col<?php echo $k; ?>"><?php echo $v['invoiceDate']; ?></td>
                            <td style="color:#FF4040"
                                id="receiptDate_Col<?php echo $k; ?>"><?php echo substr($v['receiptDate'], 0, 10); ?></td>
                            <td style="color:#FF4040"
                                id="conType_Col<?php echo $k; ?>"><?php echo substr($v['conType'], 0, 10); ?></td>
                        </tr>
                    <?php
                    } else {
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td id="contractCode_Col<?php echo $k; ?>"><?php echo '<span style="cursor:pointer;color:#3399ff" title="点击查看源单" ' .
                                    'onclick="showOpenWin(\'?model=contract_contract_contract&action=toViewTab&id=' . $v['contractId'] . '\')">' . $v['contractCode'] . '</span>';?></td>
                            <td id="contractName_Col<?php echo $k; ?>"><?php echo $v['contractName']; ?></td>
                            <td id="contractMoney_Col<?php echo $k; ?>"><?php echo number_format($v['contractMoney']) ?></td>
                            <td id="paymentterm_Col<?php echo $k; ?>"><?php echo $v['paymentterm']; ?></td>
                            <td id="paymentPer_Col<?php echo $k; ?>"><?php echo $v['paymentPer'] . "%"; ?></td>
                            <td id="money_Col<?php echo $k; ?>"><?php echo number_format($v['contractMoney'] * $v['paymentPer'] / 100); ?></td>
                            <td id="Tday_Col<?php echo $k; ?>"><?php echo $v['Tday']; ?></td>
                            <td id="actEndDate_Col<?php echo $k; ?>"><?php echo $v['actEndDate']; ?></td>
                            <td id="invoiceMoney_Col<?php echo $k; ?>"><?php echo number_format($v['invoiceMoney']); ?></td>
                            <td id="incomMoney_Col<?php echo $k; ?>"><?php echo number_format($v['incomMoney']); ?></td>
                            <td id="getInvoiceMoney_Col<?php echo $k; ?>"><?php $getInvoiceMoney = ($v['contractMoney'] * $v['paymentPer'] / 100) - $v['invoiceMoney'];
                                echo number_format($getInvoiceMoney);
                                ?></td>
                            <td id="getMoney_Col<?php echo $k; ?>"><?php $getMoney = ($v['contractMoney'] * $v['paymentPer'] / 100) - $v['incomMoney'];
                                echo number_format($getMoney);
                                ?></td>
                            <td id="deductMoney_Col<?php echo $k; ?>"><?php echo number_format($v['deductMoney']); ?></td>
                            <td id="invoiceDate_Col<?php echo $k; ?>"><?php echo $v['invoiceDate']; ?></td>
                            <td id="receiptDate_Col<?php echo $k; ?>"><?php echo substr($v['receiptDate'], 0, 10); ?></td>
                            <td id="conType_Col<?php echo $k; ?>"><?php echo substr($v['conType'], 0, 10); ?></td>
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
            data: { "customCode": 'invoiceListCode', "rows": customJson },
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
            customCode: 'invoiceListCode',
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
		window.open("?model=contractTool_contractTool_contractTool&action=exportInvoiceContract&colName=" + colName
					+ "&finishStatus=" + $("#finishStatus").val()
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