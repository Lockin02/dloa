$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
			return false;
        },
        onsuccess : function(){
        	return true;
        }
    });

	$("#name").formValidator({
		onshow : "��������������",
		onfocus : "������������2���ַ�,���50���ַ�",
		oncorrect : "��������������ƿ���"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "�����������߲����пշ���"
		},
		onerror : "���������������,��ȷ��"
	});

	$("#appraiseWorkload").formValidator({
		empty:true,
    	onshow:"��Ϊ��",
    	oncorrect:"OK"
	}).inputValidator({
		min:0.1,
		type:"value",
		onerrormin:"�������ֵ������ڵ���0.1",
		onerror:"��������ȷ��ֵ"
	});//.defaultPassed();

	$("#planDuration").formValidator({
		empty:true,
    	onshow:"��Ϊ��",
    	oncorrect:"OK"
	}).inputValidator({
		min:0.1,
		type:"value",
		onerrormin:"�������ֵ������ڵ���0.1",
		onerror:"��������ȷ��ֵ"
	});//.defaultPassed();
})