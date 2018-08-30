var show_page = function (page) {
	$("#examineGrid").yxgrid("reload");
};

$(function () {
	var info = $("#userid").val();//��ȡ�˻���Ϣ
	$("#examineGrid").yxgrid({
		model : 'hr_permanent_examine',
		title : 'ת������������',
		isDelAction : false,
		isAddAction : true,
		showcheckbox : false,
		param : {
			LinkAccount : info
//			statusArr : "1,2,3,4,5,6,7,8"
		},
		isOpButton:false,
		bodyAlign:'center',

		//����Ϣ
		colModel : [{
			name : 'formCode',
			display : '���ݱ��',
			sortable : true,
			process : function(v ,row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_permanent_examine&action=toView&id="+row.id+"\")'>"+v+"</a>"
			},
			width : 130
		},{
			name : 'formDate',
			display : '��������',
			sortable : true,
			width:80
		},{
			name : 'statusC',
			display : '״̬',
			width:60
		},{
			name : 'ExaStatus',
			display : '���״̬',
			sortable : true,
			width:60
		},{
			name : 'userNo',
			display : 'Ա�����',
			sortable : true,
			width:80
		},{
			name : 'useName',
			display : '����',
			sortable : true,
			width:60
		},{
			name : 'sex',
			display : '�Ա�',
			sortable : true,
			width:30
		},{
			name : 'deptName',
			display : '����',
			sortable : true
		},{
			name : 'positionName',
			display : 'ְλ',
			sortable : true
		},{
			name : 'permanentType',
			display : 'ת������',
			sortable : true,
			width : 80
		},{
			name : 'begintime',
			display : '��ְʱ��',
			sortable : true,
			width : 80
		},{
			name : 'finishtime',
			display : '�ƻ�ת��ʱ��',
			sortable : true,
			width : 80
		},{
			name : 'permanentDate',
			display : 'ʵ��ת������',
			width : 80
		},{
			name : 'selfScore',
			display : '��������',
			sortable : true,
			width : 70
		},{
			name : 'totalScore',
			display : '��ʦ����',
			sortable : true,
			width : 70
		},{
			name : 'leaderScore',
			display : '�쵼����',
			sortable : true,
			width : 70
		}],

		lockCol:['formCode','formDate','useName'],//����������

		toEditConfig : {
			showMenuFn : function(row) {
				if (row.status == 1 && row.userAccount == info) {
					return true;
				} else {
					return false;
				}
			},
			toEditFn : function(p, g){
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					showOpenWin("?model=hr_permanent_examine&action=toEdit&id=" + rowData[p.keyField]);
				}
			}
		},
		toAddConfig : {
			toAddFn : function(p, g){
				alert("���ã���OA�����ߣ��뵽��OA�ύ���롣лл��");
				return false;
				showOpenWin("?model=hr_permanent_examine&action=toAdd");
			}
		},
		toViewConfig : {
			toViewFn : function(p, g){
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					showOpenWin("?model=hr_permanent_examine&action=toView&id=" + rowData[p.keyField]);
				}
			}
		},

		menusEx : [{
			text : '�ύ��ʦ���',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.userAccount == info && row.status == 1 && row.planstatus == 0 && row.summarystatus == 0 && row.tutorId != '') {
					return true;
				} else {
					return false;
				}
			},
			action : function(rowData, rows, rowIds, g) {
				if (window.confirm(("ȷ��Ҫ�ύ?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_permanent_examine&action=giveMaster",
						data : {
							id : rowData.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('�ύ�ɹ���');
								g.reload();
							} else {
								alert('�ύʧ�ܣ�');
							}
						}
					});
				}
			}
		},{
			text : '�ύ�쵼���',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.userAccount == info&&row.status == 1&& row.planstatus == 0&& row.summarystatus == 0&& row.tutorId=='') {
					return true;
				} else {
					return false;
				}
			},
			action : function(rowData, rows, rowIds, g) {
				if (window.confirm(("ȷ��Ҫ�ύ?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_permanent_examine&action=giveLeader",
						data : {
							id : rowData.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('�ύ�ɹ���');
								g.reload();
							} else {
								alert('�ύʧ�ܣ�');
							}
						}
					});
				}
			}
		},{
			text : '�ύ�ܼ����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.userAccount == info && row.status == 1 && row.planstatus == 1 && row.summarystatus == 1) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid) {
				location = "?model=hr_permanent_examine&action=isAccept&id=" + row.id + "&schemeId=" + row.schemeId;
			}
		},{
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.userAccount == info && row.status == 1) {
					return true;
				} else {
					return false;
				}
			},
			action : function(rowData, rows, rowIds, g) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_permanent_examine&action=ajaxdeletes",
						data : {
							id : rowData.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								g.reload();
							}
						}
					});
				}
			}
		},{
			text : 'ȷ��ת������',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.userAccount == info && row.status == 6) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showOpenWin("?model=hr_permanent_examine&action=tolastedit&id="+row.id)
			}
		},{
		//////////////////////////Ա������////////////////////////
			text : '��ӵ�ʦ���',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.tutorId == info && row.status == 2) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid){
				showOpenWin("?model=hr_permanent_examine&action=toMasterEdit&id=" + row.id);
			}
		},{
			text : '�ύֱ���쵼���',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.tutorId == info && row.status == 2) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid) {
				if (window.confirm(("ȷ��Ҫ�ύ?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_permanent_examine&action=giveLeader",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('�ύ�ɹ���');
								show_page();
							} else {
								alert('�ύʧ�ܣ�');
							}
						}
					});
				}
			}
		},{
		/////////////////////��ʦ����//////////////////////////
			text : '����쵼���',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.leaderId == info && row.status == 3 && row.ExaStatus == "δ���") {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid){
				showModalWin("?model=hr_permanent_examine&action=toLeaderEdit&id=" + row.id);
			}
		},{
			text : '�ύHR���',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.leaderId == info && row.status == 3 && row.ExaStatus == "δ���") {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid) {
				if (window.confirm(("ȷ��Ҫ�ύ?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_permanent_examine&action=giveHr",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('�ύ�ɹ���');
								show_page();
							} else {
								alert('�ύʧ�ܣ�');
							}
						}
					});
				}
			}
