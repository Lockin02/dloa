$(document).ready(function (){

	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg){
		},
		onsuccess: function(){
			return true;
		}
	});


    $("#returnDate").formValidator({
        onshow: "��ѡ����������",
        onfocus: "��ѡ����������",
        oncorrect: "OK"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "��ѡ����������"
    });


//    $("#sourceCode").formValidator({
//    	onshow: "��ѡ��Դ�����",
//    	onfocus: "��ѡ��Դ�����",
//    	oncorrect: "OK"
//    }).inputValidator({
//    	min:1,
//    	onerror:"��ѡ��Դ�����"
//    });



     $("#purchManName").formValidator({
    	onshow: "��ѡ��ɹ�Ա",
    	onfocus: "��ѡ��ɹ�Ա",
    	oncorrect: "OK"
    }).inputValidator({
    	min:1,
    	onerror:"��ѡ��ɹ�Ա"
    });
     $("#supplierName").formValidator({
    	onshow: "��ѡ��Ӧ��",
    	onfocus: "��ѡ��Ӧ��",
    	oncorrect: "OK"
    }).inputValidator({
    	min:1,
    	onerror:"��ѡ��Ӧ��"
    });
     $("#stockName").formValidator({
    	onshow: "��ѡ�����ϲֿ�",
    	onfocus: "��ѡ�����ϲֿ�",
    	oncorrect: "OK"
    }).inputValidator({
    	min:1,
    	onerror:"��ѡ�����ϲֿ�"
    });


})