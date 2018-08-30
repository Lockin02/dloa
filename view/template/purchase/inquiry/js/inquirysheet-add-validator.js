$(document).ready(function (){

	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg){
		},
		onsuccess: function(){
//			return true;
		}
	});


    $("#deptName").formValidator({
    	onshow: "��ѡ����",
    	onfocus: "��ѡ����",
    	oncorrect: "��ѡ���˲���"
    }).inputValidator({
    	min:1,
    	onerror:"��ѡ����"
    });

     $("#purcherName").formValidator({
    	onshow: "��ѡ��ɹ�Ա����",
    	onfocus: "��ѡ��ɹ�Ա����",
    	oncorrect: "��ѡ���˲ɹ�Ա����"
    }).inputValidator({
    	min:1,
    	onerror:"��ѡ��ɹ�Ա����"
    });


    $("#inquiryBgDate").formValidator({
        onshow: "��ѡ��ѯ������",
        onfocus: "��ѡ������",
        oncorrect: "����������ںϷ�"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "������Ϸ�������,ѯ������Ϊ��"
    });

    $("#inquiryEndDate").formValidator({
        onshow: "��ѡ�񱨼۽�ֹ����",
        onfocus: "��ѡ�����ڣ�����С��ѯ������",
        oncorrect: "����������ںϷ�"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "������Ϸ�������,���۽�ֹ���ڲ���Ϊ��"
    }).compareValidator({
		desid : "inquiryBgDate",
		operateor : ">=",
		onerror : "���۽�ֹ���ڲ���С��ѯ������"
	});


    $("#effectiveDate").formValidator({
        onshow: "��ѡ����Ч����",
        onfocus: "��ѡ������",
        oncorrect: "����������ںϷ�"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "������Ϸ�������,��Ч����Ϊ��"
    });

    $("#expiryDate").formValidator({
        onshow: "��ѡ��ʧЧ����",
        onfocus: "��ѡ�����ڣ�����С����Ч����",
        oncorrect: "����������ںϷ�"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "������Ϸ�������,ʧЧ���ڲ���Ϊ��"
    }).compareValidator({
		desid : "effectiveDate",
		operateor : ">=",
		onerror : "ʧЧ���ڲ���С����Ч����"
	});
})