//		},{
//			text : '�ύ�ܼ����',
//			icon : 'edit',
//			showMenuFn : function(row) {
//				if (row.leaderId == info&&row.status == 3&&row.ExaStatus== "δ���") {
//					return true;
//				} else
//					return false;
//			},
//			action : function(row, rows, grid) {
//				location = "controller/hr/permanent/ewf_examine_index.php?actTo=ewfSelect&billId="+row.id+"&examCode=oa_hr_permanent_examine&formName=���ÿ�������";
//			}
		/////////////////�쵼����//////////////////////
		}],

		comboEx : [{
			text : '״̬',
			key : 'status',
			data : [ {
				text : '����',
				value : '1'
			},{
				text : '��ʦ���',
				value : '2'
			},{
				text : '�쵼���',
				value : '3'
			},{
				text : 'Ա��ȷ��',
				value : '6'
			},{
				text : '���',
				value : '7'
			},{
				text : '�ر�',
				value : '8'
			}]
		},{
			text : '����״̬',
			key : 'ExaStatus',
			data : [{
				text : '��������',
				value : '��������'
			},{
				text : 'δ���',
				value : 'δ���'
			},{
				text : '���',
				value : '���'
			},{
				text : '���',
				value : '���'
			}]
		}],

		searchitems : [{
			display : "���ݱ��",
			name : 'formCode'
		},{
			display : "��������",
			name : 'formCode'
		},{
			display : "Ա�����",
			name : 'userNo'
		},{
			display : "����",
			name : 'useName'
		},{
			display : "�Ա�",
			name : 'sex'
		},{
			display : "����",
			name : 'deptName'
		},{
			display : "ְλ",
			name : 'positionName'
		},{
			display : "ת������",
			name : 'permanentType'
		},{
			display : "��ְʱ��",
			name : 'begintime'
		},{
			display : "�ƻ�ת��ʱ��",
			name : 'finishtime'
		},{
			display : "ʵ��ת������",
			name : 'permanentDate'
		},{
			display : "��������",
			name : 'selfScore'
		},{
			display : "��ʦ����",
			name : 'totalScore'
		},{
			display : "�쵼����",
			name : 'leaderScore'
		}]
	});
});