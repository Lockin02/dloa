$(function(){
	$.ajax({
	    type: "POST",
	    url: "?model=finance_invpurchase_invpurchase&action=hasLimitToAudit",
	    data: "",
	    async: false,
	    success: function(data){
	   		if(data == 1){
	   			$("#auditBtn").show();
	   	   		$("#invType").after("<input type='hidden' id='audit' value='1'/>");
			}else{
	   	   		$("#invType").after("<input type='hidden' id='audit' value='0'/>");
			}
		}
	});

	$.ajax({
	    type: "POST",
	    url: "?model=finance_invpurchase_invpurchase&action=hasLimitToUnaudit",
	    data: "",
	    async: false,
	    success: function(data){
	   		if(data == 1){
	   			$("#unAuditBtn").show();
	   	   		$("#invType").after("<input type='hidden' id='unAudit' value='1'/>");
			}else{
	   	   		$("#invType").after("<input type='hidden' id='unAudit' value='0'/>");
			}
		}
	});

});

//查看方法
function viewFun(){
	//获取单据Id
	var formId = ReportViewer.Report.FieldByName("id").AsString;
    var skey = "";
    $.ajax({
	    type: "POST",
	    url: "?model=finance_invpurchase_invpurchase&action=md5RowAjax",
	    data: { "id" : formId },
	    async: false,
	    success: function(data){
	   	   skey = data;
		}
	});
	showModalWin("?model=finance_invpurchase_invpurchase&action=init&perm=view&id=" + formId +"&skey=" + skey ,1);
}

//编辑方法
function editFun(){
	//获取单据Id
	var formId = ReportViewer.Report.FieldByName("id").AsString;
	var ExaStatus = ReportViewer.Report.FieldByName("ExaStatus").AsString;
	if(ExaStatus == 1) {alert('审核完的单据不能进行编辑');return false;}
    var skey = "";
    $.ajax({
	    type: "POST",
	    url: "?model=finance_invpurchase_invpurchase&action=md5RowAjax",
	    data: { "id" : formId },
	    async: false,
	    success: function(data){
	   	   skey = data;
		}
	});
	showModalWin("?model=finance_invpurchase_invpurchase&action=init&id=" + formId +"&skey=" + skey ,1);
}

//上查方法
function upSearch(){
	var formId = ReportViewer.Report.FieldByName("id").AsString;
	$.ajax({
	    type: "POST",
	    url: "?model=common_search_searchSource&action=checkUp",
	    data: {"objId" : formId , 'objType' : 'invpurchase' },
	    async: false,
	    success: function(data){
	   		if(data != ""){
	   			var dataObj = eval("(" + data +")");
	   			for(t in dataObj){
	   				var thisType = t;
	   				var thisIds = dataObj[t];
	   			}
				showModalWin("?model=common_search_searchSource&action=upList&objType=invpurchase&orgObj="+ thisType +"&ids=" + thisIds);
	   	    }else{
				alert('没有相关联的单据');
	   	    }
		}
	});
}

//下查方法
function downSearch(){
	var formId = ReportViewer.Report.FieldByName("id").AsString;
	if( formId != ""){
		$.ajax({
		    type: "POST",
		    url: "?model=common_search_searchSource&action=checkDown",
		    data: {"objId" : formId , 'objType' : 'invpurchase' },
		    async: false,
		    success: function(data){
		   		if(data != ""){
					showModalWin("?model=common_search_searchSource&action=downList&objType=invpurchase&orgObj="+data+"&objId=" +formId);
		   	    }else{
					alert('没有相关联的单据');
		   	    }
			}
		});
	}else{
		alert('请选择一条记录');
	}
}

