$(function () {
    initWorklog();
});

//��ʼ���������־
function initWorklog() {
    var objGrid = $("#esmworklogGrid");

    // ����״̬����
    var workStatusDatadict = getDataObj('GXRYZT');

    //������־
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
                display: '����',
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
                display: 'ʡ��',
                name: 'province',
                width: 50
            },
            {
                display: '����',
                name: 'city',
                width: 50
            },
            {
                display: '����Ͷ��',
                name: 'inWorkRate',
                width: 70,
                process: function (v) {
                    return v ? v + ' %' : '';
                }
            },
            {
                display: '��־����',
                name: 'assessResultName',
                width: 60
            },
            {
                display: '��Ŀ����',
                name: 'score',
                width: 60,
                process: function (v) {
                    return v == 0 ? '--' : v;
                }
            },
            {
                display: '���˵÷�',
                name: 'accessScore',
                width: 60,
                process: function (v) {
                    return v == 0 ? '--' : v;
                }
            },
            {
                display: '����״̬',
                width: 60,
                name: 'workStatus',
                process: function (v, row) {
                    if (row.isLeave && row.isLeave == '1') {
                        return '<span class="red">��ְ</span>';
                    } else if (row.unEntry && row.unEntry == '1') {
                        return '<span class="red">δ��ְ</span>';
                    } else {
                        return workStatusDatadict[v];
                    }
                }
            },
            {
                display: '������',
                name: 'workloadDay',
                width: 60
            },
            {
                display: '��λ',
                name: 'workloadUnitName',
                width: 50
            },
            {
                display: '�澯',
                name: 'warning',
                width: 100,
                process: function (v, row) {
                    if (!row.city && row.confirmStatus == undefined && row.id != 'noId') {
                        return '<span class="red">δ��д</span>';
                    }
                    if (row.confirmStatus == '0') {
                        return '<span class="blue">δ���</span>';
                    } else if (row.confirmStatus == '2') {
                        return '<span class="blue">���</span>';
                    } else if (row.confirmStatus == '3') {
                        return '<span class="green">����������Ŀ</span>';
                    } else {
                        return '';
                    }
                }
            },
            {
                display: '���ݼ�',
                name: 'hols',
                width: 50,
                process: function (v) {
                    return v ?
                        '<span class="blue" title="���б��ڵ�����������ܼ��㲻׼ȷ�������ο���������־���˲�ѯ�е�Ϊ׼��">' + v + '</span>' :
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

//�鿴��ϸ
function searchDetail(createId) {
    var url = "?model=engineering_worklog_esmworklog&action=toSearchDetailList&createId="
            + createId
            + "&beginDate=" + $("#beginDate").val()
            + "&endDate=" + $("#endDate").val()
        ;
    showOpenWin(url, 1, 800, 1100, createId);
}