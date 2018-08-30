var show_page = function(page) {
	$("#payablescostGrid").yxgrid("reload");
};
$(function() {
	$("#payablescostGrid").yxgrid({
		model : 'finance_payablescost_payablescost',
		title : '付款申请费用分摊明细',
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'payapplyId',
				display : '申请单Id',
				sortable : true,
				width : 50
			}, {
				name : 'payapplyCode',
				display : '申请单编号',
				sortable : true,
				width : 140
			}, {
				name : 'shareTypeName',
				display : '分摊类型',
				width : 80,
				sortable : true,
				process : function(v,row){
					if(row.id == 'noId') return v;
					return "<a href='#' onclick='showThickboxWin(\"?model=finance_payablescost_payablescost&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
				}
			}, {
				name : 'deptName',
				display : '部门名称',
				sortable : true
			}, {
				name : 'deptId',
				display : '部门Id',
				sortable : true,
				hide : true
			}, {
				name : 'userName',
				display : '人员名称',
				sortable : true
			}, {
				name : 'userId',
				display : '人员Id',
				sortable : true,
				hide : true
			}, {
				name : 'projectName',
				display : '项目名称',
				sortable : true
			}, {
				name : 'projectCode',
				display : '项目编号',
				sortable : true,
				width : 130
			}, {
				name : 'projectId',
				display : '项目id',
				sortable : true,
				hide : true
			}, {
				name : 'shareMoney',
				display : '分摊费用',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'status',
				display : '状态',
				sortable : true,
				width : 80,
				process : function (v){
					if(v == '1'){
						return '启用';
					}else if(v== '0'){
						return '未启用';
					}
				}
			}, {
				name : 'createName',
				display : '创建人',
				sortable : true
			}, {
				name : 'createTime',
				display : '创建时间',
				sortable : true,
				width : 130
			}
		],

		toEditConfig : {
			showMenuFn : function(row) {
				if(row.id == 'noId'){
					return false;
				}
			},
			action : 'toEdit'
		},
		toViewConfig : {
			showMenuFn : function(row) {
				if(row.id == 'noId'){
					return false;
				}
			},
			action : 'toView'
		},
        //过滤数据
		comboEx:[
			{
				text:'分摊类型',
			    key:'shareType',
			    datacode : 'CWFYFT'
			},{
				text : '状态',
				key : 'status',
				value : 1,
				data : [
					{
						text : '启用',
						value : '1'
					}, {
						text : '未启用',
						value : '0'
					}
				]
			}
		],
		searchitems : [
			{
				display : "申请单id",
				name : 'payapplyId'
			},{
				display : "申请单编号",
				name : 'payapplyCodeSearch'
			},{
				display : "部门名称",
				name : 'deptNameSearch'
			},{
				display : "人员名称",
				name : 'userNameSearch'
			},{
				display : "项目名称",
				name : 'projectNameSearch'
			},{
				display : "项目编号",
				name : 'projectCodeSearch'
			}
		],
		sortname : 'c.payapplyId'
	});
});