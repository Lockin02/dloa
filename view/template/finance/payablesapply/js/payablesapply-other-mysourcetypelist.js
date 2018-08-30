var show_page = function(page) {
    $("#payablesapplyGrid").yxsubgrid("reload");
};

$(function() {
    $("#payablesapplyGrid").yxsubgrid({
        model: 'finance_payablesapply_payablesapply',
        action : "myApplyJson",
        title: '我的其他合同付款申请',
        isEditAction : false,
        isDelAction :false,
        isAddAction :false,
		customCode : 'myPayablesapplyGrid',
		param : {"sourceType" : $("#sourceType").val()},
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
			},
			width : 50
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
            sortable: true,
            process : function(v){
				return moneyFormat2(v);
            },
            width : 80
        },
        {
            name: 'payedMoney',
            display: '已付金额',
            sortable: true,
            process : function(v){
				return moneyFormat2(v);
            },
            width : 80
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
            name: 'deptName',
            display: '申请部门',
            sortable: true
        },
        {
            name: 'salesman',
            display: '申请人',
            sortable: true
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
        },
        {
            name: 'printCount',
            display: '打印次数',
            sortable: true,
            width : 80
        }],

        // 主从表格设置
		subGridOptions : {
			url : '?model=finance_payablesapply_detail&action=pageJsonNone',// 获取从表数据url
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
				},{
					name : 'payDesc',
					display : '备注说明',
					width : 300
				}
			]
		},
        menusEx : [
        	{
				text : '编辑',
				icon : 'edit',
				showMenuFn : function(row) {
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
					if (row.ExaStatus == '待提交') {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					if(row.sourceType == 'YFRK-02' || row.sourceType == 'YFRK-03'){
						//add chenrf 20130504    其它合同退款申请
						if(row.payFor == 'FKLX-03'){
							showThickboxWin('controller/finance/payablesapply/ewf_indexpayback.php?actTo=ewfSelect&billId='
								+ row.id + '&flowMoney=' + Math.abs(row.payMoney)
								+ '&skey=' + row['skey_']
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");

						}
						else{
							showThickboxWin('controller/finance/payablesapply/ewf_index1.php?actTo=ewfSelect&billId='
									+ row.id + '&flowMoney=' + row.payMoney
									+ '&billDept=' + row.feeDeptId
									+ '&skey=' + row['skey_']
									+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
						}

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
			},
        	{
				text : '删除',
				icon : 'delete',
				showMenuFn : function(row) {
					if (row.ExaStatus == '待提交') {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
			        if (window.confirm(("确定要删除?"))) {
						$.ajax({
							type : "POST",
							url : "?model=finance_payablesapply_payablesapply&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('删除成功！');
									show_page();
								}else{
									alert('删除失败！');
								}
							}
						});
					}
				}
			},
			{
				text : '审批情况',
				icon : 'view',
				showMenuFn : function(row) {
					if (row.ExaStatus != '待提交') {
						return true;
					}
					return false;
				},
				action : function(row) {
					showThickboxWin('controller/common/readview.php?itemtype=oa_finance_payablesapply&pid='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
				}
			},
        	{
				text : '提交财务支付',
				icon : 'edit',
				showMenuFn : function(row) {
					if(row.payDate ==""){
						if(confirm('确认提交支付吗？')){
							$.ajax({
								type : "POST",
								url : "?model=finance_payablesapply_payablesapply&action=handUpPay",
								data : {
									id : row.id
								},
								success : function(msg) {
									if (msg == 1) {
										alert('提交成功！');
										show_page();
									}else{
										alert('提交失败！');
									}
								}
							});
						}
					}else{
						var thisDate = formatDate(new Date());
						var s = DateDiff(thisDate,row.payDate);
						if(s>0){
							alert('距离期望付款日期还有 '+ s +" 天，暂不能提交财务支付");
						}else{
							if(confirm('确认提交支付吗？')){
								$.ajax({
									type : "POST",
									url : "?model=finance_payablesapply_payablesapply&action=handUpPay",
									data : {
										id : row.id
									},
									success : function(msg) {
										if (msg == 1) {
											alert('提交成功！');
											show_page();
										}else{
											alert('提交失败！');
										}
									}
								});
							}
						}
					}
				}
			},
        	{
				name : 'file',
				text : '上传附件',
				icon : 'add',
				showMenuFn : function(row) {
					if(row.status == 3){
						return false;
					}
				},
				action : function(row, rows, grid) {
					showThickboxWin("?model=finance_payablesapply_payablesapply&action=toUploadFile&id="
						+ row.id
						+ "&skey=" + row.skey_
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
				}
			},{
				text : '打印',
				icon : 'print',
				action : function(row, rows, grid) {
					showModalWin("?model=finance_payablesapply_payablesapply&action=toPrint&id=" + row.id + '&skey=' + row['skey_'],1);
				}
			},
//			{ //TODO
//				text : '录入费用分摊',
//				icon : 'edit',
//				showMenuFn : function(row) {
//					if(row.id == 'noId'){
//						return false;
//					}
//					if (row.ExaStatus == '完成') {
//						return true;
//					}
//					return false;
//				},
//				action : function(row, rows, grid) {
//					showThickboxWin('?model=finance_payablescost_payablescost&action=toShare&payapplyId='
//						+ row.id
//						+ '&payapplyCode=' + row.formNo
//						+ '&payapplyMoney=' + row.payMoney
//						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
//				}
//			},
        	{
				text : '关闭',
				icon : 'delete',
				showMenuFn : function(row) {
					if (row.ExaStatus == '完成' &&(row.status == 'FKSQD-01' || row.status == 'FKSQD-00')) {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
			        showThickboxWin('?model=finance_payablesapply_payablesapply&action=toClose&id='
						+ row.id
						+ '&skey=' + row['skey_']
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
        ],

        toAddConfig :{
        	toAddFn : function(p) {
				showModalWin("?model=finance_payablesapply_payablesapply&action=toAdd&owner=my");
			}
        },
        toViewConfig :{
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

        //过滤数据
		comboEx:[{
		     text:'审批状态',
		     key:'ExaStatus',
		     type : 'workFlow',
			 value : '完成'
		   },{
		     text:'单据状态',
		     key:'status',
		     datacode : 'FKSQD'
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
			display : '关联编号',
			name : 'objCodeSearch'
		},{
			display : '申请金额',
			name : 'payMoney'
		}],
		sortname : 'c.status asc,c.formDate'
    });
});