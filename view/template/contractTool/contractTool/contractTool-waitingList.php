<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="GBK"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>验收合同</title>
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
            <input type="hidden" name="action" value="waitingAccept"/>
            合同编号：<input class="input_text search" id="contractCode" name="contractCode"
                        value="<?php echo $contractCode; ?>"/>
            合同名称：<input class="input_text search" id="contractName" name="contractName"
                        value="<?php echo $contractName; ?>"/>
            <!--		验收条款：<input class="input_text search" id="clause" name="clause" value="<?php echo $customerName;?>"/> -->
            <input type="hidden" id="confirmStatusVal" value="<?php echo $confirmStatus; ?>"/>
            确认状态:<select class="input_select search" id="confirmStatus" name="confirmStatus">
                <option value="">所有</option>
                <option value="未确认">未确认</option>
                <option value="已确认">已确认</option>
            </select>
            <input type="hidden" id="checkStatusVal" value="<?php echo $checkStatus; ?>"/>
            验收状态:<select class="input_select search" id="checkStatus" name="checkStatus">
                <option value="">所有</option>
                <option value="未验收">未验收</option>
                <option value="已验收">已验收</option>
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
                <th id="realEndDate_Col">合同完成时间</th>
                <th id="clause_Col">验收条款</th>
                <th id="checkDate_Col">预计验收日期</th>
                <th id="confirmStatus_Col">预计验收日期确认</th>
                <!--	<th id="isSend_Col">验收日期推送</th>  -->
                <!--	<th id="remind_Col">到期未验收提醒</th>  -->
                <th id="realCheckDate_Col">实际验收日期</th>
                <th id="checkFile_Col">验收文本上传</th>
                <th id="reason_Col">超期验收原因</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($rows):
                $i = 0;
                foreach ($rows as $k => $v) {
                    $i++;
                    if ($v['isOutDate'] == 1 && $v['isFinish'] == 0) {
                        ?>
                        <tr>
                            <td style="color:#FF4040"><?php echo $i; ?></td>
                            <td style="color:#FF4040"
                                id="contractCode_Col<?php echo $k; ?>"><?php echo '<span style="color:#FF4040;cursor:pointer;" title="点击查看源单" ' .
                                    'onclick="showOpenWin(\'?model=contractTool_contractTool_contractTool&action=viewContract&id=' . $v['contractId'] . '\')">' . $v['contractCode'] . '</span>';?></td>
                            <td style="color:#FF4040"
                                id="contractName_Col<?php echo $k; ?>"><?php echo $v['contractName']; ?></td>
                            <td style="color:#FF4040"
                                id="realEndDate_Col<?php echo $k; ?>"><?php if ($v['realEndDate'] != '0000-00-00') {
                                    echo $v['realEndDate'];
                                }?></td>
                            <td style="color:#FF4040" id="clause_Col<?php echo $k; ?>"><?php echo $v['clause']; ?></td>
                            <td style="color:#FF4040"
                                id="checkDate_Col<?php echo $k; ?>"><?php if ($v['checkDate'] != '0000-00-00') {
                                    echo $v['checkDate'];
                                }?></td>
                            <td style="color:#FF4040"
                                id="confirmStatus_Col<?php echo $k; ?>"><?php echo $v['confirmStatus']; ?></td>
                            <!--	<td style="color:#FF4040" id="isSend_Col<?php echo $k;?>"><?php echo $v['isSend'];?></td> -->
                            <!--	<td style="color:#FF4040" id="remind_Col<?php echo $k;?>"><?php echo "已提醒".$v['remind']."次";?></td>  -->
                            <td style="color:#FF4040" id="realCheckDate_Col<?php echo $k; ?>">超期未验收</td>
                            <td id="checkFile_Col<?php echo $k; ?>"><?php echo $v['checkFile']; ?></td>
                            <td style="color:#FF4040" id="reason_Col<?php echo $k; ?>"><?php echo $v['reason']; ?></td>
                        </tr>
                    <?php } else { ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td id="contractCode_Col<?php echo $k; ?>"><?php echo '<span style="cursor:pointer;color:#3399ff" title="点击查看源单" ' .
                                    'onclick="showOpenWin(\'?model=contractTool_contractTool_contractTool&action=viewContract&id=' . $v['contractId'] . '\')">' . $v['contractCode'] . '</span>';?></td>
                            <td id="contractName_Col<?php echo $k; ?>"><?php echo $v['contractName']; ?></td>
                            <td id="realEndDate_Col<?php echo $k; ?>"><?php if ($v['realEndDate'] != '0000-00-00') {
                                    echo $v['realEndDate'];
                                }?></td>
                            <td id="clause_Col<?php echo $k; ?>"><?php echo $v['clause']; ?></td>
                            <td id="checkDate_Col<?php echo $k; ?>"><?php if ($v['checkDate'] != '0000-00-00') {
                                    echo $v['checkDate'];
                                }?></td>
                            <td id="confirmStatus_Col<?php echo $k; ?>"><?php echo $v['confirmStatus']; ?></td>
                            <!--	<td id="isSend_Col<?php echo $k;?>"><?php echo $v['isSend'];?></td> -->
                            <!--	<td id="remind_Col<?php echo $k;?>"><?php echo "已提醒".$v['remind']."次";?></td>  -->
                            <td id="realCheckDate_Col<?php echo $k; ?>"><?php echo $v['realCheckDate']; ?></td>
                            <td id="checkFile_Col<?php echo $k; ?>"><?php echo $v['checkFile']; ?></td>
                            <td id="reason_Col<?php echo $k; ?>"><?php echo $v['reason']; ?></td>
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
        $('#checkStatus').val($("#checkStatusVal").val());
        $('#confirmStatus').val($("#confirmStatusVal").val());
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
            data: { "customCode": 'waitingListCode', "rows": customJson },
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
            customCode: 'waitingListCode',
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
			if ($(this).css("display") != "none" && $(this).html() != "序号" && $(this).html() != "验收文本上传") {
				colName += $(this).html() + ",";
			}
		})
		window.open("?model=contractTool_contractTool_contractTool&action=exportWaitingAccept&colName=" + colName 
						+ "&confirmStatus=" + $("#confirmStatus").val()
						+ "&checkStatus=" + $("#checkStatus").val()
						+ "&contractCode=" + $("#contractCode").val()
						+ "&contractName=" + $("#contractName").val()
					);
    }
</script>
<script src="js/jquery/jquery.ba-throttle-debounce.min.js"></script>
<script src="js/jquery/jquery.stickyheader.js"></script>
</body>
</html>