var show_page = function(page) {
	$("#compenstateGrid").yxgrid("reload");
};
$(function() {
	$("#compenstateGrid").yxgrid({
		model : 'projectmanagent_borrowreturn_borrowreturnDis',
		title : '赔偿单',
		param : {'states' : '3,4'},
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				if (row) {
					showModalWin("?model=projectmanagent_borrowreturn_borrowreturnDis&action=toView&id="
							+ row.id + "&skey=" + row['skey_']);
				}
			}

		},{
			text : '确认赔偿金额',
			icon : 'add',
            showMenuFn : function(row) {
				if (row.state = '3' && (row.ExaStatus == '未审批' || row.ExaStatus == '打回')) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row) {
					showThickboxWin("?model=projectmanagent_borrowreturn_borrowreturnDis&action=comfirmComMoney&id="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=800&width=1000");
				}
			}
		},{
			text : '处理单据',
			icon : 'add',
            showMenuFn : function(row) {
				if (row.compensateState = '0' && row.ExaStatus == '完成') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定处理单据吗?"))) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_borrowreturn_borrowreturnDis&action=ajaxDisposeCom",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('提交成功！');
								$("#compenstateGrid").yxgrid("reload");
							}else{
							    alert('操作失败！');
								$("#compenstateGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		}],
		comboEx : [{
			text : '财务处理',
			key : 'compensateState',
			value : '0',
			data : [{
				text : '未处理',
				value : '0'
			}, {
				text : '已处理',
				value : '1'
			}]
		}],
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'borrowId',
			display : '借用单ID',
			sortable : true,
			hide : true
		}, {
			name : 'Code',
			display : '处理单编号',
			sortable : true,
			width : 150
		}, {
			name : 'borrowCode',
			display : '借用单编号',
			sortable : true
		}, {
			name : 'borrowLimit',
			display : '借用类型',
			sortable : true
		}, {
			name : 'disposeType',
			display : '处理方式',
			sortable : true
		}, {
			name : 'state',
			display : '单据状态',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "待归还";
				} else if (v == '1') {
					return "部分归还";
				} else if (v == '2') {
					return "已归还";
				} else if (v == '3') {
					return "赔偿单确认";
				} else if (v == '4') {
					return "确认完成";
				} else{
				    return "--";
				}
			},
			width : 60
		}, {
			name : 'money',
			display : '赔偿金额',
			sortable : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'affirmMoney',
			display : '确认赔偿金额',
			sortable : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'compensateState',
			display : '财务处理状态',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "未处理";
				} else if (v == '1') {
					return "已处理";
				} else{
				    return "--";
				}
			},
			width : 80
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true
		}, {
			name : 'disposeIdea',
			display : '处理意见',
			sortable : true,
			width : 300
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '处理单编号',
			name : 'Code'
		}, {
			display : '借用单编号',
			name : 'borrowCode'
		}]
	});
});