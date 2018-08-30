var show_page = function () {
};

//初始化待审核日志
function initWorklog() {
    var beginDate = $("#beginDate").val();
    var endDate = $("#endDate").val();

    if (beginDate == "" || endDate == "") {
        alert('请选择日期区间');
        return false;
    }
    $("#loading").show();

    //工作日志
    $("#esmworklogGrid").yxeditgrid("remove").yxeditgrid({
        url: '?model=engineering_worklog_esmworklog&action=searchJson',
        type: 'view',
        param: {
            projectId: $("#projectId").val(),
            beginDate: beginDate,
            endDate: endDate
        },
        event: {
            reloadData: function () {
                $("#loading").hide();
            }
        },
        colModel: [{
            display: 'createId',
            name: 'createId',
            type: 'hidden'
        }, {
            display: '姓名',
            name: 'createName',
            width: 120,
            process: function (v, row) {
                if (row.createId == "noId") {
                    return "<span style='font-weight:bold;'>" + v + "</span>";
                } else {
                    var rt = "<a href='javascript:void(0)' title='点击查看明细' onclick='searchDetail(\"" +
                        row.createId + "\",\"" + row.createName + "\");'>" + v + "</a>";
                    if (row.status == "1") {
                        var endDateTips = row.endDate == "" ? "未填写离开日期" : "离开日期：" + row.endDate;
                        var endDateClass = row.endDate == "" ? "red" : "blue";
                        rt += "<span title='" + endDateTips + "' class='" + endDateClass + "'>（离开）</span>";
                    }
                    return rt;
                }
            }
        }, {
            display: '员工编号',
            name: 'userNo',
            width: 100
        }, {
            display: '所属部门',
            name: 'deptName',
            width: 100
        }, {
            display: '考勤投入',
            name: 'inWorkRate',
            width: 100
        }, {
            display: '考核得分',
            name: 'monthScore',
            width: 100
        }, {
            display: '工作量',
            name: 'workloadDay',
            width: 100
        }, {
            display: '日志缺交天数',
            name: 'missingDays',
            width: 100,
            process: function (v, row) {
                var actHolsDays = row.actHolsDays ? row.actHolsDays * 1 : 0;
                return row.countDay * 1 - actHolsDays - row.inWorkRate * 1;
            }
        }, {
            display: '',
            name: ''
        }]
    });
}

//查看明细
function searchDetail(createId, createName) {
    var url = "?model=engineering_worklog_esmworklog&action=toSearchDetailList&createId="
            + createId
            + "&createName=" + createName
            + "&beginDate=" + $("#beginDate").val()
            + "&endDate=" + $("#endDate").val()
            + "&projectId=" + $("#projectId").val()
        ;
    showOpenWin(url, 1, 800, 1100, createName);
}