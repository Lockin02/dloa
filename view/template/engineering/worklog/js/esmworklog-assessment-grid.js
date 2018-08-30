$(function() {
    $("#userName").yxselect_user({
        hiddenId: 'userId',
        formCode: 'certifyapply'
    });

    $("#search").click(function() {
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
        paramObj.beginDateThan = beginDateThan;
    if (endDateThan)
        paramObj.endDateThan = endDateThan;
    if (userName)
        paramObj.userName = userName;

    var objGrid = $("#esmworklogGrid");
    //������־
    if (objGrid.children().length == 0) {
        objGrid.yxeditgrid({
            url: '?model=engineering_worklog_esmworklog&action=assessment',
            type: 'view',
            param: paramObj,
            event: {
                'reloadData': function(e) {
                    //����Loading
                    hideLoading();
                }
            },
            colModel: [{
                display: 'id',
                name: 'id',
                type: 'hidden'
            }, {
                display: '����',
                name: 'executionDate',
                width: 70,
                process: function(v, row) {
                    return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_worklog_esmworklog&action=toView&id=" +
                        row.id + '&skey=' + row.skey_ + "\",1,750,1150)'>" + v + "</a>";
                }
            }, {
                display: '��־¼������',
                name: 'createTime',
                width: 140
            }, {
                display: '�����־����',
                name: 'confirmDate',
                width: 140
            }, {
                display: '��Ŀ���',
                name: 'projectCode',
                width: 120,
                align: 'left'
            }, {
                display: 'ʡ��',
                name: 'province',
                width: 50
            }, {
                display: '����',
                name: 'city',
                width: 50
            }, {
                display: '����',
                name: 'activityName',
                width: 120,
                align: 'left'
            }, {
                display: '������',
                name: 'workloadDay',
                width: 60
            }, {
                display: '��λ',
                name: 'workloadUnitName',
                width: 40
            }, {
                display: '�����չ',
                name: 'thisActivityProcess',
                width: 60,
                process: function(v) {
                    return v + " %";
                }
            }, {
                display: '��Ŀ��չ',
                name: 'thisProjectProcess',
                width: 60,
                process: function(v) {
                    return v + " %";
                }
            }, {
                display: '��չϵ��',
                name: 'processCoefficient',
                width: 60
            }, {
                display: '�˹�Ͷ��ռ��',
                name: 'inWorkRate',
                width: 70,
                process: function(v) {
                    return v + " %";
                }
            }, {
                display: '����ϵ��',
                name: 'workCoefficient',
                width: 60
            }, {
                display: '����',
                name: 'costMoney',
                width: 60,
                type: 'hidden',
                process: function(v) {
                    if (v * 1 == 0 || v == '') {
                        return v;
                    } else {
                        return "<span class='blue'>" + moneyFormat2(v) + "</span>";
                    }
                }
            }, {
                display: '����״̬',
                width: 60,
                datacode: 'GXRYZT',
                name: 'workStatus'
            }, {
                display: '�������',
                name: 'description',
                align: 'left',
                width: 60
            }, {
                display: '���˽��',
                name: 'assessResultName',
                width: 80
            }, {
                display: '�ظ�',
                name: 'feedBack',
                align: 'left',
                process: function(v) {
                    return v;
                },
                width: 80
            }, {
                display: 'assessResult',
                name: 'assessResult',
                type: 'hidden'
            }]
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

    var url = "?model=engineering_worklog_esmworklog&action=exportLogAssessJson&userName="
            + userName
            + "&beginDateThan=" + beginDate
            + "&endDateThan=" + endDate
        ;
    showOpenWin(url, 1, 150, 300, 'exportLogExcel');
}