var show_page = function(page) {
	$("#workflowGrid").yxgrid("reload");
};


/**
 * ��������ҳ��
 */
function toAuditInfo(task,id,code,Pid,name,code,isTemp){
	showThickboxWin('controller/common/readview.php?itemtype='
		+ code
		+ '&pid='
        + Pid
		+ '&taskId='
		+ task
        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
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
//��ȡ��������������
formTypeArr = [];

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

	$("#workflowGrid").yxgrid({
		model: 'common_workflow_workflow',
		action : 'auditedPageJson',
		title: '������',
		showcheckbox : true,
		isAddAction : false,
		isEditAction :false,
		isViewAction : false,
		isDelAction : false,
		customCode : 'auditedGrid',
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
			process : function(v){
				switch(v){
					case '���۶�������' : return '���ۺ�ͬ����';break;
					case '���ó�������' : return '��������������';break;
					default : return v;
				}
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
			name: 'Result',
			display: '�������',
			sortable: true,
			process : function(v){
				if(v == 'ok'){
					return 'ͨ��';
				}else{
					return '��ͨ��';
				}
			},
			width : 70
		},{
			name: 'Endtime',
			display: '����ʱ��',
			sortable: true,
			width : 130
		},{
			name: 'content',
			display: '�������',
			width : 180,
			hide : true
		}, {
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
			hide : true,
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
			align : 'center',
			process : function(v,row){
				return "<a href='javascript:void(0)' onclick='toView(\""+ row.task + "\",\""+ row.id + "\",\""+ row.code + "\",\""+ row.Pid + "\",\""+ row.name + "\",\""+ row.code + "\",\""+ row.isTemp + "\")'>�鿴����</a> |"
				+ " <a href='javascript:void(0)' onclick='toAuditInfo(\""+ row.task + "\",\""+ row.id + "\",\""+ row.code + "\",\""+ row.Pid + "\",\""+ row.name + "\",\""+ row.code + "\",\""+ row.isTemp + "\")'>�������</a>";
			},
			width : 120
		}, {
			name: 'auditHistory',
			display: '������ʷ',
			width : 300,
			process : function(v,row){
				return v;
			},
			hide : true
		}],
        buttonsEx : [
	        {
				name : 'appendView',
				text : "�鿴����",
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
	        }
        ],
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
						+ "&audited=1"
					);
				}
			},{
				text : '�������',
				icon : 'view',
				action : function(row) {
					showThickboxWin('controller/common/readview.php?itemtype='
						+ row.code
						+ '&pid='
			            + row.Pid
						+ '&taskId='
						+ row.task
			            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
				}
			}
		],
		comboEx : [{
				text : '����',
				key : 'formName',
				value : $("#selectedCode").val(),
				data : formTypeArr
			}
		],
		searchitems : [{
			display : '��������',
			name : 'taskSearch'
		},{
			display : '�ύ��',
			name : 'creatorNameSearch'
		},{
			display : '����ʱ��',
			name : 'endTimeSearch'
		},{
			display : '����ժҪ',
			name : 'infomationsearch'
		},{
			display : '��Ŀ���',
			name : 'projectCodeSearch'
		},{
			display : '��Դ',
			name : 'costSourceTypeSrch'
		}
		// ,{
		// 	display : '���벹��',
		// 	name : 'isImptSubsidySrch'
		// }
		],
		sortname : 'Endtime'
	});

	// $("#formName").bind("change",function(i,n){
	// 	//����ѡ��ֵ
	// 	$("#selectedCode").val($(this).val());
	// 	$.ajax({
	// 	    type: "POST",
	// 	    url: "?model=common_workflow_selectedsetting&action=changeSelectedCode",
	// 	    data: {"selectedCode" : $(this).val() , "gridId" : 'audited'}
	// 	});
	// });
});