//����֤����
function checkform() {
    if ($("#inputExcel").val() == "") {
        alert("��ѡ����Ҫ�����EXCEL�ļ�");
        return false;
    }
    $("#importBtn").attr('disabled', "true");
    return true;
}