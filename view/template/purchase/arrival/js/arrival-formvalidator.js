$(document).ready(function (){

	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg){
		},
		onsuccess: function(){
			return true;
		}
	});


    $("#arrivalDate").formValidator({
        onshow: "��ѡ����������",
        onfocus: "��ѡ����������",
        oncorrect: "OK"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "��ѡ����������"
    });


//    $("#purchaseCode").formValidator({
//    	onshow: "��ѡ�񶩵���",
//    	onfocus: "��ѡ�񶩵���",
//    	oncorrect: "OK"
//    }).inputValidator({
//    	min:1,
//    	onerror:"��ѡ�񶩵���"
//    });


    $("#supplierName").formValidator({
    	onshow: "��ѡ��Ӧ��",
    	onfocus: "��ѡ��Ӧ��",
    	oncorrect: "OK"
    }).inputValidator({
    	min:1,
    	onerror:"��ѡ��Ӧ��"
    });




    $("#stockName").formValidator({
    	onshow: "��ѡ�����ϲֿ����� ",
    	onfocus: "��ѡ�����ϲֿ����� ",
    	oncorrect: "OK"
    }).inputValidator({
    	min:1,
    	onerror:"��ѡ�����ϲֿ����� "
    });

     $("#purchManName").formValidator({
    	onshow: "��ѡ��ɹ�Ա",
    	onfocus: "��ѡ��ɹ�Ա",
    	oncorrect: "OK"
    }).inputValidator({
    	min:1,
    	onerror:"��ѡ��ɹ�Ա"
    });


})