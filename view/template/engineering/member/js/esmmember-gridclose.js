var show_page = function (page) {
    window.opener.location.reload();
    $("#esmmemberGrid").yxgrid("reload");
};

$(function () {
    // 关闭网页时加载的事件
    $(window).bind('beforeunload', function () {
        window.opener.loadGrid();
    });

    var projectId = $("#projectId").val();
    $("#esmmemberGrid").yxgrid({
        model: 'engineering_member_esmmember',
        param: {
            "projectId": projectId,
            "memberIdNot": 'SYSTEM'
        },
        title: '项目成员',
        pageSize: 1000,
        usepager: false, // 是否分页
        isAddAction: false,
        isDelAction: false,
        showcheckbox: false,
        noCheckIdValue: 'noId',
        //列信息
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'projectCode',
            display: '项目编号',
            sortable: true,
            hide: true
        }, {
            name: 'projectName',
            display: '项目名称',
            sortable: true,
            hide: true
        }, {
            name: 'memberName',
            display: '姓名',
            sortable: true
        }, {
            name: 'memberId',
            display: '成员id',
            sortable: true,
            hide: true
        }, {
            name: 'personLevel',
            display: '级别',
            sortable: true,
            width: 80
        }, {
            name: 'beginDate',
            display: '加入项目',
            sortable: true,
            width: 80
        }, {
            name: 'endDate',
            display: '离开项目',
            sortable: true,
            width: 80
        }, {
            name: 'roleName',
            display: '角色',
            sortable: true,
            width: 80
        }, {
            name: 'activityName',
            display: '任务名称',
            width: 150,
            sortable: true
        }, {
            name: 'feeDay',
            display: '参与天数',
            sortable: true,
            process: function (v, row) {
                if (row.beginDate == '') {
                    return '0.00';
                }
                if (row.endDate == '') {
                    return "<span class='green' title='即时信息'>" + moneyFormat2(v) + "</span>";
                } else {
                    return moneyFormat2(v);
                }
            },
            hide: true
        }, {
            name: 'feePeople',
            display: '人力成本(天)',
            sortable: true,
            process: function (v, row) {
                if (row.beginDate == '') {
                    return '0.00';
                }
                if (row.endDate == '') {
                    return "<span class='green' title='即时信息'>" + moneyFormat2(v) + "</span>";
                } else {
                    return moneyFormat2(v);
                }
            },
            hide: true
        }, {
            name: 'feePerson',
            display: '人力成本(元)',
            sortable: true,
            process: function (v, row) {
                if (row.beginDate == '') {
                    return '0.00';
                }
                if (row.endDate == '') {
                    return "<span class='green' title='即时信息'>" + moneyFormat2(v) + "</span>";
                } else {
                    return moneyFormat2(v);
                }
            },
            hide: true
        }, {
            name: 'remark',
            display: '备注说明',
            width: 150,
            sortable: true
        }, {
            name: 'createId',
            display: '创建人Id',
            sortable: true,
            hide: true
        }, {
            name: 'createName',
            display: '创建人名称',
            sortable: true,
            hide: true
        }, {
            name: 'createTime',
            display: '创建时间',
            sortable: true,
            width: 150,
            hide: true
        }, {
            name: 'updateId',
            display: '修改人Id',
            sortable: true,
            hide: true
        }, {
            name: 'updateName',
            display: '修改人名称',
            sortable: true,
            hide: true
        }, {
            name: 'updateTime',
            display: '修改时间',
            sortable: true,
            width: 150,
            hide: true
        }
        ],
        toEditConfig: {
            formWidth: 950,
            formHeight: 400,
            action: 'toEdit',
            showMenuFn: function (row) {
                return $("#read").val() != "1";
            },
        },
        toViewConfig: {
            formWidth: 900,
            formHeight: 400,
            action: 'toView'
        },
        searchitems: [{
            display: "成员名称",
            name: 'memberNameSearch'
        }, {
            display: "成员等级",
            name: 'personLevelSearch'
        }],
        sortorder: 'ASC',
        sortname: 'personLevel'
    });
});