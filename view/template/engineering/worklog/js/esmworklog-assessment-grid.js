$(function() {
    $("#userName").yxselect_user({
        hiddenId: 'userId',
        formCode: 'certifyapply'
    });

    $("#search").click(function() {
        var begin = $("#beginDateThan").val();
        if (begin == '') {
            alert('开始时间不能为空！');
            return;
        }
        var end = $("#endDateThan").val();
        if (end == '') {
            alert('结束时间不能为空！');
            return;
        }
        if ($("#userName").val() == '') {
            alert('姓名不能为空！');
            return;
        }
        if (end < begin) {
            alert('开始时间不能大于结束时间');
            return;
        }
        initWorklog();
        //显示Loading
        showLoading();
    });
});

//初始化待审核日志
function initWorklog() {
    //参数判断
    var beginDateThan = $("#beginDateThan").val();
    var endDateThan = $("#endDateThan").val();
    var userName = $("#userName").val();

    var paramObj = {};
    if (beginDateThan)
        paramObj.beginDateThan = beginDateThan;
    if (endDateThan)
        paramObj.endDateThan = endDateThan;
    if (userName)
        paramObj.userName = userName;

    var objGrid = $("#esmworklogGrid");
    //工作日志
    if (objGrid.children().length == 0) {
        objGrid.yxeditgrid({
            url: '?model=engineering_worklog_esmworklog&action=assessment',
            type: 'view',
            param: paramObj,
            event: {
                'reloadData': function(e) {
                    //隐藏Loading
                    hideLoading();
                }
            },
            colModel: [{
                display: 'id',
                name: 'id',
                type: 'hidden'
            }, {
                display: '日期',
                name: 'executionDate',
                width: 70,
                process: function(v, row) {
                    return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_worklog_esmworklog&action=toView&id=" +
                        row.id + '&skey=' + row.skey_ + "\",1,750,1150)'>" + v + "</a>";
                }
            }, {
                display: '日志录入日期',
                name: 'createTime',
                width: 140
            }, {
                display: '审核日志日期',
                name: 'confirmDate',
                width: 140
            }, {
                display: '项目编号',
                name: 'projectCode',
                width: 120,
                align: 'left'
            }, {
                display: '省份',
                name: 'province',
                width: 50
            }, {
                display: '城市',
                name: 'city',
                width: 50
            }, {
                display: '任务',
                name: 'activityName',
                width: 120,
                align: 'left'
            }, {
                display: '工作量',
                name: 'workloadDay',
                width: 60
            }, {
                display: '单位',
                name: 'workloadUnitName',
                width: 40
            }, {
                display: '任务进展',
                name: 'thisActivityProcess',
                width: 60,
                process: function(v) {
                    return v + " %";
                }
            }, {
                display: '项目进展',
                name: 'thisProjectProcess',
                width: 60,
                process: function(v) {
                    return v + " %";
                }
            }, {
                display: '进展系数',
                name: 'processCoefficient',
                width: 60
            }, {
                display: '人工投入占比',
                name: 'inWorkRate',
                width: 70,
                process: function(v) {
                    return v + " %";
                }
            }, {
                display: '工作系数',
                name: 'workCoefficient',
                width: 60
            }, {
                display: '费用',
                name: 'costMoney',
                width: 60,
                type: 'hidden',
                process: function(v) {
                    if (v * 1 == 0 || v == '') {
                        return v;
                    } else {
                        return "<span class='blue'>" + moneyFormat2(v) + "</span>";
                    }
                }
            }, {
                display: '工作状态',
                width: 60,
                datacode: 'GXRYZT',
                name: 'workStatus'
            }, {
                display: '情况描述',
                name: 'description',
                align: 'left',
                width: 60
            }, {
                display: '考核结果',
                name: 'assessResultName',
                width: 80
            }, {
                display: '回复',
                name: 'feedBack',
                align: 'left',
                process: function(v) {
                    return v;
                },
                width: 80
            }, {
                display: 'assessResult',
                name: 'assessResult',
                type: 'hidden'
            }]
        });
    }
    else {
        objGrid.yxeditgrid("setParam", paramObj).yxeditgrid("processData");
        hideLoading();
    }
}

//导出日志考核
function exportLogExcel() {
    var beginDate = $("#beginDateThan").val();
    var endDate = $("#endDateThan").val();
    var userName = $("#userName").val();

    var url = "?model=engineering_worklog_esmworklog&action=exportLogAssessJson&userName="
            + userName
            + "&beginDateThan=" + beginDate
            + "&endDateThan=" + endDate
        ;
    showOpenWin(url, 1, 150, 300, 'exportLogExcel');
}