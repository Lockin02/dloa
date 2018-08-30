$(function () {
    initWorklog();
});

//初始化待审核日志
function initWorklog() {
    var objGrid = $("#esmworklogGrid");

    // 工作状态处理
    var workStatusDatadict = getDataObj('GXRYZT');

    //工作日志
    objGrid.yxeditgrid("remove").yxeditgrid({
        url: '?model=engineering_worklog_esmworklog&action=newSearchDetailJson',
        type: 'view',
        param: {
            projectId: $("#projectId").val(),
            beginDate: $("#beginDate").val(),
            endDate: $("#endDate").val(),
            userName: $("#userName").val()
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
                width: 80,
                process: function (v, row) {
                    if (row.city) {
                        return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_worklog_esmworklog&action=toView&id=" +
                            row.id + '&skey=' + row.skey_ + "\",1,750,1150)'>" + v + "</a>";
                    } else {
                        return v;
                    }
                }
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
                process: function (v) {
                    return v ? v + ' %' : '';
                }
            },
            {
                display: '日志考核',
                name: 'assessResultName',
                width: 60
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
                width: 50
            },
            {
                display: '告警',
                name: 'warning',
                width: 100,
                process: function (v, row) {
                    if (!row.city && row.confirmStatus == undefined && row.id != 'noId') {
                        return '<span class="red">未填写</span>';
                    }
                    if (row.confirmStatus == '0') {
                        return '<span class="blue">未审核</span>';
                    } else if (row.confirmStatus == '2') {
                        return '<span class="blue">打回</span>';
                    } else if (row.confirmStatus == '3') {
                        return '<span class="green">参与其他项目</span>';
                    } else {
                        return '';
                    }
                }
            },
            {
                display: '请休假',
                name: 'hols',
                width: 50,
                process: function (v) {
                    return v ?
                        '<span class="blue" title="本列表内的请假天数可能计算不准确，仅供参考，以新日志考核查询中的为准。">' + v + '</span>' :
                        '';
                }
            },
            {
                display: '',
                name: ''
            }
        ]
    });
}

//查看明细
function searchDetail(createId) {
    var url = "?model=engineering_worklog_esmworklog&action=toSearchDetailList&createId="
            + createId
            + "&beginDate=" + $("#beginDate").val()
            + "&endDate=" + $("#endDate").val()
        ;
    showOpenWin(url, 1, 800, 1100, createId);
}