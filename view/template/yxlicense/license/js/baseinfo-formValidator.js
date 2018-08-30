$(document).ready(function () {
    $.formValidator.initConfig({
        formid: "form1",
        //autotip: true,
        onerror: function (msg) {
            //alert(msg);
        }
    });

    /**
     * ������֤
     */
    $("#name").formValidator({
        onshow: "����������",
        onfocus: "��������1���ַ������50���ַ�",
        oncorrect: "�������������Ч"
    }).inputValidator({
        min: 1,
        max: 50,
        empty: {
            leftempty: false,
            rightempty: false,
            emptyerror: "�������߲���Ϊ��"
        },
        onerror: "����������Ʋ��Ϸ�������������"
    }).ajaxValidator({
        type: "GET",
        url: "index1.php",
        data: "model=yxlicense_license_baseinfo&action=ajaxDataCode",
        dataType: "json",
        success: function (data) {
            return data == "0";
        },
        //buttons: $("#button"),
        error: function () {
            alert("������û�з������ݣ����ܷ�����æ��������");
        },
        onError: "�����Ʋ����ã����������",
        onWait: "���ڽ���У�飬���Ժ�..."
    });

    /**
     *������ҳ����ж��Ƿ�����
     */
    checkIsUse();

});
//��ȡ��ǰҳ���URL
function saveHtm() {
    var id = $("#id").val();
    var name = $("#name").val();
    $.ajax({// �������к�
        type: "get",
        async: false,
        url: "?model=yxlicense_license_baseinfo&action=saveHtm",
        data: {
            "id": id,
            "name": name
        }
    })
}

//����Ƿ�ʹ��
function checkIsUse() {
    var status = $("#isUseHidden").val();
    $("input[name='baseinfo[isUse]']").each(function () {
        if ($(this).val() == status) {
            $(this).attr("checked", true);
            return false;
        }
    });
}