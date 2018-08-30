var show_page = function(page)
{
    $("#recontractGrid").yxsubgrid("reload");
};
var yearI;
$.ajax(
    {
        type: 'POST',
        url: '?model=hr_recontract_recontract&action=getYears',
        async: false,
        success: function(data)
        {
			yearI=eval(data);
        }
    });
$(function()
{
    // 表头按钮数组
    /*buttonsArr = [{
		text : "导入",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_recontract_recontract&action=toExcel"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")
		}
	}],*/
	
	buttonsArr =[],
	HTDC = {
		name : 'export',
		text : "导出",
		icon : 'excel',
		action : function(row) {
			var searchConditionKey = "";
			var searchConditionVal = "";
			for (var t in $("#recontractGrid").data('yxsubgrid').options.searchParam) {
				if (t != "") {
					searchConditionKey += t;
					searchConditionVal += $("#recontractGrid").data('yxsubgrid').options.searchParam[t];
				}
			}
			var isPaperContract=$('#isPaperContract').val();
			var states=$('#statusId').val();
			var month=$('#month').val();
			var year=$('#year').val();
			var urlExpoler="?model=hr_recontract_recontract&action=exportExcel&statusId="+states
			            +"&isPaperContract="+isPaperContract
						 +"&month="+month
						  +"&year="+year
						+"&"+searchConditionKey+'='+searchConditionVal;
			//alert(urlExpoler);
			//return false;			
			window.open(urlExpoler);
		}
	},$.ajax({
		type : 'POST',
		url : '?model=contract_contract_contract&action=getLimits',
		data : {
			'limitName' : '合同导入权限'
		},
		async : false,
		success : function(data) {
			
				buttonsArr.push(HTDC);
			
		}
	});
    // 表头按钮数组
    excelOutArr1 = 
    {
        name: 'exportIn',
        text: "批量操作",
        icon: 'add',
        items: [
        {
            name: "2",
            text: "提交审批",
            icon: 'add',
            action : function(rowData, rows, rowIds, g) 
            {
        		var ids='';
				for (var i = 0; i < rows.length; i++) 
                {
					if (rows[i].statusId == "2") 
                    {
                        ids=rows[i].id+','+ids;
                    }
                }
                if(ids)
				{
					options(2,ids);
				}
            }
        }, 
        {
            name: "3",
            text: "通知员工确认",
            icon: 'add',
            action: function(rowData, rows, rowIds, g)
            {
                var ids='';
				for (var i = 0; i < rows.length; i++) 
                {
                    if (rows[i].statusId == "4"||rows[i].statusId == "5") 
                    {
                        ids=rows[i].id+','+ids;
                    }
                }
                if(ids)
				{
					options(5,ids);
				}
            }
        }, 
        {
            name: "4",
            text: "签订纸质合同",
            icon: 'add',
            action: function(rowData, rows, rowIds, g)
            {
                var ids='';
				for (var i = 0; i < rows.length; i++) 
                {
                    if (rows[i].statusId == "7") 
                    {
                        ids=rows[i].id+','+ids;
                    }
                }
                if(ids)
				{
					options(7,ids);
				}
            }
        }, 
        {
            name: "5",
            text: "完成",
            icon: 'add',
            action: function(rowData, rows, rowIds, g)
            {
                var ids='';
				for (var i = 0; i < rows.length; i++) 
                {
                    if (rows[i].statusId == "6") 
                    {
                        ids=rows[i].id+','+ids;
                    }
                }
                if(ids)
				{
					options(8,ids);
				}
            }
        }, 
        {
            name: "6",
            text: "关闭",
            icon: 'add',
            action: function(rowData, rows, rowIds, g)
            {
               
                if(rowIds)
				{
					options(9,rowIds);
				}
            }
        }]
    };
	
   buttonsArr.push(excelOutArr1);
   $("#recontractGrid").yxsubgrid(
    {
        model: 'hr_recontract_recontract',
        action: 'pageJsons',
        title: '合同续签信息',
        isAddAction : true,
        isEditAction: false,
        isViewAction: false,
        isDelAction: false,
		event : {
			afterloaddata : function(e, data) {
				if (data) {
					for (var i = 0; i < data.collection.length; i++) {
						if(data.collection[i].different==2){
							$('#row' + data.collection[i].id).css('color', 'red');
						}
					}
				}
			}
		},
        lockCol:['remark'],//锁定的列名
        complexColModel: [[
        {
            name: 'id',
            display: '标红',
            width: '70',
			hide : true,
			rowspan : 2
        },{
			name : 'remark',
			display : '备注',
			sortable : true,
			width : 20,
			rowspan: 2,
			process : function(v, row) {
			  if(v == ''){
			     return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=hr_recontract_recontract&action=listremark&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900\')">'
						+ "<img src='images/icon/icon139.gif' />" + '</a>';
			  }else{
				  return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=hr_recontract_recontract&action=listremark&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900\')">'
						+ "<img src='images/icon/icon095.gif' />" + '</a>';
			  }

			}
		},{
            name: 'userInfo',
            display: '员工基本信息',
            width: '70',
            sortable: true,
			colspan: 5
        }, 
        {
            name: 'statusId',
            display: '状态',
            width: '70',
            sortable: true,
            rowspan: 2,
			process : function(v, row) 
			{
				if(v==1)
				{
				  return '未处理'
				}else if(v==2)
				{
					return '待提交'
				}else if(v==3)
				{
					return '审批中'
				}else if(v==4)
				{
					return '待通知员工'
				}else if(v==5)
				{
					return '待员工确认'
				}else if(v==6)
				{
					return '待HR确认'
				}else if(v==7)
				{
					return '待签订纸质合同'
				}else if(v==8)
				{
					return '合同完成'
				}else if(v==9)
				{
					return '合同关闭'
				}
				  
			}
        },{
            name: 'isPaperContract',
            display: '纸质合同',
            width: '50',
            sortable: true,
            rowspan: 2,
			process : function(v, row) 
			{
				if(v==2)
				{
				  return '已签'
				}else
				{
					return '未签'
				}
				  
			}
        }, 
        {
            name: 'beginDate',
            display: '上次合同签订概况',
            width: '70',
            sortable: true,
            colspan: 5
        }, 
        {
            name: 'aa',
            display: '本次合同签订公司确认结果',
            colspan: 3
        
        }, 
        {
            name: 'aaaaaa',
            display: '本次合同签订员工确认结果',
            colspan: 3
        }, 
        {
            name: 'aaaaaa',
            display: '本次合同签订最终续签结果',
            colspan: 7
        }], [
        {
            name: 'userNo',
            display: '员工编号',
            width: '50',
            sortable: true,
            hide: true
        }, 
        {
            name: 'userName',
            display: '姓名',
            width: '50',
            sortable: true
        }, 
        {
            name: 'companyName',
            display: '公司',
            width: '60',
            sortable: true
        }, 
        {
            name: 'deptName',
            display: '部门',
            width: '80',
            sortable: true
        }, 
        {
            name: 'jobName',
            display: '职位',
            width: '70',
            sortable: true
        }, 
        {
            name: 'comeinDate',
            display: '入职日期',
            width: '70',
            sortable: true
        }, 
        {
            name: 'obeginDate',
            display: '开始时间',
            width: '70',
            sortable: true
        }, 
        {
            name: 'ocloseDate',
            display: '结束时间',
            width: '70',
            sortable: true
        }, 
        {
            name: 'oconNumName',
            display: '用工年限',
            width: '60',
            sortable: true
        }, 
        {
            name: 'oconStateName',
            display: '用工方式',
            width: '60',
            sortable: true
        },{
            name: 'oconNumsName',
            display: '签订次数',
            width: '60',
            sortable: true
        }, 
        {
            name: 'aisFlagName',
            display: '是否同意续签',
            width: '60',
            sortable: true,
			process : function(v, row) 
			{
				if(row.statusId>3)
				{
				  return v
				}else
				{
					return ''
				}
				  
			}
        }, 
        {
            name: 'aconStateName',
            display: '用工方式',
            width: '70',
            sortable: true,
			process : function(v, row) 
			{
				if(row.statusId>3)
				{
				  return v
				}else
				{
					return ''
				}
				  
			}
        }, 
        {
            name: 'aconNumName',
            display: '用工年限',
            width: '70',
            sortable: true,
			process : function(v, row) 
			{
				if(row.statusId>3)
				{
				  return v
				}else
				{
					return ''
				}
				  
			}
        }, 
        {
            name: 'pisFlagName',
            display: '是否同意续签',
            width: '70',
            sortable: true
        }, 
        {
            name: 'pconNumName',
            display: '用工年限',
            width: '70',
            sortable: true
        }, 
        {
            name: 'pconStateName',
            display: '用工方式',
            width: '70',
            sortable: true
        }, 
        {
            name: 'isFlagName',
            display: '是否同意续签',
            width: '70',
            sortable: true
        }, 
        {
            name: 'beginDate',
            display: '开始时间',
            width: '70',
            sortable: true
        }, 
        {
            name: 'closeDate',
            display: '结束时间',
            width: '70',
            sortable: true
        },  
        {
            name: 'conNumName',
            display: '用工年限',
            width: '70',
            sortable: true
        }, 
        {
            name: 'conStateName',
            display: '用工方式',
            width: '70',
            sortable: true
        }, 
        {
            name: 'signCompanyName',
            display: '签订公司',
            width: '200',
            sortable: true
        }, 
        {
            name: 'repaAddress',
            display: '接收地址',
            width: '150',
            sortable: true
        }]],
		//lockCol:['userName','companyName'],//锁定的列名
		buttonsEx:buttonsArr,
		
        // 主从表格设置
        subGridOptions: 
        {
            url: '?model=hr_recontract_recontractApproval&action=pageJson',// 获取从表数据url
            // 传递到后台的参数设置数组
            param: [
            {
                paramId: 'recontractId',// 传递给后台的参数名称
                colId: 'id'// 获取主表行数据的列名称
            }],
            
            // 显示的列
            colModel: [
            {
                name: 'stepName',
                display: '操作步骤'
            }, 
            {
                name: 'createName',
                display: '操作人'
            }, 
            {
                name: 'isFlagName',
                display: '是否续签'
            
            }, 
            {
                name: 'conNumName',
                display: '用工年限'
            }, 
            {
                name: 'conStateName',
                display: '用工方式'
            }, 
            {
                name: 'beginDate',
                display: '合同开始日期'
            
            }, 
            {
                name: 'closeDate',
                display: '合同结束日期'
            
            },{
                name: 'createTime',
                display: '操作日期'
            
            }, 
            {
                name: 'conContent',
                display: '意见'
            }]
        },
        // 扩展右键菜单
        menusEx: [
        {
            text: '添加续签意见',
            icon: 'add',
            showMenuFn: function(row)
            {
                if (row.statusId > 1) 
                {
                    return false;
                }
                return true;
            },
            action: function(row, rows, grid)
            {
                if (row) 
                {
                    showThickboxWin("?model=hr_recontract_recontract&action=viewArbitra&id=" + row.id + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=800');
                    
                }
                else 
                {
                    alert("请选中一条数据");
                }
            }
        }, 
        {
            text: '修改续签意见',
            icon: 'add',
            showMenuFn: function(row)
            {
                if (row.statusId != 2) 
                {
                    return false;
                }
                return true;
            },
            action: function(row, rows, grid)
            {
                if (row) 
                {
                    showThickboxWin("?model=hr_recontract_recontract&action=editArbitra&id=" +
                    row.id +
                    '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=800');
                }
                else 
                {
                    alert("请选中一条数据");
                }
            }
        }, 
        {
            text: '审批情况',
            icon: 'add',
            showMenuFn: function(row)
            {
                if (row.statusId < 3) 
                {
                    return false;
                }
                return true;
            },
            action: function(row, rows, grid)
            {
                if (row) 
                {
                    showThickboxWin("?model=hr_recontract_recontractapproval&action=approvalInfoApp&id=" +
                    row.id +
                    '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
                }
                else 
                {
                    alert("请选中一条数据");
                }
            }
        }, 
        {
            text: '编辑',
            icon: 'add',
            showMenuFn: function(row)
            {
                if (row.statusId < 4 ||row.statusId ==5||row.statusId >6) 
                {
                    return false;
                }
                return true;
            },
            action: function(row, rows, grid)
            {
                if (row) 
                {
                    showThickboxWin("?model=hr_recontract_recontract&action=viewArbitra&id=" + row.id + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
                    
                }
                else 
                {
                    alert("请选中一条数据");
                }
            }
        }, 
        {
            text: '提交审批',
            icon: 'view',
            showMenuFn: function(row)
            {
                if (row.statusId != "2") 
                {
                    return false;
                }
                return true;
            },
            action: function(row, rows, grid)
            {
                if (row) 
                {
                    showThickboxWin("?model=hr_recontract_recontractapproval&action=toApproval&id=" +
                    row.id +
                    '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=800');
                }
                else 
                {
                    alert("请选中一条数据");
                }
            }
        }, 
        {
            text: '通知员工',
            icon: 'view',
            showMenuFn: function(row)
            {
                if (row.statusId !=4) 
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
                        url: '?model=hr_recontract_recontractapproval&action=toInformStaff',
                        data: 
                        {
                            'id': row.id
                        },
                        async: false,
                        success: function(data)
                        {
                            if (data == 1) 
                            {
                                alert('通知员工续签成功');
                            }
                            else 
                            {
                                alert('通知员工续签失败');
                                
                            }
							show_page();
                        }
                    });
                }
                else 
                {
                    alert("请选中一条数据");
                }
            }
        }, 
        {
            text: '完成',
            icon: 'view',
            showMenuFn: function(row)
            {
                if (row.statusId != "6"||row.staffFlag!=2) 
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
                        url: '?model=hr_recontract_recontractapproval&action=InEnd',
                        data: 
                        {
                            'id': row.id
                        },
                        async: false,
                        success: function(data)
                        {
                            if (data == 1) 
                            {
                                alert('提交成功');
                            }
                            else 
                            {
                                alert('提交失败');
                                
                            }
							show_page();
                        }
                    });
                }
                else 
                {
                    alert("请选中一条数据");
                }
            }
        }, 
        {
            text: '签订纸质合同',
            icon: 'view',
            showMenuFn: function(row)
            {
	            if (row.statusId != "7") 
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
                        url: '?model=hr_recontract_recontractapproval&action=InPaperContract',
                        data: 
                        {
                            'id': row.id
                        },
                        async: false,
                        success: function(data)
                        {
                            if (data == 1) 
                            {
                                alert('提交成功');
                            }
                            else 
                            {
                                alert('提交失败');
                                
                            }
							show_page();
                        }
                    });
                }
                else 
                {
                    alert("请选中一条数据");
                }
            }
        }, 
        {
            text: '关闭',
            icon: 'view',
            showMenuFn: function(row)
            {
	            if (row.statusId == "9") 
	                {
                    return false;
                }
                return true;
            },
            action: function(row, rows, grid)
            {
                if (row) 
                {
                    if (confirm("确认要关闭此合同续签？")) 
				   {
						$.ajax(
	                    {
	                        type: 'POST',
	                        url: '?model=hr_recontract_recontractapproval&action=InClose',
	                        data: 
	                        {
	                            'id': row.id
	                        },
	                        async: false,
	                        success: function(data)
	                        {
	                            if (data == 1) 
	                            {
	                                alert('关闭成功');
	                            }
	                            else 
	                            {
	                                alert('关闭失败');
	                                
	                            }
								show_page();
	                        }
	                    });
						 }
	                else 
	                {
	                    return false;
	                }
                }
                else 
                {
                    alert("请选中一条数据");
                }
            }
        }],
        /**
         * 快速搜索
         */
        searchitems: [
        {
            display: '员工姓名',
            name: 'userName'
        }, 
        {
            display: '员工编号',
            name: 'userNo'
        }, 
        {
            display: '公司',
            name: 'companyName'
        }, 
        {
            display: '部门',
            name: 'deptName'
        }, 
        {
            display: '职位',
            name: 'jobName'
        }],
        // 审批状态数据过滤
        comboEx: [
		{
            text: '年份',
            key: 'year',
            data: yearI
		},
		{
            text: '月份',
            key: 'month',
            data: [
			{
                text: '1月份',
                value: '01'
            },{
                text: '2月份',
                value: '02'
            },{
                text: '3月份',
                value: '03'
            },{
                text: '4月份',
                value: '04'
            },{
                text: '5月份',
                value: '05'
            },{
                text: '6月份',
                value: '06'
            },{
                text: '7月份',
                value: '07'
            },{
                text: '8月份',
                value: '08'
            },{
                text: '9月份',
                value: '09'
            },{
                text: '10月份',
                value: '10'
            },{
                text: '11月份',
                value: '11'
            },{
                text: '12月份',
                value: '12'
            }
			]
        }, 
        {
            text: '纸质合同状态',
            key: 'isPaperContract',
            data: [
            {
                text: '已签纸质合同',
                value: '2'
            }, 
            {
                text: '未签纸质合同',
                value: '1'
			}]
        },  {
            text: '执行状态',
            key: 'statusId',
            data: [
            {
                text: '未处理',
                value: '1'
            }, 
            {
                text: '待提交',
                value: '2'
            }, 
            {
                text: '审批中',
                value: '3'
            }
			/*, 
            {
                text: '待通知员工',
                value: '4'
            }, 
            {
                text: '待员工确认',
                value: '5'
            }*/, 
            {
                text: '待HR确认',
                value: '6'
            }, 
            {
                text: '待订签纸质合同',
                value: '7'
            }, 
            {
                text: '完成',
                value: '8'
            }, 
            {
                text: '关闭',
                value: '9'
            }]
        }]
    
    });
	//$('.sDiv2').append("<input id='recordDate' type='text' class='txt' onfocus='WdatePicker()' name='recontract[recordDate]'  />");
	
});


function  options(status,ids)
{
	$.ajax(
    {
        type: 'POST',
        url: '?model=hr_recontract_recontractapproval&action=options',
        data: 
        {
            'status': status,
			'ids': ids
        },
        async: false,
        success: function(data)
        {
             alert('操作成功！');
			 show_page();
        }
    });
	
}





