$(document).ready(function (){

	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg){
		},
		onsuccess: function(){
//			return true;
		}
	});


    $("#sendTime").formValidator({
        onshow: "��ѡ����������",
        onfocus: "��ѡ����������",
        oncorrect: "OK"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "������Ϸ�������,��������Ϊ��"
    }).compareValidator({
		desid : "dateHope",
		operateor : "<=",
		onerror : "�������ڲ��ܴ��������������"
	});;

    $("#dateHope").formValidator({
        onshow: "��ѡ�������������",
        onfocus: "��ѡ�����ڣ�����С���´�����",
        oncorrect: "OK"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "������Ϸ�������,����������ڲ���Ϊ��"
    }).compareValidator({
		desid : "sendTime",
		operateor : ">=",
		onerror : "����������ڲ���С����������"
	});
    $("#batchNumb").formValidator({
    	onshow: "���������κ�",
    	onfocus: "���������κ�",
    	oncorrect: "OK"
    }).inputValidator({
    	min:1,
    	onerror:"���������κ�"
    });

    $("#depName").formValidator({
    	onshow: "��ѡ�����벿��",
    	onfocus: "��ѡ�����벿��",
    	oncorrect: "OK"
    }).inputValidator({
    	min:1,
    	onerror:"��ѡ�����벿��"
    });


    $("#rdprojectSourceName").formValidator({
    	onshow: "��ѡ��Դ���ݺ�",
    	onfocus: "��ѡ��Դ���ݺ�",
    	oncorrect: "OK"
    }).inputValidator({
    	min:1,
    	onerror:"��ѡ��Դ���ݺ�"
    });




    $("#purchDepart").formValidator({
    	onshow: "��ѡ��ɹ�����",
    	onfocus: "��ѡ��ɹ�����",
    	oncorrect: "OK"
    }).inputValidator({
    	min:1,
    	onerror:"��ѡ��ɹ�����"
    });

     $("#sendName").formValidator({
    	onshow: "��ѡ������������",
    	onfocus: "��ѡ������������",
    	oncorrect: "OK"
    }).inputValidator({
    	min:1,
    	onerror:"��ѡ���´�������"
    });
    $("#applyReason").formValidator({
    	onshow: "����������ԭ��",
    	onfocus: "����������ԭ��",
    	oncorrect: "OK"
    }).inputValidator({
    	min:1,
    	onerror:"����������ԭ��"
    })

//     $("select").formValidator({
//    	onshow: "��ѡ��ɹ�����",
//    	onfocus: "��ѡ��ɹ�����",
//    	oncorrect: "OK"
//    }).inputValidator({
//    	min:1,
//    	onerror:"��ѡ��ɹ�����"
//    });


})