$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        },
        onsuccess: function() {
            if (confirm("������ɹ���ȷ���ύ��")) {
                return true;
            } else {
                return false;
            }
        }
    });

//    $(".tipShortTxt").css("width", "275px");
//    $(".tipLongTxt").css("width", "505px");

    $("#groupName").formValidator({
        onshow: "��������Ŀ�������",
        onfocus: "�����������2���ַ�,���50���ַ�",
        oncorrect: "�������������ƿ���"
    }).inputValidator({
        min: 2,
        max: 50,
        empty: {
            leftempty: false,
            rightempty: false,
            emptyerror: "����������߲����пշ���"
        },
        onerror: "����������Ʋ��Ϸ�,��ȷ��"
    }).ajaxValidator({
        type: "get",
        url: "index1.php",
        data: "model=rdproject_group_rdgroup&action=ajaxGroupName",
        datatype: "json",
        success: function(data) {
            if (data == "1") {
                return true;
            } else {
                return false;
            }
        },
        buttons: $("#submitSave"),
        error: function() {
            alert("������û�з������ݣ����ܷ�����æ��������");
        },
        onerror: "�����Ʋ����ã������",
        onwait: "���ڶ�������ƽ��кϷ���У�飬���Ժ�..."
    });
//
//    $("#simpleName").formValidator({
//        onshow: "��������ϼ��",
//        onfocus: "�������2���ַ�,���20���ַ�",
//        oncorrect: "������ļ�ƿ���"
//    }).inputValidator({
//        min: 2,
//        max: 20,
//        empty: {
//            leftempty: false,
//            rightempty: false,
//            emptyerror: "������߲����пշ���"
//        },
//        onerror: "������ļ�ƷǷ�,��ȷ��"
//    });

    $("#groupCode").formValidator({
        onshow: "��������ϱ��",
        onfocus: "��ϱ������5���ַ�,���50���ַ�",
        oncorrect: "���������ϱ�ſ���"
    }).inputValidator({
        min: 5,
        max: 50,
        empty: {
            leftempty: false,
            rightempty: false,
            emptyerror: "��ϱ�����߲����пշ���"
        },
        onerror: "���������ϱ�ŷǷ�,��ȷ��"
    });

    $("#managerName").formValidator({
        onshow: "��ѡ�����������",
        onfocus: "�������룬��ѡ��",
        oncorrect: "��ѡ��ĸ�������Ч"
    }).inputValidator({
        min: 1,
        onerror: "��ѡ����ϸ�����"
    });

//    $("#depName").formValidator({
//        onshow: "��ѡ�������������",
//        onfocus: "�������룬��ѡ��",
//        oncorrect: "��ѡ��Ĳ�����Ч"
//    }).inputValidator({
//        min: 1,
//        onerror: "��ѡ�������������"
//    });

});