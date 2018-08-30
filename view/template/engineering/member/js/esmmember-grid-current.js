//��ʼ��
$(function() {
	init(0);
});
//�ı�ͳ������
function changeRange(){
	init(0);
}
//��ȡ��ǰ
function returnCurrent(){
	init(1);
}
//��ȡ����
function returnAll(){
	init(2);
}
function init(condition){
	var beginDate = $("#beginDate").val();
	var endDate = $("#endDate").val();
	var projectId = $("#projectId").val();
	
	var s = DateDiff(beginDate,endDate);
	if(s < 0) { 
		alert("��ѯ��ʼ���ڲ��ܱȲ�ѯ����������");
		return false;
	}
//��Ա��Դ��״��
	var responseText = $.ajax({
	    type: "POST",
	    url: "?model=engineering_member_esmmember&action=ajaxManageCurrent",
	    data: {"beginDate" : beginDate , 'endDate' : endDate , "projectId" : projectId , "condition" :��condition},
	    async: false
	}).responseText;
	var colData = eval("(" + responseText + ")");
	var obj = [];
    $.each(colData[0] ,function(i){
        obj.push({'display':i,'name':i});
    });
    var colName = obj;
	$("#esmmemberGrid").yxeditgrid('remove').yxeditgrid({
		data : colData,
		type : 'view',
		colModel : colName
	});
	//��Ա��Դ��״ͼ�� 
	var chartData = $.ajax({
	    type: "POST",
	    url: "?model=engineering_member_esmmember&action=getChart",
	    data: {"beginDate" : beginDate , 'endDate' : endDate , "projectId" : projectId , "condition" :��condition},
	    async: false
	}).responseText;
	$("#chart").html("").append(chartData);
	
	//��Ա�б�
	$("#esmmemberListGrid").yxeditgrid('remove').yxeditgrid({
		url: "?model=engineering_member_esmmember&action=memberListJson",
		title : '��Ա�б�',
		param : {
			'projectId' : projectId,
			'beginDate' : beginDate,
			'endDate' : endDate,
			'condition' : condition
			
		},
		type : 'view',
        //����Ϣ
        colModel : [{
            display : 'id',
            name : 'id',
            type : 'hidden'
        },{
            display : '��Ŀ���',
            name : 'projectCode',
            type : 'hidden'
        },{
        	display : 'Ա�����',
            name : 'userNo'
        },{
			display : '������־������Id',
			name : 'createId',
			type : 'hidden'
		},{
			display : 'Ա��Id',
			name : 'memberId',
			type : 'hidden'
		},{
        	display : '����',
            name : 'memberName',
            process : function(v,row){
				if(row.createId){
					return "<a href='javascript:void(0)' title='�鿴������־�б�' onclick='searchDetail(\""+ row.createId+"\",\""+ row.memberName+"\");'>" + v +"</a>";
				}else{
					return v;
				}
			}
        },{
        	display : '����',
            name : 'belongDeptName'
        },{
        	display : '����',
            name : 'personLevel'
        },{
        	display : '����',
            name : 'roleName'
        },{
        	display : '������Ŀ',
            name : 'beginDate'
        },{
        	display : '�뿪��Ŀ',
            name : 'endDate'
        },{
        	display : '�˹�Ͷ��',
            name : 'inWorkRate'
        },{
        	display : '��־ȱ��',
            name : 'logMissNum',
            process : function(v,row){
				if(row){
					return '<span class="red">' + v + '</span>';
				}
			}
        },{
        	display : '����ϵ��',
            name : 'workCoefficient'
        },{
        	display : '��չϵ��',
            name : 'processCoefficient'
        },{
        	display : '��Ŀ�����',
            name : 'thisProjectProcess',
            process : function(v){
				if(v){
					return v + " %";
				}
			}
        },{
        	display : '���',
            name : 'loan',
            align: 'right',
			process : function(v,row) {
				if(row.memberId){
					return "<a href='javascript:void(0)' title='�鿴����¼��' onclick='payView(\""+ row.memberId+"\",\""+ row.projectCode+"\");'>" + moneyFormat2(v) +"</a>";
				}else{
					return moneyFormat2(v);
				}
			}
        },{
        	display : '����',
            name : 'feeFieldCount',
            align: 'right',
			process : function(v,row) {
				if(row.memberId){
					return "<a href='javascript:void(0)' title='�鿴������¼��' onclick='expense(\""+ row.memberId+"\",\""+ row.projectCode+"\");'>" + moneyFormat2(v) +"</a>";
				}else{
					return moneyFormat2(v);
				}
			}
        }]
        });
    //����Excel��ť
	$("#esmmemberListGrid td.form_header").append('<input type="button" value="����Excel" onclick="exportExcel('+condition+');"/>');
}

//�鿴������־�б�
function searchDetail(createId,createName){
	var url = "?model=engineering_worklog_esmworklog&action=toSearchDetailList&createId="
		+ createId
		+ "&createName=" + createName
		+ "&beginDate=" + $("#beginDate").val()
		+ "&endDate=" + $("#endDate").val()
		+ "&projectId=" + $("#projectId").val()
	;
	showOpenWin(url, 1 ,600 , 1100 ,createName );
}

//����Excel
function exportExcel(condition){
	var url = "?model=engineering_member_esmmember&action=memberListExport"
		+ "&beginDate=" + $("#beginDate").val()
		+ "&endDate=" + $("#endDate").val()
		+ "&projectId=" + $("#projectId").val()
		+ "&condition=" + condition
		;
	showOpenWin(url, 1 ,200 , 200);
}

//�鿴����¼��
function payView(memberId,projectCode){
	var url = "?model=hr_payView_payView&action=toEsmmemberList&userAccount="
		+ memberId+"&projectCode="+projectCode
	;
	showOpenWin(url, 1 ,600 , 1200);
}

//�鿴������¼��
function expense(memberId,projectCode){
	var url = "?model=finance_expense_expense&action=listForEsmmember&userAccount="
		+ memberId+"&projectCode="+projectCode
	;
	showOpenWin(url, 1 ,600 , 1200);
}