var show_page = function(page) {
	$("#deptGrid").yxgrid("reload");
};
$(function() {

			$("#deptGrid").yxgrid({
				model : 'hr_personnel_personnel',
               	title : '����Ա��������Ϣ',
               	param:{
					companyName:$("#companyName").val(),
					deptIdS:$("#deptIdS").val(),
					deptIdT:$("#deptIdT").val()
               	},
				showcheckbox:false,
               	isAddAction:false,
               	isEditAction:false,
               	isDelAction:false,
               	isViewAction:false,
               	isOpButton:false,
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                					name : 'userNo',
                  					display : 'Ա�����',
                  					sortable : true
                              },{
                					name : 'staffName',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'sex',
                  					display : '�Ա�',
                  					sortable : true,
                  					width:60
                              },{
                    					name : 'companyType',
                  					display : '��˾����',
                  					sortable : true
                              },{
                    					name : 'companyName',
                  					display : '��˾',
                  					sortable : true
                              },{
                    					name : 'deptNameS',
                  					display : '��������',
                  					sortable : true
                              }                    ,{
                    					name : 'deptNameT',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'jobName',
                  					display : 'ְλ',
                  					sortable : true
                              },{
                    					name : 'employeesStateName',
                  					display : 'Ա��״̬',
                  					sortable : true,
                  					width:60
                              },{
                    					name : 'personnelTypeName',
                  					display : 'Ա������',
                  					sortable : true
                              },{
                    					name : 'positionName',
                  					display : '��λ����',
                  					sortable : true
                              },{
                    					name : 'personnelClassName',
                  					display : '��Ա����',
                  					sortable : true
                              },{
                    					name : 'wageLevelName',
                  					display : '���ʼ���',
                  					sortable : true
                              },{
                    					name : 'jobLevel',
                  					display : 'ְ��',
                  					sortable : true
                              }],
		// �߼�����
//		advSearchOptions : {
//			modelName : 'personnel',
//			searchConfig : [{
//						name : 'Ա�����',
//						value : 'c.userNo'
//					}, {
//						name : '����',
//						value : 'c.userName'
//					},{
//						name : "��˾",
//						value : 'companyName'
//					},{
//						name : "����",
//						value : 'deptSearch'
//					},{
//						name : "ְλ",
//						value : 'jobName'
//					},{
//						name : "����",
//						value : 'regionName'
//					},{
//						name : "Ա��״̬",
//						value : 'employeesStateName'
//					},{
//						name : "Ա������",
//						value : 'personnelTypeName'
//					},{
//						name : "��λ����",
//						value : 'positionName'
//					},{
//						name : "��Ա����",
//						value : 'personnelClassName'
//					},{
//						name : "ְ��",
//						value : 'jobLevel'
//					}]
//		},

		searchitems : [{
						display : "Ա�����",
						name : 'userNoSearch'
					},{
						display : "����",
						name : 'staffNameSearch'
					},{
						display : "��˾",
						name : 'companyNameSearch'
					},{
						display : "����",
						name : 'deptSearch'
					},{
						display : "ְλ",
						name : 'jobNameSearch'
					},{
						display : "����",
						name : 'regionNameSearch'
					},{
						display : "Ա������",
						name : 'personnelTypeNameSearch'
					},{
						display : "��λ����",
						name : 'positionNameSearch'
					},{
						display : "��Ա����",
						name : 'personnelClassNameSearch'
					},{
						display : "ְ��",
						name : 'jobLevelSearch'
					}]
 		});
 });