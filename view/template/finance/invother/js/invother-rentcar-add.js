$(document).ready(function () {
    //归属公司
    $("#businessBelongName").yxcombogrid_branch({
        hiddenId: 'businessBelong',
        height: 250,
        isFocusoutCheck: false,
        gridOptions: {
            showcheckbox: false,
            event: {
                'row_dblclick': function (e, row, data) {
                    //初始化树结构
                    initTree();
                    //重置责任范围
                    reloadManager();
                }
            }
        }
    });

    $("#innerTable").yxeditgrid({
        objName: 'invother[items]',
        title: '发票明细',
        event: {
            removeRow: function (t, rowNum, rowData) {
                countForm();
            }
        },
        colModel: [
            {
                display: '发票名目',
                name: 'productId',
                type: 'hidden'
            },
            {
                display: '发票名目编码',
                name: 'productCode',
                type: 'hidden'
            },
            {
                display: '发票名目',
                name: 'productName',
                validation: {
                    required: true
                },
                tclass: 'txt'
            },
            {
                display: '数量',
                name: 'number',
                tclass: 'txtshort',
                event: {
                    blur: function () {
                        countAll($(this).data("rowNum"));
                    }
                }
            },
            {
                display: '单价',
                name: 'price',
                type: 'money',
                event: {
                    blur: function () {
                        countAll($(this).data("rowNum"), 'price');
                    }
                }
            },
            {
                display: '含税单价',
                name: 'taxPrice',
                type: 'money',
                event: {
                    blur: function () {
                        countAll($(this).data("rowNum"), 'taxPrice');
                    }
                }
            },
            {
                display: '金额',
                name: 'amount',
                validation: {
                    required: true
                },
                type: 'money',
                event: {
                    blur: function () {
                        //初始化单价和数量
                        initNumAndPrice($(this).data("rowNum"), 'amount');
                        countAccount($(this).data("rowNum"));
                        countForm();
                    }
                }
            },
            {
                display: '税额',
                name: 'assessment',
                type: 'money',
                event: {
                    blur: function () {
                        countAccount($(this).data("rowNum"));
                        countForm();
                    }
                }
            },
            {
                display: '价税合计',
                name: 'allCount',
                type: 'money',
                event: {
                    blur: function () {
                        //初始化单价和数量
                        initNumAndPrice($(this).data("rowNum"), 'allCount');
                        countForm();
                    }
                }
            },
            {
                display: '源单编号',
                name: 'objCode',
                readonly: true,
                tclass: 'readOnlyTxtNormal',
                value: $("#menuNo").val()
            },
            {
                display: '源单id',
                name: 'objId',
                type: 'hidden',
                value: $("#menuId").val()
            },
            {
                display: '源单类型',
                name: 'objType',
                type: 'hidden',
                value: $("#sourceType").val()
            }
        ]
    });

    //初始化合计
    initListCount();
});