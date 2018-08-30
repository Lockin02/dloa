$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        },
        onsuccess : function(msg){
			for(var i = 1 ;i <= $("#invnumber").val()*1 ; i++ ){
				if( $("#productId" + i).length == 0 ) continue;
				if($("#productId" + i).val() == ""){
					alert('���ܴ�������Ϊ�յ���');
					return false;
				}
				if($("#amount" + i).val() == "" || $("#amount" + i).val()*1 == 0){
					alert('���ϱ�����д��Ӧ�Ľ���ҽ���Ϊ0');
					return false;
				}
			}
        }

    });

	/** ��֤��Ӧ������ * */
	$("#supplierName").formValidator({
		onshow : "�����빩Ӧ������",
		onfocus : "��Ӧ����������2���ַ������50���ַ�",
		oncorrect : "�������������Ч"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "�������߲����пշ���"
		},
		onerror : "����������Ʋ��Ϸ�������������"
	});

	/** ��֤��Ʊ���� * */
	$("#objNo").formValidator({
		onshow : "�����뷢Ʊ����",
		onfocus : "��Ʊ��������2���ַ�",
		oncorrect : "������ĺ�����Ч"
	}).inputValidator({
		min : 2,
		max : 300,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "�������߲���Ϊ��"
		},
		onerror : "������ĺ��벻�Ϸ�������������"
	});

	/** ��֤��������˾ * */
	$("#businessBelongName").formValidator({
		onshow: "�����������˾",
		onfocus: "������˾��������2���ַ������50���ַ�",
		oncorrect: "�������������Ч"
	}).inputValidator({
		min: 2,
		max: 50,
		empty: {
			leftempty: false,
			rightempty: false,
			emptyerror: "�������߲����пշ���"
		},
		onerror: "����������Ʋ��Ϸ�������������"
	});
	
	/** ��֤�ɹ���ʽ * */
	$("#pruType").formValidator({
		onshow : "������ɹ���ʽ",
		onfocus : "��Ʊ��������2���ַ������50���ַ�",
		oncorrect : "������Ĳɹ���ʽ��Ч"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "�����������߲���Ϊ��"
		},
		onerror : "����������ݲ��Ϸ�������������"
	});

	/** ��֤�������� * */
	$("#payDate").formValidator({
	    onshow: "��ѡ�񸶿�����",
	    onfocus: "��ѡ������",
	    oncorrect: "����������ںϷ�"
	}).inputValidator({
	    min: "1900-01-01",
	    max: "2100-01-01",
	    type: "date",
	    onerror: "���ڱ�����\"1900-01-01\"��\"2100-01-01\"֮��"
	});

	/** ��֤�������� * */
	$("#formDate").formValidator({
	    onshow: "��ѡ�񵥾�����",
	    onfocus: "��ѡ������",
	    oncorrect: "����������ںϷ�"
	}).inputValidator({
	    min: "1900-01-01",
	    max: "2100-01-01",
	    type: "date",
	    onerror: "���ڱ�����\"1900-01-01\"��\"2100-01-01\"֮��"
	});
});