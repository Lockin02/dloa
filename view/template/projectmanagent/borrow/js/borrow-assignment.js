var show_page = function(page) {
	$("#shippingGrid").yxsubgrid("reload");
};

// 产品数量
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
	var limits = $('#limits').val();
	if (limits == '客户') {
		var ifshow = false;
	} else {
		var ifshow = true;
	}
	
	var param = {
		'ExaStatusArr' : "免审,完成,变更审批中,物料确认",
        'dealStatusArr':"0,2",
		'limits' : limits
	};
	$("#shippingGrid").yxsubgrid({
		model : 'projectmanagent_borrow_borrow',
		action : 'assignmentJson',

		param : param,
		title : '借试用物料确认需求',
		// 按钮
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		showcheckbox : false,
		isDelAction : false,

		// 列信息
		colModel : [{
//			display : '转借',
//			name : 'subTip',
//			width : '20',
//			process : function(v, row) {
//				if (row.subTip == 1) {
//					return "<img src='images/icon/icon063.gif' />";
//				}else{
//					return "";
//				}
//			},
//			sortable : true
//		}, {
			display : '物料审批状态',
			name : 'lExaStatus',
			sortable : true
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
			width : '75',
			sortable : true
		}, {
			name : 'rate',
			display : '进度',
			sortable : false,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=stock_outplan_assignrate&action=updateRate&docId='
						+ row.id
						+ "&docType=oa_borrow_borrow"
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
			sortable : true,
			process : function(v) {
				if (v == '0000-00-00') {
					return '';
				} else {
					return v;
				}
			}
		}, {
			name : 'standardDate',
			display : '标准交货期',
			width : 80,
			sortable : true,
			process : function(v) {
				if (v == '0000-00-00') {
					return '';
				} else {
					return v;
				}
			}
		}, {
			name : 'chanceId',
			display : '商机Id',
			sortable : true,
			hide : true
		}, {
			name : 'customerName',
			display : '客户名称',
			width : 160,
			sortable : true,
			hide : ifshow
		}, {
			name : 'objCode',
			display : '关联业务编号',
			width : '150',
			sortable : true
		}, {
			name : 'Code',
			display : '编号',
			sortable : true,
			width : '150',
			process : function(v, row) {
				if (row.changeTips == 1 || row.lExaStatus == '变更审批中') {
					return "<font color = '#FF0000'>"
							+ '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=stock_outplan_outplan&action=viewByBorrow&id='
							+ row.id
							+ "&objType=oa_borrow_borrow"
							+ "&linkId="
							+ row.linkId
							+ "&skey="
							+ row['skey_']
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#FF0000'>" + v + "</font>"
							+ '</a>';
				} else {
					return '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=stock_outplan_outplan&action=viewByBorrow&id='
							+ row.id
							+ "&objType=oa_borrow_borrow"
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
			name : 'Type',
			display : '类型',
			width : '40',
			sortable : true
		}, {
			name : 'limits',
			display : '范围',
			width : '40',
			sortable : true
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
				}
			},
			width : '60',
			sortable : true
		}, {
			name : 'customerName',
			display : '客户名称',
			hide : ifshow,
			sortable : true
		}, {
			name : 'createName',
			display : '申请人',
			width : 80,
			sortable : true
		}, {
			name : 'salesName',
			display : '销售负责人',
			width : 80,
			hide : ifshow,
			sortable : true
		}, {
			name : 'scienceName',
			display : '技术负责人',
			width : 80,
			hide : ifshow,
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			width : 90,
			hide : true,
			sortable : true,
			process : function(v, row){
				if(v == '完成' && row.lExaStatus == '变更审批中'){
					return row.lExaStatus;
				}else{
					return v;
				}
			}
		}, {
			name : 'subTip',
			display : '是否转借',
			width : 90,
			sortable : true,
			process : function(v, row){
			    if(v == '0'){
			       return "×";
			    }else if(v == '1'){
			       return "<b>√</b>";
			    }
			}
		}, {
			name : 'reason',
			display : '申请理由',
			width : 280,
			sortable : true
		}, {
			name : 'remark',
			display : '备注',
			width : 280,
			sortable : true
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '编号',
			name : 'Code'
		}, {
			display : '关联业务编号',
			name : 'objCode'
		}, {
			display : '申请人',
			name : 'createName'
		}],
		sortname : 'ExaDT desc ,id',
		sortorder : 'DESC',
		// 主从表格设置
		subGridOptions : {
			subgridcheck : true,
			url : '?model=projectmanagent_borrow_product&action=pageJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				'isTemp' : '0',
				'isDel' : '0'
			}, {
				paramId : 'borrowId',// 传递给后台的参数名称
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
		comboEx : [{
			text : '处理状态',
			key : 'dealStatusArr',
			data : [{
				text : '未处理',
				value : '0,2'
			}, {
				text : '已处理',
				value : '1,3'
			}],
			value : '0,2'
		}],
		// 扩展右键菜单
		menusEx : [{
			text : '查看详细',
			icon : 'view',
			action : function(row) {
				window.open(
						'?model=stock_outplan_outplan&action=viewByBorrow&id='
								+ row.id + "&objType=oa_borrow_borrow"
								+ "&linkId=" + row.linkId + "&skey="
								+ row['skey_'], 'borrowassign');
			}
		}, {
			// text : '查看发货物料',
			// icon : 'view',
			// showMenuFn : function(row) {
			// if (row.linkId) {
			// return true;
			// }
			// return false;
			// },
			// action : function(row) {
			// showModalWin('?model=projectmanagent_borrow_borrowequ&action=toViewTab&id='
			// + row.linkId + "&skey=" + row['skey_']);
			// }
			// }, {
			text : '确认发货物料',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.lExaStatus == '' && row.dealStatus != 2
						&& (row.ExaStatus == '物料确认')) {// 罗权洲新增的单据
					return true;
				}
				return false;
			},
			action : function(row) {
				window.open(
						'?model=projectmanagent_borrow_borrowequ&action=toEquAdd&id='
								+ row.id + "&skey=" + row['skey_'],
						'borrowassign');
			}
		}, {
			text : '编辑发货物料',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.dealStatus == 0
						&& (row.lExaStatus == '未提交' || row.lExaStatus == '打回')
						&& (row.ExaStatus == '物料确认')
				) {
					return true;
				}
				return false;
			},
			action : function(row) {
				window.open(
						'?model=projectmanagent_borrow_borrowequ&action=toEquEdit&id='
								+ row.id + "&skey=" + row['skey_'],
						'borrowassign');
			}
		}, {
			text : '发货物料变更',
			icon : 'delete',
			showMenuFn : function(row) {
				if(row.needSalesConfirm === '3' && row.salesConfirmId > 0){
					return false;
				}else if (row.dealStatus != 0
						//&& row.lExaStatus != ''
						// && proNumCount( row.id,'oa_borrow_borrow' )!= 0
						&& (row.ExaStatus == '完成' || ((row.ExaStatus == '物料确认' && row.dealStatus=='2'))) && row.status != 2
						&& (row.timeType != '短期借用')
						&& (row.lExaStatus != '变更审批中')
//                    && (row.dealStatus == '0' || row.dealStatus=='2')
                   ) {
					return true;
				}else if(row.ExaStatus == '免审'){
					return false;
				}
				return false;
			},
			action : function(row) {
				var fromWho = row.dealStatus == "1" ? "manager" : "apply";
				if (row.lExaStatus == '变更审批中') {
					alert("借试用物料变更审批中，请等待审批完成。");
				} else {
					window.open(
						'?model=projectmanagent_borrow_borrowequ&action=toEquChange&id='
						+ row.id + "&skey=" + row['skey_'] + "&fromWho=" + fromWho,
						'borrowassign');
				}
			}
		}, {
			text : "关闭",
			icon : 'delete',
			showMenuFn : function(row) {
				if (false && row.dealStatus != '1' && row.dealStatus != '3'
						&& (row.ExaStatus == '完成' || row.ExaStatus == '免审')) {
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
							docType : 'oa_borrow_borrow'
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
		}, {
			text : '打回',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.dealStatus != '1' && row.dealStatus != '3' && row.ExaStatus != '变更审批中') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {

					showThickboxWin("?model=projectmanagent_borrow_borrow&action=rollBack&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700")
				} else {
					alert("请选中一条数据");
				}
			}
				// }, {
				// text : '审批情况',
				// icon : 'view',
				// showMenuFn : function(row) {
				// if (row.lExaStatus != '') {
				// return true;
				// }
				// return false;
				// },
				// action : function(row) {
				//
				// showThickboxWin('controller/projectmanagent/borrow/readview.php?itemtype=oa_borrow_equ_link&pid='
				// + row.lid
				// +
				// '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
				//			}
		}]
	});

});