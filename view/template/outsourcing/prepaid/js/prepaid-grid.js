$(function () {
    // 锁定表头
    var lockCols = [
        {
            title: '外包合同号',
            field: 'contractCode',
            halign: 'center',
            align: 'left',
            width: 90
        },
        {
            title: '签约合同号',
            field: 'signCode',
            halign: 'center',
            align: 'left',
            width: 90
        },
        {
            title: "区域",
            field: "area",
            halign: 'center',
            align: 'left',
            width: 80
        },
        {
            title: "项目名称",
            field: "projectName",
            halign: 'center',
            align: 'left',
            width: 140,
            formatter: function (v) {
                return "<div style='word-wrap:break-word;word-break:break-all;white-space: normal;'>"+v+"</div>";
            }
        }
    ];
    var colModel = [
        {
            title: "项目编号",
            field: "projectCode",
            halign: 'center',
            align: 'left',
            width: 120
        },

        {
            title: "合作方式",
            field: "businessType",
            halign: 'center',
            align: 'left',
            width: 80
        },
        {
            title: '税率',
            field: 'taxRate',
            halign: 'center',
            align: 'center',
            formatter: function (v) {
                return v == "" ? "" : v + " %";
            },
            width: 70
        },
        {
            title: '总预提（不含税）',
            field: 'subPrepaid',
            halign: 'center',
            align: 'right',
            sortable: true,
            formatter: function (v) {
                return moneyFormat2(v);
            },
            width: 100
        },
        {
            title: '总预提',
            field: 'subPrepaidWithTax',
            halign: 'center',
            align: 'right',
            sortable: true,
            formatter: function (v) {
                return moneyFormat2(v);
            },
            width: 90
        },
        {
            title: '总支付',
            field: 'subPay',
            halign: 'center',
            align: 'right',
            sortable: true,
            formatter: function (v) {
                return moneyFormat2(v);
            },
            width: 90
        },
        {
            title: '未完成结算',
            field: 'unDeal',
            halign: 'center',
            align: 'right',
            sortable: true,
            formatter: function (v) {
                return moneyFormat2(v);
            },
            width: 90
        },
        {
            title: '月份',
            field: 'yearMonth',
            halign: 'center',
            align: 'center',
            width: 90
        },
        {
            title: '原始',
            field: 'original',
            halign: 'center',
            align: 'right',
            formatter: function (v) {
                return moneyFormat2(v);
            },
            width: 90
        },
        {
            title: '补差额',
            field: 'adjust',
            halign: 'center',
            align: 'right',
            formatter: function (v) {
                return moneyFormat2(v);
            },
            width: 90
        },
        {
            title: '预提',
            field: 'prepaidWithTax',
            halign: 'center',
            align: 'right',
            formatter: function (v) {
                return moneyFormat2(v);
            },
            width: 90
        },
        {
            title: '预提(不含税)',
            field: 'prepaid',
            halign: 'center',
            align: 'right',
            formatter: function (v) {
                return moneyFormat2(v);
            },
            width: 90
        },
        {
            title: '备注',
            field: 'remark',
            halign: 'center',
            align: 'left',
            width: 90,
            formatter: function (v) {
                return "<div style='word-wrap:break-word;word-break:break-all;white-space: normal;'>"+v+"</div>";
            }
        },
        {
            title: '实际',
            field: 'act',
            halign: 'center',
            align: 'right',
            formatter: function (v) {
                return moneyFormat2(v);
            },
            width: 90
        },
        {
            title: '收票',
            field: 'invoice',
            halign: 'center',
            align: 'right',
            formatter: function (v) {
                return moneyFormat2(v);
            },
            width: 90
        },
        {
            title: '收票日期',
            field: 'invoiceDT',
            halign: 'center',
            align: 'center',
            width: 90
        },
        {
            title: '支付',
            field: 'pay',
            halign: 'center',
            align: 'right',
            formatter: function (v) {
                return moneyFormat2(v);
            },
            width: 90
        },
        {
            title: '支付日期',
            field: 'payDT',
            halign: 'center',
            align: 'center',
            width: 90
        },
        {
            title: '预提与实际差额',
            field: 'diff',
            halign: 'center',
            align: 'right',
            formatter: function (v) {
                return moneyFormat2(v);
            },
            width: 90
        }
    ]; // 表头

    // 初始化
    $("#search").click(function () {
        var year = $("#year").val();
        if (year == "") {
            alert("请选择年份");
            return false;
        }

        var thisHeight = document.documentElement.clientHeight - 40;

        var beginYear = $("#beginYear").val();
        var beginMonth = $("#beginMonth").val();
        if (beginYear != "" && beginMonth == "") {
            alert("请同时填写开始年和开始月");
            return false;
        }

        var endYear = $("#endYear").val();
        var endMonth = $("#endMonth").val();
        if (endYear != "" && endMonth == "") {
            alert("请同时填写结束年和结束月");
            return false;
        }

        $('#grid').datagrid({
            url: "?model=outsourcing_prepaid_prepaid&action=summaryList&beginYear=" + beginYear
            + "&beginMonth=" + beginMonth + "&endYear=" + endYear + "&endMonth=" + endMonth + "&searchVal=" + $("#searchVal").val(),
            loadMsg: '加载中，请稍后...',
            emptyMsg: '没有查询到相关数据...',
            height: thisHeight,
            columns: [colModel],
            frozenColumns: [lockCols]
        });
    });

    // 检查权限并且绑定导出功能
    $.ajax({
        type: 'POST',
        url: '?model=outsourcing_prepaid_prepaid&action=getLimits',
        data: {limitName: "导出权限"},
        async: false,
        success: function (data) {
            if (data == "1") {
                // 导出时间绑定
                $("#export").show().click(function () {
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
                    var url = "?model=outsourcing_prepaid_prepaid&action=export"
                        + '&colName=' + colName.toString() + '&colCode=' + colCode.toString();
                    window.open(url + "&beginYear=" + $("#beginYear").val()
                        + "&beginMonth=" + $("#beginMonth").val()
                        + "&endYear=" + $("#endYear").val()
                        + "&endMonth=" + $("#endMonth").val() + "&searchVal=" + $("#searchVal").val(), "", "width=200,height=200,top=200,left=200");
                });
            }
        }
    });

    // 检查权限并且绑定导出功能
    $.ajax({
        type: 'POST',
        url: '?model=outsourcing_prepaid_prepaid&action=getLimits',
        data: {limitName: "导入权限"},
        async: false,
        success: function (data) {
            if (data == "1") {
                // 导出时间绑定
                $("#import").show().click(function () {
                    showThickboxWin("?model=outsourcing_prepaid_prepaid&action=toExcelIn" +
                        "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
                });
            }
        }
    });
});

