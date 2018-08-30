var show_page = function () {
    $("#grid").yxgrid("reload");
};
$(function () {
    // 获取配置

    // 列表初始化
    $("#grid").yxgrid({
        model: 'bi_deptFee_deptMapping',
        title: '部门关系配置',
        isOpButton: false,
        //列信息
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'business',
                display: '事业部',
                sortable: true
            },
            {
                name: 'secondDept',
                display: '二级部门',
                sortable: true,
                width: 80
            },
            {
                name: 'thirdDept',
                display: '三级部门',
                sortable: true,
                width: 80
            },
            {
                name: 'fourthDept',
                display: '四级部门',
                sortable: true,
                width: 80
            },
            {
                name: 'mappingDept',
                display: '包含部门',
                width: 200
            },
            {
                name: 'module',
                display: '板块',
                sortable: true,
                width: 80
            },
            {
                name: 'productLine',
                display: '产品线',
                sortable: true,
                width: 80
            },
            {
                name: 'costCategory',
                display: '费用类别',
                sortable: true,
                width: 80
            },
            {
                name: 'forbidFeeType',
                display: '不统计项',
                sortable: true,
                width: 80
            },
            {
                name: 'sortOrder',
                display: '序号',
                sortable: true,
                width: 30,
                align: 'center'
            },
            {
                name: 'filterStartDateStr',
                display: '统计开始日期',
                sortable: true,
                width: 80,
                align: 'center'
            },
            {
                name: 'filterEndDateStr',
                display: '统计截止日期',
                sortable: true,
                width: 80,
                align: 'center'
            },
            {
                name: 'remark',
                display: '备注说明',
                width: 200
            }
        ],
        buttonsEx: [{
            text: "导入数据",
            icon: 'add',
            action: function () {
                showThickboxWin("?model=bi_deptFee_deptMapping&action=toImport"
                    + "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=700")
            }
        }, {
            text: '导出数据',
            icon: 'excel',
            action: function () {
                var thisGrid = $("#grid").data('yxgrid');
                var colName = [];
                var colCode = [];
                var colModel = thisGrid.options.colModel;
                for (var i = 0; i < colModel.length; i++) {
                    if (!colModel[i].hide) {
                        colName.push(colModel[i].display);
                        colCode.push(colModel[i].name);
                    }
                }
                var url = "?model=bi_deptFee_deptMapping&action=export"
                    + '&colName=' + colName.toString() + '&colCode=' + colCode.toString();
                if (thisGrid.options.qtype) {
                    url += "&" + thisGrid.options.qtype + "=" + thisGrid.options.query;
                }
                window.open(url, "", "width=200,height=200,top=200,left=200");
            }
        }],
        toEditConfig: {
            action: 'toEdit'
        },
        toViewConfig: {
            action: 'toView'
        },
        searchitems: [
            {
                display: "事业部/中心",
                name: 'businessSearch'
            },
            {
                display: "二级部门",
                name: 'secondDeptSearch'
            },
            {
                display: "三级部门",
                name: 'thirdDeptSearch'
            },
            {
                display: "四级部门",
                name: 'fourthDeptSearch'
            },
            {
                display: "包含部门",
                name: 'mappingDeptSearch'
            },
            {
                display: "备注说明",
                name: 'remarkSearch'
            }
        ]
    });
});