$(document).ready(function (){
	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg){
		},
		onsuccess: function(){
			return true;
		}
	});


    $("#sendTime").formValidator({
        onshow: "��ѡ���´�����",
        onfocus: "��ѡ���´�����",
        oncorrect: "����������ںϷ�"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "������Ϸ�������,�´�����Ϊ��"
    });

    $("#dateHope").formValidator({
        onshow: "��ѡ�������������",
        onfocus: "��ѡ�����ڣ�����С���´�����",
        oncorrect: "����������ںϷ�"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "������Ϸ�������,����������ڲ���Ϊ��"
    }).compareValidator({
		desid : "sendTime",
		operateor : ">=",
		onerror : "����������ڲ���С���´�����"
	});


    $("#contractName").formValidator({
        onshow: "��ѡ���ͬ",
        onfocus: "��ѡ���ͬ",
        oncorrect: "��ѡ���˺�ͬ"
    }).inputValidator({
        min:1,
        onerror: "��ѡ���ͬ"
    });

    $("#depName").formValidator({
    	onshow: "��ѡ����",
    	onfocus: "��ѡ����",
    	oncorrect: "��ѡ���˲���"
    }).inputValidator({
    	min:1,
    	onerror:"��ѡ����"
    });

    $("#userName").formValidator({
    	onshow : "��ѡ��ʹ����",
    	onfocus:"��ѡ��ʹ����",
    	oncorrect:"ѡ����ʹ����"
    }).inputValidator({
    	min:1,
    	onerror:"��ѡ��ʹ����"
    });
        $("#sendName").formValidator({
    	onshow: "��ѡ���´�������",
    	onfocus: "��ѡ���´�������",
    	oncorrect: "��ѡ�����´�������"
    }).inputValidator({
    	min:1,
    	onerror:"��ѡ���´�������"
    });

})