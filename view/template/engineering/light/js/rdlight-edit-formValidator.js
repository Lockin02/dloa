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
			onerror : "�������ֵ,��ȷ��"
		});

		$("#Min").formValidator({
			onshow : "�����뷶Χ����",
			oncorrect : "�������ֵ��ȷ"

		}).inputValidator({
			onerror : "�������ֵ,��ȷ��"
		}).compareValidator({
			desid : "Max" ,
			operateor : "<=" ,
			onerror : "��Χ���޲��ܴ��ڷ�Χ����"
		});
})