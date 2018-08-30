$(document).ready(function (){

	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg){
		},
		onsuccess: function(){

		}
	});

	//��Ӧ������
    $("#suppName").formValidator({
        onshow: "�����빩Ӧ������",
        onfocus: "�����빩Ӧ������",
        oncorrect: "������Ĺ�Ӧ�����ƿ���"
    }).inputValidator({
        min: 2,
        empty: {
            leftempty: false,
            rightempty: false,
            emptyerror: "��Ӧ���������߲����пշ���"
        },
        onerror: "����������Ʋ�����,��ȷ��"
    }).ajaxValidator({
    	type : "get",
    	url : "index1.php" ,
    	data : "model=supplierManage_formal_flibrary&action=ajaxSuppName",
    	datatype : "json",
    	success : function(data){
    		if( data == "1" ){
    			return true;
    		}else{
    			return false;
    		}
    	},
    	//���buttons���ύ��ť��id��
    	buttons : $("#saveAndNext"),
    	error : function(){
    		alert("������û�з������ݣ����ܷ�����æ��������");
    	},
    	onerror : "�����Ʋ����ã������",
    	onwait : "���ڶԹ�Ӧ�����ƽ��кϷ���У�飬���Ժ�..."
    });

    //ע���ʽ�
//	$("#registeredFunds").formValidator({
//		onshow : "������ע���ʽ�",
//		onfocus : "������ע���ʽ�",
//		oncorrect : "OK"
//	}).inputValidator({
//		min : 1,
//
//		onerror : "������ע���ʽ�"
//	});

	//��Ӧ�̵�ַ
	$("#address").formValidator({
        onshow: "�����빩Ӧ�̵�ַ",
        oncorrect: "OK"
    }).inputValidator({
        min: 1,
        max: 500,

        onerror: "�����빩Ӧ�̵�ַ"
    });

    //�˿�����
	$("#products").formValidator({
        onshow: "��������Ҫ��Ӫ��Χ",
        oncorrect: "OK"
    }).inputValidator({
        min: 1,
        max: 500,

        onerror: "��Ҫ��Ӫ��ΧΪ��,������"
    });

    //�˿�����
	$("#legalRepre").formValidator({
        onshow: "�����뷨�˴���",
        oncorrect: "OK"
    }).inputValidator({
        min: 1,
        max: 50,

        onerror: "���˴���Ϊ��,������"
    });

	//���̵ǼǺ�
	$("#businRegistCode").formValidator({
		onshow : "�����빤�̵ǼǺ�",
		oncorrect : "OK"
	}).inputValidator({
		min:2,
		max:50,
		onerror: "�����빤�̵ǼǺ�"
	});

	//Ӫҵִ�ձ��
	$("#businessCode").formValidator({
		onshow : "������Ӫҵִ�ձ��",
		oncorrect : "OK"
	}).inputValidator({
		min:2,
		max:50,
		onerror: "������Ӫҵִ�ձ��"
	});
/**
    //ע���ʽ�
	$("#registeredFunds").formValidator({
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

	//��Ӧ�̱��
	$("#busiCode").formValidator({
		onshow : "�����빩Ӧ�̱��",
		onfocus : "���빩Ӧ�̱��",
		oncorrect : "OK"
	}).inputValidator({
		min:2,
		max:50,
		empty:{
			leftempty:false,
			rightempty:false,
			emptyerror:"��Ӧ�̱�����߲����пո�"
		}
	});

	//��Ӧ�̵�ַ
	$("#address").formValidator({
        onshow: "�����빩Ӧ�̵�ַ",
        oncorrect: "������Ĺ�Ӧ�̵�ַ��ȷ"
    }).inputValidator({
        min: 1,
        max: 500,

        onerror: "������ĵ�ַ���Ϸ�,��ȷ��"
    });

    //�˿�����
	$("#products").formValidator({
        onshow: "�����뾭Ӫ��Χ",
        oncorrect: "OK"
    }).inputValidator({
        min: 1,
        max: 500,

        onerror: "��Ӫ��ΧΪ��,������"
    });

    //��������
    $("#registeredDate").formValidator({
        onshow: "��ѡ�񴴽�����",
        onfocus: "��ѡ������",
        oncorrect: "����������ںϷ�"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "������Ϸ�������,���������ڲ���Ϊ��"
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
    });

    $("#effectDate").formValidator({
        onshow: "��ѡ�񹩻���Ч����",
        onfocus: "��ѡ������",
        oncorrect: "����������ںϷ�"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "������Ϸ�������,���ƻ�������Ч����Ϊ��"
    });

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
	});
	*/
})