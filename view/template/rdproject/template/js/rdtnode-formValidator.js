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
/** ��֤�ڵ����� * */
$("#nodeEl").formValidator({
	onshow : "������ڵ�����",
	onfocus : "�ڵ���������2���ַ������50���ַ�",
	oncorrect : "������Ľڵ�������Ч"
}).inputValidator({
	min : 2,
	max : 50,
	empty : {
		leftempty : false,
		rightempty : false,
		emptyerror : "�ڵ��������߲���Ϊ��"
	},
	onerror : "������Ľڵ����Ʋ��Ϸ�������������"
});

});
