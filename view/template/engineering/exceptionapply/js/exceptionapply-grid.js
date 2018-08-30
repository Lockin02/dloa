var show_page = function(page) {
	$("#exceptionapplyGrid").yxgrid("reload");
};
$(function() {
	$("#exceptionapplyGrid").yxgrid({
		model : 'engineering_exceptionapply_exceptionapply',
		title : '工程超权限申请',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'formNo',
				display : '申请单号',
				sortable : true,
				process : function(v,row){
					if(row.id == 'noId') return v;
					return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_exceptionapply_exceptionapply&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=480&width=800\")'>" + v + "</a>";
				},
				width : 80
			}, {
				name : 'applyTypeName',
				display : '申请类型',
				sortable : true,
				width : 70
			}, {
				name : 'applyUserName',
				display : '申请人',
				sortable : true,
				width : 90
			}, {
				name : 'applyDate',
				display : '申请日期',
				sortable : true,
				width : 80
			}, {
				name : 'applyMoney',
				display : '申请金额',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			}, {
				name : 'useRangeName',
				display : '适用范围',
				sortable : true,
				width : 80
			}, {
				name : 'projectCode',
				display : '归属项目编号',
				sortable : true,
				hide : true
			}, {
				name : 'projectName',
				display : '归属项目',
				sortable : true,
				width : 130
			}, {
				name : 'applyReson',
				display : '申请原因',
				sortable : true,
				width : 120
			}, {
				name : 'products',
				display : '物件',
				sortable : true,
				hide : true
			}, {
				name : 'rentalType',
				display : '租车性质',
				sortable : true,
				hide : true
			}, {
				name : 'rentalTypeName',
				display : '租车性质名称',
				sortable : true,
				hide : true
			}, {
				name : 'area',
				display : '使用区域',
				sortable : true,
				hide : true
			}, {
				name : 'carNumber',
				display : '车辆数',
				sortable : true,
				hide : true
			}, {
				name : 'remark',
				display : '备注信息',
				sortable : true,
				width : 120
			}, {
				name : 'ExaStatus',
				display : '审核状态',
				sortable : true,
				width : 70
			}, {
				name : 'ExaDT',
				display : '审核日期',
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
				width : 120,
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
			action : 'toView',
			formHeight : 480
		},
        //过滤数据
		comboEx:[{
		     text:'审核状态',
		     key:'ExaStatus',
		     value : '完成',
		     data : [{
		     	'text' : '完成',
		     	'value' : '完成'
		     },{
		     	'text' : '审核中',
		     	'value' : '审核中'
		     },{
		     	'text' : '待提交',
		     	'value' : '待提交'
		     },{
		     	'text' : '打回',
		     	'value' : '打回'
		     }]
		   },{
		     text:'申请类型',
		     key:'applyType',
		     datacode : 'GCYCSQ'
		   }],
		searchitems : [{
			display : "申请单号",
			name : 'formNoSearch'
		},{
			display : "申请日期",
			name : 'applyDateSearch'
		},{
			display : "归属项目",
			name : 'projectNameSearch'
		},{
			display : "申请金额",
			name : 'applyMoney'
		}],
		sortname : 'c.createTime',
		sortorder : 'DESC'
	});
});