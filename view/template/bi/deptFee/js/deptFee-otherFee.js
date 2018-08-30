$(function () {
    var thisHeight = document.documentElement.clientHeight - 40;

    // 初始化
    $("#search").click(function () {
        var beginYear = $("#beginYear").val() * 1;
        if (beginYear == "") {
            alert("请选择开始年份");
            return false;
        }
        var beginMonth = $("#beginMonth").val() * 1;
        if (beginMonth == "") {
            alert("请选择开始月份");
            return false;
        }
        var endYear = $("#endYear").val() * 1;
        if (endYear == "") {
            alert("请选择结束年份");
            return false;
        }
        var endMonth = $("#endMonth").val() * 1;
        if (endMonth == "") {
            alert("请选择结束月份");
            return false;
        }

        // 显示loading
        //$("#loading").show();

        // 列表渲染
        $("#grid").datagrid({
            url: "?model=bi_deptFee_deptFee&action=otherFeeDetail&beginYear=" + beginYear
            + "&beginMonth=" + beginMonth
            + "&endYear=" + endYear
            + "&endMonth=" + endMonth
            + "&isImport=" + 1,
            loadMsg: '加载中，请稍后...',
            emptyMsg: '没有查询到相关数据...',
            height: thisHeight,
            frozenColumns: [],
            columns: [[
                {
                    title: '事业部',
                    field: 'business',
                    align: 'left',
                    width: 100
                },
                {
                    title: '二级部门',
                    field: 'secondDept',
                    align: 'left',
                    width: 100
                },
                {
                    title: '三级部门',
                    field: 'thirdDept',
                    align: 'left',
                    width: 100
                },
                {
                    title: '四级部门',
                    field: 'fourthDept',
                    align: 'left',
                    width: 100
                },
                {
                    title: '年',
                    field: 'thisYear',
                    width: 70
                },
                {
                    title: '月',
                    field: 'thisMonth',
                    width: 70
                },
                {
                    title: '费用类型',
                    field: 'costType',
                    width: 80
                },
                {
                    title: '预算',
                    field: 'budget',
                    align: 'right',
                    formatter: function (v) {
                        return moneyFormat2(v);
                    },
                    width: 80
                },
                {
                    title: '决算',
                    field: 'fee',
                    align: 'right',
                    formatter: function (v) {
                        return moneyFormat2(v);
                    },
                    width: 80
                }
            ]]
        });
        return true;
    });

    // 初始化
    $("#searchSummary").click(function () {
        var beginYear = $("#beginYear").val() * 1;
        if (beginYear == "") {
            alert("请选择开始年份");
            return false;
        }
        var beginMonth = $("#beginMonth").val() * 1;
        if (beginMonth == "") {
            alert("请选择开始月份");
            return false;
        }
        var endYear = $("#endYear").val() * 1;
        if (endYear == "") {
            alert("请选择结束年份");
            return false;
        }
        var endMonth = $("#endMonth").val() * 1;
        if (endMonth == "") {
            alert("请选择结束月份");
            return false;
        }

        // 显示loading
        //$("#loading").show();

        // 列渲染
        var lockCols = [
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
            },
            {
                title: '费用类型',
                field: 'costType',
                width: 90
            },
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
            }
        ];

        var colModel = [];
        for (var i = beginYear; i <= endYear; i++) {
            var j, month, begin = 1, end = 12;
            if (i == beginYear) {
                begin = beginMonth;
            }
            if (i == endYear) {
                end = endMonth;
            }
            // 月份列渲染
            for (j = begin; j <= end; j++) {
                month = j >= 10 ? j : "0" + j;
                colModel.push({
                    title: i + "" + month,
                    field: 'd' + i + "" + month,
                    align: 'right',
                    formatter: function (v) {
                        return moneyFormat2(v);
                    },
                    width: 90
                });
            }
        }

        // 列表渲染
        $("#grid").datagrid({
            url: "?model=bi_deptFee_deptFee&action=otherFeeSummary&beginYear=" + beginYear
            + "&beginMonth=" + beginMonth
            + "&endYear=" + endYear
            + "&endMonth=" + endMonth
            + "&isImport=" + 1,
            height: thisHeight,
            columns: [colModel],
            frozenColumns: [lockCols],
            loadMsg: '加载中，请稍后...',
            emptyMsg: '没有查询到相关数据...'
        });
        return true;
    });

    // 导入事件绑定
    $("#import").click(function () {
        showThickboxWin("?model=bi_deptFee_deptFee&action=toImport"
            + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
    });

    // 导出时间绑定
    $("#export").click(function () {
        window.open("?model=bi_deptFee_deptFee&action=export&beginYear="
            + $("#beginYear").val() + "&beginMonth=" + $("#beginMonth").val() + "&endYear="
            + $("#endYear").val() + "&endMonth=" + $("#endMonth").val() + "&isImport=" + 1);
    });
});