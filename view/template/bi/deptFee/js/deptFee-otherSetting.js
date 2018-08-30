$(function () {
    // 获取过滤的费用类型
    $.ajax({
        url: "?model=common_otherdatas&action=getConfig",
        data: {name: 'deptFee_filter_costType', type: ''},
        type: 'post',
        async: false,
        success: function(msg) {
            $("#costType").val(msg);
        }
    });

    $.ajax({
        url: "?model=common_otherdatas&action=getConfig",
        data: {name: 'deptFee_filter_deptLevel', type: ''},
        type: 'post',
        async: false,
        success: function(msg) {
            $("#deptLevel").val(msg);
        }
    });

    // 保存按钮
    $("#costTypeBtn").click(function() {
        $.ajax({
            url: "?model=common_otherdatas&action=updateConfig",
            data: {name: 'deptFee_filter_costType', type: '', value: $("#costType").val()},
            type: 'post',
            async: false,
            success: function(msg) {
                alert(msg);
            }
        });
    });

    $.ajax({
        url: "?model=common_otherdatas&action=getConfig",
        data: {name: 'deptFee_pk_mapping', type: ''},
        type: 'post',
        async: false,
        success: function(msg) {
            $("#pkMapping").val(msg);
        }
    });

    // 保存按钮
    $("#pkMappingBtn").click(function() {
        $.ajax({
            url: "?model=common_otherdatas&action=updateConfig",
            data: {name: 'deptFee_pk_mapping', type: '', value: $("#pkMapping").val()},
            type: 'post',
            async: false,
            success: function(msg) {
                alert(msg);
            }
        });
    });

    // 保存按钮
    $("#deptLevelBtn").click(function() {
        $.ajax({
            url: "?model=common_otherdatas&action=updateConfig",
            data: {name: 'deptFee_filter_deptLevel', type: '', value: $("#deptLevel").val()},
            type: 'post',
            async: false,
            success: function(msg) {
                alert(msg);
            }
        });
    });

    var allLength;
    var now;

    // 保存按钮
    $("#feeUpdateBtn").click(function() {
        var feeTypeTypes = $("input[id^='feeType_']:checked");

        if (feeTypeTypes.length == 0) {
            alert("请选择一种更新类型");
        } else if (confirm('确认更新决算数据吗？')) {
            // 更新进度
            allLength = feeTypeTypes.length;

            // 重置更新情况
            reloadUpdateResult();

            feeTypeTypes.each(function() {
                $.ajax({
                    url: "?model=bi_deptFee_deptFee&action=updateFee",
                    data: {feeType: $(this).val(), year: $("#year").val(), month: $("#month").val()},
                    type: 'post',
                    success: function(msg) {
                        setUpdateResult(msg);
                    }
                });
            });
        }
    });

    // 重载更新情况
    var reloadUpdateResult = function() {
        // 加载loading
        $("#loading").show();
        $("#feeShowArea").html("").append("<table class='table' id='showAreaTable' border='0' cellspacing='0' cellpadding='0'>" +
            "<thead style='text-align: left;'><th>费用类型</th><th>更新状态</th>" +
            "<th style='text-align: right;'>更新金额</th><th style='text-align: right;'>异常金额</th>" +
            "<th style='width:200px;'>异常信息</th></thead>" +
            "<tbody id='showAreaBody'></tbody>" +
            "</table>");
        now = 0;
    };

    // 构建表格
    var setUpdateResult = function(msg) {
        now++;
        msg = eval("(" + msg + ")");
        var state = msg.rst == "none" ? "无更新数据" : "更新完成";
        $("#showAreaBody").append("<tr><td>" + msg.feeTypeName + "</td>" +
            "<td>" + state + "</td>" +
            "<td style='text-align: right;'>" + msg.feeAll + "</td>" +
            "<td style='text-align: right;'>" + msg.ignore + "</td>" +
            "<td>" + msg.ignoreInfo + "</td></tr>");
        if (now == allLength) {
            $("#loading").hide();
        }
    };
});