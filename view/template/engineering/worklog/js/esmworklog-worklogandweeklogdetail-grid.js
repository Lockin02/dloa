$(function() {
	//Ĭ����ʾ��־��ͼ
	initWorklog();
	$("#viewType").click(function(){
		if($(this).val() == "�л����ܱ���ͼ"){//�л����ܱ���ͼ
			$("#esmworklogGrid").yxeditgrid("remove");
			$(this).val("�л�����־��ͼ");
			$("#title").text("�ܱ�ͳ��");
			initWeeklog();
		}else{//�л�����־��ͼ
			$("#esmweeklogGrid").yxeditgrid("remove");
			$(this).val("�л����ܱ���ͼ");
			$("#title").text("��־ͳ��");
			initWorklog();
		}
	})
});

//��ʼ���������־
function initWorklog(){
	var objGrid = $("#esmworklogGrid");
	//������־
	objGrid.yxeditgrid({
		url : '?model=engineering_worklog_esmworklog&action=searchDetailJson',
		type : 'view',
		param : {
			projectId : $("#projectId").val(),
			beginDateThan : $("#beginDate").val(),
			endDateThan : $("#endDate").val(),
			createId : $("#createId").val()
		},
		colModel : [{
				display : 'id',
				name : 'id',
				type : 'hidden'
			},{
				display : '����',
				name : 'executionDate',
				width : 120,
				process : function(v,row){
					if(row.id == "noId"){
						return "<span style='font-weight:bold;'>" + v + "</span>";
					}else{
						return v;
					}
				}
			}, {
				display : '����״̬',
				name : 'workStatus',
				datacode : 'GXRYZT',
				width : 80
			}, {
				display : '�˹�Ͷ��',
				name : 'inWorkRateOne',
				width : 100,
				process : function(v,row){
					if(row.id == "noId"){
						return "<span style='font-weight:bold;'>" + v + "</span>";
					}else{
						return v;
					}
				}
			}, {
				display : '����ϵ��',
				name : 'workCoefficient',
				width : 100,
				process : function(v,row){
					if(!v){
						return ;
					}
					if(row.id == "noId"){
						return "<span style='font-weight:bold;'>" + v + "</span>";
					}else{
						return v;
					}
				}
			}, {
				display : '��Ŀ�����',
				name : 'thisProjectProcess',
				width : 100,
				process : function(v,row){
					if(!v){
						return ;
					}
					if(row.id == "noId"){
						return "<span style='font-weight:bold;'>" + v + " %</span>";
					}else{
						return v + " %";
					}
				}
			}, {
				display : '��չϵ��',
				name : 'processCoefficient',
				width : 100,
				process : function(v,row){
					if(!v){
						return ;
					}
					if(row.id == "noId"){
						return "<span style='font-weight:bold;'>" + v + "</span>";
					}else{
						return v;
					}
				}
			}, {
				display : '����',
				name : 'costMoney',
				width : 100,
				process : function(v,row){
					if(!v){
						return ;
					}
					if(v * 1 == 0 || v == ''){
						return v;
					}else{
						if(row.id == "noId"){
							return "<span class='blue' style='font-weight:bold;'>" + moneyFormat2(v) + "</span>";
						}else{
							return "<span class='blue'>" + moneyFormat2(v) + "</span>";
						}
					}
				}
			}, {
				display : '���˽��',
				name : 'assessResultName',
				width : 100
			}, {
				display : '���˷���',
				name : 'assessScore',
				width : 100
			}, {
				display : '�澯',
				name : 'warning',
				width : 100,
				process : function(v,row){
					if(!row.costMoney && row.id != 'noId'){
						return '<span class="red">δ��д</span>';
					}
					if(row.confirmStatus == '0'){
						return '<span class="blue">δ���</span>';
					}
				}
			}, {
				display : '',
				name : ''
			}
		]
	});
}

//��ʼ��������ܱ�
function initWeeklog(){
	//�����ж�
	var beginDateThan = $("#beginDate").val();
	var endDateThan = $("#endDate").val();
	var projectId = $("#projectId").val();

	var paramObj = {};
	if(beginDateThan)
		paramObj.beginDateThan=beginDateThan;
	if(endDateThan)
		paramObj.endDateThan=endDateThan;
	if(projectId)
		paramObj.projectId=projectId;

	var objGrid = $("#esmweeklogGrid");
	//��Ŀ�ܱ�
	objGrid.yxeditgrid({
		url : '?model=engineering_project_statusreport&action=warnView',
		type : 'view',
		param : paramObj,
		colModel : [{
				display : '�ܴ�',
				name : 'weekNo',
				align : 'center'
			}, {
				display : '�澯',
				name : 'msg',
				process:function(v){
					return '<font color="red">'+v+'</font>';
				}
			}
		]
	});
}