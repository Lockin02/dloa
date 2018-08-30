var show_page = function(page) {
	$("#otherlistGrid").yxgrid("reload");
};
$(function() {
	 buttonsArr = [{
		 text : "验收文件",
		 icon : 'add',
		 action : function(row) {
			 showThickboxWin("?model=contract_contract_aidhandle&action=chooseContract"
				 +'&handleType=YSWJ'
				 + "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=800")
		 }
	 },{
		text : "附件上传",
		icon : 'add',
		action : function(row) {
			showThickboxWin("?model=contract_contract_aidhandle&action=chooseContract"
			        +'&handleType=FJSC'
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=800")
		}
	}, {
		text : "盖章申请",
		icon : 'add',
		action : function(row) {
			showThickboxWin("?model=contract_contract_aidhandle&action=chooseContract"
			        +'&handleType=GZSQ'
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=800")
		}
	}],
//	var param = {
//		'states' : '1,2,3,4,5,6,7',
//		'isTemp' : '0'
//	}
	$("#otherlistGrid").yxgrid({
		model : 'contract_contract_aidhandle',
		title : '销售助理列表',
		action : 'PageJson',
//		param : param,

		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		isOpButton:false,

		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'contractCode',
					display : '合同编号',
					sortable : true,
					width : 100
				}, {
					name : 'contractName',
					display : '合同名称',
					sortable : true,
					width : 100
				}, {
					name : 'customerName',
					display : '客户名称',
					sortable : true,
					width : 200
				}, {
					name : 'prinvipalName',
					display : '合同负责人',
					sortable : true,
					width : 80
				}, {
					name : 'areaPrincipal',
					display : '区域负责人',
					sortable : true,
					width : 80
				}, {
					name : 'handleType',
					display : '操作类型',
					sortable : true,
					process : function(v) {
						if (v == 'FJSC') {
							return "附件上传";
						} else if (v == 'GZSQ') {
							return "盖章申请";
						}
					}
				}, {
					name : 'createName',
					display : '操作人',
					sortable : true,
					width : 80
				}, {
					name : 'createTime',
					display : '操作时间',
					sortable : true,
					width : 150
				}],

		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '合同编号',
			name : 'contractCode'
		}, {
			display : '合同名称',
			name : 'contractName'
		}, {
			display : '客户名称',
			name : 'customerName'
		}],
		buttonsEx : buttonsArr,
		sortname : "createTime"
	});
});