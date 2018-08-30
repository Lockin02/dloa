var show_page = function(page) {
	$("#cardrecordsGrid").yxgrid("reload");
};
$(function() {
	$("#cardrecordsGrid").yxgrid({
		model : 'cardsys_cardrecords_cardrecords',
		title : '测试卡使用记录',
		param : { 'projectId' : $("#projectId").val() },
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'cardNo',
				display : '测试卡号',
				sortable : true
			}, {
				name : 'projectCode',
				display : '项目编号',
				sortable : true,
				width : 140,
				hide : true
			}, {
				name : 'projectName',
				display : '项目名称',
				sortable : true,
				width : 150,
				hide : true
			}, {
				name : 'ownerId',
				display : '使用人',
				sortable : true,
				hide : true
			}, {
				name : 'ownerName',
				display : '使用人',
				sortable : true
			}, {
				name : 'useDate',
				display : '使用日期',
				sortable : true
			}, {
				name : 'useAddress',
				display : '使用地点',
				sortable : true
			}, {
				name : 'rechargerMoney',
				display : '充值金额',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			}, {
				name : 'useReson',
				display : '用途',
				sortable : true,
				width : 120
			}, {
				name : 'remark',
				display : '备注说明',
				sortable : true,
				hide : true
			}, {
				name : 'createId',
				display : '录入人Id',
				sortable : true,
				hide : true
			}, {
				name : 'createName',
				display : '录入人',
				sortable : true,
				hide : true
			}, {
				name : 'createTime',
				display : '录入时间',
				sortable : true,
				hide : true
			}
		],
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "卡号",
			name : 'cardNoSearch'
		}]
	});
});