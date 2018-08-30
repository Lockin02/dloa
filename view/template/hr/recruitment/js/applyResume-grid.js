var show_page = function(page) {
	$("#applyResumeGrid").yxgrid("reload");
};

$(function() {
	buttonArr = [{
		name : 'inone',
		text : "��Ӽ���",
		icon : 'add',
		action : function(row) {
			showModalWin ("?model=hr_recruitment_applyResume&action=toSelect&gridName=resumeGrid"
				+ "&id=" + $("#id").val(),"1")
		}
	},{
		name : 'add',
		text : "��������",
		icon : 'add',
		action : function(row) {
			showModalWin ("?model=hr_recruitment_resume&action=toAdd"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=700&type=apply&id=" + $("#id").val(),"1")
		}
	}];

	//������Ӽ�����������������
	var state = new Array('ȡ��' ,'��ͣ' ,'�ύ' ,'δ�´�');
	if($.inArray($("#stateC").val() ,state) != '-1' || $("#ExaStatus").val() == "���") {
		buttonArr = [];
	}

	$("#applyResumeGrid").yxgrid({
		model : 'hr_recruitment_applyResume',
		title : '��Ա���������',
		isViewAction : false,
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		isOpButton : false,
		bodyAlign : 'center',
		param : {
			parentId : $("#id").val()
		},

		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'formCode',
			display : '������',
			sortable : true,
			width:120
		},{
			name : 'resumeCode',
			display : '�������',
			sortable : true,
			process : function(v, row) {
				return '<a href="javascript:void(0)" title="����鿴����" onclick="javascript:showModalWin(\'?model=hr_recruitment_resume&action=toView&id=' + row.resumeId + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' + "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		},{
			name : 'applicantName',
			display : 'ӦƸ������',
			sortable : true,
			width:60
		},{
			name : 'sex',
			display : '�Ա�',
			sortable : true,
			width:60
		},{
			name : 'workSeniority',
			display : '��������',
			sortable : true,
			width:60
		},{
			name : 'phone',
			display : '��ϵ�绰',
			sortable : true
		},{
			name : 'email',
			display : '��������',
			sortable : true,
			width : 150
		},{
			name : 'stateC',
			display : '״̬'
		}],

		menusEx : [{
			text : '�鿴����',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=hr_recruitment_resume&action=toView&id='
					+ row.resumeId + "&skey=" + row['skey_'],'1');
			}
		},{
			text : '֪ͨ����',
			icon : 'edit',
			showMenuFn: function(row) {
				if (row.state == 1) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showModalWin('?model=hr_recruitment_invitation&action=toapplyAdd&id='+row.id+'&applyid='
					+ row.parentId + "&resumeid=" + row.resumeId,'1');
			}
		},{
			text:"����¼��֪ͨ",
			icon:'add',
			showMenuFn:function(row){
				if(row.stateC=='�˲ų�ѡ'){
					return true;
				}
				return false;
			},
			action:function(row){
				showModalWin('?model=hr_recruitment_invitation&action=sendNotify&interviewType=1&resumeId='+row.resumeId+'&applyid='
					+ row.parentId + "&resumeid=" + row.resumeId+"&applyResumeId="+row.id,'1');
			}
		},{
			text : '���������',
			icon : 'add',
			showMenuFn: function(row) {
				if (row.stateC != '������'&&row.stateC !='����ְ')
					return true;
				else
					return false;
			},
			action : function(row) {
				$.ajax({
					type : "POST",
					url : "?model=hr_recruitment_applyResume&action=toBlack",
					data : {
						id : row.id
					},
					success:function(msg){
						if(msg == 1) {
							if (window.show_page != "undefined") {
								show_page();
							} else {
								g.reload();
							}
							alert("����������ɹ���~");
						} else if (msg == 3) {
							alert("����ʧ�ܣ���Ϊ�Ѿ������ˣ�");
						} else {
							alert("����ʧ�ܣ�~");
						}
					}
				});
			}
		},{
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.state == 1) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_recruitment_applyResume&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								show_page();
							}
						}
					});
				}
			}
		}],

		buttonsEx : buttonArr,
		toEditConfig : {
			action : 'toEdit'
		},

		toViewConfig : {
			action : 'toView'
		},

		searchitems : [{
			display : "�������",
			name : 'resumeCode'
		},{
			display : "ӦƸ������",
			name : 'applicantName'
		}]
	});
});