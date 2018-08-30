var show_page = function () {

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

//初始化待审核周报
function initWorklog() {
    //参数判断
    var beginDateThan = $("#beginDateThan").val();
    var endDateThan = $("#endDateThan").val();
    var deptId = $("#deptId").val();
    var year = $("#year").val();
    var month = $("#month").val();
    var ids = $("#ids").val();

    var paramObj = {};
    if (beginDateThan) paramObj.beginDateThan = beginDateThan;
    if (endDateThan) paramObj.endDateThan = endDateThan;
    if (deptId) paramObj.deptId = deptId;
    if (year) paramObj.year = year;
    if (month) paramObj.month = month;
    if (ids) paramObj.projectIds = ids;

    var objGrid = $("#statusreportGrid");
    //项目周报
    if (objGrid.children().length == 0) {
        var num = 0;
        objGrid.yxeditgrid({
            url: '?model=engineering_project_statusreport&action=warnView',
            type: 'view',
            param: paramObj,
            event: {
                'reloadData': function (e) {
                    //隐藏Loading
                    hideLoading();
                    num = 0;
                }
            },
            colModel: [
                {
                    display: '归属区域',
                    name: 'officeName'
                },
                {
                    display: '项目编号',
                    name: 'projectCode',
                    process: function (v, row) {
                        return "<a href='javascript:void(0)' onclick='viewProjectById(" + row.projectId + ");'>" + v + "</a>";
                    }
                },
                {
                    display: '项目名称',
                    name: 'projectName'
                },
                {
                    display: '项目经理',
                    name: 'managerName'
                },
                {
                    display: '周次',
                    name: 'weekNo'
                },
                {
                    display: '告警(<span id="totalNum">row.length</span>)',
                    name: 'msg',
                    process: function (v) {
                        num += 1;
                        $("#totalNum").text(num);
                        return v == '未填报' ? '<span class="red">' + v + '</span>' : '<span class="blue">' + v + '</span>';
                    }
                }
            ]
        });
    } else {
        objGrid.yxeditgrid("setParam", paramObj).yxeditgrid("processData");
        hideLoading();
    }
}

/**
 * 查看项目
 * @param projectId
 */
function viewProjectById(projectId) {
    var skey = '';
    $.ajax({
        type: "POST",
        url: "?model=engineering_project_esmproject&action=md5RowAjax",
        data: { "id": projectId },
        async: false,
        success: function (data) {
            skey = data;
        }
    });
    var url = "?model=engineering_project_esmproject&action=viewTab&id=" + projectId + '&skey=' + skey;
    showModalWin(url);
}

//显示loading
function showLoading() {
    $("#loading").show();
}

//导出周报告警视图
function exportLogEmergencyExcel() {
    var beginDate = $("#beginDateThan").val();
    var endDate = $("#endDateThan").val();
    var deptId = $("#deptId").val();

    var url = "?model=engineering_project_statusreport&action=exportLogEmergencyJson&deptId="
            + deptId
            + "&beginDateThan=" + beginDate
            + "&endDateThan=" + endDate
        ;
    showOpenWin(url, 1, 150, 300, 'exportLogEmergencyExcel');
}

function hideLoading() {
    this.el.empty();
    this.el.unbind();
    this.destroy();
}