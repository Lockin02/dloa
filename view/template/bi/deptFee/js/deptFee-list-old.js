$(function () {
    // 部门等级
    var deptLevel = $("#deptLevel").val();

    // 初始化
    $("#search").click(function () {
        // 如果没定义部门层级，则查询绑定提示
        if (deptLevel == "0") {
            alert('未设置部门层级，请先在层级设置中进行配置');
            return true;
        } else {
            var year = $("#year").val();
            if (year == "") {
                alert("请选择年份");
                return false;
            }

            // 显示loading
            $("#loading").show();

            // 部门
            var deptModel = [
                {
                    display: '事业部',
                    name: 'business',
                    align: 'left',
                    width: 120
                },
                {
                    display: '二级部门',
                    name: 'secondDept',
                    align: 'left',
                    width: 120
                },
                {
                    display: '三级部门',
                    name: 'thirdDept',
                    align: 'left',
                    width: 120
                },
                {
                    display: '四级部门',
                    name: 'fourthDept',
                    align: 'left',
                    width: 120
                }
            ];

            // 剪切显示层级的部门
            var colModel = deptModel.slice(0, deptLevel);

            // 列渲染
            colModel.push(
                {
                    display: '预算',
                    name: 'budget',
                    align: 'right',
                    process: function (v) {
                        return moneyFormat2(v);
                    },
                    width: 90
                },
                {
                    display: '决算',
                    name: 'fee',
                    align: 'right',
                    process: function (v) {
                        return moneyFormat2(v);
                    },
                    width: 90
                },
                {
                    display: '费用进度',
                    name: 'feeProcess',
                    align: 'right',
                    process: function (v) {
                        return v + " %";
                    },
                    width: 90
                }
            );

            var beginYear = $("#beginYear").val();
            var beginMonth = $("#beginMonth").val();
            var endYear = $("#endYear").val();
            var endMonth = $("#endMonth").val();

            // 获取其余动态列
            $.ajax({
                url: "?model=bi_deptFee_deptFee&action=getCostTypeList",
                data: {
                    beginYear: beginYear,
                    beginMonth: beginMonth,
                    endYear: endYear,
                    endMonth: endMonth
                },
                type: 'POST',
                async: false,
                dataType: 'json',
                success: function(data) {
                    for (var i = 0; i < data.length; i++) {
                        colModel.push({
                            display: data[i].costType,
                            name: data[i].costType,
                            align: 'right',
                            process: function () {
                                return "-";
                            },
                            width: 90
                        });
                    }
                }
            });

            // 空列
            colModel.push({
                display: "费用类别",
                name: "costCategory",
                align: 'left',
                width: 120
            });

            // 空列
            colModel.push({
                display: "",
                name: ""
            });

            var cl = colModel.length + 1;

            // 列表渲染
            $("#grid").yxeditgrid('remove').yxeditgrid({
                url: "?model=bi_deptFee_deptFee&action=summaryList",
                param: {
                    beginYear: beginYear,
                    beginMonth: beginMonth,
                    endYear: endYear,
                    endMonth: endMonth
                },
                titleAlign: true,
                type: 'view',
                event: {
                    reloadData: function (e, g, data) {
                        if (!data || data.length == 0) {
                            // 暂无数据提示
                            $(this).find('tbody').append("<tr><td colspan='" + cl + "'>-- 暂无相关数据 --</td></tr>");
                            // 隐藏加载
                            $("#loading").hide();
                        } else {
                            // 加载明细数据
                            loadData();
                        }
                    }
                },
                colModel: colModel
            });
            return true;
        }
    });

    // 加载数据
    var loadData = function() {
        $("#grid").yxeditgrid("getCmpByCol", "business").each(function() {
            // 行号
            var beginYear = $("#beginYear").val();
            var beginMonth = $("#beginMonth").val();
            var endYear = $("#endYear").val();
            var endMonth = $("#endMonth").val();
            var rowNum = $(this).data("rowNum");
            var business = $(this).val();
            var secondDept = $("#grid_cmp_secondDept" + rowNum).val();
            var thirdDept = $("#grid_cmp_thirdDept" + rowNum).val();
            var fourthDept = $("#grid_cmp_fourthDept" + rowNum).val();
            var fee = $("#grid_cmp_fee" + rowNum).val();

            // 如果决算大于0，才进行明细加载
            if (fee > 0) {
                // 获取其余动态列
                $.ajax({
                    url: "?model=bi_deptFee_deptFee&action=summaryDetail",
                    data: {
                        rowNum: rowNum,
                        beginYear: beginYear,
                        beginMonth: beginMonth,
                        endYear: endYear,
                        endMonth: endMonth,
                        business: business,
                        secondDept: secondDept,
                        thirdDept: thirdDept,
                        fourthDept: fourthDept
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(data) {
                        var rowNum = data.rowNum;
                        var rows = data.rows;

                        for (var i in rows) {
                            $("#grid_cmp_" + i + rowNum).text(moneyFormat2(rows[i]));
                        }
                    }
                });
            }
            // 隐藏加载
            $("#loading").hide();
        });
    };

    // 检查权限并且绑定导出功能
    $.ajax({
        type: 'POST',
        url: '?model=bi_deptFee_deptFee&action=getLimit',
        data: {limitName: "部门费用统计导出"},
        async: false,
        success: function (data) {
            if (data == "1") {
                // 导出时间绑定
                $("#export").show().click(function () {
                    var thisGrid = $("#grid").data('yxeditgrid');
                    var colName = [];
                    var colCode = [];
                    var colModel = thisGrid.options.colModel;
                    for (var i = 0; i < colModel.length; i++) {
                        if (!colModel[i].hide) {
                            colName.push(colModel[i].display);
                            colCode.push(colModel[i].name);
                        }
                    }
                    var url = "?model=bi_deptFee_deptFee&action=exportSummary"
                        + '&colName=' + colName.toString() + '&colCode=' + colCode.toString();
                    window.open(url + "&beginYear=" + $("#beginYear").val()
                        + "&beginMonth=" + $("#beginMonth").val()
                        + "&endYear=" + $("#endYear").val()
                        + "&endMonth=" + $("#endMonth").val(), "", "width=200,height=200,top=200,left=200");
                });
            }
        }
    });
});