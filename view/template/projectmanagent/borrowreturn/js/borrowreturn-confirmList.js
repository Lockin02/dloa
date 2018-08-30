var show_page = function() {
	$("#myreturnGrid").yxsubgrid("reload");
};
$(function() {
	$("#myreturnGrid").yxsubgrid({
		model : 'projectmanagent_borrowreturn_borrowreturn',
		title : '待确认归还申请',
		param : {'disposeStateNot' : '8','ExaStatusArr' : '完成,免审'},
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
			name : 'disposeState',
			display : '提示灯',
			sortable : true,
			align : 'center',
			process : function(v) {
				switch(v){
					case '0' : return '<img src="images/icon/cicle_yellow.png" title="待处理"/>';
					case '1' : return '<img src="images/icon/cicle_blue.png" title="质检中"/>';
					case '2' : return '<img src="images/icon/cicle_grey.png" title="已处理"/>';
                    case '3' : return '<img src="images/icon/cicle_green.png" title="质检完成"/>';
//                    case '9' : return '<img src="images/icon/cicle_black.png" title="等待销售确认"/>';
				}
			},
			width : 50
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
//                    case '9' : return '等待销售确认';
                    default : return v;
                }
            },
            width : 80
        }, {
            name : 'receiveStatus',
            display : '接收确认',
            sortable : true,
            process : function(v) {
                switch(v){
	            	case '0' : return '否';
	                case '1' : return '是';
                }
            },
            width : 60
        }, {
			name : 'receiveId',
			display : '接收确认人id',
			sortable : true,
			hide : true
		}, {
			name : 'receiveName',
			display : '接收确认人',
			sortable : true,
			width : 90
		}, {
			name : 'receiveTime',
			display : '接收确认时间',
			sortable : true,
			width : 130
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
			text : '提交质检',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.disposeState == "0" && row.applyType == 'JYGHSQLX-01') {//归还的才有此操作
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row) {
					showOpenWin("?model=produce_quality_qualityapply&action=toAdd&relDocId="
						+ row.id
						+ "&relDocType=ZJSQYDGH"
						+ "&relDocCode=" + row.Code
						,1,500,1000,row.id
					);
				}
			}
		},{
			text : '下达归还确认单',
			icon : 'add',
			showMenuFn : function(row) {
				if ((row.disposeState == "3" && row.receiveStatus == '1') || (row.disposeState != "2" && row.applyType == 'JYGHSQLX-02')) {//如果是设备遗失，直接确认
					return true;
				}
				return false;
			},
			action : function(row,rows,grid,g) {
				if (row) {
                    showModalWin("?model=projectmanagent_borrowreturn_borrowreturnDis&action=toAdd&borrowreturnId="
						+ row.id + "&skey=" + row['skey_'] + "&borrowreturnCode=" + row.Code
						,1,row.id);
				}
			}
		},{
			text : '打回',
			icon : 'delete',
			showMenuFn : function(row) {
				if(row.disposeState == '0' && row.applyType == 'JYGHSQLX-01') {
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
		},{
			text : '接收确认',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.disposeState == "3" && row.receiveStatus == '0') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					if(confirm("确定要接收确认？")){
						$.ajax({
							url:'?model=projectmanagent_borrowreturn_borrowreturn&action=ajaxReceive&id=' + row.id,
							type:'get',
							dataType:'json',
							success:function(msg){
								if(msg==1){
									show_page();
									alert('操作成功');
								}else
									alert('操作失败');
							},
							error:function(){
								alert('服务器连接失败');
							}
						});
					}
				} else {
					alert("请选中一条数据");
				}
			}
		}],
		//过滤数据
		comboEx : [{
			text : '处理状态',
			key : 'disposeStates',
			value : '0,1,3',
			data : [{
				text : '待处理',
				value : '0'
			}, {
				text : '质检中',
				value : '1'
			}, {
                text : '质检完成',
                value : '3'
            }, {
				text : '已处理',
				value : '2'
			}, {
//				text : '等待销售确认',
//				value : '9'
//			}, {
				text : '待处理、质检中、质检完成',
				value : '0,1,3'
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
		}, {
			display : '客户名称',
			name : 'customerName'
		}, {
			display : '序列号',
			name : 'serialName'
		}, {
            display : '备注',
            name : 'remark'
        }]
	});
});