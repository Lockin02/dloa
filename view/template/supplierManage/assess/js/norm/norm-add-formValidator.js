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

    $("#normName").formValidator({
        onshow: "������ָ������",
        onfocus: "ָ����������5���ַ�,���50���ַ�",
        oncorrect: "�������ָ�����ƿ���"
    }).inputValidator({
        min: 5,
        max: 50,
        empty: {
            leftempty: false,
            rightempty: false,
            emptyerror: "ָ���������߲����пշ���"
        },
        onerror: "����������Ʋ��Ϸ�,��ȷ��"
    }).functionValidator({
        fun: function(val, elem) {
            if (val != "��ѡ��...") {
                return true;
            } else {
                return "��ѡ�������ָ��";
            }
        }
    });

    $("#normTotal").formValidator({
        onshow: "�������ܷ�",
        onfocus: "�ֱܷ�����1-9999֮������",
        oncorrect: "��������ֿܷ���"
    }).inputValidator({
        min: 1,
        max: 9999,
        empty: {
            leftempty: false,
            rightempty: false,
            emptyerror: "�ܷ����߲����пշ���"
        },
        type: "number",
        onerror: "������Ϸ����ܷ�,���ֲܷ���Ϊ��"
    }).defaultPassed();

	$("#weight").formValidator({
        onshow: "������Ȩ��",
        onfocus: "Ȩ�ر�����1-100֮������",
        oncorrect: "�������Ȩ�ؿ���"
    }).inputValidator({
        min: 1,
        max: 100,
        empty: {
            leftempty: false,
            rightempty: false,
            emptyerror: "Ȩ�����߲����пշ���"
        },
        type: "number",
        onerror: "������Ϸ���Ȩ��,����Ȩ�ز���Ϊ��"
    });

    $("#asseserName").formValidator({
        onshow: "��ѡ������",
        onfocus: "�������룬��ѡ��",
        oncorrect: "��ѡ��ĸ�������Ч"
    }).inputValidator({
        min: 1,
        onerror: "��ѡ������"
    });

});