// ����֤
var checkSubmit = function() {
    try {
        if ($("#business").val() == "") {
            alert("��Ҫ��д��ҵ��");
            return false;
        }
        var deprMoney = $("#deprMoney").val();
        if (deprMoney == "" || deprMoney == "0") {
            alert("��Ҫ��д�����۾ɽ��ҽ���Ϊ0");
            return false;
        }

        // ��̯��ϸ
        var shareMoney = 0;
        var projectDepr = 0;

        // ��ϸ��֤
        var girdObj = $("#grid");
        girdObj.yxeditgrid('getCmpByCol', 'feeIn').each(function () {
            shareMoney = accAdd(shareMoney, $(this).val(), 2);
        });
        girdObj.yxeditgrid('getCmpByCol', 'projectDepr').each(function () {
            projectDepr = accAdd(projectDepr, $(this).val(), 2);
        });

        if (shareMoney == 0) {
            alert("�е����Ϊ0�������·���е����");
            return false;
        }

        var shareAll = accSub(deprMoney, projectDepr, 2);
        var diff = accSub(shareAll, shareMoney, 2);
        if (diff != 0) {
            alert("�е���" + shareMoney + "��������۾ɽ�" + shareAll + "�����ȣ����Ϊ��" + diff +
                "�������������������¼��ع����۾�");
            return false;
        }
    } catch (e) {
        console.log(e);
        alert('����֤�쳣');
        return false;
    }
    return true;
};