$(document).ready(function(){
	$.formValidator.initConfig({
		formid:"form1",
		onerror:function(msg){
		},
		onsuccess:function(){
			return true;
		}
	});


	//�����Ŀ����ʱ���ж�
	$("#projectType").formValidator({
		onshow:"��������Ŀ��������",
		onfocus:"��Ŀ���������벻Ҫ�������25������",
		oncorrect:"���������Ŀ�������ƿ���"
	}).inputValidator({
		min:1,
		max:50,
		empty:{
			leftempty:false,
			rightempty:false,
			emptyerror:"��Ŀ�����������߲����пշ���"
		},
		onerror:"���������Ŀ�������Ʋ�����Ҫ������"
	});

	//����Ŀ���ͱ����Ψһ���ж�
	$("#typeCode").formValidator({
		onshow:"����������Ŀ���͵�Ψһ����",
		onfocus:"���ô�д��ĸ���룬��Ҫ����25����ĸ",
		oncorrect:"���������Ŀ���ͱ������"
	}).inputValidator({
		min:1,
		max:50,
		empty:{
			leftempty:false,
			rightempty:false,
			emptyerror:"��Ŀ���ͱ������߲����пշ���"
		},
		onerror:"���������Ŀ���ͱ��벻����Ҫ������"
	});

});



//ǿ��ת�����ͣ�����Ŀ���ͱ���ת���ɴ�д��ĸ
function upperCase(){
//	var x = document.getElementById("typeCode").value
//	document.getElementById("typeCode").value = x.toUpperCase()
}