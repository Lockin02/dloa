$(function() {
	var objGrid = $("#esmworklogGrid");
	//������־
	objGrid.yxgrid({
		model : 'engineering_worklog_esmworklog',
		action : 'suppUserLogJson',
		title : '������־',
		showcheckbox : false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		colModel : [{
				display : 'id',
				name : 'id',
				hide : true
			},{
				display : '��д��',
				name : 'createName',
				width : 80
			},{
				display : '����',
				name : 'executionDate',
				width : 70
			},{
				display : '��Ŀ���',
				name : 'projectCode',
				width : 140,
				align : 'left'
			},{
				display : '����',
				name : 'activityName',
				width : 120,
				align : 'left'
			}, {
				display : '������',
				name : 'workloadDay',
				width : 60
			}, {
				display : '��λ',
				name : 'workloadUnitName',
				width : 40
			}, {
				display : '�����չ',
				name : 'thisActivityProcess',
				width : 60,
				process : function(v){
					return v + " %";
				}
			}, {
				display : '��Ŀ��չ',
				name : 'thisProjectProcess',
				width : 60,
				process : function(v){
					return v + " %";
				}
			}, {
				display : '��չϵ��',
				name : 'processCoefficient',
				width : 60
			}, {
				display : '�˹�Ͷ��ռ��',
				name : 'inWorkRate',
				width : 70,
				process : function(v){
					return v + " %";
				}
			}, {
				display : '����ϵ��',
				name : 'workCoefficient',
				width : 60
			}, {
				display : '����',
				name : 'costMoney',
				width : 60,
				process : function(v,row){
					if(v * 1 == 0 || v == ''){
						return v;
					}else{
						return "<span class='blue'>" + moneyFormat2(v) + "</span>";
					}
				}
			}, {
				display : '�������',
				name : 'description',
				align : 'left'
			}, {
				display : '���˽��',
				name : 'assessResultName',
				width : 60
			}, {
				display : '�ظ�',
				name : 'feedBack',
				align : 'left',
				process : function(v,row){
					return v;
				}
			},{
				display : 'assessResult',
				name : 'assessResult',
				hide : true
			}
		],
		buttonsEx : [{
				name : 'workVerify',
				text : '��������ȷ��',
				icon : 'add',
				action :function(row) {
					showModalWin("?model=outsourcing_workverify_suppVerify&action=toAdd"
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=650&width=950")

				}
			}
        ],
		searchitems : [{
				display : "��д��",
				name : 'createName'
			},{
				display : "����",
				name : 'deptNameSearch'
			},{
				display : "����",
				name : 'executionDate'
			}, {
				display : "��Ŀ���",
				name : 'projectCode'
			}
		],
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				showOpenWin("?model=engineering_worklog_esmworklog&action=toView&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
			}
		}]
	});
});