var show_page = function(page) {
    $("#payablesapplyGrid").yxsubgrid("reload");
};

$(function() {
    $("#payablesapplyGrid").yxsubgrid({
        model: 'finance_payablesapply_payablesapply',
        action : 'pageJsonForRead',
        title: '付款申请信息',
        isEditAction : false,
        isDelAction : false,
        isAddAction : false,
        customCode : 'payablesapplyGrid',
        //列信息
        colModel: [{
            display: '打印',
            name: 'printId',
            width : 30,
            align : 'center',
            sortable: false,
			process : function(v,row){
				if(row.id == 'noId') return '';
				if(row.printCount > 0){
					return '<img src="images/icon/print.gif" title="已打印，打印次数为:'+ row.printCount+'"/>';
				}else{
					return '<img src="images/icon/print1.gif" title="未打印过的单据"/>';
				}
			}
        },{
            display: '付款单号',
            name: 'id',
            width : 40,
            sortable: true,
			process : function(v,row){
				if(row.id == 'noId'){
					return v;
				}
				if(row.payFor == 'FKLX-03'){
					if(row.sourceType != ''){
						return "<a href='javascript:void(0)' title='退款申请' style='color:red' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=toView&id=" + row.id + '&skey=' + row.skey_ +"\")'>" + v + "</a>";
					}else{
						return "<a href='javascript:void(0)' title='退款申请' style='color:red' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ +"\")'>" + v + "</a>";
					}
				}else{
					if(row.sourceType != ''){
						return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=toView&id=" + row.id + '&skey=' + row.skey_ +"\")'>" + v + "</a>";
					}else{
						return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ +"\")'>" + v + "</a>";
					}
				}
			}
        },
        {
            name: 'formNo',
            display: '申请单编号',
            sortable: true,
            width : 140,
			process : function(v,row){
				if(row.id == 'noId'){
					return v;
				}
				if(row.payFor == 'FKLX-03'){
					if(row.sourceType != ''){
						return "<a href='javascript:void(0)' title='退款申请' style='color:red' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=toView&id=" + row.id + '&skey=' + row.skey_ +"\")'>" + v + "</a>";
					}else{
						return "<a href='javascript:void(0)' title='退款申请' style='color:red' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ +"\")'>" + v + "</a>";
					}
				}else{
					if(row.sourceType != ''){
						return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=toView&id=" + row.id + '&skey=' + row.skey_ +"\")'>" + v + "</a>";
					}else{
						return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ +"\")'>" + v + "</a>";
					}
				}
			}
        },
        {
            name: 'formDate',
            display: '单据日期',
            sortable: true,
            width : 80
        },
        {
            name: 'payDate',
            display: '期望付款日期',
            sortable: true,
            width : 80
        },
        {
            name: 'actPayDate',
            display: '实际付款日期',
            sortable: true,
            width : 80
        },
        {
            name: 'sourceType',
            display: '源单类型',
            sortable: true,
            datacode : 'YFRK',
            width : 80
        },
        {
            name: 'payFor',
            display: '申请类型',
            sortable: true,
            datacode : 'FKLX',
            width : 80
        },
        {
            name: 'supplierName',
            display: '供应商名称',
            sortable: true,
            width : 150
        },
        {
            name: 'payMoney',
            display: '申请金额',
            sortable: false,
            process : function(v){
            	if(v >= 0){
					return moneyFormat2(v);
            	}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
            	}
            },
            width : 80
        },
        {
            name: 'payedMoney',
            display: '已付金额',
            sortable: true,
            process : function(v){
            	if(v >= 0){
					return moneyFormat2(v);
            	}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
            	}
            },
            width : 80
        }, {
                name: 'pchMoney',//源单金额
                display: '源单合同金额',
                sortable: true,
                process: function(v) {
                    if (v >= 0) {
                        return moneyFormat2(v);
                    } else {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    }
                },
                width: 80
            }, {
            name: 'status',
            display: '单据状态',
            sortable: true,
            datacode: 'FKSQD',
            width : 70
        },
        {
            name: 'ExaStatus',
            display: '审批状态',
            sortable: true,
            width : 80
        },
        {
            name: 'ExaDT',
            display: '审批时间',
            sortable: true,
            width : 80
        },
        {
            name: 'deptName',
            display: '申请部门',
            sortable: true,
            width : 80
        },
        {
            name: 'salesman',
            display: '申请人',
            sortable: true,
            width : 80
        },
        {
            name: 'feeDeptName',
            display: '费用归属部门',
            sortable: true,
            width : 80
        },
        {
            name: 'feeDeptId',
            display: '费用归属部门id',
            sortable: true,
            hide : true,
            width : 80
        },
        {
            name: 'shareStatus',
            display: '费用分摊状态',
            sortable: true,
            width : 80,
            process : function(v){
            	if(v == '1'){
            		return '已分摊';
            	}else if(v == '0'){
            		return '未分摊';
            	}else if(v == '2'){
            		return '部分分摊';
            	}
            }
        },
        {
            name: 'shareMoney',
            display: '分摊金额',
            sortable: true,
            width : 80,
            process : function(v){
				return moneyFormat2(v);
            }
        },
        {
            name: 'createName',
            display: '创建人',
            sortable: true
        },
        {
            name: 'createTime',
            display: '创建日期',
            sortable: true,
            width : 120,
            hide : true
        }],

		// 主从表格设置
		subGridOptions : {
			url : '?model=finance_payablesapply_detail&action=pageJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [
				{
					paramId : 'payapplyId',// 传递给后台的参数名称
					colId : 'id'// 获取主表行数据的列名称
				}
			],

			// 显示的列
			colModel : [{
					name : 'objType',
					display : '源单类型',
					datacode : 'YFRK'
				},{
					name : 'objCode',
					display : '源单编号',
					width : 150
				},{
					name : 'money',
					display : '申请金额',
					process : function(v){
						return moneyFormat2(v);
					}
				},{
					name : 'purchaseMoney',
					display : '源单金额',
					process : function(v){
						return moneyFormat2(v);
					}
				}
			]
		},
        menusEx : [{
				text : '打印',
				icon : 'print',
				showMenuFn : function(row) {
					if(row.id == 'noId'){
						return false;
					}
					if (row.status != 'FKSQD-04' ) {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					showModalWin("?model=finance_payablesapply_payablesapply&action=toPrint&id=" + row.id + '&skey=' + row['skey_'],1);
				}
			},{ //TODO
				text : '录入费用分摊',
				icon : 'edit',
				showMenuFn : function(row) {
					if(row.id == 'noId' || row.payFor == 'FKLX-03'){
						return false;
					}
					if (row.ExaStatus == '完成' && (row.status != 'FKSQD-04' || row.status != 'FKSQD-05')) {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					showThickboxWin('?model=finance_payablescost_payablescost&action=toShare&payapplyId='
						+ row.id
						+ '&payapplyCode=' + row.formNo
						+ '&payapplyMoney=' + row.payMoney
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				}
			}
        ],
        toViewConfig :{
			showMenuFn : function(row) {
				if(row.id == 'noId'){
					return false;
				}
			},
        	toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					if(rowData.sourceType != ''){
						showModalWin("?model=finance_payablesapply_payablesapply&action=toView&id=" + rowData.id + keyUrl,1);
					}else{
						showModalWin("?model=finance_payablesapply_payablesapply&action=init&perm=view&id=" + rowData.id + keyUrl,1);
					}
				} else {
					alert('请选择一行记录！');
				}
			}
        },
		buttonsEx :[
	        {
				name : 'view',
				text : "高级查询",
				icon : 'view',
				action : function() {
					showThickboxWin("?model=finance_payablesapply_payablesapply&action=toSearch&"
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
				}
	        }
		],
      	//高级搜索
//		advSearchOptions : {
//			modelName : 'payablesapplySearch',
//			// 选择字段后进行重置值操作
//			selectFn : function($valInput) {
//				$valInput.yxselect_user("remove");
//			},
//			searchConfig : [{
//		            name : '单据日期',
//		            value : 'c.formDate',
//					changeFn : function($t, $valInput) {
//						$valInput.click(function() {
//							WdatePicker({
//								dateFmt : 'yyyy-MM-dd'
//							});
//						});
//					}
//		        },{
//		            name : '期望付款日期',
//		            value : 'c.payDate',
//					changeFn : function($t, $valInput) {
//						$valInput.click(function() {
//							WdatePicker({
//								dateFmt : 'yyyy-MM-dd'
//							});
//						});
//					}
//		        },{
//		            name : '实际付款日期',
//		            value : 'c.actPayDate',
//					changeFn : function($t, $valInput) {
//						$valInput.click(function() {
//							WdatePicker({
//								dateFmt : 'yyyy-MM-dd'
//							});
//						});
//					}
//		        },{
//					name : '申请人',
//					value : 'c.salesman',
//					changeFn : function($t, $valInput, rowNum) {
//						if (!$("#salesmanId" + rowNum)[0]) {
//							$hiddenCmp = $("<input type='hidden' id='salesmanId"+ rowNum + "'/>");
//							$valInput.after($hiddenCmp);
//						}
//						$valInput.yxselect_user({
//							hiddenId : 'salesmanId' + rowNum,
//							height : 200,
//							width : 550,
//							formCode : 'payablesapply',
//							gridOptions : {
//								showcheckbox : false
//							}
//						});
//					}
//				},{
//		            name : '供应商名称',
//		            value : 'c.supplierName'
//		        },{
//		            name : '原单类型',
//		            value : 'sourceType',
//					type:'select',
//		            datacode : 'YFRK'
//		        }
//			]
//		},

        //过滤数据
		comboEx:[{
		     text:'审批状态',
		     key:'ExaStatus',
		     type : 'workFlow',
			 value : '完成'
		   },{
		     text:'单据状态',
		     key:'status',
		     datacode : 'FKSQD',
			 value : 'FKSQD-01'
		   },{
		     text:'费用分摊状态',
		     key:'shareStatus',
		     data : [{
					text : ' 未分摊',
					value : '0'
				}, {
					text : '部分分摊',
					value : '2'
				}, {
					text : '已分摊',
					value : '1'
				}
			]
		}],

		searchitems : [{
			display : '供应商名称',
			name : 'supplierName'
		},{
			display : '申请单编号',
			name : 'formNoSearch'
		},{
			display : '源单编号',
			name : 'objCodeSearch'
		},{
			display : 'id',
			name : 'id'
		},{
			display : '申请人',
			name : 'salesmanSearch'
		},{
			display : '申请部门',
			name : 'deptNameSearch'
		},{
			display : '费用归属部门',
			name : 'feeDeptNameSearch'
		}],
		sortorder : 'DESC',
		sortname : 'c.actPayDate DESC,c.formDate '
    });
});