//审核方法
function auditFun(){
	var formId = ReportViewer.Report.FieldByName("id").AsString;
	var status = ReportViewer.Report.FieldByName("status").AsString;
	var ExaStatus = ReportViewer.Report.FieldByName("ExaStatus").AsString;
	if(ExaStatus == 1) {alert('已审核单据！');return false;}
	if( formId != ""){
		if(confirm('确定要审核?')){
			$.ajax({
				type : "POST",
				url : "?model=finance_invpurchase_invpurchase&action=audit",
				data : {
					"id" : formId
				},
				success : function(msg) {
					if (msg == 1) {
						alert('审核成功！');
						show_page();
					}else{
						alert('审核失败!');
					}
				}
			});
		}
	}else{
		alert('请选择一条记录');
	}
}

//反审核方法
function unAuditFun(){
	var formId = ReportViewer.Report.FieldByName("id").AsString;
	var status = ReportViewer.Report.FieldByName("status").AsString;
	var ExaStatus = ReportViewer.Report.FieldByName("ExaStatus").AsString;
	var belongId = ReportViewer.Report.FieldByName("belongId").AsString;
	if(ExaStatus == 0) {alert('未审核单据！');return false;}
	if(status == 1) {alert('已勾稽的单据不能进行反审核！');return false;}
	if(belongId != "") {alert('被拆分的单据不能反审核');return false;}

	//判断是否为被拆分采购发票
	$.ajax({
	    type: "POST",
	    url: "?model=finance_invpurchase_invpurchase&action=isBreak",
	    data: {"id" : formId},
	    async: false,
	    success: function(data){
	   	   if(data == 1){
	   	   		alert('被拆分单据不能反审核');
			}
		}
	});

	if( formId != ""){
		if(confirm('确定要反审核?')){
			$.ajax({
				type : "POST",
				url : "?model=finance_invpurchase_invpurchase&action=unaudit",
				data : {
					"id" : formId
				},
				success : function(msg) {
					if (msg == 1) {
						alert('反审核成功！');
						show_page();
					}else{
						alert('反审核失败!');
					}
				}
			});
		}
	}else{
		alert('请选择一条记录');
	}
}

//钩稽方法
function hookFun(){
	var formId = ReportViewer.Report.FieldByName("id").AsString;
	var status = ReportViewer.Report.FieldByName("status").AsString;
	var ExaStatus = ReportViewer.Report.FieldByName("ExaStatus").AsString;
	if( formId != ""){
		if(ExaStatus == 0) {alert('请先对单据进行审核！');return false;}
		if(status == 0){
			showModalWin('?model=finance_invpurchase_invpurchase&action=toHook&id=' + formId );
		}else{
			alert('单据已钩稽');
		}
	}else{
		alert('请选择一条记录');
	}
}

//反钩稽方法
function unHookFun(){
	var formId = ReportViewer.Report.FieldByName("id").AsString;
	var status = ReportViewer.Report.FieldByName("status").AsString;
	var ExaStatus = ReportViewer.Report.FieldByName("ExaStatus").AsString;
	if(ExaStatus == 0) {alert('未审核单据无法进行反钩稽！');return false;}
	if(status == 0) {alert('未钩稽单据无法进行反钩稽！');return false;}
	if(confirm('确定要反钩稽?')){
		$.ajax({
			type : "POST",
			url : "?model=finance_related_baseinfo&action=unHookByInv",
			data : {
				"invPurId" : formId
			},
			success : function(msg) {
				if (msg == 1) {
					alert('反钩稽成功！');
					show_page();
				}else{
					alert('反钩稽失败!');
				}
			}
		});
	}
}

//高级查询
function searchFun(){
	showOpenWin("?model=finance_invpurchase_invpurchase&action=toViewListSearch",1);
}

//清空查询
function clearFun(){
	this.location='?model=finance_invpurchase_invpurchase&action=viewlist';
}

//返回表格
function toGrid(){
	this.location='?model=finance_invpurchase_invpurchase';
}

//弹出全屏
function allScreen(){
	showModalWin('?model=finance_invpurchase_invpurchase&action=viewlist');
}

//页面刷新
function show_page(){
	window.location.reload();
	window.opener.show_page();
}

//关闭列表
function closeFun(){
	window.opener.show_page();
	this.close();
}