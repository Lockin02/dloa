var show_page = function(page) {
	$("#resourceapplyGrid").yxgrid("reload");
};
$(function() {
	$("#resourceapplyGrid").yxgrid({
		model : 'engineering_resources_resourceapply',
		param : {"projectId" : $("#projectId").val()},
		title : '项目设备申请表',
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		isOpButton : false,
		showcheckbox : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'formNo',
				display : '申请单编号',
				sortable : true,
				width : 120,
				process : function(v, row) {
					return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_resources_resourceapply&action=toView&id="
							+ row.id + '&skey=' + row.skey_ + "\",1,700,1100,"+row.id+")'>" + v + "</a>";
				}
			}, {
				name : 'applyUser',
				display : '申请人',
				sortable : true
			}, {
				name : 'applyUserId',
				display : '申请人id',
				sortable : true,
				hide : true
			}, {
				name : 'applyDate',
				display : '申请日期',
				sortable : true,
				width : 80
			}, {
				name : 'applyTypeName',
				display : '申请类型',
				sortable : true,
				width : 80
			}, {
				name : 'getTypeName',
				display : '领用方式',
				sortable : true,
				width : 80
			}, {
				name : 'place',
				display : '设备使用地',
				sortable : true
			}, {
				name : 'deptName',
				display : '所属部门',
				sortable : true,
				hide : true
			}, {
				name : 'projectCode',
				display : '项目编号',
				sortable : true,
				width : 120,
				hide : true
			}, {
				name : 'projectName',
				display : '项目名称',
				sortable : true,
				width : 120,
				hide : true
			}, {
				name : 'managerName',
				display : '项目经理',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'managerId',
				display : '项目经理id',
				sortable : true,
				hide : true
			}, {
				name : 'remark',
				display : '备注信息',
				sortable : true,
				width : 130,
				hide : true
			}, {
				name : 'status',
				display : '单据状态',
				sortable : true,
				width : 80,
				process : function(v){
					switch(v){
						case '0' : return '未处理';
						case '1' : return '处理中';
						case '2' : return '已处理';
						case '3' : return '已完成';
						default : return v;
					}
				}
			}, {
				name : 'ExaStatus',
				display : '审批状态',
				sortable : true,
				width : 80
			}, {
				name : 'ExaDT',
				display : '审批日期',
				sortable : true,
				width : 80
			}, {
				name : 'createName',
				display : '创建人',
				sortable : true,
				hide : true
			}, {
				name : 'createTime',
				display : '创建时间',
				sortable : true,
				hide : true
			}, {
				name : 'updateName',
				display : '修改人',
				sortable : true,
				hide : true
			}, {
				name : 'updateTime',
				display : '修改时间',
				sortable : true,
				hide : true
			}],
		toViewConfig : {
			toViewFn : function(p, g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				showOpenWin("?model=engineering_resources_resourceapply&action=toView&id="
						+ row[p.keyField],1,700,1100,row.id);
			}
		},
		comboEx : [{
			text : '单据状态',
			key : 'status',
			data : [{
				text : '未处理',
				value : '0'
			}, {
				text : '处理中',
				value : '1'
			}, {
				text : '已处理',
				value : '2'
			}, {
				text : '已完成',
				value : '3'
			}]
		},{
		     text:'审核状态',
		     key:'ExaStatus',
		     type : 'workFlow'
		}],
		searchitems : [{
			display : "申请单号",
			name : 'formNoSch'
		},{
			display : "项目编号",
			name : 'projectCodeSch'
		},{
			display : "项目名称",
			name : 'projectNameSch'
		}]
	});
});