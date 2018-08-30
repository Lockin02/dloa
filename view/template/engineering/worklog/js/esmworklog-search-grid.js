var show_page = function () {
};

//��ʼ���������־
function initWorklog() {
    var beginDate = $("#beginDate").val();
    var endDate = $("#endDate").val();

    if (beginDate == "" || endDate == "") {
        alert('��ѡ����������');
        return false;
    }
    $("#loading").show();

    //������־
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
            display: '����',
            name: 'createName',
            width: 120,
            process: function (v, row) {
                if (row.createId == "noId") {
                    return "<span style='font-weight:bold;'>" + v + "</span>";
                } else {
                    var rt = "<a href='javascript:void(0)' title='����鿴��ϸ' onclick='searchDetail(\"" +
                        row.createId + "\",\"" + row.createName + "\");'>" + v + "</a>";
                    if (row.status == "1") {
                        var endDateTips = row.endDate == "" ? "δ��д�뿪����" : "�뿪���ڣ�" + row.endDate;
                        var endDateClass = row.endDate == "" ? "red" : "blue";
                        rt += "<span title='" + endDateTips + "' class='" + endDateClass + "'>���뿪��</span>";
                    }
                    return rt;
                }
            }
        }, {
            display: 'Ա�����',
            name: 'userNo',
            width: 100
        }, {
            display: '��������',
            name: 'deptName',
            width: 100
        }, {
            display: '����Ͷ��',
            name: 'inWorkRate',
            width: 100
        }, {
            display: '���˵÷�',
            name: 'monthScore',
            width: 100
        }, {
            display: '������',
            name: 'workloadDay',
            width: 100
        }, {
            display: '��־ȱ������',
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

//�鿴��ϸ
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