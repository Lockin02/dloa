$(document).ready(function(){
	$("#milestoneplanTemplateName").formValidator({
		onshow:"��������̱��ƻ�ģ������",
		onfocus:"ģ�������벻Ҫ�������25������",
		oncorrect:"�������ģ�����Ʋ�����"
	}).inputValidator({
		min:1,
		max:50,
		empty:{
			leftempty:false,
			rightempty:false,
			emptyerror:"ģ���������߲����пշ���"
		},
		onerror:"�������ģ�����Ʋ�����Ҫ������"
	});
});