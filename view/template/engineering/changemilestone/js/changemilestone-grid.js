var show_page = function(page) {
	$("#changemilestoneGrid").yxgrid("reload");
};
$(function() {
	projectId = $("#projectId").val();
	changeId = $("#changeId").val();
	versionNo = $("#versionNo").val();
	$("#changemilestoneGrid").yxgrid({
		model : 'engineering_changemilestone_changemilestone',
		title : '��Ŀ��̱������',
		param : {
			"changeId" : changeId
		},
		isDelAction : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'milestoneName',
				display : '��̱�������',
				sortable : true
			}, {
				name : 'planBeginDate',
				display : '�ƻ���ʼ',
				sortable : true,
				width : 80
			}, {
				name : 'planEndDate',
				display : '�ƻ����',
				sortable : true,
				width : 80
			}, {
				name : 'actBeginDate',
				display : 'ʵ�ʿ�ʼ',
				sortable : true,
				width : 80,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				}
			}, {
				name : 'actEndDate',
				display : 'ʵ�ʽ���',
				sortable : true,
				width : 80,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				}
			}, {
				name : 'projectCode',
				display : '��Ŀ���',
				sortable : true,
				hide : true
			}, {
				name : 'projectName',
				display : '��Ŀ����',
				sortable : true,
				hide : true
			}, {
				name : 'versionNo',
				display : '�汾��',
				sortable : true,
				width : 80
			}, {
				name : 'status',
				display : '״̬',
				sortable : true,
				datacode : 'LCBZT',
				width : 80
			}, {
				name : 'preMilestoneName',
				display : 'ǰ����̱�',
				sortable : true
			}, {
				name : 'remark',
				display : '��ע˵��',
				sortable : true,
				width : 250
			}, {
				name : 'createId',
				display : '������Id',
				sortable : true,
				hide : true
			}, {
				name : 'createName',
				display : '����������',
				sortable : true,
				hide : true
			}, {
				name : 'createTime',
				display : '����ʱ��',
				sortable : true,
				hide : true
			}, {
				name : 'updateId',
				display : '�޸���Id',
				sortable : true,
				hide : true
			}, {
				name : 'updateName',
				display : '�޸���',
				sortable : true,
				hide : true
			}, {
				name : 'updateTime',
				display : '�޸�ʱ��',
				sortable : true,
				hide : true
			}
		],
		toAddConfig : {
			plusUrl : "&projectId="+ projectId + "&changeId=" + changeId + "&versionNo=" + versionNo
		},
		toEditConfig : {
			action : 'toEdit',
			showMenuFn : function(row) {
				if (row.status != "LCBZTC" && row.milestoneId != "") {
					return true;
				}
				return false;
			}
		},
		toViewConfig : {
			action : 'toView'
		},
		menusEx : [{
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if ((row.actBeginDate == "" || row.actBeginDate == "0000-00-00")&& row.milestoneId != "" ){
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					if (window.confirm("ȷ��ɾ����?")) {
						$.ajax({
							type : "POST",
							url : "?model=engineering_changemilestone_changemilestone&action=ajaxdeletes",
							data : {
								id : row.id,
								key : row['skey_']
							},
							success : function(msg) {
								if (msg == 1) {
									alert('ɾ���ɹ�!');
									show_page();
								} else {
									alert('ɾ��ʧ��!');
								}
							}
						});
					}
				}
			}
		}],
		sortname : 'c.planBeginDate',
		sortorder : 'ASC'
	});
});