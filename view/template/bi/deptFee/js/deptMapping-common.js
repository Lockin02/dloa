// ����֤
var checkSubmit = function() {
    try {
        if ($("#business").val() == "") {
            alert("��Ҫ��д��ҵ��λ");
            return false;
        }
        if ($("#secondDept").val() == "") {
            alert("��Ҫ��д��������");
            return false;
        }
    } catch (e) {
        console.log(e);
        alert('����֤�쳣');
        return false;
    }
    return true;
};