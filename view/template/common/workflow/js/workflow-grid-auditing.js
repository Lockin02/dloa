var show_page = function(page) {
	$("#workflowGrid").yxgrid("reload");
};

/**
 * ��������ҳ��
 */
function toAudit(task,id,code,Pid,name,code,isTemp,receiveStatus,isReceive){
	if(isReceive == '1' && receiveStatus == '0'){
		alert('���յ���,������������');
		return false;
	}
	location.href = "controller/common/workflow/ewf_index.php?actTo=ewfExam"
		+ "&taskId=" + task
		+ "&spid=" + id
		+ "&examCode=" + code
		+ "&billId=" + Pid
		+ "&formName=" + name
		+ "&code=" + code
		+ "&isTemp=" + isTemp
		;
}

/**
 * ��������ҳ��
 */
function toView(task,id,code,Pid,name,code,isTemp){
	showModalWin('?model=common_workflow_workflow&action=toViweObjInfo'
		+ "&taskId=" + task
		+ "&spid=" + id
		+ "&examCode=" + code
		+ "&billId=" + Pid
		+ "&formName=" + name
		+ "&code=" + code
		+ "&isTemp=" + isTemp
	);
}

/**
 * �յ�
 */
function toReceive(task,id,code,Pid,name,code,isTemp){
	//if(confirm('ȷ���յ���')){
		$.ajax({
		    type: "POST",
		    url: "?model=common_workflow_workflow&action=receiveForm",
		    data: {"spid" : id },
		    async: false,
		    success: function(data){
				if(data == "1"){
					//alert('�յ��ɹ�');
					show_page();
				}else if(data == "-1"){
					alert('�����յ��������ô�������ϵ����Ա');
				}
			}
		});
	//}
}

/**
 * �˵�
 */
function toBack(task,id,code,Pid,name,code,isTemp){
	//if(confirm('ȷ���˵���')){
		$.ajax({
		    type: "POST",
		    url: "?model=common_workflow_workflow&action=backForm",
		    data: {"spid" : id },
		    async: false,
		    success: function(data){
				if(data == "1"){
					//alert('�˵��ɹ�');
					show_page();
				}else if(data == "-1"){
					alert('�����յ��������ô�������ϵ����Ա');
				}
			}
		});
	//}
}

//��ȡ��������������
var formTypeArr = [];

//��ȡ��������������������
var batchAuditArr = []; //������������
var batchAuditArr2 = []; //�����ж�

