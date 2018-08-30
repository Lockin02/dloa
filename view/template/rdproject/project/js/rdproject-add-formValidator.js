$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        },
        onsuccess: function() {
            if (confirm("������ɹ���ȷ��������")) {
                return true;
            } else {
                return false;
            }
        }
    });

//    $("#groupSName").formValidator({
//        onshow: "��ѡ�����",
//        oncorrect: "��ѡ�����Ͽ���"
//    }).inputValidator({
//        min: 2,
//        max: 50,
//        nValue:"��ѡ��...",
//        empty: {
//            leftempty: false,
//            rightempty: false,
//            emptyerror: "������߲����пշ���"
//        },
//        onerror: "��ѡ�����ϷǷ�,��ȷ��"
//    }).functionValidator({
//        fun: function(val, elem) {
//            if (val != "��ѡ��...") {
//                return true;
//            } else {
//                return "��ѡ����Ŀ���";
//            }
//        }
//    });
    $("#IpoId").formValidator({
        onshow: "��ѡ��ļͶ��Ŀ",
        onfocus: "ļͶ��Ŀ�Ǳ�ѡ��",
        oncorrect: "лл������"
    }).inputValidator({
        min: 1,
        onerror: "���ǲ�������ѡ��ļͶ��Ŀ��!"
    });

    $("#projectName").formValidator({
        onshow: "��������Ŀ����",
        onfocus: "��Ŀ��������2���ַ�,���50���ַ�",
        oncorrect: "���������Ŀ���ƿ���"
    }).inputValidator({
        min: 2,
        max: 50,
        empty: {
            leftempty: false,
            rightempty: false,
            emptyerror: "��Ŀ�������߲����пշ���"
        },
        onerror: "����������Ʋ��Ϸ�,��ȷ��"
    }).ajaxValidator({
        type: "get",
        url: "index1.php",
        data: "model=rdproject_project_rdproject&action=ajaxProjectName",
        datatype: "json",
        success: function(data) {
            if (data == "1") {
                return true;
            } else {
                return false;
            }
        },
        buttons: $("#submitSave"),
        error: function() {
            alert("������û�з������ݣ����ܷ�����æ��������");
        },
        onerror: "�����Ʋ����ã������",
        onwait: "���ڶ���Ŀ���ƽ��кϷ���У�飬���Ժ�..."
    });




//    $("#simpleName").formValidator({
//        onshow: "��������Ŀ���",
//        onfocus: "�������2���ַ�,���20���ַ�",
//        oncorrect: "������ļ�ƿ���"
//    }).inputValidator({
//        min: 2,
//        max: 20,
//        empty: {
//            leftempty: false,
//            rightempty: false,
//            emptyerror: "������߲����пշ���"
//        },
//        onerror: "������ļ�ƷǷ�,��ȷ��"
//    });

    $("#projectCode").formValidator({
        onshow: "��������Ŀ���",
        onfocus: "��Ŀ�������5���ַ�,���50���ַ�",
        oncorrect: "���������Ŀ��ſ���"
    }).inputValidator({
        min: 5,
        max: 50,
        empty: {
            leftempty: false,
            rightempty: false,
            emptyerror: "��Ŀ������߲����пշ���"
        },
        onerror: "���������Ŀ��ŷǷ�,��ȷ��"
    }).ajaxValidator({
		type : "get",
		url : "index1.php",
		data : "model=rdproject_project_rdproject&action=checkProjectCode",
		datatype : "json",
		success : function(data) {
			if (data == "1") {
				return true;
			} else {
				return false;
			}
		},
		buttons : $("#submitSave"),
		error : function() {
			alert("������û�з������ݣ����ܷ�����æ��������");
		},
		onerror : "����Ŀ������ظ��������",
		onwait : "���ڶ����ϱ�Ž��кϷ���У�飬���Ժ�..."
	});

	$("#projectType").formValidator({
        onshow: "��ѡ����Ŀ����",
        onfocus: "��Ŀ�����Ǳ�ѡ��",
        oncorrect: "лл������"
    }).inputValidator({
        min: 1,
        onerror: "���ǲ�������ѡ����Ŀ������!"
    });

	$("#projectLevel").formValidator({
        onshow: "��ѡ����Ŀ�����ȼ�",
        onfocus: "���ȼ�����ѡ��",
        oncorrect: "лл������"
    }).inputValidator({
        min: 1,
        onerror: "���ǲ�������ѡ����Ŀ���ȼ���!"
    });

    $("#planDateStart").formValidator({
        onshow: "��ѡ��ƻ���ʼ����",
        onfocus: "��ѡ������",
        oncorrect: "����������ںϷ�"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "������Ϸ�������,���ƻ���ʼ���ڲ���Ϊ��"
    }); //.defaultPassed();

    $("#planDateClose").formValidator({
        onshow: "��ѡ��ƻ��ƻ���������",
        onfocus: "��ѡ�����ڣ�����С�ڼƻ���ʼ����Ŷ",
        oncorrect: "����������ںϷ�"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "������Ϸ�������,���ƻ���ʼ���ڲ���Ϊ��"
    }).compareValidator({
		desid : "planDateStart",
		operateor : ">=",
		onerror : "�ƻ�������ڲ���С�ڼƻ���ʼ����"
	}); // .defaultPassed();

//	$("#appraiseWorkload").formValidator({
//		forcevalid : true,
//		triggerevent : "change",
//		onshow : "��������ƹ����������֣�",
//		onfocus : "����������(1-999999)",
//		oncorrect : "�������������ȷ"
//	}).inputValidator({
//		min : 1,
//		max : 999999,
//		type : "value",
//		onerrormin : "�������ֵ������1-999999֮��",
//		onerror : "��������ƹ�����(����1-999999)"
//	});

    $("#managerName").formValidator({
        onshow: "��ѡ��������",
        onfocus: "�������룬��ѡ��",
        oncorrect: "��ѡ��ĸ�������Ч"
    }).inputValidator({
        min: 1,
        onerror: "��ѡ������"
    });

//    $("#depName").formValidator({
//        onshow: "��ѡ����������",
//        onfocus: "�������룬��ѡ��",
//        oncorrect: "��ѡ��Ĳ�����Ч"
//    }).inputValidator({
//        min: 1,
//        onerror: "��ѡ����������"
//    });

});