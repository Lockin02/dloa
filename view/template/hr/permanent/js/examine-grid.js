var show_page = function (page) {
	$("#examineGrid").yxgrid("reload");
};

$(function () {
	$("#examineGrid").yxgrid({
		model : 'hr_permanent_examine',
		title : 'ת������������',
		isDelAction : false,
		isAddAction : false,
		showcheckbox : false,
		param : {
			userAccount : $("#userid").val(),
			statusArr : "1,2,3,4,5,6,7,8"
		},
		isOpButton:false,
		bodyAlign:'center',

		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'formCode',
			display : '���ݱ��',
			sortable : true,
			process : function(v,row){
				return "<a href='#' onclick='showOpenWin(\"?model=hr_permanent_examine&action=toView&id="+row.id+"\")'>"+v+"</a>"
			},
			width : 130
		},{
			name : 'formDate',
			display : '��������',
			sortable : true,
			width : 80
		},{
			name : 'statusC',
			display : '״̬',
			width : 60
		},{
			name : 'ExaStatus',
			display : '���״̬',
			sortable : true,
			width : 70
		},{
			name : 'userNo',
			display : 'Ա�����',
			sortable : true//,
			// process : function(v,row){
			// 	return "<a href='#' onclick='showOpenWin(\"?model=hr_personnel_personnel&action=toCodeView&userNo="+v+"\")'>"+v+"</a>"
			// },
			width : 80
		},{
			name : 'useName',
			display : '����',
			sortable : true,
			width : 70
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
			sortable : true,
			width : 80
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
			width : 80,
			process : function(v ,row) {
				if(row.ExaStatus == '���') {
					return v;
				}else{
					return '';
				}
			}
		},{
			name : 'selfScore',
			display : '�����ܷ�',
			sortable : true,
			width : 80
		},{
			name : 'totalScore',
			display : '���˳ɼ�',
			sortable : true,
			width : 80
		}],

		toEditConfig : {
			showMenuFn : function(row) {
				if (row.status == 1) {
					return true;
				} else
				return false;
			},
			toEditFn : function(p, g){
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					showOpenWin("?model=hr_permanent_examine&action=toEdit&id=" + rowData[p.keyField]);
				}
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
				if (row.status == 1&& row.planstatus == 0&& row.summarystatus == 0&& row.tutorId!='') {
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
							id : g.getCheckedRowIds().toString()
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
				if (row.status == 1 && row.planstatus == 0 && row.summarystatus == 0 && row.tutorId == '') {
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
							id : g.getCheckedRowIds().toString()
						},
						success : function(msg) {
							if (msg == 1) {
								alert('�ύ�ɹ���');
								g.reload();
							}else{
								//alert(msg);
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
				if (row.status == 1 && row.planstatus == 1 && row.summarystatus == 1) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid) {
				parent.location = "?model=hr_permanent_examine&action=isAccept&id=" + row.id + "&schemeId=" + row.schemeId;
			}
		},{
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.status == 1) {
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
							id : g.getCheckedRowIds().toString()
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
			text : '��дԱ�����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == 6) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showOpenWin("?model=hr_permanent_examine&action=tolastedit&id=" + row.id);
			}
		}]
	});
});