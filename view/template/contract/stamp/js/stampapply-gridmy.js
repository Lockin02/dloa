var show_page = function(page) {
	$("#stampapplyGrid").yxgrid("reload");
};
$(function() {
	$("#stampapplyGrid").yxgrid({
		model : 'contract_stamp_stampapply',
		action : 'myPageJson',
		title : '我的盖章申请',
		isDelAction : false,
		isOpButton : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'status',
				display : '盖章',
				sortable : true,
				width : 50,
				align : 'center',
				process : function(v,row){
					if(v=="1"){
						return '<img title="已盖章" src="images/icon/ok3.png" style="width:15px;height:15px;">';
					}else if(v=='2'){
						return "已关闭";
					}else{
						return "未盖章";
					}
				}
			}, {
				name : 'applyDate',
				display : '申请日期',
				sortable : true,
            	width : 75
			}, {
				name : 'contractType',
				display : '文件类型',
				sortable : true,
            	width : 70,
            	datacode : 'HTGZYD'
			}, {
				name : 'fileName',
				display : '文件名',
				sortable : true,
                process : function(v){
                    if(v == ""){return "无";}
                    else{return v;}
                }
			},{
                name : 'printDoubleSide',
                display : '是否双面印刷',
                sortable : true,
                process : function(v){
                    if(v == "n"){return "否";}
                    else if(v == "y"){return "是";}
                    else{return "未定义";}
                }
            },{
                name : 'fileNum',
                display : '文件份数',
                sortable : true
            },{
                name : 'filePageNum',
                display : '每份文件页数',
                width : 120,
                sortable : true
            },{
				name : 'signCompanyName',
				display : '文件发往单位',
				sortable : true,
            	width : 130
			}, {
				name : 'contractMoney',
				display : '合同金额',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				hide : true
			}, {
				name : 'applyUserId',
				display : '申请人id',
				sortable : true,
				hide : true
			}, {
				name : 'applyUserName',
				display : '申请人',
				sortable : true,
            	width : 80,
				hide : true
			}, {
				name : 'stampType',
				display : '盖章类型',
				sortable : true
			},{
				name : 'stampCompanyId',
				display : '公司ID',
				sortable : true,
				hide : true
			}, {
				name : 'stampCompany',
				display : '公司名',
				sortable : true
			},{
				name : 'useMatters',
				display : '使用事项',
				sortable : true	
			}, {
				name : 'useMattersId',
				display : '使用事项id',
				sortable : true,
				hide : true
			}, {
				name : 'attn',
				display : '业务经办人',
				sortable : true,
				width : 80
			}, {
				name : 'attnId',
				display : '业务经办人Id',
				sortable : true,
				hide : true
			}, {
				name : 'attnDept',
				display : '业务经办人部门',
				sortable : true,
				hide : true
			}, {
				name : 'attnDeptId',
				display : '业务经办人部门Id',
				sortable : true,
				hide : true
			}, {
				name : 'isNeedAudit',
				display : '是否需要审批',
				sortable : true,
				hide : true
			}, {
				name : 'ExaStatus',
				display : '审批状态',
				sortable : true,
				width : 70
			}, {
				name : 'objCode',
				display : '业务编号',
				width : 120,
				sortable : true,
				hide : true
			}, {
				name : 'batchNo',
				display : '盖章批号',
				sortable : true,
				hide : true
			}, {
				name : 'contractId',
				display : '合同id',
				sortable : true,
				hide : true
			}, {
				name : 'contractCode',
				display : '合同编号',
            	width : 130,
				sortable : true
			}, {
				name : 'contractName',
				display : '合同名称',
				sortable : true,
            	width : 130
			}, {
				name : 'remark',
				display : '备注说明',
				sortable : true
			}
		],
		toAddConfig : {
			action : 'toAdd',
			formHeight : 500,
			formWidth : 900
		},
		toEditConfig : {
			showMenuFn : function(row) {
				if (row.ExaStatus == '待提交'||row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : 'toEdit',
			formHeight : 500,
			formWidth : 900
		},
		toViewConfig : {
			action : 'toView',
			formHeight : 500,
			formWidth : 900
		},
        menusEx : [
        	{
				text : '提交盖章',
				icon : 'add',
				showMenuFn : function(row) {
					if (row.ExaStatus == '待提交' && row.isNeedAudit == 1) {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					//非合同类盖章，判断是否需要审批
					if(row.contractType == "HTGZYD-05" && row.isNeedAudit == 1){
						showThickboxWin('controller/contract/stamp/ewf_index.php?actTo=ewfSelect&billId=' + row.id
								+ '&billDept=' + row.attnDeptId
                                + '&categoryId=' + row.categoryId
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
					}else if(row.contractType == 'HTGZYD-04'){ // 合同盖章需要审批
                        $.ajax({
                            type : "POST",
                            url : "?model=contract_stamp_stampapply&action=contractIsAudited",
                            data : {
                                contractId : row.contractId
                            },
                            success : function(msg) {
                                if (msg == "1") {
                                    showThickboxWin('controller/contract/stamp/ewf_indexcontract.php?actTo=ewfSelect&billId=' + row.id
                                        + '&billDept=' + row.attnDeptId
                                        + '&categoryId=' + row.categoryId + "&flowMoney=10"
                                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
                                } else {
                                    showThickboxWin('controller/contract/stamp/ewf_indexcontract.php?actTo=ewfSelect&billId=' + row.id
                                        + '&billDept=' + row.attnDeptId
                                        + '&categoryId=' + row.categoryId + "&flowMoney=1"
                                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
                                }
                            }
                        });
                    }else{
						 if (window.confirm(("确定提交盖章?"))) {
                            $.ajax({
                                type : "POST",
                                url : "?model=contract_stamp_stampapply&action=ajaxStamp",
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
			},
			{
				text : '提交',
				icon : 'add',
				showMenuFn : function(row) {
					if (row.ExaStatus == '待提交' && row.isNeedAudit == 0) {
						return true;
					}
					return false;
				},
				action : function(row) {
					showThickboxWin('?model=contract_stamp_stampapply&action=toSend'
					+ '&id='+row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
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
							url : "?model=contract_stamp_stampapply&action=ajaxdeletes",
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
				text : '撤销审批',
				icon : 'edit',
				showMenuFn : function(row) {
					if (row.ExaStatus == '部门审批') {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					if (row) {
						if(row.businessBelong == 'bx'){//贝讯的合同需要走合同盖章申请审批流
							var ewfurl = 'controller/contract/stamp/ewf_indexcontract.php?actTo=delWork&billId=';
						}else{
						    var ewfurl = 'controller/contract/stamp/ewf_index.php?actTo=delWork&billId=';
						}

						$.ajax({
							type : "POST",
							url : "?model=common_workflow_workflow&action=isAudited",
							data : {
								billId : row.id,
								examCode : 'oa_sale_stampapply'
							},
							success : function(msg) {
								if (msg == '1') {
									alert('单据已经存在审批信息，不能撤销审批！');
							    	show_page();
									return false;
								}else{
									if(confirm('确定要撤消审批吗？')){
										$.ajax({
										    type: "GET",
										    url: ewfurl,
										    data: {"billId" : row.id },
										    async: false,
										    success: function(data){
										    	alert(data)
										    	show_page();
											}
										});
									}
								}
							}
						});
					} else {
						alert("请选中一条数据");
					}
				}
			}],
		searchitems : [{
			display : "合同编号",
			name : 'contractCodeSer'
		},{
			display : "申请人",
			name : 'applyUserNameSer'
		}],
		// 盖章状态数据过滤
		comboEx : [{
			text: "合同类型",
			key: 'contractType',
			datacode : 'HTGZYD'
		},{
			text: "盖章状态",
			key: 'status',
			value :'0',
			data : [{
				text : '未盖章',
				value : '0'
			}, {
				text : '已盖章',
				value : '1'
			}, {
				text : '已关闭',
				value : '2'
			}]
		}]
	});
});