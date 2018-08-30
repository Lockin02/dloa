<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="GBK"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>��ͬ����</title>
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
        <input type="hidden" name="action" value="deliveryContract"/>
        ��ͬ��ţ�<input class="input_text search" id="contractCode" name="contractCode"
                    value="<?php echo $contractCode; ?>"/>
        ��ͬ���ƣ�<input class="input_text search" id="contractName" name="contractName"
                    value="<?php echo $contractName; ?>"/>
        �ͻ����ƣ�<input class="input_text search" id="customerName" name="customerName"
                    value="<?php echo $customerName; ?>"/>
        <input type="hidden" id="stateVal" value="<?php echo $state; ?>"/>
        ��ͬ״̬ :<select class="input_select search" id="state" name="state">
            <option value="2,4">����</option>
            <option value="2">ִ����</option>
            <option value="4">�����</option>
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
                <th id="contractCode_Col">��ͬ��</th>
                <th id="contractName_Col">��ͬ����</th>
                <th id="ExaDTOne_Col">��ͬ����ʱ��</th>
                <th id="deliveryDate_Col">������������</th>
                <th id="standardDate_Col">��׼��������</th>
                <th id="shipPlanDateCol">Ԥ�ƽ�������</th>
                <th id="isExceed_Col">�Ƿ񰴼ƻ�����</th>
                <!--							<th id="planReason_Col">ԭ��</th>-->
                <!--							<th id="planConfirm_Col">��ͬ������ȷ��</th>-->
                <!--	<th id="outGoodsRemind_Col">����δ��������</th> -->
                <th id="shipDateCol">ʵ�ʷ�������</th>
                <th id="outGoodsReason_Col">���ڷ���ԭ��</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($rows):
                $i = 0;
                foreach ($rows as $k => $v) {
                    $i++;
                    if ($v['isExceed'] == "��") {
                        ?>
                        <tr>
                            <td style="color:#FF4040"><?php echo $i; ?></td>
                            <td id="contractCode_Col<?php echo $k; ?>">
                                <?php echo
                                    '<span style="color:#FF4040;cursor:pointer;" title="����鿴Դ��" ' .
                                    'onclick="showModalWin(\'?model=contractTool_contractTool_contractTool&action=viewContract&id=' . $v['id'] . '\')">' . $v['contractCode'] . '</span>';
                                ?>
                            </td>
                            <td style="color:#FF4040"
                                id="contractName_Col<?php echo $k; ?>"><?php echo $v['contractName']; ?></td>
                            <td style="color:#FF4040"
                                id="ExaDTOne_Col<?php echo $k; ?>"><?php if ($v['ExaDTOne'] != '0000-00-00') {
                                    echo $v['ExaDTOne'];
                                }?></td>
                            <td style="color:#FF4040"
                                id="deliveryDate_Col<?php echo $k; ?>"><?php if ($v['deliveryDate'] != '0000-00-00') {
                                    echo $v['deliveryDate'];
                                }?></td>
                            <td style="color:#FF4040"
                                id="standardDate_Col<?php echo $k; ?>"><?php if ($v['standardDate'] != '0000-00-00') {
                                    echo $v['standardDate'];
                                }?></td>
                            <td style="color:#FF4040" id="shipPlanDate_Col<?php echo $k; ?>">
                                <?php
                                if ($v['outplanRow']) {
                                    foreach ($v['outplanRow'] as $key => $val) {
                                        echo '<span style="color:#FF4040;cursor:pointer;" title="����鿴Դ��" ' .
                                            'onclick="showModalWin(\'?model=stock_outplan_outplan&action=toView&id=' . $val['id'] . '&docType=oa_contract_contract\')">' . $val['planCode'] . '</span>:<br>(' . date("Y/m/d", (strtotime($val['shipPlanDate']))) . ')<br>';
                                    }
                                }
                                if ($v['esmprojectRow']) {
                                    foreach ($v['esmprojectRow'] as $key => $val) {
                                        echo '<span style="color:#FF4040;cursor:pointer;" title="����鿴Դ��" ' .
                                            'onclick="esmView(' . $val['id'] . ');">' . $val['projectCode'] . '</span>:<br>(' . date("Y/m/d", (strtotime($val['planBeginDate']))) . '~' . date("Y/m/d", (strtotime($val['planEndDate']))) . ')<br>';
                                    }
                                }
                                ?></td>
                            <td style="color:#FF4040"
                                id="isExceed_Col<?php echo $k; ?>"><?php echo $v['isExceed']; ?></td>
                            <td style="color:#FF4040" id="shipDate_Col<?php echo $k; ?>"><?php
                                if (empty($v['outstockDate']) || $v['outstockDate'] == '0000-00-00') {
                                    echo "-";
                                } else {
                                    echo $v['outstockDate'];
                                }
                                ?></td>
                            <td style="color:#FF4040"
                                id="outGoodsReason_Col<?php echo $k; ?>"><?php echo $v['delayDetail']; ?></td>
                        </tr>
                    <?php
                    } else {
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td id="contractCode_Col<?php echo $k; ?>">
                                <?php echo
                                    '<span style="cursor:pointer;color:#3399ff;" title="����鿴Դ��" ' .
                                    'onclick="showModalWin(\'?model=contractTool_contractTool_contractTool&action=viewContract&id=' . $v['id'] . '\')">' . $v['contractCode'] . '</span>';
                                ?>
                            </td>
                            <td id="contractName_Col<?php echo $k; ?>"><?php echo $v['contractName']; ?></td>
                            <td id="ExaDTOne_Col<?php echo $k; ?>"><?php if ($v['ExaDTOne'] != '0000-00-00') {
                                    echo $v['ExaDTOne'];
                                }?></td>
                            <td id="deliveryDate_Col<?php echo $k; ?>"><?php if ($v['deliveryDate'] != '0000-00-00') {
                                    echo $v['deliveryDate'];
                                }?></td>
                            <td id="standardDate_Col<?php echo $k; ?>"><?php if ($v['standardDate'] != '0000-00-00') {
                                    echo $v['standardDate'];
                                }?></td>
                            <td id="shipPlanDate_Col<?php echo $k; ?>">
                                <?php
                                if ($v['outplanRow']) {
                                    foreach ($v['outplanRow'] as $key => $val) {
                                        echo '<span style="cursor:pointer;color:#3399ff;" title="����鿴Դ��" ' .
                                            'onclick="showModalWin(\'?model=stock_outplan_outplan&action=toView&id=' . $val['id'] . '&docType=oa_contract_contract\')">' . $val['planCode'] . '</span>:<br>(' . date("Y/m/d", (strtotime($val['shipPlanDate']))) . ')<br>';
                                    }
                                }
                                if ($v['esmprojectRow']) {
                                    foreach ($v['esmprojectRow'] as $key => $val) {
                                        echo '<span style="cursor:pointer;color:#3399ff;" title="����鿴Դ��" ' .
                                            'onclick="esmView(' . $val['id'] . ');">' . $val['projectCode'] . '</span>:<br>(' . date("Y/m/d", (strtotime($val['planBeginDate']))) . '~' . date("Y/m/d", (strtotime($val['planEndDate']))) . ')<br>';
                                    }
                                }
                                ?></td>
                            <td id="isExceed_Col<?php echo $k; ?>"><?php echo $v['isExceed']; ?></td>
                            <td id="shipDate_Col<?php echo $k; ?>"><?php
                                if (empty($v['outstockDate']) || $v['outstockDate'] == '0000-00-00') {
                                    echo "-";
                                } else {
                                    echo $v['outstockDate'];
                                }
                                ?></td>
                            <td id="outGoodsReason_Col<?php echo $k; ?>"><?php echo $v['delayDetail']; ?></td>
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
<script>
    $(function () {
        $('#state').val($("#stateVal").val());
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
            data: { "customCode": 'deliveryListCode', "rows": customJson },
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
            customCode: 'deliveryListCode',
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
    function esmView(id) {
        var skey = "";
        $.ajax({
            type: "POST",
            url: "?model=engineering_project_esmproject&action=md5RowAjax",
            data: { "id": id },
            async: false,
            success: function (data) {
                skey = data;
            }
        });
        showModalWin("?model=engineering_project_esmproject&action=viewTab&id=" + id + "&skey=" + skey);
    }
    //����
    function exportExcel(){
		var colName = "";
		$(".stickyheader").children("thead").children("tr")
		.children("th").each(function() {
			if ($(this).css("display") != "none" && $(this).html() != "���") {
				colName += $(this).html() + ",";
			}
		})
		window.open("?model=contractTool_contractTool_contractTool&action=exportDeliveryContract&colName=" + colName
						+ "&state=" + $("#state").val()
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