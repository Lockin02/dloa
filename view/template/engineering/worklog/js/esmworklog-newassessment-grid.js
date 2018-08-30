$(function () {
    $("#userName").yxselect_user({
        hiddenId: 'userId',
        formCode: 'certifyapply'
    });

    $("#search").click(function () {
        var begin = $("#beginDateThan").val();
        if (begin == '') {
            alert('��ʼʱ�䲻��Ϊ�գ�');
            return;
        }
        var end = $("#endDateThan").val();
        if (end == '') {
            alert('����ʱ�䲻��Ϊ�գ�');
            return;
        }
        if ($("#userName").val() == '') {
            alert('��������Ϊ�գ�');
            return;
        }
        if (end < begin) {
            alert('��ʼʱ�䲻�ܴ��ڽ���ʱ��');
            return;
        }
        initWorklog();
        //��ʾLoading
        showLoading();
    });
});

//��ʼ���������־
function initWorklog() {
    //�����ж�
    var beginDateThan = $("#beginDateThan").val();
    var endDateThan = $("#endDateThan").val();
    var userName = $("#userName").val();

    var paramObj = {};
    if (beginDateThan)
        paramObj.beginDate = beginDateThan;
    if (endDateThan)
        paramObj.endDate = endDateThan;
    if (userName)
        paramObj.userName = userName;

    var objGrid = $("#esmworklogGrid");

    // ����״̬����
    var workStatusDatadict = getDataObj('GXRYZT');

    //������־
    if (objGrid.children().length == 0) {
        objGrid.yxeditgrid({
            url: '?model=engineering_worklog_esmworklog&action=newAssessment',
            type: 'view',
            param: paramObj,
            event: {
                'reloadData': function () {
                    //����Loading
                    hideLoading();
                }
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
                    width: 70,
                    process: function (v, row) {
                        if (v) {
                            return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_worklog_esmworklog&action=toView&id=" +
                                row.id + '&skey=' + row.skey_ + "\",1,750,1150)'>" + v + "</a>";
                        }
                    }
                },
                {
                    display: '��Ŀ���',
                    name: 'projectCode',
                    width: 120,
                    align: 'left'
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
                    process: function (v, row) {
                        if (row.hols && row.hols > 0) {
                            return "<span class='blue' title='���ݼ٣�" + row.hols + " ��'>" + v + " %</span>";
                        }
                        return v + " %";
                    }
                },
                {
                    display: '��־����',
                    name: 'assessResultName',
                    width: 60,
                    process: function (v) {
                        return v == 0 ? '--' : v;
                    }
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
                    width: 40
                },
                {
                    display: '��־¼������',
                    name: 'createTime',
                    width: 140
                },
                {
                    display: '�����־����',
                    name: 'confirmDate',
                    width: 140
                }
            ]
        });
    }
    else {
        objGrid.yxeditgrid("setParam", paramObj).yxeditgrid("processData");
        hideLoading();
    }
}

//������־����
function exportLogExcel() {
    var beginDate = $("#beginDateThan").val();
    var endDate = $("#endDateThan").val();
    var userName = $("#userName").val();

    var url = "?model=engineering_worklog_esmworklog&action=exportNewAssessment&userName="
            + userName
            + "&beginDate=" + beginDate
            + "&endDate=" + endDate
        ;
    showOpenWin(url, 1, 150, 300, 'exportLogExcel');
}