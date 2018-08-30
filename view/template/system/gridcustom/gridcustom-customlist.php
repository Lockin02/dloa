<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="GBK"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>自定义表格</title>
    <link rel="stylesheet" type="text/css" href="css/newstyle/normalize.css"/>
    <link rel="stylesheet" type="text/css" href="css/newstyle/demo.css"/>
    <link rel="stylesheet" type="text/css" href="css/newstyle/component.css"/>
    <link rel="stylesheet" type="text/css" href="js/jeasyui_newest/themes/default/easyui.css"/>
    <link rel="stylesheet" type="text/css" href="js/jeasyui_newest/themes/icon.css"/>
    <!--[if IE]>
    <script type="text/javascript" src="js/html5.js"></script>
    <![endif]-->
    <style>
        .one-line {
            float: left;
        }
    </style>
</head>
<body>
<form>
    <div class="component">
        <div class="customgrid-div">
            <ul id="dropArea">
                <?php foreach ($rows as $v): ?>
                    <li style="margin: 18px;">
                        <div class="one-line" style="width:300px;">
                            <span>
                                <input type="checkbox"
                                       name="gridcustom[<?php echo $v['id']; ?>][isShow]" <?php echo $v['isShow'] ? "checked='checked'" : ""; ?>
                                       value="1"/>
                                <input type="hidden" name="gridcustom[<?php echo $v['id']; ?>][id]"
                                       value="<?php echo $v['id']; ?>"/>
                                <?php echo $colInfo[$v['colName']]; ?>
                            </span>
                        </div>
                        <div class="one-line" style="50px;">
                            宽度：
                        </div>
                        <div class="one-line">
                            <input class="sliderInput" style="width:200px;display: none;"
                                   name="gridcustom[<?php echo $v['id']; ?>][colWidth]"
                                   value="<?php echo empty($v['colWidth']) ? 100 : $v['colWidth']; ?>"/>
                        </div>
                        </br>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="component">
        <div class="customgrid-div">
            <ul>
                <input type="hidden" id="customCode" value="<?php echo $customCode;?>"/>
                &nbsp;&nbsp;<input type="button" id="save" class="input_button" value="保存"/>
                &nbsp;&nbsp;<input type="button" id="reset" class="input_button" value="重置"/>
            </ul>
        </div>
    </div>
</form>
</body>
<script type="text/javascript" src="js/jquery/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="js/jeasyui_newest/jquery.easyui.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery.dragsort-0.5.1.min.js"></script>
<script type="text/javascript">
    $(function () {
        // 拖拽排序
        $("#dropArea").dragsort({
            dragSelector: 'span'
        });

        // 初始化sliber
        $('.sliderInput').slider({
            showTip: true,
            max: 200
        });

        // ajax保存
        $("#save").click(function () {
            $.ajax({
                type: "POST",
                url: "?model=system_gridcustom_gridcustom&action=saveCustom",
                data: $("form").serialize(),// 你的formid
                error: function () {
                    alert("保存失败");
                },
                success: function () {
                    alert("保存成功");
                    opener.location.reload();
                    window.close();
                }
            });
        });

        // 重置
        $("#reset").click(function () {
            if (confirm("重置会清除当前表格的自定义列记录，确认重置吗？")) {
                $.ajax({
                    type: "POST",
                    url: "?model=system_gridcustom_gridcustom&action=reset",
                    data: {customCode: $("#customCode").val()},
                    error: function () {
                        alert("重置失败");
                    },
                    success: function () {
                        alert("重置完成");
                        opener.location.reload();
                        window.close();
                    }
                });
            }
        });
    });
</script>
</html>