$(document).ready(function() {
	var id=$("#id").val();
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        },
        onsuccess: function() {
            if (confirm("������ɹ���ȷ���ύ��")) {
                return true;
            } else {
                return false;
            }
        }
    });

//    $(".tipShortTxt").css("width", "275px");
//    $(".tipLongTxt").css("width", "505px");

    $("#projectName").formValidator({
        onshow: "��������Ŀ��Ŀ����",
        onfocus: "��Ŀ��������2���ַ�,���50���ַ�",
        oncorrect: "���������Ŀ���ƿ���",
        onempty: "��Ŀ���Ʋ���Ϊ��"
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
            if (data == "1" || $("#projectNameOld").val()==$("#projectName").val() ) {
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
    }).defaultPassed();



//
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
//    }).defaultPassed();;
//
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
    })
    .ajaxValidator({
		type : "get",
		url : "index1.php",
		data : "model=rdproject_project_rdproject&action=checkProjectCode&id="+id,
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
	})
	.defaultPassed();
///**
// *
//	$("#projectType").formValidator({
//        onshow: "��ѡ����Ŀ����",
//        onfocus: "��Ŀ�����Ǳ�ѡ��",
//        oncorrect: "лл������"
//    }).inputValidator({
//        min: 1,
//        onerror: "���ǲ�������ѡ����Ŀ������!"
//    });
//**/
//
	$("#projectLevel").formValidator({
        onshow: "��ѡ����Ŀ�����ȼ�",
        onfocus: "���ȼ�����ѡ��",
        oncorrect: "лл������"
    }).inputValidator({
        min: 1,
        onerror: "���ǲ�������ѡ����Ŀ���ȼ���!"
    }).defaultPassed();
//
//    $("#planDateStart").formValidator({
//        onshow: "��ѡ��ƻ���ʼ����",
//        onfocus: "��ѡ������",
//        oncorrect: "����������ںϷ�"
//    }).inputValidator({
//        min: "1900-01-01",
//        max: "2000-01-01",
//        type: "date",
//        onerror: "���ڱ�����\"1900-01-01\"��\"2000-01-01\"֮��"
//    }).defaultPassed();
//
//    $("#planDateClose").formValidator({
//        onshow: "��ѡ��ƻ��ƻ���������",
//        onfocus: "��ѡ�����ڣ�����С�ڼƻ���ʼ����Ŷ",
//        oncorrect: "����������ںϷ�"
//    }).inputValidator({
//        min: "1900-01-01",
//        max: "2000-01-01",
//        type: "date",
//        onerror: "���ڱ�����\"1900-01-01\"��\"2000-01-01\"֮��"
//    }).compareValidator({
//		desid : "planDateStart",
//		operateor : ">=",
//		onerror : "�ƻ�������ڲ���С�ڼƻ���ʼ����"
//	}).defaultPassed();
//
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
//	}).defaultPassed();
//
    $("#managerName").formValidator({
        onshow: "��ѡ��������",
        onfocus: "�������룬��ѡ��",
        oncorrect: "��ѡ��ĸ�������Ч"
    }).inputValidator({
        min: 1,
        onerror: "��ѡ������"
    }).defaultPassed();
//
//    $("#depName").formValidator({
//        onshow: "��ѡ����������",
//        onfocus: "�������룬��ѡ��",
//        oncorrect: "��ѡ��Ĳ�����Ч"
//    }).inputValidator({
//        min: 1,
//        onerror: "��ѡ����������"
//    }).defaultPassed();

});