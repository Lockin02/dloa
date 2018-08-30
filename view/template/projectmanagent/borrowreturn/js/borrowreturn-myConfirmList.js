var show_page = function() {
	$("#myreturnGrid").yxsubgrid("reload");
};
$(function() {
	$("#myreturnGrid").yxsubgrid({
		model : 'projectmanagent_borrowreturn_borrowreturn',
		title : '待确认归还申请',
		param : {'disposeState' : '9','ExaStatusArr' : '完成,免审','salesId' : $("#userId").val()},
		isAddAction : false,
		isDelAction : false,
		showcheckbox : false,
		isOpButton : false,
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
			name : 'applyTypeName',
			display : '申请类型',
			width : 60,
			sortable : true
		}, {
			name : 'Code',
			display : '单据编号',
			sortable : true,
			width : 130
		}, {
			name : 'borrowCode',
			display : '借用单编号',
			sortable : true,
			width : 100
		}, {
			name : 'borrowLimit',
			display : '借用类型',
			width : 60,
			sortable : true
		}, {
			name : 'customerName',
			display : '客户名称',
			width : 100,
			sortable : true
		}, {
			name : 'remark',
			display : '备注',
			sortable : true,
			width : 150
		}, {
			name : 'createName',
			display : '操作人',
			sortable : true,
			width : 90
		}, {
			name : 'salesName',
			display : '设备责任人',
			sortable : true,
			width : 90
		}, {
			name : 'deptName',
			display : '责任人部门',
			sortable : true,
			width : 80
		}, {
			name : 'createTime',
			display : '申请时间',
			sortable : true,
			width : 130
		}, {
            name : 'disposeState',
            display : '处理状态',
            sortable : true,
            process : function(v) {
                switch(v){
                    case '0' : return '待处理';
                    case '1' : return '质检中';
                    case '2' : return '已处理';
                    case '3' : return '质检完成';
                    case '8' : return '打回';
                    case '9' : return '待确认';
                    default : return v;
                }
            },
            width : 70
        }],
		// 主从表格设置
		subGridOptions : {
//			subgridcheck:true,
			url : '?model=projectmanagent_borrowreturn_borrowreturnequ&action=pageJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				paramId : 'returnId',// 传递给后台的参数名称
				colId : 'id'// 获取主表行数据的列名称
			}],
			// 显示的列
			colModel : [{
				display : '物料编号',
				name : 'productNo',
				width : 80
			},{
				display : '物料名称',
				name : 'productName',
				width : 130
			}, {
				display : '申请归还数量',
				name : 'number',
				width : 80
			}, {
				display : '申请质检数量',
				name : 'qualityNum',
				width : 80
			}, {
				display : '合格数量',
				name : 'qPassNum',
				width : 60
			}, {
				display : '不合格数量',
				name : 'qBackNum',
				width : 80
			}, {
				display : '已下达归还数量',
				name : 'disposeNumber',
				width : 90
			}, {
				display : '已下达出库数量',
				name : 'outNum',
				width : 90
			}, {
				display : '已下达赔偿数量',
				name : 'compensateNum',
				width : 90
			}, {
				name : 'serialName',
				display : '序列号',
				width : 150
			}]
		},
		toEditConfig : {
            showMenuFn : function(row) {
                return row.disposeState == "0" ? true : false;
            },
            toEditFn : function(p,g) {
                var rowObj = g.getSelectedRow();
                var rowData = rowObj.data('data');
                showModalWin("?model=projectmanagent_borrowreturn_borrowreturn&action=toEditManage&id=" + rowData[p.keyField] );
            }
		},
		toViewConfig : {
            toViewFn : function(p,g) {
                var rowObj = g.getSelectedRow();
                var rowData = rowObj.data('data');
                showModalWin("?model=projectmanagent_borrowreturn_borrowreturn&action=toView&id=" + rowData[p.keyField] );
            }
		},
		// 扩展右键菜单
		menusEx : [{
			text : '确认',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.disposeState == "9") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=projectmanagent_borrowreturn_borrowreturn&action=toSaleConfirm&id="
						+ row.id
						+ "&skey=" + row.skey_
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
				} else {
					alert("请选中一条数据");
				}
			}
		},{
			text : '打回',
			icon : 'delete',
			showMenuFn : function(row) {
				if(row.disposeState == '9') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=projectmanagent_borrowreturn_borrowreturn&action=toDisposeback&id="
						+ row.id
						+ "&skey=" + row.skey_
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
				} else {
					alert("请选中一条数据");
				}
			}
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '归还单编号',
			name : 'Code'
		}, {
			display : '借用单编号',
			name : 'borrowCodeSearch'
		}, {
			display : '操作人姓名',
			name : 'createName'
		}, {
			display : '客户名称',
			name : 'customerName'
		}, {
			display : '序列号',
			name : 'serialName'
		}]
	});
});