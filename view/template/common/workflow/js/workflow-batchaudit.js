
//ajax审批 - 触发部分
function toAjaxAudit(){
	if(!confirm('确定审批吗？')) return false;

	var spidArr = $("input[id^='spid']");
	var result; //审批结果
	var content; //审批意见
	var isSend; //是否通知已审批
	var isSendNext; //是否通知下一步审批者

	//循环处理审批
	spidArr.each(function(i,n){
		//取审批结果
		result = $("input:radio[name=result"+ this.value +"]:radio:checked").val();
		//取审批意见
		content = $("#content" + this.value).val();
		//取是否通知已审批
		isSend = $("#isSend" + this.value).attr("checked") == true ? 'y' : 'n';
		//取是否通知已审批
		isSendNext = $("#isSendNext" + this.value).attr("checked") == true ? 'y' : 'n' ;

		//审批处理
		rs = ajaxAudit(this.value,result,content,isSend,isSendNext);

		//渲染显示信息
		initAuditShow(this.value,rs);
	});

	alert('审批完成');
	self.parent.show_page();
	self.parent.tb_remove();
}

//ajax审批 - 调用部分
function ajaxAudit(spid,result,content,isSend,isSendNext){
	var rsVal = '审批完成';
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

//渲染审批结果
function initAuditShow(spid,rs){
	//审批结果
	$("#resultShow" + spid).empty().html(rs);
	//清空意见
	$("#contentShow" + spid).empty();
	//清空通知
	$("#mailShow" + spid).empty();
}

//更改结果
function changeResult(spid,rs){
	if(rs == 'ok'){
		$("#resultYesInfo" + spid).attr("class","blue");
		$("#resultNoInfo" + spid).attr("class","");
	}else{
		$("#resultYesInfo" + spid).attr("class","");
		$("#resultNoInfo" + spid).attr("class","blue");
	}
}