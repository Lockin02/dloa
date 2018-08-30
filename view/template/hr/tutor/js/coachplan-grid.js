var show_page = function(page) {
	$("#coachplanGrid").yxgrid("reload");
};
$(function() {
	$("#coachplanGrid").yxgrid({
		model : 'hr_tutor_coachplan',
		title : 'Ա�������ƻ�',
		action : 'pageJsonForCoachplan',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isOpButton : false,
		bodyAlign:'center',
		customCode : 'hr_coachplan_list',
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'studentNo',
				display : 'ѧԱԱ�����',
				sortable : true,
				width:'80',
				hide : true
			}, {
				name : 'studentName',
				display : 'ѧԱ����',
				width:'60',
				sortable : true
			}, {
				name : 'studentJob',
				display : 'ѧԱְλ',
				width:'80',
				sortable : true
			}, {
				name : 'studentDeptName',
				display : 'ѧԱ����',
				width:'80',
				sortable : true
			}, {
				name : 'userNo',
				display : '��ʦԱ�����',
				width:'80',
				sortable : true,
				hide : true
			}, {
				name : 'userName',
				display : '��ʦ����',
				width:'60',
				sortable : true
			},{
				name : 'jobName',
				display : '��ʦְλ',
				width:'80',
				sortable : true
			},{
				name : 'deptName',
				display : '��ʦ����',
				width:'80',
				sortable : true
			}, {
				name : 'studentSuperior',
				display : 'ѧԱֱ���ϼ�',
				width:'80',
				sortable : true
			},{
				name : 'createTime',
				display : '�ύʱ��',
				width:'80',
				sortable : true
			},{
				name : 'reachinfoStu',
				display : 'ѧԱ�Ƿ��Ѿ�ȷ�ϸ����ƻ�������',
				sortable : true,
				type:'statictext',
				width:'210',
				process:function(v){
					if(v==1&&v!=null){
						return "��";
					}else{
						return "��";
					}
				}
			}, {
				name : 'reachinfoTut',
				display : '��ʦ�Ƿ��Ѿ�ȷ�ϸ����ƻ�������',
				sortable : true,
				width:'210',
				process:function(v){
					if(v==1&&v!=null){
						return "��";
					}else{
						return "��";
					}
				}
			}],
		lockCol:['studentNo','studentName','studentJob'],//����������
		toViewConfig : {
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showModalWin("?model=hr_tutor_coachplan&action=toView&id=" + rowData[p.keyField] );
			}
		},
		// ��չ�Ҽ��˵�
		menusEx : [{
			name : 'aduit',
			text : '�������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' || row.ExaStatus == '���' || row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_tutor_coachplan&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}],
		searchitems : [{
			display : "ѧԱ����",
			name : 'studentNameSearch'
		},{
			display : "��ʦ����",
			name : 'userNameSearch'
		},{
			display : "ѧԱְλ",
			name : 'studentJobSearch'
		},{
			display : "��ʦְλ",
			name : 'jobNameSearch'
		},{
			display : "ѧԱ����",
			name : 'studentDeptNameSearch'
		},{
			display : "��ʦ����",
			name : 'deptNameSearch'
		}]
	});
});