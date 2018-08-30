$(function () {
    // 部门等级
    var deptLevel = $("#deptLevel").val();
    var lockCols = []; // 锁定表头
    var colModel = []; // 表头

    // 初始化
    $("#search").click(function () {
        // 如果没定义部门层级，则查询绑定提示
        if (deptLevel == "0") {
            alert('未设置部门层级，请先在层级设置中进行配置');
            return true;
        } else {
            var beginYear = $("#beginYear").val();
            if (beginYear == "") {
                alert("请选择开始年份");
                return false;
            }
            var beginMonth = $("#beginMonth").val();
            if (beginMonth == "") {
                alert("请选择开始月份");
                return false;
            }
            var endYear = $("#endYear").val();
            if (endYear == "") {
                alert("请选择结束年份");
                return false;
            }
            var endMonth = $("#endMonth").val();
            if (endMonth == "") {
                alert("请选择结束月份");
                return false;
            }

            // 表头处理
            var deptModel = [
                {
                    title: '事业部',
                    field: 'business',
                    align: 'left',
                    width: 120
                },
                {
                    title: '二级部门',
                    field: 'secondDept',
                    align: 'left',
                    width: 120
                },
                {
                    title: '三级部门',
                    field: 'thirdDept',
                    align: 'left',
                    width: 120
                },
                {
                    title: '四级部门',
                    field: 'fourthDept',
                    align: 'left',
                    width: 120
                }
            ];

            // 剪切显示层级的部门
            colModel = deptModel.slice(0, deptLevel);

            // 列渲染
            colModel.push(
                {
                    title: '预算',
                    field: 'budget',
                    align: 'right',
                    formatter: function (v) {
                        return moneyFormat2(v);
                    },
                    width: 90
                },
                {
                    title: '决算',
                    field: 'fee',
                    align: 'right',
                    formatter: function (v) {
                        return moneyFormat2(v);
                    },
                    width: 90
                },
                {
                    title: '费用进度',
                    field: 'feeProcess',
                    align: 'right',
                    formatter: function (v) {
                        return v + " %";
                    },
                    width: 90
                }
            );

            lockCols = colModel;

            colModel = [];

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
                success: function (data) {
                    for (var i = 0; i < data.length; i++) {
                        colModel.push({
                            title: data[i].costType,
                            field: data[i].costType,
                            align: 'right',
                            formatter: function (v) {
                                return moneyFormat2(v);
                            },
                            width: 90
                        });
                    }
                }
            });

            // 空列
            colModel.push({
                title: "产品线",
                field: "productLine",
                align: 'left',
                width: 120
            }, {
                title: "费用类别",
                field: "costCategory",
                align: 'left',
                width: 120
            });

            var thisHeight = document.documentElement.clientHeight - 40;

            $('#grid').datagrid({
                url: "?model=bi_deptFee_deptFee&action=summaryList&beginYear=" + beginYear
                + "&beginMonth=" + beginMonth + "&endYear=" + endYear + "&endMonth=" + endMonth,
                loadMsg: '加载中，请稍后...',
                emptyMsg: '没有查询到相关数据...',
                height: thisHeight,
                columns: [colModel],
                frozenColumns: [lockCols]
            });
        }
    });

    // 检查权限并且绑定导出功能
    $.ajax({
        type: 'POST',
        url: '?model=bi_deptFee_deptFee&action=getLimit',
        data: {limitName: "部门费用统计导出"},
        async: false,
        success: function (data) {
            if (data == "1") {
                // 导出事件绑定
                $("#export").show().click(function () {
                    if (colModel.length == 0) {
                        alert('请查询后再导出数据');
                    } else {
                        var colName = [];
                        var colCode = [];
                        for (var i = 0; i < lockCols.length; i++) {
                            if (!colModel[i].hide) {
                                colName.push(lockCols[i].title);
                                colCode.push(lockCols[i].field);
                            }
                        }
                        for (i = 0; i < colModel.length; i++) {
                            if (!colModel[i].hide) {
                                colName.push(colModel[i].title);
                                colCode.push(colModel[i].field);
                            }
                        }
                        var url = "?model=bi_deptFee_deptFee&action=exportSummary"
                            + '&colName=' + colName.toString() + '&colCode=' + colCode.toString();
                        window.open(url + "&beginYear=" + $("#beginYear").val()
                            + "&beginMonth=" + $("#beginMonth").val()
                            + "&endYear=" + $("#endYear").val()
                            + "&endMonth=" + $("#endMonth").val(), "", "width=200,height=200,top=200,left=200");
                    }
                });

                // 导出明细绑定
                $("#toExportDetail").show().click(function () {
                    $('#detailWindow').show().window({
                        title: '选择需要导出的明细类型',
                        width: 600,
                        height: 300,
                        modal: true,
                        collapsible: false,
                        minimizable: false,
                        maximizable: false
                    });
                });

                // 绑定导出事件
                $("#exportDetail").show().click(function () {
                    // 生成
                    var exportYear = $("#exportYear").val();
                    if (exportYear == "") {
                        alert("请选择年份");
                        return false;
                    }
                    var exportMonth = $("#exportMonth").val();
                    if (exportMonth == "") {
                        alert("请选择月份");
                        return false;
                    }

                    var selectedFeeType = $("input[id^='feeType_']:checked");
                    if (selectedFeeType.length == 0) {
                        alert("请选择需要导出的类型");
                        return false;
                    }
                    var exportItems = [];
                    selectedFeeType.each(function () {
                        if ($(this).attr("checked")) {
                            exportItems.push($(this).val());
                        }
                    });

                    window.open("?model=bi_deptFee_deptFee&action=exportDetail" + "&exportYear=" + exportYear
                        + "&exportMonth=" + exportMonth
                        + "&exportItems=" + exportItems.toString(), "", "width=200,height=200,top=200,left=200");
                });
            }
        }
    });
});