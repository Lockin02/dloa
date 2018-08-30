var show_page = function () {

};

$(function () {
    $("#deptName").yxselect_dept({
        hiddenId: 'deptId',
        mode: 'no'
    });
    $("#search").click(function () {
        if ($("#beginDateThan").val() == '') {
            alert('��ʼʱ�䲻��Ϊ�գ�');
            return;
        }
        if ($("#endDateThan").val() == '') {
            alert('����ʱ�䲻��Ϊ�գ�');
            return;
        }
        if ($("#endDateThan").val() < $("#beginDateThan").val()) {
            alert('��ʼʱ�䲻�ܴ��ڽ���ʱ��');
            return;
        }
        initWorklog();
        //��ʾLoading
        showLoading();
    });

    if ($("#t").val() != "4") {
        $(".searchTbl").show();
    } else {
        initWorklog();
        //��ʾLoading
        showLoading();
    }
});

//��ʼ��������ܱ�
function initWorklog() {
    //�����ж�
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
    //��Ŀ�ܱ�
    if (objGrid.children().length == 0) {
        var num = 0;
        objGrid.yxeditgrid({
            url: '?model=engineering_project_statusreport&action=warnView',
            type: 'view',
            param: paramObj,
            event: {
                'reloadData': function (e) {
                    //����Loading
                    hideLoading();
                    num = 0;
                }
            },
            colModel: [
                {
                    display: '��������',
                    name: 'officeName'
                },
                {
                    display: '��Ŀ���',
                    name: 'projectCode',
                    process: function (v, row) {
                        return "<a href='javascript:void(0)' onclick='viewProjectById(" + row.projectId + ");'>" + v + "</a>";
                    }
                },
                {
                    display: '��Ŀ����',
                    name: 'projectName'
                },
                {
                    display: '��Ŀ����',
                    name: 'managerName'
                },
                {
                    display: '�ܴ�',
                    name: 'weekNo'
                },
                {
                    display: '�澯(<span id="totalNum">row.length</span>)',
                    name: 'msg',
                    process: function (v) {
                        num += 1;
                        $("#totalNum").text(num);
                        return v == 'δ�' ? '<span class="red">' + v + '</span>' : '<span class="blue">' + v + '</span>';
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
 * �鿴��Ŀ
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

//��ʾloading
function showLoading() {
    $("#loading").show();
}

//�����ܱ��澯��ͼ
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