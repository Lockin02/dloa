var show_page = function(page) {
	$("#esmweeklogGrid").yxsubgrid("reload");
};

$(function() {
	$("#esmweeklogGrid").yxsubgrid({
		model : 'engineering_worklog_esmweeklog',
		action : 'suppUserLogJson',
		title : '�����ܱ�',
		showcheckbox : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'weekTitle',
				display : '�ܱ�����',
				sortable : true,
				width : '300',
				hide : true
			}, {
				name : 'createName',
				display : '��д��',
				sortable : true
			}, {
				name : 'depId',
				display : '����id',
				sortable : true,
				hide : true
			}, {
				name : 'depName',
				display : '��������',
				sortable : true
			}, {
				name : 'weekTimes',
				display : '�ܴ�',
				sortable : true,
				process : function(v,row){
					return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_worklog_esmweeklog&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ +"\")'>" + v + "</a>";
				},
				width : 60
			}, {
				name : 'weekBeginDate',
				display : '��ʼ����',
				sortable : true
			}, {
				name : 'weekEndDate',
				display : '��������',
				sortable : true
			}, {
				name : 'assessmentId',
				display : '������ID',
				sortable : true,
				hide : true
			}, {
				name : 'subStatus',
				display : '�ύ״̬',
				sortable : true,
				process : function(v) {
					if (v == "WTJ") {
						return "δ�ύ";
					} else if (v == "YTJ") {
						return "���ύ";
					} else if( v=="BTG"){
						return " ��ͨ��";
					}else{
						return "��ȷ��";
					}
				},
				hide : true,
				width : 70
			}, {
				name : 'assessmentName',
				display : '�� �� ��',
				hide : true,
				sortable : true
			}, {
				name : 'exaDate',
				display : '��������',
				sortable : true,
				hide : true,
				width : 80
			}, {
				name : 'rsLevel',
				display : '���˵ȼ�',
				sortable : true,
				hide : true,
				width : 80
			}, {
				name : 'rsScore',
				display : '���˷���',
				sortable : true,
				hide : true,
				width : 80
			}, {
				name : 'updateTime',
				display : '����ʱ��',
				sortable : true,
				width : 130
			}
		],
		subGridOptions : {
			url : '?model=engineering_worklog_esmworklog&action=pageJson',
			param : [{
					paramId : 'weekId',
					colId : 'id'
				}
			],
			colModel : [{
					name : 'projectName',
					display : '��Ŀ����',
					width : 120
				}, {
					name : 'executionDate',
					display : 'ִ������',
					width : 80
				}, {
					name : 'province',
					display : '����ʡ��',
					width : 80
				}, {
					name : 'city',
					display : '���ڳ���',
					width : 80
				}, {
					name : 'workStatus',
					display : '����״̬',
					datacode : 'GXRYZT',
					width : 60
				}, {
					name : 'workloadDay',
					display : '������',
					width : 60
				}, {
					name : 'workloadUnitName',
					display : '��������λ',
					width : 60
				}, {
					name : 'description',
					display : '��������',
					width : 300
				}
			]
		},
		toAddConfig : {
			toAddFn : function(p) {
				showModalWin("?model=engineering_worklog_esmworklog&action=toAdd&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750");
			}
		},
		menusEx : [{
			text : '���ܱ�',
			icon : 'view',
			action : function(row, rows, grid) {
				showModalWin("?model=engineering_worklog_esmweeklog&action=init&perm=view&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
			}
		}],
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		sortorder : "DESC",
		sortname : "weekBeginDate",
		searchitems : [{
				display : "��д��",
				name : 'createName'
			},{
				display : "��������",
				name : 'depName'
			}, {
				display : "ȷ����",
				name : 'assessmentName'
			}, {
				display : "�� ��",
				name : 'weekTimes'
			}, {
				display : "�ܱ�����",
				name : 'worklogDate'
			}, {
				display : "�޸�ʱ��",
				name : 'updateTime'
			}
		],
		comboEx : [{
			text : '�ύ״̬',
			key : 'subStatus',
			data : [{
				text : 'δ�ύ',
				value : 'WTJ'
			}, {
				text : '���ύ',
				value : 'YTJ'
			}, {
				text : '��ȷ��',
				value : 'YQR'
			}]
		}]
	});
});