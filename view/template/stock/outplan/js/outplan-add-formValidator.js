$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        },
		onsuccess : function(msg) {
			var ifSubmit = true;
			if( $('#pageAction').val()=='change' ){
				var temp = $('#rowNum').val();
				var noRows = $('#noRows').val();
				if( noRows == 1 ){
					alert( "δ��д�����豸��ϸ��Ϣ���������´" )
					ifSubmit = false;
				}
				for(var i=1;i<=temp;i++){
					var rowDisplay = $('#number' + i).parent().parent().get(0).style.display;
					if( $('#number' + i).val() == '' && rowDisplay != 'none' ){
						alert( '�豸��������������ȷ�����룡' );
						return false;
					}
				}
			}
			return ifSubmit;
		}

    });
///** �ֿ� * */
//$("#stockCode").formValidator({
//	onshow : "�����뷢���ֿ�",
//	onfocus : "�����ֿ�����2���ַ������50���ַ�",
//	oncorrect : "������ķ����ֿ���Ч"
//}).inputValidator({
//	min : 2,
//	max : 50,
//	empty : {
//		leftempty : false,
//		rightempty : false,
//		emptyerror : "�����ֿ����߲���Ϊ��"
//	},
//	onerror : "������ķ����ֿⲻ�Ϸ�������������"
//});
/** �ƻ��������� * */
 $("#shipPlanDate").formValidator({
	    onshow: "��ѡ��ƻ�����",
	    onfocus: "��ѡ������",
	    oncorrect: "����������ںϷ�"
	}).inputValidator({
	    min: "1900-01-01",
	    max: "2100-01-01",
	    type: "date",
	    onerror: "���ڱ�����\"1900-01-01\"��\"2100-01-01\"֮��"
	});

});
