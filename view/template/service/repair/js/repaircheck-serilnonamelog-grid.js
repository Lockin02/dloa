var show_page = function(page) {
	$("#serilnoNameLogGrid").yxgrid("reload");
};
$(function() {
	$("#serilnoNameLogGrid").yxgrid({
		model : 'service_repair_repaircheck',
		title : '序列号历史维修记录',
		param : {
			'repairUserCode' : $("#repairUserCode").val(),//根据维修人员过滤
			'serilnoName' : $("#serilnoName").val(),//根据序列号过滤
			'docStatus' : 'YWX'	//只显示已维修的记录		
		},
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isShowCheckBox : false,
		showcheckbox : false,
		isOpButton : false,
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					hide : true
				}, {
					name : 'docCode',
					display : '单据编号',
					width : 120
				}, {
					name : 'issuedTime',
					display : '下达时间',
					width : 120
				}, {
					name : 'serilnoName',
					display : '序列号',
					width : 200
				}, {
					name : 'prov',
					display : '省份',
					width : 60
				}, {
					name : 'customerName',
					display : '客户名称',
					width : 200
				}, {
					name : 'contactUserName',
					display : '客户联系人',
					width : 80
				}, {
					name : 'telephone',
					display : '联系电话'
				}, {
					name : 'isGurantee',
					display : '是否过保',
					width : 80,
					process : function(v) {
						if (v == '0') {
							return "是";
						} else if (v == '1') {
							return "否";
						}
					}
				}, {
					name : 'troubleType',
					display : '故障类型',
					width : 100
				}, {
					name : 'troubleInfo',
					display : '反馈故障',
					sortable : true,
					width : 200
				}, {
					name : 'checkInfo',
					display : '检测处理方法',
					sortable : true,
					width : 150

				}, {
					name : 'finishTime',
					display : '维修完成时间',
					sortable : true

				}, {
					name : 'remark',
					display : '备注',
					sortable : true,
					width : 150
				}],
		toViewConfig : {
			action : 'toView',
			formWidth : 800,
			formHeight : 500
		},
		searchitems : [{
					display : '单据编号',
					name : 'docCode'
				}, {
					display : '省份',
					name : 'prov'
				}, {
					display : '客户名称',
					name : 'customerName'
				}, {
					display : '物料名称',
					name : 'productName'
				}, {
					display : '故障类型',
					name : 'troubleType'
				}]
	});
});