//表单验证方法
function checkform() {
    if ($("#inputExcel").val() == "") {
        alert("请选择需要导入的EXCEL文件");
        return false;
    }
    $("#importBtn").attr('disabled', "true");
    return true;
}