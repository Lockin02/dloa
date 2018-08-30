var show_page = function (page) {
};

$(function () {
    initWorklog();
});

//��ʼ���������־
function initWorklog() {
    //������־
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
            display: '����',
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
            display: '����״̬',
            name: 'workStatus',
            datacode: 'GXRYZT',
            width: 80
        }, {
            display: '����Ͷ��',
            name: 'inWorkRate',
            width: 80
        }, {
            display: '��־����',
            name: 'assessResultName',
            width: 80,
            process: function (v) {
                return v == 0 ? '--' : v;
            }
        }, {
            display: '��Ŀ����',
            name: 'score',
            width: 80,
            process: function (v) {
                return v == 0 ? '--' : v;
            }
        }, {
            display: '���˵÷�',
            name: 'accessScore',
            width: 80,
            process: function (v) {
                return v == 0 ? '--' : v;
            }
        }, {
            display: '������',
            name: 'workloadDay',
            width: 80
        }, {
            display: '��λ',
            name: 'workloadUnitName',
            width: 60
        }, {
            display: '�澯',
            name: 'warning',
            align: 'left',
            width: 250,
            process: function (v, row) {
                if (row.isLeave) {
                    return '<span class="blue">��ְ</span>';
                }
                if (row.notEntry) {
                    return '<span class="blue">δ��ְ</span>';
                }
                if (row.hols) {
                    return '<span class="blue">���ݼ٣�' + row.hols + '</span>';
                }
                if (row.notEntryProject) {
                    return '<span class="blue">������Ŀ��</span>';
                }
                if (row.otherProject) {
                    return '<span class="blue">������Ŀ��' + row.projectCode + '</span>';
                }
                if (!row.assessResultName && row.id != 'noId') {
                    return '<span class="red">δ��д</span>';
                }
                if (row.confirmStatus == '0') {
                    return '<span class="blue">δ���</span>';
                }
            }
        }, {
            display: '',
            name: ''
        }]
    });
}

//�鿴��ϸ
function searchDetail(createId) {
    var url = "?model=engineering_worklog_esmworklog&action=toSearchDetailList&createId="
            + createId
            + "&beginDate=" + $("#beginDate").val()
            + "&endDate=" + $("#endDate").val()
        ;
    showOpenWin(url, 1, 800, 1100, createId);
}