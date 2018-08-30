$(document).ready(function (){

	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg){
		},
		onsuccess: function(){
			if(confirm("������ɹ�,ȷ���ύ��?")){
				return true;
			}else{
				return false;
			}

		}
	});
    $("#suppName").formValidator({
        onshow: "�����빩Ӧ������",
        onfocus: "��Ӧ����������2���ַ�,���50���ַ�",
        oncorrect: "������Ĺ�Ӧ�����ƿ���"
    }).inputValidator({
        min: 2,
        max: 50,
        empty: {
            leftempty: false,
            rightempty: false,
            emptyerror: "��Ӧ���������߲����пշ���"
        },
        onerror: "����������Ʋ��Ϸ�,��ȷ��"
    });


    $("#busiCode").formValidator({
        onshow: "������ҵ����",
        onfocus: "ҵ��������5���ַ�,���50���ַ�",
        oncorrect: "�������ҵ���ſ���"
    }).inputValidator({
        min: 5,
        max: 50,
        empty: {
            leftempty: false,
            rightempty: false,
            emptyerror: "ҵ�������߲����пշ���"
        },
        onerror: "�������ҵ���ŷǷ�,��ȷ��"
    });
	$("#regiCapital").formValidator({
		forcevalid : true,
		triggerevent : "change",
		onshow : "������ע���ʽ����֣�",
		onfocus : "����������",
		oncorrect : "�������������ȷ"
	}).inputValidator({
		min : 1,
		max : 999999999999999999999999999,
		type : "value",

		onerror : "������ע���ʽ�"
	});
	$("#address").formValidator({
        onshow: "�����빩Ӧ�̵�ַ",
        oncorrect: "������Ĺ�Ӧ�̵�ַ��ȷ"
    }).inputValidator({
        min: 1,
        max: 500,

        onerror: "������ĵ�ַ���Ϸ�,��ȷ��"
    });
	$("#products").formValidator({
        onshow: "��������Ʒ����",
        oncorrect: "���������Ʒ������ȷ"
    }).inputValidator({
        min: 1,
        max: 500,

        onerror: "���������Ʒ���Ʋ��Ϸ�,��ȷ��"
    });
    $("#foundedDate").formValidator({
        onshow: "��ѡ�񴴽�����",
        onfocus: "��ѡ������",
        oncorrect: "����������ںϷ�"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "������Ϸ�������,���������ڲ���Ϊ��"
    }); //.defaultPassed();
     $("#foundedDate").formValidator({
        onshow: "��ѡ�񴴽�����",
        onfocus: "��ѡ������",
        oncorrect: "����������ںϷ�"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "������Ϸ�������,���������ڲ���Ϊ��"
    }); //.defaultPassed();})
    $("#effectDate").formValidator({
        onshow: "��ѡ�񹩻���Ч����",
        onfocus: "��ѡ������",
        oncorrect: "����������ںϷ�"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "������Ϸ�������,���ƻ�������Ч����Ϊ��"
    }); //.defaultPassed();

    $("#failureDate").formValidator({
        onshow: "��ѡ�񹩻�ʧЧ����",
        onfocus: "��ѡ�����ڣ�����С�ڹ���ʧЧ����",
        oncorrect: "����������ںϷ�"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "������Ϸ�������,������ʧЧ���ڲ���Ϊ��"
    }).compareValidator({
		desid : "effectDate",
		operateor : ">=",
		onerror : "�ƻ�������ڲ���С�ڹ�����Ч����"
	}); // .defaultPassed();
})