/**
 * 下拉研发合同表格组件 create by can
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_orderRdproject', {
		options : {
			hiddenId : 'id',
			nameCol : 'orderCode',
			gridOptions : {
				model : 'rdproject_yxrdproject_rdproject',
				// 列信息
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							name : 'sign',
							display : '是否签约',
							sortable : true,
							hide : true,
							width : 50
						}, {
							name : 'orderstate',
							display : '纸质合同状态',
							sortable : true,
							hide : true,
							width : 70
						}, {
							name : 'parentOrder',
							display : '父合同名称',
							sortable : true,
							hide : true
						}, {
							name : 'orderCode',
							display : '鼎利合同号',
							sortable : true
						}, {
							name : 'orderTempCode',
							display : '临时合同号',
							sortable : true
						}, {
							name : 'orderName',
							display : '合同名称',
							sortable : true
						}, {
							name : 'cusName',
							display : '签约方',
							sortable : true
						}, {
							name : 'state',
							display : '合同状态',
							sortable : true,
							process : function(v) {
								if (v == '0') {
									return "未提交";
								} else if (v == '1') {
									return "审批中";
								} else if (v == '2') {
									return "执行中";
								} else if (v == '3') {
									return "已关闭";
								} else if (v == '4') {
									return "已完成";
								}
							},
							width : 90
						}, {
							name : 'ExaStatus',
							display : '审批状态',
							sortable : true,
							width : 90
						}],
				// 快速搜索
				searchitems : [{
							display : '合同编号',
							name : 'orderCode'
						}, {
							display : '合同名称',
							name : 'orderName',
							isdefault : true
						}],

				// 默认搜索顺序
				sortorder : "DESC"
			}
		}
	});
})(jQuery);