/**
 * 物料基本信息下拉表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_chance', {
		options : {
			hiddenId : 'id',
			nameCol : 'chanceName',
			gridOptions : {
				isTitle : true,
				showcheckbox : false,
				model : 'projectmanagent_chance_chance',
				action : 'pageJson',
				//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'chanceCode',
			display : '商机编号',
			sortable : true
		}, {
			name : 'chanceName',
			display : '商机名称',
			sortable : true
		}, {
			name : 'customerName',
			display : '客户名称',
			sortable : true
		}, {
			name : 'trackman',
			display : '跟踪人',
			sortable : true
		}, {
			name : 'customerProvince',
			display : '客户所属省份',
			sortable : true
		}, {
			name : 'customerType',
			display : '客户类型',
			datacode : 'KHLX',
			sortable : true
		}, {
			name : 'customerTypeName',
			display : '客户类型名称',
			sortable : true,
			hide : true
		}, {
			name : 'status',
			display : '商机状态',
			process : function(v) {
				if (v == 0) {
					return "跟踪中";
				}else if(v == 3){
					return "关闭";
				}else if(v == 4){
					return "已生成合同";
				}else if(v == 5){
				    return "跟踪中"
				}else if(v == 6){
				    return "暂停"
				}
//				return "可接收状态";

			},
			sortable : true
		},{
			name : 'chanceType',
			display : '商机类型',
            process : function(v) {
				if (v == "SJLX-XSXS") {
					return "销售项目";
				}else if(v == "SJLX-FWXM"){
					return "服务项目";
				}else if(v == "SJLX-ZL"){
					return "租赁项目";
				}else if(v == "SJLX-YF"){
				    return "研发项目"
				}

			},
			sortable : true
		},{
		   name : 'chanceLevel',
		   display : '商机等级',
		   datacode : 'SJDJ',
		   sortable : true
		}],
		comboEx : [ {
			text : '商机类型',
			key : 'chanceType',
			data : [ {
				text : '销售项目',
				value : 'SJLX-XSXS'
			},{
				text : '服务项目',
				value : 'SJLX-FWXM'
			}, {
				text : '租赁项目',
				value : 'SJLX-ZL'
			},{
				text : '研发项目',
				value : 'SJLX-YF'
			}  ]
		},
		   {
			text : '商机状态',
			key : 'status',
			data : [ {
				text : '跟踪中',
				value : '0,5'
			},{
				text : '暂停',
				value : '6'
			}, {
				text : '关闭',
				value : '3'
			},{
				text : '已生成合同',
				value : '4'
			}  ]
		}
		],
				// 快速搜索
				searchitems : [{
							display : '商机编号',
							name : 'chanceCode'
						},{
							display : '商机名称',
							name : 'chanceName'
						}],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);