var show_page = function(page) {
	$("#weeklyGrid").yxgrid("reload");
};
$(function() {
	var role = $("#role").val();
	buttonsArr = [], addButton = {
		name : 'Add',
		// hide : true,
		text : "����",
		icon : 'add',

		action : function(row) {
			showThickboxWin("?model=hr_tutor_weekly&action=toAdd&tutorId="
					+ $("#tutorId").val()
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=1000")
		}
	}
	if (role == "ѧԱ") {
		buttonsArr.push(addButton);
	}else{
		var paramState='1';
	}

	$("#weeklyGrid").yxgrid({
		model : 'hr_tutor_weekly',
		title : 'Ա�������ܱ�',
		action:'pageForRead',
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		showcheckbox : false,
		bodyAlign:'center',
		buttonsEx : buttonsArr,
		customCode : 'hr_weekly_list',
		param : {
			"tutorId" : $("#tutorId").val(),
			"role" : $("#role").val(),
			"state":paramState
		},
		event : {
			'row_dblclick' : function(e, row, data) {
				showThickboxWin("?model=hr_tutor_weekly&action=toView&id="+data.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800")
			}
		},
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showThickboxWin("?model=hr_tutor_weekly&action=toView&id="+row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=1000")
			}
		},{
			text : '�༭',
			icon : 'edit',
			 showMenuFn : function(row) {

			 if (role == 'ѧԱ' && row.state == '0') {
			    return true;
			 }
			    return false;
			 },
			action : function(row) {
				showThickboxWin("?model=hr_tutor_weekly&action=toEditWeekly&id="+row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=1000")
			}
		},{
			text : '�����ܱ�',
			icon : 'edit',
			 showMenuFn : function(row) {

			 if (role == '��ʦ' && row.isSign == '0') {
			    return true;
			 }
			    return false;
			 },
			action : function(row) {
				showThickboxWin("?model=hr_tutor_weekly&action=toEdit&id="+row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=1000")
			}
		}],
		comboEx : [{
			text : '����״̬',
			key : 'isSign',
			data : [{
				text : 'δ����',
				value : '0'
			}, {
				text : '������',
				value : '1'
			}]
		}],
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'studentName',
			display : 'ѧԱ����',
			width:'80',
			sortable : true
		}, {
			name : 'studentDeptName',
			display : 'ѧԱ����',
			width:'80',
			sortable : true
		}, {
			name : 'studentJob',
			display : 'ѧԱְλ',
			width:'80',
			sortable : true
		}, {
			name : 'userName',
			display : '��ʦ����',
			width:'80',
			sortable : true
		},{
			name : 'state',
			display : '�ύ״̬',
			sortable : true,
			width:'80',
			process : function(v, row) {
				if (v == '0') {
					return "<span style='color:red'>δ�ύ</span>";
				} else if (v == '1') {
					return "���ύ";
				}
			}
		}, {
			name : 'isSign',
			display : '�Ƿ�����',
			sortable : true,
			width:'80',
			process : function(v, row) {
				if (v == '0') {
					return "<span style='color:red'>δ����</span>";
				} else if (v == '1') {
					return "������";
				}
			}
		}, {
			name : 'signDate',
			display : '��ʦ��������',
			width:'80',
			sortable : true
		}, {
			name : 'submitDate',
			display : '�ύ����',
			width:'80',
			sortable : true
		},{
			name : 'isOnTime',
			display : '�Ƿ�������',
			width:'100',
			sortable : true,
			process:function(v){
				if(v!=null&&v!=''){
					if(v==0){
					  return '��';
					}else{
					  return '��';
					}
				}
			}
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
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
			display : "ѧԱ����",
			name : 'studentDeptNameSearch'
		}]
	});
});