var show_page = function (page) {
};

$(function () {
    initWorklog();
});

//初始化待审核日志
function initWorklog() {
    //工作日志
    $("#esmworklogGrid").yxeditgrid("remove").yxeditgrid({
        url: '?model=engineering_worklog_esmworklog&action=searchDetailJson',
        type: 'view',
        param: {
            projectId: $("#projectId").val(),
            beginDateThan: $("#beginDate").val(),
            endDateThan: $("#endDate").val(),
            createId: $("#createId").val(),
            createName: $("#createName").val()
        },
        colModel: [{
            display: 'id',
            name: 'id',
            type: 'hidden'
        }, {
            display: '日期',
            name: 'executionDate',
            width: 100,
            process: function (v, row) {
                if (row.id == "noId") {
                    return "<span style='font-weight:bold;'>" + v + "</span>";
                } else {
                    return v;
                }
            }
        }, {
            display: '工作状态',
            name: 'workStatus',
            datacode: 'GXRYZT',
            width: 80
        }, {
            display: '考勤投入',
            name: 'inWorkRate',
            width: 80
        }, {
            display: '日志考核',
            name: 'assessResultName',
            width: 80,
            process: function (v) {
                return v == 0 ? '--' : v;
            }
        }, {
            display: '项目考核',
            name: 'score',
            width: 80,
            process: function (v) {
                return v == 0 ? '--' : v;
            }
        }, {
            display: '考核得分',
            name: 'accessScore',
            width: 80,
            process: function (v) {
                return v == 0 ? '--' : v;
            }
        }, {
            display: '工作量',
            name: 'workloadDay',
            width: 80
        }, {
            display: '单位',
            name: 'workloadUnitName',
            width: 60
        }, {
            display: '告警',
            name: 'warning',
            align: 'left',
            width: 250,
            process: function (v, row) {
                if (row.isLeave) {
                    return '<span class="blue">离职</span>';
                }
                if (row.notEntry) {
                    return '<span class="blue">未入职</span>';
                }
                if (row.hols) {
                    return '<span class="blue">请休假：' + row.hols + '</span>';
                }
                if (row.notEntryProject) {
                    return '<span class="blue">不在项目中</span>';
                }
                if (row.otherProject) {
                    return '<span class="blue">其他项目：' + row.projectCode + '</span>';
                }
                if (!row.assessResultName && row.id != 'noId') {
                    return '<span class="red">未填写</span>';
                }
                if (row.confirmStatus == '0') {
                    return '<span class="blue">未审核</span>';
                }
            }
        }, {
            display: '',
            name: ''
        }]
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