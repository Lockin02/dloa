var show_page = function (page) {

};

$(function () {
    $("#deptName").yxselect_dept({
        hiddenId: 'deptId',
        mode: 'no'
    });
    $("#search").click(function () {
        if ($("#beginDateThan").val() == '') {
            alert('开始时间不能为空！');
            return;
        }
        if ($("#endDateThan").val() == '') {
            alert('结束时间不能为空！');
            return;
        }
        if ($("#deptName").val() == '') {
            alert('部门不能为空！');
            return;
        }
        if ($("#endDateThan").val() < $("#beginDateThan").val()) {
            alert('开始时间不能大于结束时间');
            return;
        }
        initWorklog();
        //显示Loading
        showLoading();
    });

    if ($("#t").val() != "4") {
        $(".searchTbl").show();
    } else {
        initWorklog();
        //显示Loading
        showLoading();
    }
});

//初始化待审核日志
function initWorklog() {
    //参数判断
    var beginDateThan = $("#beginDateThan").val();
    var endDateThan = $("#endDateThan").val();
    var deptId = $("#deptId").val();
    var year = $("#year").val();
    var month = $("#month").val();
    var feeDeptId = $("#feeDeptId").val();
    var createId = $("#createId").val();

    var paramObj = {};
    if (beginDateThan)
        paramObj.beginDateThan = beginDateThan;
    if (endDateThan)
        paramObj.endDateThan = endDateThan;
    if (year)
        paramObj.year = year;
    if (month)
        paramObj.month = month;
    if ($("#t").val() != "") {
        paramObj.k = 0;
        paramObj.deptId = feeDeptId;
        paramObj.createId = createId;
    } else {
        paramObj.deptId = deptId;
    }

    var objGrid = $("#esmworklogGrid");
    //工作日志
    if (objGrid.children().length == 0) {
        objGrid.yxeditgrid({
            url: '?model=engineering_worklog_esmworklog&action=warnView',
            type: 'view',
            param: paramObj,
            event: {
                'reloadData': function (e) {
                    //隐藏Loading
                    hideLoading();
                }
            },
            colModel: [
                {
                    display: '日期',
                    name: 'executionDate'
                },
                {
                    display: '姓名',
                    name: 'createName'
                },
                {
                    display: '归属部门',
                    name: 'belongDeptName'
                },
                {
                    display: '职位(级别)',
                    name: 'jobName'
                },
                {
                    display: '告警',
                    name: 'msg',
                    process: function (v) {
                    	return v == '未填报' ? '<span class="red">' + v + '</span>' : '<span class="blue">' + v + '</span>';
                    }
                }
            ]
        });
    }
    else {
        objGrid.yxeditgrid("setParam", paramObj).yxeditgrid("processData");
        hideLoading();
    }

}
//导出日志告警视图
function exportLogEmergencyExcel() {
    var beginDate = $("#beginDateThan").val();
    var endDate = $("#endDateThan").val();
    var deptId = $("#deptId").val();

    var url = "?model=engineering_worklog_esmworklog&action=exportLogEmergencyJson&deptId="
            + deptId
            + "&beginDateThan=" + beginDate
            + "&endDateThan=" + endDate
        ;
    showOpenWin(url, 1, 150, 300, 'exportLogEmergencyExcel');
}