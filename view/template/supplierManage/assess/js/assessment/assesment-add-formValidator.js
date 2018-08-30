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

    $("#assesName").formValidator({
        onshow: "������������������",
        onfocus: "����������������5���ַ�,���50���ַ�",
        oncorrect: "������������������ƿ���"
    }).inputValidator({
        min: 5,
        max: 50,
        empty: {
            leftempty: false,
            rightempty: false,
            emptyerror: "���������������߲����пշ���"
        },
        onerror: "����������Ʋ��Ϸ�,��ȷ��"
    });

    $("#assesType").formValidator({
        onshow: "��ѡ����������",
        onfocus: "�������ͱ���ѡ��",
        oncorrect: "лл������"
    }).inputValidator({
        min: 1,
        onerror: "���ǲ�������ѡ������������!"
    });

    $("#manageName").formValidator({
        onshow: "��ѡ��������",
        onfocus: "�������룬��ѡ��",
        oncorrect: "��ѡ��ĸ�������Ч"
    }).inputValidator({
        min: 1,
        onerror: "��ѡ������"
    });

    $("#beginDate").formValidator({
        onshow: "��ѡ����ʼ����",
        onfocus: "��ѡ������",
        oncorrect: "����������ںϷ�"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "������Ϸ�������,��:2000-01-01"
    }); //.defaultPassed();

    $("#endDate").formValidator({
        onshow: "��ѡ��ƻ���ֹ����",
        onfocus: "��ѡ�����ڣ�����С����ʼ����Ŷ",
        oncorrect: "����������ںϷ�"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "������Ϸ�������,��:2000-01-01"
    }).compareValidator({
		desid : "beginDate",
		operateor : ">=",
		onerror : "��ֹ���ڲ���С����ʼ����"
	});

});