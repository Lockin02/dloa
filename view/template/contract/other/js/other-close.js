$(document).ready(function() {
    // ��֤��Ϣ
    validate({
        closeReason: {
            required: true
        }
    });

    // ����Ǹ����ͬ����ʾ�����Ϣ
    var fundType = $("#fundType").val();
    if (fundType == 'KXXZB') {
        $("#showMoney").show();
    }

    var submitBtn = $("#submitBtn");
    submitBtn.bind("click", function() {
        // �����ﴦ��ر�����
        if (fundType == 'KXXZB') {
            // ��̯���
            var costMoney = $("#costMoney").val();

            // ������з�̯��������߼�����
            if (costMoney != '' && parseFloat(costMoney) != 0) {
                var orderMoney = $("#orderMoney").val(); // ��ͬ���
                var hookMoney = $("#hookMoney").val(); // �������

                // �����ͬδ�����꣬���ҷ�̯�����ڹ�����������δ���
                if (parseFloat(hookMoney) != parseFloat(orderMoney) && parseFloat(costMoney) != parseFloat(hookMoney)) {
                    alert('��ͬδ������ɣ������Ҫ�رգ���֪ͨ������˷�Ʊ�������г�������ķ�̯��');
                    return false;
                }
            }
        }

        // �ύ��
        $("form").submit();
    });

    // �ж�Ȩ�ޣ��ı��ύ��ť��ֵ
    if ($("#closeLimit").val() == "1") {
        submitBtn.val("�ύ");
    }
});