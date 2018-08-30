$(document).ready(function (){
	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg){
		},
		onsuccess: function(){
				$("input[type='submit']").attr("disabled","disabled");
				$("#closeBtn").attr("disabled","disabled");
		}
	});


    $("#sendTime").formValidator({
        onshow: "��ѡ�������´�����",
        onfocus: "��ѡ�������´�����",
        oncorrect: "��ѡ�������´�����"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "��ѡ�������´�����"
    });

    $("#dateHope").formValidator({
        onshow: "��ѡ�������������",
        onfocus: "��ѡ�����ڣ�����С�������´�����",
        oncorrect: "OK"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "��ѡ�������������"
    }).compareValidator({
		desid : "sendTime",
		operateor : ">=",
		onerror : "����������ڲ���С�������´�����"
	});


    $("#sendUserId").formValidator({
        onshow: "��ѡ������",
        onfocus: "��ѡ������",
        oncorrect: "OK"
    }).inputValidator({
        min:1,
        onerror: "��ѡ������"
    });

    $("#depName").formValidator({
    	onshow: "��ѡ����",
    	onfocus: "��ѡ����",
    	oncorrect: "OK"
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