$(function () {
    //��ѡ���´�
    $("#officeNames").yxcombogrid_office({
        hiddenId: 'officeIds',
        height: 250,
        gridOptions: {
            showcheckbox: true,
            isTitle: true,
            event: {
                'row_click': function (e, row, data) {
                    // ��ʼ����̯
                    $("#grid").yxeditgrid('remove');
                }
            }
        }
    });

    // ��ʼ��
    loadDepr(1);

    // �󶨼����¼�
    $("#loadBtn").click(function() {
        // ���ط�̯����
        loadDepr(0);
    });
});

// ��ʼ����̯
var loadDepr = function (init) {
    var deprMoney = $("#deprMoney").val();
    if (deprMoney == "") {
        alert("����������۾ɽ��");
        return false;
    }
    var officeName = $("#officeName").val();
    if (officeName == "") {
        alert("��ѡ���̯����");
        return false;
    }
    var thisYear = $("#thisYear").val();
    if (thisYear == "") {
        alert("��ѡ�����");
        return false;
    }
    var thisMonth = $("#thisMonth").val();
    if (thisMonth == "") {
        alert("��ѡ���·�");
        return false;
    }

    var url, param;
    if (init == 1) {
        url = "?model=bi_deptFee_assetShare&action=listJson";
        param = {
            deprId: $("#id").val(),
            thisYear: thisYear,
            thisMonth: thisMonth
        };
    } else {
        url = "?model=engineering_resources_esmdevicefee&action=getOfficeEquFee";
        param = {
            officeIds: $("#officeIds").val(),
            deprMoney: deprMoney,
            thisYear: thisYear,
            thisMonth: thisMonth
        };
    }

    $("#grid").yxeditgrid('remove').yxeditgrid({
        objName: 'assetDepr[assetShare]',
        title: '�۾ɷ�̯�嵥',
        tableClass: 'form_in_table',
        url: url,
        param: param,
        isAddAndDel: false,
        colModel: [
            {
                display: '��̯����',
                name: 'officeName',
                readonly: true,
                tclass: 'readOnlyTxtMiddle'
            },
            {
                display: '��̯����ID',
                name: 'officeId',
                type: 'hidden'
            },
            {
                display: '��������',
                name: 'deptName'
            },
            {
                display: '��Ŀ�۾�',
                name: 'projectDepr',
                readonly: true,
                type: 'money',
                tclass: 'readOnlyTxtMiddle'
            },
            {
                display: '�۾ɱ�����%��',
                name: 'projectDeprRate',
                type: 'money',
                readonly: true,
                tclass: 'readOnlyTxtMiddle'
            },
            {
                display: '�е����',
                name: 'feeIn',
                type: 'money'
            }
        ]
    });
    return true;
};