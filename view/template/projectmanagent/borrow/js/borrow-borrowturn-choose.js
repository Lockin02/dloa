var show_page = function (page) {
    $("#borrowequGrid").yxgrid("reload");
};
$(function () {
    $("#borrowequGrid").yxsubgrid({
        model: 'projectmanagent_borrow_borrow',
        action: 'borrowequJsons',
        param: {
        	'customerId': $("#customerId").val(),
        	'showAll': $("#showAll").val()
        },
        showcheckbox: true,
        isAddAction: false,
        isDelAction: false,
        isEditAction: false,
        isViewAction: false,
        // 列信息
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'chanceId',
                display: '商机Id',
                sortable: true,
                hide: true
            },
            {
                name: 'Code',
                display: '编号',
                sortable: true,
    			process : function(v, row) {
    				if (row.salesNameId == $("#salesNameId").val()){
    					return "<font color='red'>" + v + "</font>" + '</a>';
    				}else{
    					return "<font color='green'>" + v + "</font>" + '</a>';
    				}
    			}
            },
            {
                name: 'Type',
                display: '类型',
                sortable: true,
                hide: true
            },
            {
                name: 'customerName',
                display: '客户名称',
                sortable: true,
                width: 120
            },
            {
                name: 'salesName',
                display: '销售负责人',
                sortable: true,
                width: 80
            },
            {
                display: 'salesNameId',
                name: 'salesNameId',
                sortable: true,
                hide: true
            },
            {
                name: 'beginTime',
                display: '开始日期',
                sortable: true,
                width: 80
            },
            {
                name: 'closeTime',
                display: '截止日期',
                sortable: true,
                width: 80
            },
            {
                name: 'scienceName',
                display: '技术负责人',
                sortable: true,
                width: 80
            },
            {
                name: 'status',
                display: '单据状态',
                sortable: true,
                process: function (v) {
                    if (v == '0') {
                        return "正常";
                    } else if (v == '1') {
                        return "部分归还";
                    } else if (v == '2') {
                        return "关闭";
                    } else if (v == '3') {
                        return "退回";
                    } else if (v == '4') {
                        return "续借申请中"
                    } else if (v == '5') {
                        return "转至执行部"
                    } else if (v == '6') {
                        return "转借确认中"
                    } else {
                        return v;
                    }
                },
                width: 70
            },
            {
                name: 'ExaStatus',
                display: '审批状态',
                sortable: true,
                width: 70
            }
            ,
            {
                name: 'backStatus',
                display: '归还状态',
                sortable: true,
                process: function (v) {
                    if (v == '0') {
                        return "未归还";
                    } else if (v == '1') {
                        return "已归还";
                    } else if (v == '2') {
                        return "部分归还";
                    }
                },
                width: 70
            }
            ,
            {
                name: 'DeliveryStatus',
                display: '发货状态',
                sortable: true,
                process: function (v) {
                    if (v == 'WFH') {
                        return "未发货";
                    } else if (v == 'YFH') {
                        return "已发货";
                    } else if (v == 'BFFH') {
                        return "部分发货";
                    } else if (v == 'TZFH') {
                        return "停止发货";
                    }
                },
                width: 70
            },
            {
                name: 'ExaDT',
                display: '审批时间',
                sortable: true,
                hide: true,
                width: 70
            },
            {
                name: 'createName',
                display: '创建人',
                sortable: true,
                hide: true
            },
            {
                name: 'remark',
                display: '备注',
                sortable: true
            },
            {
                name: 'objCode',
                display: '业务编号',
                width: 120
            }
        ],
        comboEx: [
            {
                text: '数据过滤类型',
                key: 'objType',
                value: $("#chanceId").val(),
                data: [
                    {
                        text: '商机',
                        value: $("#chanceId").val()
                    }
                ]
            }
        ],
        buttonsEx: [
            {
                name: 'Add',
                text: "选择",
                icon: 'add',
                action: function (row, rows, rowIds, g) {
                    var dataArr = g.getAllSubSelectRowDatas();
                    if (dataArr) {
                    	var salesNameId = $("#salesNameId").val();
                    	var isWarming = false;
                    	$.each(dataArr,function(){
                    		if(salesNameId != $("#row" + this.borrowId).find("td[namex='salesNameId'] div").html()){
                    			isWarming = true;
                            	return false;
                    		}
                    	})
                    	if(isWarming){
                    		if(!confirm('存在非当前销售名下借试用单的物料，是否仍要选择？')){
                    			return false;
                    		}
                    	}
                        parent.window.returnValue = dataArr;
                        // $.showDump(outJson);
                        parent.window.close();
                    } else {
                        alert('请先选择记录');
                    }
                }
            }
        ],
        // 主从表格设置
        subGridOptions: {
            subgridcheck: true,
            url: '?model=projectmanagent_borrow_borrowequ&action=listJsonEqu&checkIds=' + $("#checkIds").val(),// 获取从表数据url
            // 传递到后台的参数设置数组
            param: [
                {
                    paramId: 'borrowId',// 传递给后台的参数名称
                    colId: 'id'// 获取主表行数据的列名称
                }
            ],
            colModel: [
                {
                    name: 'productName',
                    display: '物料名称',
                    sortable: true
                },
                {
                    name: 'productId',
                    display: '物料ID',
                    sortable: true
                },
                {
                    name: 'productNo',
                    display: '物料编码',
                    sortable: true
                },
                {
                    name: 'productModel',
                    display: '规格/型号',
                    sortable: true
                },
                {
                    name: 'warrantyPeriod',
                    display: '保修期',
                    sortable: true
                },
                {
                    name: 'number',
                    display: '数量',
                    sortable: true
                },
                {
                    name: 'executedNum',
                    display: '已执行数量',
                    sortable: true
                },
                {
                    name: 'backNum',
                    display: '归还数量',
                    sortable: true
                }
//					, {
//						name : 'toContractNum',
//						display : '转销售数量',
//						sortable : true,
//						width : 50,
//						process : function(v){
//						   if(v == ''){
//						      return 0;
//						   }else{
//						      return v;
//						   }
//						}
//					}
                ,
                {
                    name: 'price',
                    display: '单价',
                    sortable: true
                },
                {
                    name: 'money',
                    display: '金额',
                    sortable: true
                },
                {
                    name: 'borrowId',
                    display: '借试用源单ID',
                    sortable: true,
                    hide: true
                }
            ]
        },
//		event : {
//			'row_dblclick' : function(e, row, rowData) {
//				if (rowData) {
//					// 输出json
//					outJson = {
//						"id" : rowData.id,
//						"prodcutId" : rowData.productId,
//						"productCode" : rowData.prodcutNo,
//						"productName" : rowData.productName,
//						"productModel" : rowData.productModel,
//						"number" : rowData.number,
//						"price" : rowData.price,
//						"money" : rowData.money,
//						"warrantyPeriod" : rowData.warrantyPeriod,
//						"isBorrowToorder" : 1,
//						"toBorrowId" : rowData.borrowId,
//						"toBorrowequId" : rowData.id
//					};
//					parent.window.returnValue = outJson;
//
//					// $.showDump(outJson);
//					parent.window.close();
//				} else {
//					alert('请先选择记录');
//				}
//			}
//		},
//		toViewConfig : {
//			action : 'toView'
//		},
        searchitems: [
            {
                display: "编号",
                name: 'Code'
            },
            {
                display: "客户名称",
                name: 'customerName'
            },
            {
                display: "销售负责人",
                name: 'salesName'
            }, {
                display: '序列号',
                name: 'serialName2'
            }
        ]
    });

});
