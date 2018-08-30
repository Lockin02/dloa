var show_page = function(page) {
	$("#applicationGrid").yxsubgrid("reload");
};

$(function() {
		$("#applicationGrid").yxsubgrid({
				model : 'stockup_application_application',
               	title : '产品备货申请',
				action : 'personListJson',
				isAddAction:false,
				isEditAction:false,
               	isDelAction:false,
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'listNo',
										display : '申请编号',
										sortable : true,
										width:140
								  },{
                    					name : 'createName',
										display : '申请人名',
										sortable : true
								  },{
                    					name : 'batchNum',
										display : '批次号',
										sortable : true
								  },/*{
                    					name : 'stockNum',
										display : '库存数量',
										sortable : true
								  },{
                    					name : 'needsNum',
										display : '累计需求数量',
										sortable : true
								  },*/{
                    					name : 'createTime',
										display : '申请时间',
										sortable : true
								  }/*,{
                    					name : 'stockupNum',
										display : '申请备货数量',
										sortable : true
								  },{
                    					name : 'expectAmount',
										display : '预计发生金额',
										sortable : true
								  }*/,{
                    					name : 'ExaStatus',
										display : '审批状态',
										sortable : true,
										process : function(v, row)
											{
												if(row.ExaStatus=='打回')
												{
													return '打回'
												}else if(row.ExaStatus=='部门审批')
												{
													return '部门审批'
												}else if(row.ExaStatus=='完成')
												{
													return '完成'
												}else
												{
													return '待提交'
												}

											}
								  }/*,{
                    					name : 'status',
										display : '表单状态',
										sortable : true
								  },{
                    					name : 'remark',
										display : '备注',
										sortable : true
								  }*/],
		// 主从表格设置
		subGridOptions : {
			url : '?model=stockup_application_applicationMatter&action=pageItemJson',
			param : [{
						paramId : 'appId',
						colId : 'id'
					}],
			colModel : [{
						display : '产品名称',
						name : 'productName',
						type : 'txt',
						width : 120
						},{
						display : '产品编码',
						name : 'productCode',
						type : 'txt',
						width : 120
						},{
							display : '申请数量',
							name : 'stockupNum',
							type : 'txt',
							width : 80
						},{
							display : '预计发生金额',
							name : 'expectAmount',
							type : 'txt',
							width : 80,
							process : function(v) {
								return moneyFormat2(v);
							}
						},{
							display : '累计需求数',
							name : 'stockNum',
							type : 'txt',
							width : 80
						},{
							display : '库存数量',
							name : 'needsNum',
							type : 'txt',
							width : 80
						}]
		},
		//表头按钮
	buttonsEx : [{
			name : 'searchExport',
			text : "导出",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=stockup_application_application&action=toExport"
			          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800")
				}
			}],
		menusEx: [{
            text: '编辑',
            icon: 'edit',
            showMenuFn: function(row)
            {
                if (row.ExaStatus=='部门审批'||row.ExaStatus=='完成')
                {
                    return false;
                }
                return true;
            },
            action: function(row, rows, grid)
            {
                if (row)
                {
                    showThickboxWin("?model=stockup_application_application&action=toEdit&id=" +
                    row.id +
                    '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=800');

                }
                else
                {
                    alert("请选中一条数据");
                }
            }
        },{
            name: 'status',
			text: '提交审批',
			icon: 'view',
			showMenuFn: function(row) {
				if (row.ExaStatus == "完成" || row.ExaStatus == "部门审批") {
					return false;
				} else {
					return true;
				}
			},
			action: function(row) {
				if(row.productNum > 100){
					showThickboxWin('controller/stockup/application/ewf_index.php?isto=2&actTo=ewfSelect&billId=' + row.id + "&flowMoney=6&billDept="+ row.appDeptId+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				}else{
					showThickboxWin('controller/stockup/application/ewf_index.php?isto=2&actTo=ewfSelect&billId=' + row.id + "&flowMoney=3&billDept="+ row.appDeptId+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				}
			}
        },{
            text: '删除',
            icon: 'delete',
            showMenuFn: function(row)
            {
               if (row.ExaStatus=='部门审批'||row.ExaStatus=='完成')
                {
                    return false;
                }
                return true;
            },
            action: function(row, rows, grid)
            {
                if (row)
                {
                    $.ajax(
                    {
                        type: 'POST',
                        url: '?model=stockup_application_application&action=delete',
                        data:
                        {
                            'id': row.id
                        },
                        async: false,
                        success: function(data)
                        {
                            if (data == 1)
                            {
                                alert('删除成功');
								show_page();
                            }
                            else
                            {
                                alert('删除失败');

                            }
                        }
                    });


                }
                else
                {
                    alert("请选中一条数据");
                }
            }
        },{
			name: 'aduit',
			text: '审批情况',
			icon: 'view',
			showMenuFn: function(row) {
				if (row.ExaStatus != ""&&row.ExaStatus != "待提交") {
					return true;
				}
				return false;
			},
			action: function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_stockup_application&pid=" + row.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600");
				}
			}
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "申请人名",
					name : 'createName'
				},{
					display : "批次号",
					name : 'batchNum'
				}],
		// 审批状态数据过滤
        comboEx: [
        {
            text: '审批状态',
            key: 'ExaStatus',
            data: [
            {
                text: '打回',
                value: '打回'
            },{
                text: '部门审批',
                value: '部门审批'
            },
            {
                text: '完成',
                value: '完成'
            },
            {
                text: '待提交',
                value: '待提交'
            }]
        }]
 		});
 });