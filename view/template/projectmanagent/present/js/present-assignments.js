var show_page = function(page) {
	$("#shipmentsGrid").yxsubgrid("reload");
};

//产品数量
function proNumCount(docId, type) {
	var proNumCount = 0
	$.ajax({
		type : 'POST',
		url : '?model=common_contract_allsource&action=hasProduct',
		data : {
			id : docId,
			type : type
		},
		async : false,
		success : function(data) {
			proNumCount = data;
			return false;
		}
	})
	return proNumCount;
}

$(function() {
	$("#shipmentsGrid").yxsubgrid({
		model : 'projectmanagent_present_present',
		action : 'assignmentJson',
		customCode : 'presentAssignmentsGrid',
		param : {
			'ExaStatusArr' : "完成,变更审批中,物料确认,部门审批",
		},
		title : '赠送申请物料确认需求',
		// 按钮
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,

		// 列信息
		colModel : [{
			display : '物料审批状态',
			name : 'lExaStatus',
			sortable : true,
			hide : true
		}, {
			display : '物料审批表Id',
			name : 'lid',
			sortable : true,
			hide : true
		}, {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'ExaDT',
			display : '建立时间',
			width : 70,
			sortable : true
		}, {
			name : 'rate',
			display : '进度',
			sortable : false,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=stock_outplan_assignrate&action=updateRate&docId='
						+ row.id
						+ "&docType=oa_present_present"
						+ "&objCode="
						+ row.objCode
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">备注 : '
						+ "<font color='gray'>" + v + "</font>" + '</a>';
			}
		}, {
			name : 'deliveryDate',
			display : '交货日期',
			width : 80,
			sortable : true
		}, {
			name : 'customerName',
			display : '客户名称',
			width : 150,
			sortable : true
		}
//		, {
//			name : 'orderCode',
//			display : '源单编号',
//			width : 170,
//			sortable : true
//		}, {
//			name : 'orderName',
//			display : '源单名称',
//			width : 170,
//			hide : true,
//			sortable : true
//		}
		, {
			name : 'Code',
			display : '编号',
			width : 120,
			sortable : true,
			process : function(v, row) {
				if (v == '') {
					v = '无'
				}
				if (row.changeTips == 1) {
					return "<font color = '#FF0000'>"
							+ '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=stock_outplan_outplan&action=viewByPresent&id='
							+ row.oldId
							+ "&objType=oa_present_present"
							+ "&linkId="
							+ row.linkId
							+ "&skey="
							+ row['skey_']
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#FF0000'>" + v + "</font>"
							+ '</a>';
				} else {
					return '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=stock_outplan_outplan&action=viewByPresent&id='
							+ row.id
							+ "&objType=oa_present_present"
							+ "&linkId="
							+ row.linkId
							+ "&skey="
							+ row['skey_']
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#4169E1'>"
							+ v
							+ "</font>"
							+ '</a>';
				}
			}
		}, {
			name : 'dealStatus',
			display : '处理状态',
			process : function(v) {
				if (v == '0') {
					return "未处理";
				} else if (v == '1') {
					return "已处理";
				} else if (v == '2') {
					return "变更未处理";
				} else if (v == '3') {
					return "已关闭";
				}else if (v == '4') {
					return "未处理";// 提交合同物料待确认
				}
			},
			width : '60',
			sortable : true
		}, {
			name : 'salesName',
			display : '申请人',
			width : 80,
			sortable : true
		}, {
			name : 'reason',
			display : '申请理由',
			hide : true,
			sortable : true
		}, {
			name : 'remark',
			display : '备注',
			hide : true,
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			width : 60,
			sortable : true
		}, {
			name : 'objCode',
			display : '业务编号',
			width : 120
		}, {
			name : 'rObjCode',
			display : '源单业务编号',
			width : 120
		}],
		// 主从表格设置
		subGridOptions : {
			subgridcheck : true,
			url : '?model=projectmanagent_present_product&action=pageJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				// 'isTemp' : '0',
				'isDel' : '0'
			}, {
				paramId : 'presentId',// 传递给后台的参数名称
				colId : 'id'// 获取主表行数据的列名称
			}],
			// 显示的列
			colModel : [{
				name : 'conProductName',
				width : 200,
				display : '产品名称',
				process : function(v, row) {
					if (row.changeTips == 1) {
						return '<img title="变更编辑的产品" src="images/changeedit.gif" />'
								+ v;
					} else if (row.changeTips == 2) {
						return '<img title="变更新增的产品" src="images/new.gif" />'
								+ v;
					} else {
						return v;
					}
				}
			}, {
				name : 'conProductDes',
				width : 200,
				display : '产品描述'
			}, {
				name : 'number',
				display : '数量',
				width : 40
			}]
		},

		menusEx : [{
			text : '查看详细',
			icon : 'view',
			action : function(row) {
				showOpenWin('?model=stock_outplan_outplan&action=viewByPresent&id='
						+ row.oldId
						+ "&objType=oa_present_present"
						+ "&linkId="
						+ row.linkId + "&skey=" + row['skey_']);
			}
		}, {
			//			text : '查看发货物料',
			//			icon : 'view',
			//			showMenuFn : function(row) {
			//				if (row.linkId) {
			//					return true;
			//				}
			//				return false;
			//			},
			//			action : function(row) {
			//				showModalWin('?model=projectmanagent_present_presentequ&action=toViewTab&id='
			//						+ row.linkId + "&skey=" + row['skey_']);
			//			}
			//		}, {
			text : '确认发货物料',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.lExaStatus == '' && row.ExaStatus == '完成' && row.dealStatus!='3') {
					return true;
				}
				return false;
			},
			action : function(row) {
				window.open(
						'?model=projectmanagent_present_presentequ&action=toEquAdd&id='
								+ row.id + "&skey=" + row['skey_'],
						'presentassign');
			}
		}, {
			text : '编辑发货物料',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.dealStatus == 0
						&& (row.lExaStatus == '未提交' || row.lExaStatus == '打回')
						&& row.ExaStatus == '完成') {
					return true;
				}else if(row.dealStatus == 4 && row.ExaStatus == '物料确认'){
					return true;
				}
				return false;
			},
			action : function(row) {
				window.open(
						'?model=projectmanagent_present_presentequ&action=toEquEdit&id='
								+ row.id + "&skey=" + row['skey_'],
						'presentassign');
			}
		}, {
			text : '发货物料变更',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.dealStatus != 0 && row.lExaStatus != ''
						//				&& proNumCount( row.id,'oa_present_present' )!= 0
						&& row.ExaStatus == '完成'  && (row.dealStatus == '0' || row.dealStatus=='2')) {
					return true;
				}else if(row.dealStatus == 2 && row.ExaStatus == '物料确认' && row.isTemp != 4){
					return true;
				}
				return false;
			},
			action : function(row) {
				var isChange = (row.changeTips == 1)? 1 : 0;
				var url = ((row.dealStatus == 2 && row.ExaStatus == '物料确认'))?
						'?model=projectmanagent_present_presentequ&action=toEquChange&id='
						+ row.id + '&isChange='+isChange+'&skey=' + row['skey_']
						:
						'?model=projectmanagent_present_presentequ&action=toEquChange&id='
						+ row.id + '&skey=' + row['skey_'];
				window.open(url,'presentassign');
			}
		},{
			text : '打回',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.dealStatus=='4' || row.dealStatus=='2') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if(row.dealStatus == '2'){
					var isSubAppChange = 1;
				}else{
					var isSubAppChange = 0;
				}
				if (window.confirm(("确定要打回?"))) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_present_presentequ&action=ajaxBack&isSubAppChange="+isSubAppChange,
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('打回成功！');
							}else{
								alert('打回失败！');
							}
							show_page();
						}
					});
				}
			}

		},{
			text : "关闭",
			icon : 'delete',
			showMenuFn : function(row) {
				if (false&&row.dealStatus != '1' && row.dealStatus != '3'
						&& row.ExaStatus == '完成') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (confirm('确定要关闭该物料确认需求？')) {
					$.ajax({
						type : 'POST',
						url : '?model=common_contract_allsource&action=closeConfirm&skey='
								+ row['skey_'],
						data : {
							id : row.id,
							docType : 'oa_present_present'
						},
						// async: false,
						success : function(data) {
							if (data == 1) {
								alert('关闭成功，该需求将放到已处理需求中。')
								show_page();
							} else {
								alert('关闭失败，请联系管理员。')
							}
							return false;
						}
					});
				}
			}
				//		}, {
				//			text : '审批情况',
				//			icon : 'view',
				//			showMenuFn : function(row) {
				//				if (row.lExaStatus != '') {
				//					return true;
				//				}
				//				return false;
				//			},
				//			action : function(row) {
				//
				//				showThickboxWin('controller/contract/contract/readview.php?itemtype=oa_present_equ_link&pid='
				//						+ row.lid
				//						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
				//			}
		}],

		comboEx : [{
			text : '处理状态',
			key : 'dealStatusArr',
			data : [{
				text : '未处理',
				value : '0,2,4'
			}, {
				text : '已处理',
				value : '1,3'
			}],
			value : '0,2,4'
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '编号',
			name : 'Code'
		}, {
			display : '业务编号',
			name : 'objCode'
		}, {
			display : '源单业务编号',
			name : 'rObjCode'
		}, {
			display : '申请人',
			name : 'createName'
		}],
		sortname : 'ExaDT desc ,id',
		sortorder : 'DESC'
	});
});