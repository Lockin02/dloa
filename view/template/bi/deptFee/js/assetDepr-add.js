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

    // �󶨼����¼�
    $("#loadBtn").click(function() {
        // ���ط�̯����
        loadDepr();
    });
});

// ��ʼ����̯
var loadDepr = function () {
    var deprMoney = $("#deprMoney").val();
    if (deprMoney == "") {
        alert("����������۾ɽ��");
        return false;
    }

    var officeNames = $("#officeNames").val();
    if (officeNames == "") {
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

    $("#grid").yxeditgrid('remove').yxeditgrid({
        objName: 'assetDepr[assetShare]',
        title: '�۾ɷ�̯�嵥',
        tableClass: 'form_in_table',
        url: "?model=engineering_resources_esmdevicefee&action=getOfficeEquFee",
        param: {
            officeIds: $("#officeIds").val(),
            deprMoney: deprMoney,
            thisYear: thisYear,
            thisMonth: thisMonth
        },
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
}