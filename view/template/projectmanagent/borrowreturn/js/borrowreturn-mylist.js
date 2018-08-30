var show_page = function () {
    $("#myreturnGrid").yxgrid("reload");
};
$(function () {
    $("#myreturnGrid").yxgrid({
        model: 'projectmanagent_borrowreturn_borrowreturn',
        title: '我的归还申请',
        param: {'createId': $("#userId").val()},
        isViewAction: false,
        isAddAction: false,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: false,
        // 列信息
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'borrowId',
                display: '借用单ID',
                sortable: true,
                hide: true
            },
            {
                name: 'applyTypeName',
                display: '申请类型',
                width: 80,
                sortable: true
            },
            {
                name: 'Code',
                display: '归还单编号',
                sortable: true,
                width: 140,
                process: function (v, row) {
                    if (row.remark != "") {
                        v += " <img src='images/icon/msg.png' style='width:14px;height:14px;' title='备注 : " + row.remark + "'/>";
                    }
                    return v;
                }
            },
            {
                name: 'borrowCode',
                display: '借用单编号',
                sortable: true
            },
            {
                name: 'borrowLimit',
                display: '借用类型',
                sortable: true,
                width: 80
            },
            {
                name: 'customerName',
                display: '客户名称',
                sortable: true,
                width: 220
            },
            {
                name: 'remark',
                display: '备注',
                sortable: true,
                width: 180,
                hide: true
            },
            {
                name: 'disposeState',
                display: '处理状态',
                sortable: true,
                process: function (v) {
                    switch (v) {
                        case '0' :
                            return '待处理';
                            break;
                        case '1' :
                            return '正在处理';
                            break;
                        case '2' :
                            return '已处理';
                            break;
                        case '3' :
                            return '质检完成';
                            break;
                        case '8' :
                            return '打回';
                            break;
//					case '9' : return '等待销售确认';break;
                        default :
                            return '--';
                    }
                },
                width: 80
            },
            {
                name: 'ExaStatus',
                display: '审批状态',
                sortable: true,
                width: 80
            },
            {
                name: 'ExaDT',
                display: '审批日期',
                sortable: true,
                width: 80
            },
            {
                name: 'updateTime',
                display: '更新时间',
                sortable: true,
                width: 120
            }
        ],
        toEditConfig: {
            action: 'toEdit'
        },
        toViewConfig: {
            action: 'toView'
        },
        // 扩展右键菜单
        menusEx: [
            {
                text: '查看',
                icon: 'view',
                action: function (row) {
                    if (row) {
                        showOpenWin("?model=projectmanagent_borrowreturn_borrowreturn&action=toView&id="
                            + row.id + "&skey=" + row['skey_']);
                    }
                }
            },
            {
                text: '编辑',
                icon: 'edit',
                showMenuFn: function (row) {
                    if (row.ExaStatus == '待提交' || (row.ExaStatus == '免审' && (row.disposeState == '8') || (row.disposeState=='0' && row.ExaStatus=='打回') ||  (row.disposeState=='8' && row.ExaStatus=='完成'))) {
                        return true;
                    }
                    return false;
                },
                action: function (row, rows, grid) {
                    if (row) {
                        showOpenWin("?model=projectmanagent_borrowreturn_borrowreturn&action=toEdit&id="
                            + row.id
                            + "&skey="
                            + row['skey_']);
                    } else {
                        alert("请选中一条数据");
                    }
                }
            },
            {
                text: '删除',
                icon: 'delete',
                showMenuFn: function (row) {
                    if (row.ExaStatus == '待提交' || (row.ExaStatus == '免审' && (row.disposeState == '8'))) {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    if (window.confirm(("确定要删除?"))) {
                        $.ajax({
                            type: "POST",
                            url: "?model=projectmanagent_borrowreturn_borrowreturn&action=ajaxdeletes",
                            data: {
                                id: row.id
                            },
                            success: function (msg) {
                                if (msg == 1) {
                                    alert('删除成功！');
                                    show_page();
                                }
                            }
                        });
                    }
                }
            }
        ],
        //过滤数据
        comboEx: [
            {
                text: '处理状态',
                key: 'disposeStates',
                data: [
                    {
                        text: '待处理',
                        value: '0'
                    },
                    {
                        text: '正在处理',
                        value: '1'
                    },
                    {
                        text: '已处理',
                        value: '2'
                    },
                    {
                        text: '质检完成',
                        value: '3'
                    },
                    {
//				text : '等待销售确认',
//				value : '9'
//			}, {
                        text: '打回',
                        value: '8'
                    }
                ]
            }
        ],
        searchitems: [
            {
                display: "归还单号",
                name: 'Code'
            },
            {
                display: "借试用单号",
                name: 'borrowCodeSearch'
            },
            {
                display: "序列号",
                name: 'serialName'
            }
        ]
    });
});