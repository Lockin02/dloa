$(document).ready(function () {

});

//����֤
function checkform() {
    if ($("input[name='esmproject[status]']:checked").length == 0) {
        alert("��ѡ����Ҫ���µ�״̬");
        return false;
    }

    if ($.trim($("#remark").val()) == "") {
        alert("����д��ע��Ϣ");
        return false;
    }
    return true;
}