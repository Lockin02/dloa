// 表单验证
var checkSubmit = function() {
    try {
        if ($("#business").val() == "") {
            alert("需要填写事业单位");
            return false;
        }
        if ($("#secondDept").val() == "") {
            alert("需要填写二级部门");
            return false;
        }
    } catch (e) {
        console.log(e);
        alert('表单验证异常');
        return false;
    }
    return true;
};