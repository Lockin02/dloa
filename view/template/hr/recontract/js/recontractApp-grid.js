var show_page = function(page) {
	$("#recontractGrid").yxgrid("reload");
};
//表头按钮数组
buttonsArr = [];

// 表头按钮数组
excelOutArr1 = {
	name : 'exportIn',
	text : "批量操作",
	icon : 'edit',
	items:[{
            name: "2",
            text: "同意续签",
            icon: 'add',
            action : function(rowData, rows, rowIds, g) 
            {
				var ids='';
				if(rowIds)
				{
				 for (var i = 0; i < rows.length; i++) 
                {
					if (rows[i].statusId == "3") 
                    {
                        ids+='{"pid":'+rows[i].pid+',"id":'+rows[i].id+'},';
                    }
                }
                if(ids)
				{
					options(1,ids);
				}	
				}else
				{
					alert('请选择一条数据！');
				}
				
				
            }
        }, 
        {
            name: "3",
            text: "不同意续签",
            icon: 'add',
            action: function(rowData, rows, rowIds, g)
            {
                var ids='';
				if(rowIds)
				{
				 for (var i = 0; i < rows.length; i++) 
                {
					if (rows[i].statusId == "3") 
                    {
                        ids+='{"pid":'+rows[i].pid+',"id":'+rows[i].id+'},';
                    }
                }
                if(ids)
				{
					options(2,ids);
				}	
				}else
				{
					alert('请选择一条数据！');
				}
				
            }
        },{
            name: "4",
            text: "同意上一审批人意见",
            icon: 'add',
            action : function(rowData, rows, rowIds, g) 
            {
				var ids='';
				if(rowIds)
				{
				 for (var i = 0; i < rows.length; i++) 
                {
					if (rows[i].statusId == "3") 
                    {
                        ids+='{"pid":'+rows[i].pid+',"id":'+rows[i].id+'},';
                    }
                }
                if(ids)
				{
					options(3,ids);
				}	
				}else
				{
					alert('请选择一条数据！');
				}
				
				
            }
        }]
}
;
buttonsArr.push(excelOutArr1);

$(function() {

	$("#recontractGrid")
			.yxgrid(
					{
						model : 'hr_recontract_recontractapproval',
						action:'pageJsonAppList',
						title : '续签审批',
						isAddAction : false,
						isEditAction : false,
						isViewAction : false,
						isDelAction : false,
						// 列信息
						colModel : [
								{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								},
								{
									name : 'userNo',
									display : '员工编号',
									width:'50',
									sortable : true,
									hide : true
								},
								{
									name : 'userName',
									display : '姓名',
									width:'60',
									sortable : true
								},
								{
									name : 'companyName',
									display : '公司',
									width:'80',
									sortable : true
								},
								{
									name : 'deptName',
									display : '部门',
									width:'100',
									sortable : true
								},
								{
									name : 'jobName',
									display : '职位',
									width:'80',
									sortable : true
								},{
									name : 'Flag',
									display : '审批状态',
									width:'80',
									sortable : true
								},
								{
									name : 'comeinDate',
									display : '入职日期',
									width:'70',
									sortable : true
								},{
									name : 'obeginDate',
									display : '上次合同开始时间',
									width:'85',
									sortable : true
								}, {
									name : 'ocloseDate',
									display : '上次合同结束时间',
									width:'85',
									sortable : true
								}, {
									name : 'oconNumName',
									display : '上次合同用工年限',
									width:'85',
									sortable : true
								}, {
									name : 'oconStateName',
									display : '上次合同用工方式',
									width:'85',
									sortable : true
								},{
									name : 'beginDate',
									display : '本次合同开始时间',
									width:'85',
									sortable : true
								}, {
									name : 'closeDate',
									display : '本次合同结束时间',
									width:'85',
									sortable : true
								}, {
									name : 'conNumName',
									display : '本次合同用工年限',
									width:'85',
									sortable : true
								}, {
									name : 'conStateName',
									display : '本次合同用工方式',
									width:'85',
									sortable : true
								}, {
									name : 'conContent',
									display : '续签建议',
									sortable : true
								}],
						buttonsEx : buttonsArr,
						
						// 扩展右键菜单
						menusEx : [{
							text : '审批',
							icon : 'add',
							showMenuFn : function(row) {
								if (row.Flag =='已审批'&&row.Result =='1') {
									return false;
								}
								return true;
							},
							action : function(row, rows, grid) {
								if (row) {
									showThickboxWin("?model=hr_recontract_recontract&action=toApproval&id=" + row.pid + '&fspid=' + row.id + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=700&width=800');
								} else {
									alert("请选中一条数据");
								}
							}
						},{
							text : '查看明细',
							icon : 'view',
							showMenuFn : function(row) {
								if (row.id == "noId") {
									return false;
								}
								return true;
							},
							action : function(row, rows, grid) {
								if (row) 
								{
									showThickboxWin("?model=hr_recontract_recontract&action=viewApproval&id=" + row.pid + '&fspid=' + row.id + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=700&width=800');
								} else {
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
						comboEx : [  {
							text : '审批状态',
							key : 'ExaStatus',
							value : '2',
							data : [ {
								text : '未审批',
								value : '2'
							}, {
								text : '已审批',
								value : '1'
							} ]
						} ]

					});
});


function  options(status,ids)
{
	$.ajax(
    {
        type: 'POST',
        url: '?model=hr_recontract_recontractapproval&action=batchApproval',
        data: 
        {
            'status': status,
			'ids': ids
        },
        async: false,
        success: function(data)
        {
           if(data==1)
		   {
		   	 alert('数据错误！');
		   }else
		   {

		   	var jsonobj=eval('('+data+')');
		    alert('有'+jsonobj.sc+'已审批成功！,有'+jsonobj.fc+'审批失败！');
		   }
           show_page();
        }
		
    });
	
}