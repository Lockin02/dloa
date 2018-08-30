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

//�鿴����
function viewFun(){
	//��ȡ����Id
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

//�༭����
function editFun(){
	//��ȡ����Id
	var formId = ReportViewer.Report.FieldByName("id").AsString;
	var ExaStatus = ReportViewer.Report.FieldByName("ExaStatus").AsString;
	if(ExaStatus == 1) {alert('�����ĵ��ݲ��ܽ��б༭');return false;}
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

//�ϲ鷽��
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
				alert('û��������ĵ���');
	   	    }
		}
	});
}

//�²鷽��
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
					alert('û��������ĵ���');
		   	    }
			}
		});
	}else{
		alert('��ѡ��һ����¼');
	}
}

//��˷���
function auditFun(){
	var formId = ReportViewer.Report.FieldByName("id").AsString;
	var status = ReportViewer.Report.FieldByName("status").AsString;
	var ExaStatus = ReportViewer.Report.FieldByName("ExaStatus").AsString;
	if(ExaStatus == 1) {alert('����˵��ݣ�');return false;}
	if( formId != ""){
		if(confirm('ȷ��Ҫ���?')){
			$.ajax({
				type : "POST",
				url : "?model=finance_invpurchase_invpurchase&action=audit",
				data : {
					"id" : formId
				},
				success : function(msg) {
					if (msg == 1) {
						alert('��˳ɹ���');
						show_page();
					}else{
						alert('���ʧ��!');
					}
				}
			});
		}
	}else{
		alert('��ѡ��һ����¼');
	}
}

//����˷���
function unAuditFun(){
	var formId = ReportViewer.Report.FieldByName("id").AsString;
	var status = ReportViewer.Report.FieldByName("status").AsString;
	var ExaStatus = ReportViewer.Report.FieldByName("ExaStatus").AsString;
	var belongId = ReportViewer.Report.FieldByName("belongId").AsString;
	if(ExaStatus == 0) {alert('δ��˵��ݣ�');return false;}
	if(status == 1) {alert('�ѹ����ĵ��ݲ��ܽ��з���ˣ�');return false;}
	if(belongId != "") {alert('����ֵĵ��ݲ��ܷ����');return false;}

	//�ж��Ƿ�Ϊ����ֲɹ���Ʊ
	$.ajax({
	    type: "POST",
	    url: "?model=finance_invpurchase_invpurchase&action=isBreak",
	    data: {"id" : formId},
	    async: false,
	    success: function(data){
	   	   if(data == 1){
	   	   		alert('����ֵ��ݲ��ܷ����');
			}
		}
	});

	if( formId != ""){
		if(confirm('ȷ��Ҫ�����?')){
			$.ajax({
				type : "POST",
				url : "?model=finance_invpurchase_invpurchase&action=unaudit",
				data : {
					"id" : formId
				},
				success : function(msg) {
					if (msg == 1) {
						alert('����˳ɹ���');
						show_page();
					}else{
						alert('�����ʧ��!');
					}
				}
			});
		}
	}else{
		alert('��ѡ��һ����¼');
	}
}

//��������
function hookFun(){
	var formId = ReportViewer.Report.FieldByName("id").AsString;
	var status = ReportViewer.Report.FieldByName("status").AsString;
	var ExaStatus = ReportViewer.Report.FieldByName("ExaStatus").AsString;
	if( formId != ""){
		if(ExaStatus == 0) {alert('���ȶԵ��ݽ�����ˣ�');return false;}
		if(status == 0){
			showModalWin('?model=finance_invpurchase_invpurchase&action=toHook&id=' + formId );
		}else{
			alert('�����ѹ���');
		}
	}else{
		alert('��ѡ��һ����¼');
	}
}

//����������
function unHookFun(){
	var formId = ReportViewer.Report.FieldByName("id").AsString;
	var status = ReportViewer.Report.FieldByName("status").AsString;
	var ExaStatus = ReportViewer.Report.FieldByName("ExaStatus").AsString;
	if(ExaStatus == 0) {alert('δ��˵����޷����з�������');return false;}
	if(status == 0) {alert('δ���������޷����з�������');return false;}
	if(confirm('ȷ��Ҫ������?')){
		$.ajax({
			type : "POST",
			url : "?model=finance_related_baseinfo&action=unHookByInv",
			data : {
				"invPurId" : formId
			},
			success : function(msg) {
				if (msg == 1) {
					alert('�������ɹ���');
					show_page();
				}else{
					alert('������ʧ��!');
				}
			}
		});
	}
}

//�߼���ѯ
function searchFun(){
	showOpenWin("?model=finance_invpurchase_invpurchase&action=toViewListSearch",1);
}

//��ղ�ѯ
function clearFun(){
	this.location='?model=finance_invpurchase_invpurchase&action=viewlist';
}

//���ر��
function toGrid(){
	this.location='?model=finance_invpurchase_invpurchase';
}

//����ȫ��
function allScreen(){
	showModalWin('?model=finance_invpurchase_invpurchase&action=viewlist');
}

//ҳ��ˢ��
function show_page(){
	window.location.reload();
	window.opener.show_page();
}

//�ر��б�
function closeFun(){
	window.opener.show_page();
	this.close();
}