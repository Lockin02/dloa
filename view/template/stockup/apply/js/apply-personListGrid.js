var show_page = function(page) {
	$("#personListGrid").yxsubgrid("reload");
};
$(function() {
	$("#personListGrid").yxsubgrid({
				model : 'stockup_apply_apply',
               	title : '备货申请表',
				action : 'personListJson',
               	isEditAction:false,
               	isDelAction:false,
		        showcheckbox:false,
				bodyAlign:'center',
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
										width : 120
								  },{
										name : 'projectName',
										display : '项目名称',
										sortable : true
								  },{
										name : 'chanceCode',
										display : '商机编号',
										sortable : true,
										width : 120
								  },{
										name : 'chanceName',
										display : '商机名称',
										sortable : true,
										width : 120
								  },{
											name : 'appDate',
										display : '申请时间',
										sortable : true,
										width : 70
								  },{
											name : 'status',
											display : '交付审核状态',
											sortable : true,
											width : 80,
											process : function(v, row)
											{
												if(row.status==1||row.status==2)
												{
													return '未审核'
												}else if(row.status==3)
												{
													return '审核中'
												}else if(row.status==4)
												{
													return '部分已审核'
												}else if(row.status==5)
												{
													return '完成'
												}

											}
								  },{
											name : 'ExaStatus',
											display : '审批状态',
											sortable : true,
											width : 60,
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
								  },{
											name : 'description',
										display : '说明',
										sortable : true,
										align:'left',
										width : 220
								  }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=stockup_apply_applyProducts&action=pageItemJson',
			param : [{
						paramId : 'appId',
						colId : 'id'
					}],
			colModel : [{
						display : '物料名称（产品）',
						name : 'productName',
						type : 'txt',
						width : 120
						},{
							display : '数量',
							name : 'productNum',
							type : 'txt',
							width : 50
						},{
							display : '产品配置',
							name : 'productConfig',
							type : 'txt',
							width : 250
						},{
							display : '期望交付日期',
							name : 'exDeliveryDate',
							type : 'date',
							width : 80
						},{
							display : '备注',
							name : 'remark',
							type : 'txt',
							width : 140
						},{
								name : 'isClose',
								display : '是否关闭',
								sortable : true,
								process : function(v, row)
								{
									if(row.isClose==1)
									{
										return '否'
									}else
									{
										return '是'
									}

								}
					  }]
		},
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
                    showThickboxWin("?model=stockup_apply_apply&action=toEdit&id=" +
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
			icon: 'add',
			showMenuFn: function(row) {
				if (row.ExaStatus == "完成" || row.ExaStatus == "部门审批") {
					return false;
				} else {
					return true;
				}
			},
			action: function(row) {
				if(row.productNum > 100){
					showThickboxWin('controller/stockup/apply/ewf_index.php?actTo=ewfSelect&billId=' + row.id + "&flowMoney=6&billDept="+ row.appDeptId+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				}else{
					showThickboxWin('controller/stockup/apply/ewf_index.php?actTo=ewfSelect&billId=' + row.id + "&flowMoney=3&billDept="+ row.appDeptId+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
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
                        url: '?model=stockup_apply_apply&action=delete',
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
					showThickboxWin("controller/common/readview.php?itemtype=oa_stockup_apply&pid=" + row.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600");
				}
			}
		}],
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "项目名称",
					name : 'projectName'
				},{
					display : "申请编号",
					name : 'listNo'
				},{
					display : "商机编号",
					name : 'chanceCode'
				},{
					display : "商机名称",
					name : 'chanceName'
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
        },{
            text: '交付审核状态',
            key: 'status',
            data: [
            {
                text: '审核中',
                value: '3'
            },{
                text: '部分已审核',
                value: '4'
            },
            {
                text: '完成',
                value: '5'
            },
            {
                text: '未审核',
                value: '1'
            }]
        }]
 		});
 });