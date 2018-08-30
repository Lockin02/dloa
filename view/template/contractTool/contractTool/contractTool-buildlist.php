<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="GBK"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>�ｨ�еĺ�ͬ</title>
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
<div class="searchArea">
    <form method="get">
        <input type="hidden" name="model" value="contractTool_contractTool_contractTool"/>
        <input type="hidden" name="action" value="buildContract"/>
        ��ͬ��ţ�<input class="input_text search" id="contractCode" name="contractCode"
                    value="<?php echo $contractCode; ?>"/>
        ��ͬ���ƣ�<input class="input_text search" id="contractName" name="contractName"
                    value="<?php echo $contractName; ?>"/>
        �ͻ����ƣ�<input class="input_text search" id="customerName" name="customerName"
                    value="<?php echo $customerName; ?>"/>
        ��˾��<input class="input_text search" id="formBelongName" name="formBelongName"
                    value="<?php echo "$formBelongName" ; ?>"/>
        <input type="hidden" id="finishStatusVal" value="<?php echo $finishStatus; ?>"/>
        ���״̬:<select class="input_select search" id="finishStatus" name="finishStatus">
            <option value="0">δ���</option>
            <option value="1">�����</option>
            <option value="3">����</option>
        </select>
        <input type="hidden" id="isIncomeVal" value="<?php echo $isIncome; ?>"/>
        �Ƿ�ؿ����:<select class="input_select search" id="isIncome" name="isIncome">
            <option value="all">����</option>
            <option value="0">δ���</option>
            <option value="1">�����</option>

        </select>
        <input type="submit" class="input_button" value="����"/>
        <input type="button" class="input_button" value="���" onclick="$('.search').val('');"/>
        <input type="submit" class="input_button" value="����" onclick="$('.search').val('');"/>
        <input type="button" class="input_button" value="����" onclick="exportExcel();"/>
    </form>
</div>
<div class="container">
    <div class="component">
        <?php echo $page; ?>
        <table class="stickyheader">
            <thead>
            <tr>
                <th width="4%">���</th>
                <th id="createTime_Col">��ͬ����ʱ��</th>
                <th id="contractCode_Col">��ͬ��</th>
                <th id="contractName_Col">��ͬ����</th>
                <th id="customer_Col">�ͻ�����</th>
                <th id="contractMoney_Col">��ͬ���</th>
                <th id="delDate_Col">������������</th>
                <th id="returnDate_Col">Ԥ��ֽ�ʺ�ͬ��������</th>
                <th id="accept_Col">��������</th>
                <th id="invoice_Col">�տ�����</th>
                <th id="Tday_Col">����T��</th>
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
                        <td id="createTime_Col<?php echo $k; ?>"><?php if ($v['ExaDTOne'] != '0000-00-00') {
                                echo $v['ExaDTOne'];
                            }?></td>
                        <td id="contractCode_Col<?php echo $k; ?>">
                            <?php echo
                                '<div style="cursor:pointer;;color:#3399ff;" title="����鿴Դ��" ' .
                                'onclick="showModalWin(\'?model=contractTool_contractTool_contractTool&action=viewContract&id=' . $v['id'] . '\')">' . $v['contractCode'] . '</span>';
                            ?>
                        </td>
                        <td id="contractName_Col<?php echo $k; ?>"><?php echo $v['contractName']; ?></td>
                        <td id="customer_Col<?php echo $k; ?>"><?php echo $v['customerName']; ?></td>
                        <td id="contractMoney_Col<?php echo $k; ?>"><?php echo number_format($v['contractMoney']); ?></td>
                        <td id="delDate_Col<?php echo $k; ?>"><?php if ($v['deliveryDate'] != '0000-00-00') {
                                echo $v['deliveryDate'];
                            }?></td>
                        <td id="returnDate_Col<?php echo $k; ?>"><?php if ($v['ExaDTOne'] && $v['ExaDTOne'] != '0000-00-00') {
                                echo date("Y-m-d", strtotime("+3 month", strtotime($v['ExaDTOne'])));
                            }
                            ?></td>
                        <td id="accept_Col<?php echo $k; ?>"><?php foreach ($v['checkaccept'] as $key => $val) {
                                echo '<span style="cursor:pointer;color:#3399ff;" title="����鿴Դ��" ' .
                                    'onclick="showModalWin(\'?model=contractTool_contractTool_contractTool&action=checkacceptList&id=' . $v['id'] . '\')">' . $val['clause'] . '<br></span>';
                            }
                            ?></td>
                        <td id="invoice_Col<?php echo $k; ?>"><?php foreach ($v['receiptplan'] as $key => $val) {
                                if ($val['isfinance'] == 0) {
                                    echo '<span style="cursor:pointer;color:#3399ff;" title="����鿴Դ��" ' .
                                        'onclick="showModalWin(\'?model=contractTool_contractTool_contractTool&action=receiptplanList&id=' . $v['id'] . '\')">' . $val['paymentterm'] . "(" . $val['paymentPer'] . "%):" . $val['money'] . '<br></span>';
                                }
                            }
                            ?></td>
                         <td id="Tday_Col<?php echo $k; ?>"><?php foreach ($v['receiptplan'] as $key => $val) {
                                if ($val['isfinance'] == 0) {
                                	echo $val['Tday']."<br/>";
                                }
                            }
                            ?></td>
                    </tr>
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
<script>
    $(function () {
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
            data: { "customCode": 'buildlistCode', "rows": customJson },
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
            customCode: 'buildlistCode',
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


        $('#finishStatus').val($("#finishStatusVal").val());
        $('#isIncome').val($("#isIncomeVal").val());
    });
    //����
    function exportExcel(){
		var colName = "";
		$(".stickyheader").children("thead").children("tr")
		.children("th").each(function() {
			if ($(this).css("display") != "none" && $(this).html() != "���") {
				colName += $(this).html() + ",";
			}
		})
		window.open("?model=contractTool_contractTool_contractTool&action=exportBuildContract&colName=" + colName
						+ "&finishStatus=" + $("#finishStatus").val()
                        + "&isIncome=" + $("#isIncome").val()
						+ "&contractCode=" + $("#contractCode").val()
						+ "&contractName=" + $("#contractName").val()
						+ "&customerName=" + $("#customerName").val()
					);
    }
</script>
<!-- ������� -->
<script type="text/javascript" src="js/common/businesspage.js"></script>
<script type="text/javascript" src="js/jquery/woo.js"></script>
<script type="text/javascript" src="js/jquery/component.js"></script>
<script type="text/javascript" src="js/jquery/dump.js"></script>

<!-- ������� -->
<script type="text/javascript" src="js/thickbox.js"></script>
<script src="js/jquery/jquery.ba-throttle-debounce.min.js"></script>
<script src="js/jquery/jquery.stickyheader.js"></script>

</body>
</html>