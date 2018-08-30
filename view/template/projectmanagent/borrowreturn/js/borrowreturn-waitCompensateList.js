var show_page = function(page) {
	$("#myreturnGrid").yxsubgrid("reload");
};
$(function() {
	$("#myreturnGrid").yxsubgrid({
		model : 'projectmanagent_borrowreturn_borrowreturn',
		title : '待确认归还申请',
		param : {'disposeStateNot' : '8','ExaStatusArr' : '完成,免审'},
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
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
			name : 'state',
			display : '赔偿状态',
			sortable : true,
			align : 'center',
			process : function(v) {
				switch(v){
					case '0' : return '暂无';break;
					case '1' : return '待生成赔偿单';break;
					case '2' : return '已完成赔偿单';break;
				}
			}
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
                    case '0' : return '待处理';break;
                    case '1' : return '正在处理';break;
                    case '2' : return '已处理';break;
                    case '8' : return '打回';break;
                    default : return '--';
                }
            },
            width : 70
        }],
		// 主从表格设置
		subGridOptions : {
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
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				if (row) {
					showOpenWin("?model=projectmanagent_borrowreturn_borrowreturn&action=toView&id="
							+ row.id + "&skey=" + row['skey_']
						,1,500,1000,row.id);
				}
			}
		},{
			text : '下达赔偿单',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.state == "1") {//如果是设备遗失，直接确认
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row) {
					showOpenWin("?model=finance_compensate_compensate&action=toAdd&relDocId="
						+ row.id + "&skey=" + row['skey_'] + "&relDocType=PCYDLX-01"
						,1,700,1100,row.id);
				}
			}
		},{
			text : '完成赔偿',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.state == "1" && row.applyType == 'JYGHSQLX-01') {//设备遗失不能进行此操作
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定要进行此操作吗?"))) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_borrowreturn_borrowreturn&action=ajaxState",
						data : {
							id : row.id,
							state : 2
						},
						success : function(msg) {
							if (msg == 1) {
								alert('操作成功！');
								show_page();
							}else{
							    alert('操作失败！');
							}
						}
					});
				}
			}
		}],
		//过滤数据
		comboEx : [{
			text : '赔偿状态',
			key : 'states',
            value : '1',
			data : [{
				text : '暂无',
				value : '0'
			}, {
				text : '待生成赔偿单',
				value : '1'
			}, {
				text : '已生成赔偿单',
				value : '2'
			}]
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
			display : '责任人姓名',
			name : 'salesName'
		}]
	});
});