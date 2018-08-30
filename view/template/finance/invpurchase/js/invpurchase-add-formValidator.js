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

				if( accAdd($("#amount" + i).val(),$("#assessment" + i).val(),2)*1 != $("#allCount" + i).val()*1 ){
					alert('��¼��˰�ϼƲ����ںϼƽ�����˰��');
					return false;
				}

			}
			if($("#formDate").val() == ""){
				$("#formDate").val($("#payDate").val()) ;
			}

			//��ֹ�ظ��ύ��֤
			$("input[type='submit']").attr('disabled',true);
		}
	});

	/** ��֤��Ӧ������ * */
	$("#supplierName").formValidator({
		onshow: "�����빩Ӧ������",
		onfocus: "��Ӧ����������2���ַ������50���ַ�",
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

	/** ������֤ * */
	$("#departments").formValidator({
		onshow: "��ѡ����",
		oncorrect: "OK"
	}).inputValidator({
		min: 2,
		max: 50,
		onerror: "��ѡ����"
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

	/** ��֤ҵ��Ա * */
	$("#salesman").formValidator({
		onshow: "��ѡ��ҵ��Ա",
		oncorrect: "OK"
	}).inputValidator({
		min: 2,
		max: 50,
		onerror: "��ѡ��ҵ��Ա"
	});

	/** ��֤��Ʊ���� * */
	$("#objNo").formValidator({
		onshow: "�����뷢Ʊ����",
		onfocus: "��Ʊ��������2���ַ�",
		oncorrect: "������ĺ�����Ч"
	}).inputValidator({
		min: 2,
		max: 300,
		empty: {
			leftempty: false,
			rightempty: false,
			emptyerror: "�������߲���Ϊ��"
		},
		onerror: "������ĺ��벻�Ϸ�������������"
	}).ajaxValidator({
		url : "index1.php?model=finance_invpurchase_invpurchase&action=ajaxCheck",
		success : function(data){
			if(data == "1"){
				return false;
			}else{
				return true;
			}
		},
		buttons: $("#button"),
		error: function(XMLHttpRequest, textStatus, errorThrown){alert("������û�з������ݣ����ܷ�����æ��������"+errorThrown);},
		onerror : "�÷�Ʊ�����Ѵ���",
		onwait : "���ڶԷ�Ʊ������кϷ���У�飬���Ժ�..."
	}).defaultPassed();

	/** ��֤�ɹ���ʽ * */
	$("#pruType").formValidator({
		onshow: "������ɹ���ʽ",
		onfocus: "�ɹ���ʽ����2���ַ������50���ַ�",
		oncorrect: "������Ĳɹ���ʽ��Ч"
	}).inputValidator({
		min: 2,
		max: 50,
		empty: {
			leftempty: false,
			rightempty: false,
			emptyerror: "�����������߲���Ϊ��"
		},
		onerror: "����������ݲ��Ϸ�������������"
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