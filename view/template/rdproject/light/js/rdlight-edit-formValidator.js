$(document).ready(function(){
	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg){
		},
		onsuccess : function(){
			if(confirm("������ɹ�,ȷ���ύ��")){
				return true;
			}else{
				return false;
				}
			}
		});

		$("#Max").formValidator({
			onshow : "�����뷶Χ����",
			oncorrect : "�������ֵ��ȷ"

		}).inputValidator({
			min :1,
			onerror : "�������ֵ,��ȷ��"
		})
//		.compareValidator({
//			desid : "Min" ,
//			operateor : ">" ,
//			onerror : "��Χ���޲���С�ڷ�Χ����"
//		})
//		.ajaxValidator({
//			type : "get",
//			url : "index1.php",
//			data : "model=rdproject_light_rdlight&action=ajaxRange&notId=" + $("#id").val() ,
//			datatype : "json",
//			success : function(data) {
//				if (data == "1") {
//					return true;
//				} else {
//					return false;
//				}
//			},
//			buttons : $("#submitSave"),
//			error : function() {
//				alert("������û�з������ݣ����ܷ�����æ��������");
//			},
//			onerror : "���뷶Χ����,���ܷ�Χ��������ʶ�Ƴ�ͻ",
//			onwait : "���ڶ���Ŀ���ƽ��кϷ���У�飬���Ժ�..."
//		})
		;

		$("#Min").formValidator({
			onshow : "�����뷶Χ����",
			oncorrect : "�������ֵ��ȷ"

		}).inputValidator({
			min :1,
			onerror : "�������ֵ,��ȷ��"
		})
//		.compareValidator({
//			desid : "Max" ,
//			operateor : "<" ,
//			onerror : "��Χ���޲��ܴ��ڷ�Χ����"
//		})
//		.ajaxValidator({
//			type : "get",
//			url : "index1.php",
//			data : "model=rdproject_light_rdlight&action=ajaxRange&notId=" + $("#id").val() ,
//			datatype : "json",
//			success : function(data) {
//				if (data == "1") {
//					return true;
//				} else {
//					return false;
//				}
//			},
//			buttons : $("#submitSave"),
//			error : function() {
//				alert("������û�з������ݣ����ܷ�����æ��������");
//			},
//			onerror : "���뷶Χ����,���ܷ�Χ��������ʶ�Ƴ�ͻ",
//			onwait : "���ڶ���Ŀ���ƽ��кϷ���У�飬���Ժ�..."
//		})
		;
})