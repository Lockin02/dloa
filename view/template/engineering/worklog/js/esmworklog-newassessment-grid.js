$(function () {
    $("#userName").yxselect_user({
        hiddenId: 'userId',
        formCode: 'certifyapply'
    });

    $("#search").click(function () {
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
        paramObj.beginDate = beginDateThan;
    if (endDateThan)
        paramObj.endDate = endDateThan;
    if (userName)
        paramObj.userName = userName;

    var objGrid = $("#esmworklogGrid");

    // 工作状态处理
    var workStatusDatadict = getDataObj('GXRYZT');

    //工作日志
    if (objGrid.children().length == 0) {
        objGrid.yxeditgrid({
            url: '?model=engineering_worklog_esmworklog&action=newAssessment',
            type: 'view',
            param: paramObj,
            event: {
                'reloadData': function () {
                    //隐藏Loading
                    hideLoading();
                }
            },
            colModel: [
                {
                    display: 'id',
                    name: 'id',
                    type: 'hidden'
                },
                {
                    display: '日期',
                    name: 'executionDate',
                    width: 70,
                    process: function (v, row) {
                        if (v) {
                            return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_worklog_esmworklog&action=toView&id=" +
                                row.id + '&skey=' + row.skey_ + "\",1,750,1150)'>" + v + "</a>";
                        }
                    }
                },
                {
                    display: '项目编号',
                    name: 'projectCode',
                    width: 120,
                    align: 'left'
                },
                {
                    display: '省份',
                    name: 'province',
                    width: 50
                },
                {
                    display: '城市',
                    name: 'city',
                    width: 50
                },
                {
                    display: '考勤投入',
                    name: 'inWorkRate',
                    width: 70,
                    process: function (v, row) {
                        if (row.hols && row.hols > 0) {
                            return "<span class='blue' title='请休假：" + row.hols + " 天'>" + v + " %</span>";
                        }
                        return v + " %";
                    }
                },
                {
                    display: '日志考核',
                    name: 'assessResultName',
                    width: 60,
                    process: function (v) {
                        return v == 0 ? '--' : v;
                    }
                },
                {
                    display: '项目考核',
                    name: 'score',
                    width: 60,
                    process: function (v) {
                        return v == 0 ? '--' : v;
                    }
                },
                {
                    display: '考核得分',
                    name: 'accessScore',
                    width: 60,
                    process: function (v) {
                        return v == 0 ? '--' : v;
                    }
                },
                {
                    display: '工作状态',
                    width: 60,
                    name: 'workStatus',
                    process: function (v, row) {
                        if (row.isLeave && row.isLeave == '1') {
                            return '<span class="red">离职</span>';
                        } else if (row.unEntry && row.unEntry == '1') {
                            return '<span class="red">未入职</span>';
                        } else {
                            return workStatusDatadict[v];
                        }
                    }
                },
                {
                    display: '工作量',
                    name: 'workloadDay',
                    width: 60
                },
                {
                    display: '单位',
                    name: 'workloadUnitName',
                    width: 40
                },
                {
                    display: '日志录入日期',
                    name: 'createTime',
                    width: 140
                },
                {
                    display: '审核日志日期',
                    name: 'confirmDate',
                    width: 140
                }
            ]
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

    var url = "?model=engineering_worklog_esmworklog&action=exportNewAssessment&userName="
            + userName
            + "&beginDate=" + beginDate
            + "&endDate=" + endDate
        ;
    showOpenWin(url, 1, 150, 300, 'exportLogExcel');
}