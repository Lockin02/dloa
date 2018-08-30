
//ajax���� - ��������
function toAjaxAudit(){
	if(!confirm('ȷ��������')) return false;

	var spidArr = $("input[id^='spid']");
	var result; //�������
	var content; //�������
	var isSend; //�Ƿ�֪ͨ������
	var isSendNext; //�Ƿ�֪ͨ��һ��������

	//ѭ����������
	spidArr.each(function(i,n){
		//ȡ�������
		result = $("input:radio[name=result"+ this.value +"]:radio:checked").val();
		//ȡ�������
		content = $("#content" + this.value).val();
		//ȡ�Ƿ�֪ͨ������
		isSend = $("#isSend" + this.value).attr("checked") == true ? 'y' : 'n';
		//ȡ�Ƿ�֪ͨ������
		isSendNext = $("#isSendNext" + this.value).attr("checked") == true ? 'y' : 'n' ;

		//��������
		rs = ajaxAudit(this.value,result,content,isSend,isSendNext);

		//��Ⱦ��ʾ��Ϣ
		initAuditShow(this.value,rs);
	});

	alert('�������');
	self.parent.show_page();
	self.parent.tb_remove();
}

//ajax���� - ���ò���
function ajaxAudit(spid,result,content,isSend,isSendNext){
	var rsVal = '�������';
	$.ajax({
	    type: "POST",
	    url: "?model=common_workflow_workflow&action=ajaxAudit",
	    data: { "spid" : spid , "result" : result , "content" : content ,"isSend" : isSend,"isSendNext" : isSendNext},
	    async: false,
	    success: function(data){
	    	if(data != "1"){
	    		rsVal = data;
	    	}
		}
	});
	return rsVal;
}

//��Ⱦ�������
function initAuditShow(spid,rs){
	//�������
	$("#resultShow" + spid).empty().html(rs);
	//������
	$("#contentShow" + spid).empty();
	//���֪ͨ
	$("#mailShow" + spid).empty();
}

//���Ľ��
function changeResult(spid,rs){
	if(rs == 'ok'){
		$("#resultYesInfo" + spid).attr("class","blue");
		$("#resultNoInfo" + spid).attr("class","");
	}else{
		$("#resultYesInfo" + spid).attr("class","");
		$("#resultNoInfo" + spid).attr("class","blue");
	}
}