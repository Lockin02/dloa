var show_page = function(page) {
	$("#talentGrid").yxgrid("reload");
};
$(function() {
	//��ͷ��ť����
	buttonsArr = [];
	$("#talentGrid").yxgrid({
		model : 'hr_recruitment_resume',
		title : '����֪ͨ������',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		isOpButton:false,
		bodyAlign:'center',
		customCode : 'resumeGrid',
		param : {
			resumeType_d : 2
		},
		// ��չ�Ҽ��˵�

		menusEx : [{
			text : '�鿴����',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=hr_recruitment_resume&action=toView&id='
						+ row.id + "&skey=" + row['skey_'],'1');
			}
		},{
				text : '֪ͨ����',
				icon : 'edit',
				/*action : function(row) {
						showModalWin('?model=hr_recruitment_invitation&action=toAdd&resumeid=' + row.id);
				},*/
				action : function(row) {
				/*
					//����Ƿ������������
					 $.ajax({
				         type:"POST",
				         url:"?model=hr_recruitment_interview&action=isAdded",
				         data:{
				         	resumeId:row.id
				         },
				         success:function(msg){
				            if(msg==0){//�ж��Ƿ�����������
									if (window.confirm(("�ü���������������,�Ƿ����?"))) {
										showModalWin('?model=hr_recruitment_invitation&action=toAdd&resumeid=' + row.id);
										return ;
									}
				            }
				         }
				     });*/
					//����Ƿ������������֪ͨ
					 $.ajax({
				         type:"POST",
				         url:"?model=hr_recruitment_invitation&action=ajaxCheckExistsResume",
				         data:{
				         	resumeId:row.id
				         },
				         success:function(msg){
				            if(msg!=0){//�ж��Ƿ�����������
									if (window.confirm(("�ü����ѷ���������֪ͨ,�Ƿ�������?"))) {
										showModalWin('?model=hr_recruitment_invitation&action=toAdd&resumeid=' + row.id,'1');
									}
				            }else{
				            	showModalWin('?model=hr_recruitment_invitation&action=toAdd&resumeid=' + row.id,'1');
				            }
				         }
				     });

				}
		}
		],
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'resumeCode',
			display : '�������',
			sortable : true,
			process : function(v, row) {
				return '<a href="javascript:void(0)" title="����鿴����" onclick="javascript:showModalWin(\'?model=hr_recruitment_resume&action=toView&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}, {
			name : 'applicantName',
			display : 'ӦƸ������',
			width:70,
			sortable : true
		}, {
			name : 'isInform',
			display : '����֪ͨ',
			sortable : true,
			process : function(v, row) {
				if(v==1)return "��֪ͨ";
				else return "δ֪ͨ";
			}
		}, {
			name : 'post',
			display : 'ӦƸְλ',
			sortable : true,
			datacode : 'YPZW'
		}, {
			name : 'phone',
			display : '��ϵ�绰',
			sortable : true
		}, {
			name : 'email',
			display : '��������',
			sortable : true,
			width : 200
		}],
		comboEx : [{
			text : '��������',
			key : 'resumeType',
			data : [{
				text : '��˾����',
				value : '0'
			}, {
				text : 'Ա������',
				value : '1'
			}]
		}],
		buttonsEx : buttonsArr,

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