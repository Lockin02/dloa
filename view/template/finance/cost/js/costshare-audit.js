$(function () {
    // �ر���ҳʱ���ص��¼�
    $(window).bind('beforeunload',function(){
        window.opener.show_page();
    });

    var thisPeriod = $("#thisPeriod").val();

    // ���ط�̯�б�
    $("#shareGrid").costShareGrid({
        title: "",
        objName: "costshare[detail]",
        type: "audit",
        url: "?model=finance_cost_costshare&action=listjson",
        // param: {objType: $("#objType").val(), objId: $("#objId").val(), 'inPeriodSmall': thisPeriod},
        param: {objType: $("#objType").val(), objId: $("#objId").val()},
        isShowCountRow: true,
        countKey: "objMoney",
        thisPeriod: thisPeriod
    });

    // ������¼�
    $("#audit").bind('click', function () {
        //��ʾ���÷�̯��ϸ
        if ($("#shareGrid").costShareGrid('checkForm', $("#objMoney").val(), false) != false) {
            if (confirm("ȷ�������") == true) {
                $("form").submit();
            }
        }
    });

    // �󶨲�������һ��
    $("#auditAndNext").bind('click', function () {
        //��ʾ���÷�̯��ϸ
        if ($("#shareGrid").costShareGrid('checkForm', $("#objMoney").val(), false) != false) {
            if (confirm("ȷ�������") == true) {
                $("#goNext").val(1);
                $("form").submit();
            }
        }
    });

    // ��������
    $("#exportAudit").bind('click', function () {
        window.open(
            '?model=finance_cost_costshare&action=exportAudit&objType=' + $("#objType").val() +
                '&objId=' + $("#objId").val()
            ,
            '���÷�̯����',
            'width=200,height=200,top=200,left=200'
        );
    });

    // �󶨳���ʱ��
    $("#back").bind('click', function () {
        if (confirm("ȷ�ϳ��ص�����") == true) {
            $.ajax({
                type: "POST",
                url: "?model=finance_cost_costshare&action=back",
                data: {objType: $("#objType").val(), objId: $("#objId").val()},
                async: false,
                success: function (data) {
                    if (data == "0") {
                        alert('����ʧ�ܡ�');
                    } else if (data == "1") {
                        alert('���سɹ���������Ҫ��˵ĵ��ݡ�');
                        window.close();
                    } else {
                        alert('���سɹ���');
                        // ·����ת
                        location.href = data;
                    }
                }
            });
        }
    });
});