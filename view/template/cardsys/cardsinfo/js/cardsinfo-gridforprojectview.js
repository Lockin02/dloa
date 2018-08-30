var show_page = function(page) {
	$("#cardsinfoGrid").yxgrid("reload");
};
$(function() {
	$("#cardsinfoGrid").yxgrid({
		model : 'cardsys_cardsinfo_cardsinfo',
		title : '测试卡信息',
		param : {
			'projectId' : $("#projectId").val()
		},
		isEditAction : false,
		isDelAction : false,
		isAddAction : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'operators',
				display : '运营商',
				sortable : true
			}, {
				name : 'netType',
				display : '网络类型',
				sortable : true,
				datacode : 'WLLX'
			}, {
				name : 'packageType',
				display : '套餐类型',
				sortable : true
			}, {
				name : 'ratesOf',
				display : '资费情况',
				sortable : true
			}, {
				name : 'carNo',
				display : '卡号',
				sortable : true
			}, {
				name : 'pinNo',
				display : 'pin码',
				width : 80
			}, {
				name : 'cityName',
				display : '归属地(市)',
				width : 80
			}, {
				name : 'cardType',
				display : '卡型',
				width : 80
			}, {
				name : 'status',
				display : '状态',
				width : 80,
				datacode : 'CSKZT'
			}, {
				name : 'projectCode',
				display : '项目编号',
				sortable : true,
				hide : true
			}, {
				name : 'projectName',
				display : '项目名称',
				sortable : true,
				width : 180,
				hide : true
			}, {
				name : 'ownerId',
				display : '持卡员工Id',
				sortable : true,
				hide : true
			}, {
				name : 'ownerName',
				display : '持卡人',
				sortable : true
			}, {
				name : 'openerId',
				display : '开卡员工Id',
				sortable : true,
				hide : true
			}, {
				name : 'openerName',
				display : '开卡员工',
				sortable : true,
				hide : true
			}, {
				name : 'openDate',
				display : '开卡日期',
				sortable : true,
				hide : true
			}, {
				name : 'openMoney',
				display : '开卡金额',
				sortable : true,
				process : function(v) {
					return moneyFormat2(v);
				},
				hide : true
			}, {
				name : 'initMoney',
				display : '初始金额',
				sortable : true,
				process : function(v) {
					return moneyFormat2(v);
				},
				hide : true
			}
		],
		toViewConfig : {
			action : 'toView'
		},
		isDelAction : false,
		searchitems : [{
				display : "持卡人",
				name : 'ownerNameSearch'
			}, {
				display : "卡号",
				name : 'carNoSearch'
			}
		]
	});
});