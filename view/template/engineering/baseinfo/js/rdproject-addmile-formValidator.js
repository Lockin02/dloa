$(document).ready(function(){
	$.formValidator.initConfig({
		formid:"form1",
		onerror:function(msg){
			//alert(msg);
		},
		onsuccess:function(){
			if(confirm("������ɹ���ȷ���ύ��")){
				return true;
			}else{
				return flase;
			}
		}
	});

	//��������ж�
	$("#projectType").formValidator({
		onshow:"��ѡ����Ŀ����",
		onfocus:"��Ŀ�����Ǳ�����",
		oncorrect:"лл"
	}).inputValidator({
		min:"",
		onerror:"���ǲ�������ѡ����Ŀ������"
	}).defaultPassed();



	//��ͨ�������ж�
	$("#projectName").formValidator({
		onshow:"��������Ŀ����",
		onfocus:"��Ŀ�����벻Ҫ�������25������",
		oncorrect:"���������Ŀ���ƿ���"
	}).inputValidator({
		min:1,
		max:50,
		empty:{
			leftempty:false,
			rightempty:false,
			emptyerror:"��Ŀ�������߲����пշ���"
		},
		onerror:"���������Ŀ���Ʋ�����Ҫ������"
	});


	$("#milestoneName").formValidator({
		onshow:"��������̱�����",
		onfocus:"��̱������벻Ҫ�������25������",
		oncorrect:"���������̱����ƿ���"
	}).inputValidator({
		min:1,
		max:50,
		empty:{
			leftempty:false,
			rightempty:false,
			emptyerror:"��̱��������߲����пշ���"
		},
		onerror:"���������̱����Ʋ�����Ҫ������"
	});

	$("#editMilestoneName").formValidator({
		onshow:"������Ҫ���ĵ���̱�����",
		onfocus:"��̱������벻Ҫ�������25������",
		oncorrect:"���������̱����ƿ���"
	}).inputValidator({
		min:1,
		max:50,
		empty:{
			leftempty:false,
			rightempty:false,
			emptyerror:"��̱��������߲����пշ���"
		},
		onerror:"���������̱����Ʋ�����Ҫ������"
	});












});