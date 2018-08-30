$(document).ready(function() {
	//������ѡ����
	$('#weekTimes').numberspinner({
	    editable: true,
	    min: 1000,
   	 	max: 9999,
	    onSpinUp : function(value,value2){
    		goWeek();
	    },
	    onSpinDown : function(value){
    		goWeek();
	    }
	});
});

//��ȡ����
function goWeek(){
	var weekTimes = $('#weekTimes').val();
	var projectId = $("#projectId").val();
	if(projectId != '0'){
		$.ajax({
		    type: "POST",
		    url: "?model=engineering_cost_esmcostdetail&action=ajaxManageListYW",
		    data: {"weekTimes" : weekTimes , "projectId" : projectId },
		    async: false,
		    success: function(data){
		   		if(data){
					initPage(data);
		   	    }else{
					alert('����ʧ��');
		   	    }
			}
		});
	}
}

//����ȷ�Ϸ���ҳ��
function confirmCost(worklogId){
	//����ǲ鿴����ֱ�ӵ����鿴ҳ��
//	if($("#isView").val() == "1"){
		viewCost(worklogId);
//	}else{
//		var url = "?model=engineering_worklog_esmworklog&action=toConfirmNew&id=" + worklogId;
//		var height = 800;
//		var width = 1150;
//		window.open(url, "�����־����",
//		'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width='
//				+ width + ',height=' + height);
//	}
}

//����鿴����ҳ��
function viewCost(worklogId){
	var url = "?model=engineering_worklog_esmworklog&action=toView&id=" + worklogId;
	var height = 800;
	var width = 1150;
	window.open(url, "�鿴��־��Ϣ",
	'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width='
			+ width + ',height=' + height);
}

//��������show_page��
function show_page(){
	//����ˢ���б�
	changeRange();
}

//�������ʱˢ���б�
function changeRange(){
	var beginDate = $("#beginDate").val();
	var endDate = $("#endDate").val();
	var projectId = $("#projectId").val();

	var s = DateDiff(beginDate,endDate);
	if(s < 0) {
		alert("��ѯ��ʼ���ڲ��ܱȲ�ѯ����������");
		return false;
	}

	$.ajax({
	    type: "POST",
	    url: "?model=engineering_cost_esmcostdetail&action=ajaxManageList",
	    data: {"beginDate" : beginDate , 'endDate' : endDate , "projectId" : projectId },
	    async: false,
	    success: function(data){
	   		if(data){
				initPage(data);
	   	    }else{
				alert('����ʧ��');
	   	    }
		}
	});
}

//��ʼ��ҳ��
function initPage(data){

	//���ر�ͷ
	var header = '<tr class="main_tr_header">' +
			'<th style="width:40px">���</th>' +
			'<th style="width:70px">Ա������</th>' +
			'<th style="width:60px">�ύ�ܱ�</th>' +
			'<th style="width:80px">ȫ������</th>' +
			'<th style="width:80px">δ��˷���</th>' +
			'<th style="width:80px">��ǰ����</th>'
		;

	var htmObj = eval("(" + data + ")");
	header = header + htmObj.tr + "</tr>";
	$("#thisHead").html(header);

	//���ر������
	var tbody = htmObj.list;
	$("#thisTbody").html(tbody);

	$("#beginDate").val(htmObj.beginDate);
	$("#endDate").val(htmObj.endDate);

	//�ڴ�����
	if(htmObj.weekTimes){
		var weekTimesObj = $('#weekTimes');
		if( htmObj.weekTimes != weekTimesObj.val()){
			weekTimesObj.val(htmObj.weekTimes);
			weekTimesObj.next().val(htmObj.weekTimes);
		}
	}

	//��ʽ������
	formateMoney();
}

//���ص�ǰ��
function returnDefualtWeek(){
	var defaultWeekTimesObj = $('#defaultWeekTimes');
	var weekTimesObj = $('#weekTimes');
	if(defaultWeekTimesObj.val() != weekTimesObj.val()){
		weekTimesObj.val(defaultWeekTimesObj.val());
		goWeek();
	}else{
		alert('�Ѿ��ǵ�ǰ��');
	}
}

//��ѯδ���
function returnUnconfirm(){
	//��ѯ������δ��˷��õ���
	var weekTimesObj = $('#weekTimes');

	$.ajax({
	    type: "POST",
	    url: "?model=engineering_cost_esmcostdetail&action=getUnconfirmWeek",
	    data: { "projectId" : $("#projectId").val() },
	    async: false,
	    success: function(data){
	   		if(data != "-1" && data != "0"){
				weekTimesObj.val(data);
				goWeek();
	   	    }else if(data == "-1"){
				alert('��ѯʧ��');
	   	    }else{
	   	    	alert('δ��ѯ����Ҫ��˵ķ���');
	   	    }
		}
	});
}