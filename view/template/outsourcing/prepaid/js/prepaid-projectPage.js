$(function () {
    // 锁定表头
    var lockCols = [
        {
            title: '外包合同号',
            field: 'contractCode',
            halign: 'center',
            width: 90
        }
    ];
    var colModel = [
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
            sortable : true,
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
            sortable : true,
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
            sortable : true,
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
            sortable : true,
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
            width: 90
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
        // 初始化
        var thisHeight = document.documentElement.clientHeight - 40;
        var projectId = $("#projectId").val();

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
            + "&beginMonth=" + beginMonth + "&endYear=" + endYear + "&endMonth=" + endMonth
            + "&searchVal=" + $("#searchVal").val() + "&projectId=" + projectId,
            loadMsg: '加载中，请稍后...',
            emptyMsg: '没有查询到相关数据...',
            height: thisHeight,
            columns: [colModel],
            frozenColumns: [lockCols]
        });
    }).trigger("click");
});