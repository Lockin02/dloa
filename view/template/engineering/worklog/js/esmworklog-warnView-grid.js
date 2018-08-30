var show_page = function (page) {

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
        if ($("#deptName").val() == '') {
            alert('���Ų���Ϊ�գ�');
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

//��ʼ���������־
function initWorklog() {
    //�����ж�
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
    //������־
    if (objGrid.children().length == 0) {
        objGrid.yxeditgrid({
            url: '?model=engineering_worklog_esmworklog&action=warnView',
            type: 'view',
            param: paramObj,
            event: {
                'reloadData': function (e) {
                    //����Loading
                    hideLoading();
                }
            },
            colModel: [
                {
                    display: '����',
                    name: 'executionDate'
                },
                {
                    display: '����',
                    name: 'createName'
                },
                {
                    display: '��������',
                    name: 'belongDeptName'
                },
                {
                    display: 'ְλ(����)',
                    name: 'jobName'
                },
                {
                    display: '�澯',
                    name: 'msg',
                    process: function (v) {
                    	return v == 'δ�' ? '<span class="red">' + v + '</span>' : '<span class="blue">' + v + '</span>';
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
//������־�澯��ͼ
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