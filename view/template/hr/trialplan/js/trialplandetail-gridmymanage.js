var show_page = function(page) {
	$("#trialplandetailGrid").yxgrid("reload");
};
$(function() {
	$("#trialplandetailGrid").yxgrid({
		model : 'hr_trialplan_trialplandetail',
		action : 'myManageJson',
		title : '�ҵ���������',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'taskName',
				display : '��������',
				sortable : true,
				width : 130
			}, {
				name : 'description',
				display : '��������',
				sortable : true,
				width : 150
			}, {
				name : 'managerName',
				display : '��������',
				sortable : true,
				hide : true
			}, {
				name : 'managerId',
				display : '��������id',
				sortable : true,
				hide : true
			}, {
				name : 'taskScore',
				display : '�������',
				sortable : true,
				width : 60
			}, {
				name : 'baseScore',
				display : '���û���',
				sortable : true,
				width : 60
			}, {
				name : 'memberName',
				display : '����ִ����',
				sortable : true,
				width : 80
			}, {
				name : 'memberId',
				display : '����ִ����id',
				sortable : true,
				hide : true
			}, {
				name : 'status',
				display : '״̬',
				sortable : true,
				process : function(v){
					switch(v){
						case '0' : return 'δ����';break;
						case '1' : return 'ִ����';break;
						case '2' : return '�����';break;
						case '3' : return '�����';break;
						default : return v;
					}
				},
				width : 60
			}, {
				name : 'handupDate',
				display : '�ύ����',
				sortable : true,
				width : 80
			}, {
				name : 'score',
				display : '����',
				sortable : true,
				width : 60
			}, {
				name : 'scoreDate',
				display : '��������',
				sortable : true,
				width : 80
			}, {
				name : 'scoreDesc',
				display : '����˵��',
				sortable : true,
				width : 130
			}, {
				name : 'beforeId',
				display : 'ǰ������id',
				sortable : true,
				hide : true
			}, {
				name : 'beforeName',
				display : 'ǰ����������',
				sortable : true,
				width : 130,
				hide : true
			}],

		menusEx : [{
			name : 'edit',
			text : "���",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '2') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin("?model=hr_trialplan_trialplandetail&action=toScore&id=" + row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
			}
		}],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
        //��������
		comboEx:[{
		     text:'״̬',
		     key:'status',
		     value : 2,
		     data : [{
					text : 'δ����',
					value : '0'
				}, {
					text : 'ִ����',
					value : '1'
				}, {
					text : '�����',
					value : '2'
				}, {
					text : '�����',
					value : '3'
				}
			]
		}],
		searchitems : [{
			display : "��������",
			name : 'taskNameSearch'
		}],
		sortorder : 'ASC'
	});
});