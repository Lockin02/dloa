<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="GBK"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>��Ʊ/�����ͬ</title>
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
            ��ͬ��ţ�<input class="input_text search" id="contractCode" name="contractCode"
                        value="<?php echo $contractCode; ?>"/>
            ��ͬ���ƣ�<input class="input_text search" id="contractName" name="contractName"
                        value="<?php echo $contractName; ?>"/>
            �ͻ����ƣ�<input class="input_text search" id="customerName" name="customerName"
                        value="<?php echo $customerName; ?>"/>
            <input type="hidden" id="finishStatusVal" value="<?php echo $finishStatus; ?>"/>
            ���״̬ :<select class="input_select search" id="finishStatus" name="finishStatus">
                <option value="">����</option>
                <option value="0">δ���</option>
                <option value="1">�����</option>
            </select>
            <input type="submit" class="input_button" value="����"/>
            <input type="button" class="input_button" value="���" onclick="$('.search').val('');"/>
            <input type="submit" class="input_button" value="����" onclick="$('.search').val('');"/>
            <input type="button" class="input_button" value="����" onclick="exportExcel();"/>
        </form>
    </div>
    <div class="component">
        <?php echo $page; ?>
        <table class="stickyheader">
            <thead>
            <tr>
                <th width="4%">���</th>
                <th id="contractCode_Col">��ͬ��</th>
                <th id="contractName_Col">��ͬ����</th>
                <th id="contractMoney_Col">��ͬ���</th>
                <th id="paymentterm_Col">�տ�����</th>
                <th id="paymentPer_Col">����</th>
                <th id="money_Col">���</th>
                <th id="Tday_Col">T��ȷ��</th>
                <th id="actEndDate_Col">��ͬ���ʱ��</th>
                <th id="invoiceMoney_Col">��Ʊ���</th>
                <th id="incomMoney_Col">������</th>
                <th id="getInvoiceMoney_Col">ʣ��δ��Ʊ���</th>
                <th id="getMoney_Col">Ӧ���˿����</th>
                <th id="deductMoney_Col">�ۿ�</th>
                <th id="invoiceDate_Col">���һ�ο�Ʊ����</th>
                <th id="receiptDate_Col">���һ���տ�����</th>
                <th id="conType_Col">��ͬ�ı�</th>

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
                                id="contractCode_Col<?php echo $k; ?>"><?php echo '<span style="color:#FF4040;cursor:pointer;" title="����鿴Դ��" ' .
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
                            <td id="contractCode_Col<?php echo $k; ?>"><?php echo '<span style="cursor:pointer;color:#3399ff" title="����鿴Դ��" ' .
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
                    <td colspan="20" style="text-align:center;">-- ���޲�ѯ���� --</td>
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
<!-- ������� -->
<script type="text/javascript" src="js/common/businesspage.js"></script>
<script type="text/javascript" src="js/jquery/woo.js"></script>
<script type="text/javascript" src="js/jquery/component.js"></script>
<script type="text/javascript" src="js/jquery/dump.js"></script>

<!-- ������� -->
<script type="text/javascript" src="js/thickbox.js"></script>
<script>
    $(function () {
        $('#finishStatus').val($("#finishStatusVal").val());
        //��ʼ���Զ�����
        var customJson = {};//��ǰ�Զ�����
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

        var isLoad = false;//��̬������ݻ���
        //��ʼ���Զ�����
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

        //�ص�����
        $.scrolltotop({
            className: 'totop',
            offsetx: 30,
            offsety: 30
        });

        //�Զ�����
        $.scrolltotop({
            titleName: '�Զ�����',
            className: 'customgrid',
            offsetx: 30,
            offsety: 80,
            customGrid: true,
            customCode: 'invoiceListCode',
            notOpacity: true,
            customJson: customJson
        });
        //���ع�����
        $.scrolltotop({
            titleName: '���ع�����',
            className: 'customgrid2',
            offsetx: 30,
            offsety: 130,
            notOpacity: true,
            click: function () {
                if (parent.parent.openTab) {
                    var openTab = parent.parent.openTab;
                }
                openTab('index1.php?model=contractTool_contractTool_mytable&action=toMytable', '������');
            }
        });
    })
    //����
    function exportExcel(){
		var colName = "";
		$(".stickyheader").children("thead").children("tr")
		.children("th").each(function() {
			if ($(this).css("display") != "none" && $(this).html() != "���") {
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