$(function() {
	$.ajax({
	    type: "POST",
	    url: "?model=common_workflow_workflow&action=getFormType",
	    data: "",
	    async: false,
	    success: function(data){
	   		formTypeArr = eval( "(" + data + ")" );
		}
	});

	//������������
	var comboArr = [{
			text : '��������',
			key : 'formName',
			value : $("#selectedCode").val(),
			data : formTypeArr
		}
	];

	//������������
	var buttonsArr = [
	    {
			name : 'appendView',
			text : "�鿴",
			icon : 'view',
			action: function(row,rows,idArr ) {
				if(row){
					if(idArr.length > 1){
						alert('һ��ֻ��ѡ��һ�����ݽ��в鿴');
					}else{
						showModalWin('?model=common_workflow_workflow&action=toViweObjInfo'
							+ '&taskId='
							+ row.task
							+ "&spid="
							+ row.id
							+ "&examCode="
							+ row.code
							+ "&billId="
							+ row.Pid
							+ "&formName="
							+ row.name
							+ "&code="
							+ row.code
							+ "&isTemp="
							+ row.isTemp
						);
					}
				}else{
					alert('����ѡ���¼');
				}
			}
	    },
	    {
			name : 'exam',
			text : "��ϸ����",
			icon : 'edit',
			action: function(row,rows,idArr ) {
				if(row){
					if(idArr.length > 1){
						alert('һ��ֻ�ܶ�һ����������������');
					}else{
						if(row.isReceive == '1' && row.receiveStatus == '0'){
							alert('���յ���,������������');
							return false;
						}
						location.href = "controller/common/workflow/ewf_index.php?actTo=ewfExam&taskId="
							+ row.task
							+ "&spid="
							+ row.id
							+ "&examCode="
							+ row.code
							+ "&billId="
							+ row.Pid
							+ "&formName="
							+ row.name
							+ "&code="
							+ row.code
							+ "&isTemp="
							+ row.isTemp
					}
				}else{
					alert('����ѡ���¼');
				}
			}
	    }
	];

	//��������ת��
	function initBatchArr(){
		$.ajax({
		    type: "POST",
		    url: "?model=common_workflow_workflow&action=getBatchAudit",
		    data: "",
		    async: false,
		    success: function(data){
		   		batchAuditArr = eval( "(" + data + ")" );
			}
		});

		for(var i = 1; i < batchAuditArr.length ; i++){
			batchAuditArr2.push(batchAuditArr[i].value);
		}

		//����
		comboArr.push({
			text : '��������',
			key : 'formNames',
			data : batchAuditArr
		});

		//��ť
	    buttonsArr.shift();
	    buttonsArr.push({
			name : 'exam',
			text : "��������",
			icon : 'edit',
			action: function(row,rows,idArr ) {
				if(row){
					//taskId����
					var taskIdArr = [];
					//spid����
					var spidArr = [];
					//ѭ��ȡ��
					for(var i = 0;i < rows.length ; i++){
						//�յ��ж�
						if(rows[i].isReceive == '1' && rows[i].receiveStatus == '0'){
							alert('��������Ϊ��'+ rows[i].task +' ,ҵ����Ϊ��' + rows[i].objCode + ' �ĵ��ݻ�δ�յ������յ���,������������');
							return false;
						}
						//�ж�ҵ���Ƿ�����������ҵ����
						if(batchAuditArr2.indexOf(rows[i].name) == -1){
							alert('��������Ϊ��'+ rows[i].task +' ,ҵ������Ϊ��' + rows[i].name + ' �ĵ����ݲ�֧�����������������������Ͻǲ�ѯ����������������');
							return false;
						}

						taskIdArr.push( rows[i].task );
						spidArr.push( rows[i].id );
					}
					showThickboxWin('?model=common_workflow_workflow&action=toBatchAudit'
						+ "&taskIds=" + taskIdArr.toString()
						+ "&spids=" + spidArr.toString()
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
				}else{
					alert('����ѡ���¼');
				}
			}
	    });
	}

	if($("#batchAuditLimit").val() == "1"){
		//����ת��
		initBatchArr();
	}

	// ������ť
	var DCBtn = {
		name: 'export',
		text: "����",
		icon: 'excel',
		action: function (row) {
			var colId = "";
			var colName = "";
			$("#workflowGrid_hTable").children("thead").children("tr")
				.children("th").each(function () {
				if ($(this).css("display") != "none"
					&& $(this).attr("colId") != undefined) {
					if($(this).attr("colId") != 'thisAction'){
						colName += $(this).children("div").html() + ",";
						colId += $(this).attr("colId") + ",";
					}
				}
			});

			var msg = $.ajax({
				url: '?model=common_workflow_workflow&action=setColInfoToSession',
				data: 'ColId=' + colId + '&ColName=' + colName + '&sType=workflowAuditingData',
				dataType: 'html',
				type: 'post',
				async: false
			}).responseText;

			if (msg == 1) {
				window.open("?model=common_workflow_workflow&action=exportData&sType=workflowAuditingData")
			}
		}
	};

	if($("#canExportLimit").val() == 1){
		buttonsArr.push(DCBtn);
	}

	//��Ⱦ�б�
	$("#workflowGrid").yxgrid({
		model: 'common_workflow_workflow',
		action : 'auditingPageJson',
		title: '����',
//		showcheckbox : false,
		isAddAction : false,
		isEditAction :false,
		isViewAction : false,
		isDelAction : false,
		customCode : 'auditingGrid',
		isOpButton : false,
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide : true
		},{
			name: 'task',
			display: '��������',
			sortable: true,
			width : 70
		},
		{
			name: 'name',
			display: '��������',
			sortable: true,
			width : 120,
			process : function(v,row){
				if(v == "���ó�������"){
					v = '��������������'
				}
				return "<a href='javascript:void(0)'onclick='toView(\""+ row.task + "\",\""+ row.id + "\",\""+ row.code + "\",\""+ row.Pid + "\",\""+ row.name + "\",\""+ row.code + "\",\""+ row.isTemp + "\")'>" + v  +"</a>" ;
			}
		},
		{
			name: 'creatorName',
			display: '�ύ��',
			sortable: true,
			width : 110
		},
		{
			name: 'start',
			display: '�ύʱ��',
			sortable: true,
			width : 130
		},
		{
			name: 'Pid',
			display: 'Դ��id',
			sortable: true,
			hide : true
		},{
			name: 'infomation',
			display: '����ժҪ',
			sortable: true,
			width : 150
		},{
			display: '�յ�״̬',
			name: 'receiveStatus',
			align : 'center',
			process : function(v,row){
				if(row.isReceive == "1"){
					if(v == "1"){
						return '���յ�';
					}else{
						return 'δ�յ�';
					}
				}
			},
			width : 50,
			hide : true
		},{
			name: 'objCode',
			display: 'ҵ����',
			sortable: true,
			width : 120
		},{
			name: 'costSourceType',
			display: '��Դ',
			sortable: true,
			width : 80
				// process : function(v,row){
				// 	var str = "";
				// 	if(v == '��'){
				// 		str = "����";
				// 	}else{
				// 		str = row.costSourceType;
				// 	}
				// 	return str;
				// }
		},{
			name: 'projectCode',
			display: '��Ŀ���',
			sortable: true,
			width : 120
		},{
			name: 'objName',
			display: 'ҵ������',
			sortable: true,
			hide : true,
			width : 120
		},{
			name: 'objCustomer',
			display: 'ҵ��ͻ�',
			sortable: true,
			hide : true,
			width : 120
		},{
			name: 'objAmount',
			display: 'ҵ����',
			sortable: true,
			hide : true,
			width : 80,
			process : function(v){
				return moneyFormat2(v);
			}
		},{
			name: 'objUserName',
			display: 'ҵ����Ա',
			sortable: true,
			hide : true,
			width : 80
		},{
			display: '�� ��',
			name : 'thisAction',
			process : function(v,row){
				var url = "<a href='javascript:void(0)' onclick='toView(\""+ row.task + "\",\""+ row.id + "\",\""+ row.code + "\",\""+ row.Pid + "\",\""+ row.name + "\",\""+ row.code + "\",\""+ row.isTemp + "\")'>�鿴����</a> |"
				+ " <a href='javascript:void(0)' onclick='toAudit(\""+ row.task + "\",\""+ row.id + "\",\""+ row.code + "\",\""+ row.Pid + "\",\""+ row.name + "\",\""+ row.code + "\",\""+ row.isTemp + "\",\""+ row.receiveStatus + "\",\""+ row.isReceive + "\")'>����</a>";

				if(row.isReceive == "1"){
					if(row.receiveStatus == "1"){
						url += " | <a href='javascript:void(0)' style='color:red;' onclick='toBack(\""+ row.task + "\",\""+ row.id + "\",\""+ row.code + "\",\""+ row.Pid + "\",\""+ row.name + "\",\""+ row.code + "\",\""+ row.isTemp + "\")'>�˵�</a>";
					}else{
						url += " | <a href='javascript:void(0)' onclick='toReceive(\""+ row.task + "\",\""+ row.id + "\",\""+ row.code + "\",\""+ row.Pid + "\",\""+ row.name + "\",\""+ row.code + "\",\""+ row.isTemp + "\")'>�յ�</a>";
					}
				}
				return url;
			},
			width : 110
		},{
			name: 'auditHistory',
			display: '������ʷ',
			width : 300,
			process : function(v,row){
				return v;
			},
			hide : true
		}],
		menusEx : [
			{
				text : '�鿴����',
				icon : 'view',
				action : function(row,rows,grid){
					showModalWin('?model=common_workflow_workflow&action=toViweObjInfo'
						+ '&taskId='
						+ row.task
						+ "&spid="
						+ row.id
						+ "&examCode="
						+ row.code
						+ "&billId="
						+ row.Pid
						+ "&formName="
						+ row.name
						+ "&code="
						+ row.code
						+ "&isTemp="
						+ row.isTemp
					);
				}
			},{
				text : '����',
				icon : 'edit',
				action : function(row) {
					if(row.isReceive == '1' && row.receiveStatus == '0'){
						alert('���յ���,������������');
						return false;
					}
					location.href = "controller/common/workflow/ewf_index.php?actTo=ewfExam&taskId="
						+ row.task
						+ "&spid="
						+ row.id
						+ "&examCode="
						+ row.code
						+ "&billId="
						+ row.Pid
						+ "&formName="
						+ row.name
						+ "&code="
						+ row.code
						+ "&isTemp="
						+ row.isTemp
				}
			}
		],
        buttonsEx : buttonsArr,
		comboEx : comboArr,
		searchitems : [{
			display : '��������',
			name : 'taskSearch'
		},{
			display : '�ύ��',
			name : 'creatorNameSearch'
		},{
			display : '�ύʱ��',
			name : 'startSearch'
		},{
			display : '����ժҪ',
			name : 'infomationSearch'
		},{
			display : 'ҵ����',
			name : 'objCodeSearch'
		},{
			display : '��Ŀ���',
			name : 'projectCodeSearch'
		},{
			display : 'ҵ������',
			name : 'objNameSearch'
		},{
			display : 'ҵ��ͻ�',
			name : 'objCustomerNameSearch'
		},{
			display : 'ҵ����',
			name : 'objAmountSearch'
		},{
			display : '��Դ',
			name : 'costSourceTypeSrch'
		}
			// {
			// 	display : '���벹��',
			// 	name : 'isImptSubsidySrch'
			// }
		]
	});

	// $("#formName").bind("change",function(i,n){
	// 	//����ѡ��ֵ
	// 	$("#selectedCode").val($(this).val());
	// 	$.ajax({
	// 	    type: "POST",
	// 	    url: "?model=common_workflow_selectedsetting&action=changeSelectedCode",
	// 	    data: {"selectedCode" : $(this).val() , "gridId" : 'auditing'}
	// 	});
	// });
});