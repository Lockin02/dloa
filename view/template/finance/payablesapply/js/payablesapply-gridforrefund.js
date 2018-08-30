var show_page = function(page) {
    $("#payablesapplyGrid").yxsubgrid("reload");
};

$(function() {
    $("#payablesapplyGrid").yxsubgrid({
        model: 'finance_payablesapply_payablesapply',
        title: '退款申请',
        action : 'pageJsonList',
        param : {"payForArr" : 'FKLX-03'},
        isAddAction : false,
        isEditAction : false,
        isDelAction : false,
        customCode : 'payablesapplyGrid',
        //列信息
        colModel: [{
            display: '打印',
            name: 'id',
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
            display: 'id',
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
            name: 'isAdvPay',
            display: '提前申请',
            sortable: true,
            width : 80,
            hide: true,
            process : function(v){
				if( v == '1'){
					return '是';
				}else{
					return '否';
				}
            }
        },
        {
            name: 'actPayDate',
            display: '实际退款日期',
            sortable: true,
            width : 80
        },
        {
            name: 'formDate',
            display: '单据日期',
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
            name: 'bank',
            display: '开户银行',
            sortable: true,
            width : 120
        },
        {
            name: 'account',
            display: '银行账号',
            sortable: true,
            width : 120
        },
        {
            name: 'payMoney',
            display: '申请金额',
            sortable: true,
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
        },
        {
            name: 'payMoneyCur',
            display: '本位币金额',
            sortable: true,
            process: function (v) {
                if (v >= 0) {
                    return moneyFormat2(v);
                } else {
                    return "<span class='red'>" + moneyFormat2(v) + "</span>";
                }
            },
            width: 80
        }, {
            name: 'currency',
            display: '付款币种',
            sortable: true,
            width: 60
        }, {
            name: 'rate',
            display: '汇率',
            sortable: true,
            width: 60
        },
        {
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
            name: 'ExaUser',
            display: '审批人',
            sortable: true
        },
        {
            name: 'ExaContent',
            display: '审批信息',
            sortable: true,
            width : 130
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
            name: 'createName',
            display: '创建人',
            hide : true,
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
        menusEx : [
        	{
				text : '编辑',
				icon : 'edit',
				showMenuFn : function(row) {
					if(row.id == 'noId'){
						return false;
					}
					if (row.ExaStatus == '待提交') {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					if(row.sourceType != ''){
						showModalWin("?model=finance_payablesapply_payablesapply&action=toEdit&id=" + row.id + '&skey=' + row['skey_'],1);
					}else{
						showModalWin("?model=finance_payablesapply_payablesapply&action=init&owner=my&id=" + row.id + '&skey=' + row['skey_'],1);
					}
				}
			},
        	{
				text : '提交审批',
				icon : 'add',
				showMenuFn : function(row) {
					if(row.id == 'noId'){
						return false;
					}
					if (row.ExaStatus == '待提交') {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					if(row.sourceType == 'YFRK-02' || row.sourceType == 'YFRK-03'){
						showThickboxWin('controller/finance/payablesapply/ewf_index1.php?actTo=ewfSelect&billId='
							+ row.id + '&flowMoney=' + row.payMoney
							+ '&flowDept=' + row.feeDeptId
							+ '&skey=' + row['skey_']
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
					}else{
						if(row.payFor == 'FKLX-03'){
							showThickboxWin('controller/finance/payablesapply/ewf_indexback.php?actTo=ewfSelect&billId='
								+ row.id + '&flowMoney=' + Math.abs(row.payMoney)
								+ '&skey=' + row['skey_']
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");

						}else{
							showThickboxWin('controller/finance/payablesapply/ewf_index.php?actTo=ewfSelect&billId='
								+ row.id + '&flowMoney=' + row.payMoney
								+ '&skey=' + row['skey_']
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
						}
					}
				}
			},{
				text : '删除',
				icon : 'delete',
				showMenuFn : function(row) {
					if(row.id == 'noId'){
						return false;
					}
					if (row.ExaStatus == '待提交') {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					if (confirm('确定要删除？')) {
						$.post(
							"?model=finance_payablesapply_payablesapply&action=ajaxdeletes",
							{ "id": row.id },
							function(data){
								if(data == 1){
									alert('删除成功');
									show_page();
								}else{
									alert('删除失败');
									show_page();
								}
							}
						)
					}
				}
			},
			{
				text : '录入退款记录',
				icon : 'add',
				showMenuFn : function(row) {
					if(row.id == 'noId'){
						return false;
					}
					if (row.ExaStatus == '完成' && row.status != 'FKSQD-03') {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					if(row.payFor == 'FKLX-01'){
						showOpenWin('?model=finance_payables_payables&action=toAddForApply&objId='
							+ row.id
							+ '&formType=CWYF-01'
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
					}else if(row.payFor == 'FKLX-02'){
						showOpenWin('?model=finance_payables_payables&action=toAddForApply&objId='
							+ row.id
							+ '&formType=CWYF-02'
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
					}else{
						showOpenWin('?model=finance_payables_payables&action=toAddForApply&objId='
							+ row.id
							+ '&formType=CWYF-03'
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
					}
				}
//			},{
//				text : '审批情况',
//				icon : 'view',showMenuFn : function(row) {
//					if (row.ExaStatus == '完成' || row.ExaStatus == '部门审批') {
//						return true;
//					}
//					return false;
//				},
//				action : function(row) {
//					showThickboxWin('controller/common/readview.php?itemtype=oa_finance_payablesapply&pid='
//						+ row.id
//						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
//				}
			},
        	{
				text : '打印',
				icon : 'print',
				showMenuFn : function(row) {
					if(row.id == 'noId'){
						return false;
					}
				},
				action : function(row, rows, grid) {
					showModalWin("?model=finance_payablesapply_payablesapply&action=toPrint&id=" + row.id + '&skey=' + row['skey_'],1);
				}
			}
        ],

        toAddConfig :{
        	toAddFn : function(p) {
//				showModalWin("?model=finance_payablesapply_payablesapply&action=toAdd");
				showModalWin("?model=finance_payablesapply_payablesapply&action=toAddDept&sourceType=YFRK-04");
			}
        },
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
		buttonsEx :[{
				text: "确认退款",
				icon: 'add',
				action: function(row,rows,idArr ) {
					if(row){
						if(confirm('确认退款么？')){
							var markIdSupplier = "";
							var markIsRed = "";
							var isSame = true;
							for (var i = 0; i < rows.length; i++) {
								if(rows[i].ExaStatus !== '完成'){
									alert('单据 ['+ rows[i].id +'] 审批未完成，不能进行确认付款操作');
									return false;
								}

								if(rows[i].status != 'FKSQD-01'){
									alert('单据 ['+ rows[i].id +'] 不是未付款状态，不能进行确认付款操作');
									return false;
								}
							}
							idStr = idArr.toString();
							$.ajax({
								type : "POST",
								url : "?model=finance_payables_payables&action=addInGroupOneKey",
								data : {
									"ids" : idStr
								},
								success : function(msg) {
									if (msg == 1) {
										alert('录入成功！');
										show_page(1);
									}else{
										alert('录入失败!');
									}
								}
							});
						}
					}else{
						alert('请先选择至少一条记录');
					}
				}
			},
	        {
				name : 'view',
				text : "高级查询",
				icon : 'view',
				action : function() {
					showThickboxWin("?model=finance_payablesapply_payablesapply&action=toSearch&"
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
				}
	        },{
				name : 'excOut',
				text : "导出",
				icon : 'excel',
				action : function() {
					$thisGrid = $("#payablesapplyGrid").data('yxsubgrid');
					$advArr = $("#payablesapplyGrid").yxsubgrid('getAdvSearchArr');

//					$.showDump($advArr);
					url = "?model=finance_payablesapply_payablesapply&action=excelOut"
						+ '&ExaStatus=' + filterUndefined( $thisGrid.options.param.ExaStatus )
						+ '&status=' + filterUndefined( $thisGrid.options.param.status )

						+ '&supplierName=' + filterUndefined( $thisGrid.options.param.supplierName )

						+ '&formDateBegin=' + filterUndefined( $thisGrid.options.param.formDateBegin )
						+ '&formDateEnd=' + filterUndefined( $thisGrid.options.param.formDateEnd )

						+ '&salesman=' + filterUndefined( $thisGrid.options.param.salesman )
						+ '&salesmanId=' + filterUndefined( $thisGrid.options.param.salesmanId )

						+ '&deptName=' + filterUndefined( $thisGrid.options.param.deptName )
						+ '&deptId=' + filterUndefined( $thisGrid.options.param.deptId )

						+ '&feeDeptName=' + filterUndefined( $thisGrid.options.param.feeDeptName )
						+ '&feeDeptId=' + filterUndefined( $thisGrid.options.param.feeDeptId )

						+ '&sourceType=' + filterUndefined( $thisGrid.options.param.sourceType )
						+ '&payForArr=FKLX-03'
					;

//					alert(url)
//					openPostWindow(url,$advArr,'退款导出');
//					window.open(url);
					window.open(url,"", "width=200,height=200,top=200,left=200");
				}
		    }
		],

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