//�ύУ��
function checkForm() {

    // ��֤����ԭ��
    if($("#applyTbl").find("input:checkbox[type='checkbox']:checked").length == 0) {
        alert("�빴ѡ����ԭ��");
        return false;
    }

    var outType = $('input:radio[name="apply[outType]"]:checked').val();
    if (outType == 1 || outType == 2) {//�������߷ְ�
        // ����޼�
        if ($("#limitPrice_v").val() == "") {
            alert('����д����޼�');
            return false;
        }
        // ����޼��ۿ�
        if ($("#limitDiscount").val() == "") {
            alert('����д����޼��ۿ�');
            return false;
        }
        //  ����������
        if ($("#workloadDescription").val() == "") {
            alert('����д����������');
            return false;
        }
        // ��ĿҪ��
        if ($("#projectRequest").val() == "") {
            alert('����д��ĿҪ��');
            return false;
        }
        // �ͻ�Ҫ��
        if ($("#customerRequest").val() == "") {
            alert('����д�ͻ�Ҫ��');
            return false;
        }
        $("#wapdiv").html('');
    }
}

// �����޼�
function calculateLimit(type) {
    // ˰ǰ���
    var projectMoneyWithTax = $("#projectMoneyWithTax").val();
    if (projectMoneyWithTax == "") {
        $("#limitDiscount").val("");
        setMoney("limitPrice", "");
    } else {
        var limitPrice;
        var limitDiscount;
        if (type == 0) {
            limitPrice = $("#limitPrice").val();
            limitDiscount = accMul(accDiv(limitPrice, projectMoneyWithTax, 3), 10, 2);
            $("#limitDiscount").val(limitDiscount);
        } else {
            limitDiscount = accDiv($("#limitDiscount").val(), 10, 4);
            limitPrice = accMul(limitDiscount, projectMoneyWithTax, 2);
            setMoney("limitPrice", limitPrice, 2);
        }
    }
}