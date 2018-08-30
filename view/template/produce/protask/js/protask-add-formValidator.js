$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        },
		onsuccess : function(msg) {
			var ifSubmit = true;
			var temp = $('#rowNum').val();
			var noRows = $('#noRows').val();
			if( noRows == 1 ){
				alert( "δ��д�����豸��ϸ��Ϣ���������´" )
				ifSubmit = false;
			}
			for(var i=1;i<=temp;i++){
				if( $('#number' + i).val() == '' ){
					alert( '�豸��������������ȷ�����룡' );
					return false;
				}
			}
			return ifSubmit;
		}

    });
/** �ֿ� * */
$("#issuedDeptName").formValidator({
	onshow : "�������µ�����",
	onfocus : "�µ���������2���ַ������50���ַ�",
	oncorrect : "��������µ�������Ч"
}).inputValidator({
	min : 2,
	max : 50,
	empty : {
		leftempty : false,
		rightempty : false,
		emptyerror : "�µ��������߲���Ϊ��"
	},
	onerror : "��������µ����Ų��Ϸ�������������"
});
/** �ֿ� * */
$("#execDeptName").formValidator({
	onshow : "������ִ�в���",
	onfocus : "ִ�в�������2���ַ������50���ַ�",
	oncorrect : "�������ִ�в�����Ч"
}).inputValidator({
	min : 2,
	max : 50,
	empty : {
		leftempty : false,
		rightempty : false,
		emptyerror : "ִ�в������߲���Ϊ��"
	},
	onerror : "�������ִ�в��Ų��Ϸ�������������"
});
/** �ƻ��������� * */
 $("#referDate").formValidator({
	    onshow: "��ѡ�񽻻�����",
	    onfocus: "��ѡ������",
	    oncorrect: "����������ںϷ�"
	}).inputValidator({
	    min: "1900-01-01",
	    max: "2100-01-01",
	    type: "date",
	    onerror: "���ڱ�����\"1900-01-01\"��\"2100-01-01\"֮��"
	});

});
