$(document).ready(function (){

	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg){
		},
		onsuccess: function(){
			if(confirm("������ɹ�,ȷ��������?")){
				return true;
			}else{
				return false;
			}

		}
	});

	//��Ӧ������
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
    }).ajaxValidator({
    	type : "get",
    	url : "index1.php" ,
    	data : "model=supplierManage_temporary_temporary&action=ajaxSuppName&id="+$("#id").val(),
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
    }).defaultPassed();
/**
    //ע���ʽ�
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
	}).defaultPassed();

	//���̵ǼǺ�
	$("#businRegistCode").formValidator({
		onshow : "�����빤�̵ǼǺ�",
		onfocus : "����ʵ��д�Ա����",
		oncorrect : "������ĵǼǺ��ѱ���"
	}).inputValidator({
		min:2,
		max:50,
		empty:{
			leftempty:false,
			rightempty:false,
			emptyerror:"���̵ǼǺ����߲����пո�"
		}
	}).defaultPassed();

	//ҵ����
	$("#busiCode").formValidator({
		onshow : "����������ҵ����",
		onfocus : "����ҵ����",
		oncorrect : "�������ҵ�����ѱ���"
	}).inputValidator({
		min:2,
		max:50,
		empty:{
			leftempty:false,
			rightempty:false,
			emptyerror:"ҵ�������߲����пո�"
		}
	}).defaultPassed();

	//��Ӧ�̵�ַ
	$("#address").formValidator({
        onshow: "�����빩Ӧ�̵�ַ",
        oncorrect: "������Ĺ�Ӧ�̵�ַ��ȷ"
    }).inputValidator({
        min: 1,
        max: 500,

        onerror: "������ĵ�ַ���Ϸ�,��ȷ��"
    }).defaultPassed();

    //�˿�����
	$("#products").formValidator({
        onshow: "��������Ʒ����",
        oncorrect: "���������Ʒ������ȷ"
    }).inputValidator({
        min: 1,
        max: 500,

        onerror: "���������Ʒ���Ʋ��Ϸ�,��ȷ��"
    }).defaultPassed();

    //��������
    $("#foundedDate").formValidator({
        onshow: "��ѡ�񴴽�����",
        onfocus: "��ѡ������",
        oncorrect: "����������ںϷ�"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "������Ϸ�������,���������ڲ���Ϊ��"
    }).defaultPassed();

     $("#foundedDate").formValidator({
        onshow: "��ѡ�񴴽�����",
        onfocus: "��ѡ������",
        oncorrect: "����������ںϷ�"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "������Ϸ�������,���������ڲ���Ϊ��"
    }).defaultPassed();

    $("#effectDate").formValidator({
        onshow: "��ѡ�񹩻���Ч����",
        onfocus: "��ѡ������",
        oncorrect: "����������ںϷ�"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "������Ϸ�������,���ƻ�������Ч����Ϊ��"
    }).defaultPassed();

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
	}).defaultPassed();
	*/
})