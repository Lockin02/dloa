$(function () {
    if ($("#TO_ID").length != 0) {
        $("#TO_NAME").yxselect_user({
            mode: 'check',
            hiddenId: 'TO_ID',
            formCode: 'income'
        });
    }

    if ($("#ADDIDS").length != 0) {
        $("#ADDNAMES").yxselect_user({
            mode: 'check',
            hiddenId: 'ADDIDS',
            formCode: 'income'
        });
    }
});

function checkform() {
    var actBeginDate = $("#actBeginDate").val();
    var actEndDate = $("#actEndDate").val();

    if (actBeginDate == "") {
        alert("������ʵ�ʿ�ʼ����");
        return false;
    }

    if (actEndDate == "") {
        alert("������ʵ�ʽ�������");
        return false;
    }

    if (DateDiff(actBeginDate, actEndDate) < 0) {
        alert("ʵ�ʽ������ڲ��ܴ���ʵ�ʿ�ʼ����");
        return false;
    }

    return confirm("ȷ��Ҫ����Ŀת���깤״̬�룿");
}
