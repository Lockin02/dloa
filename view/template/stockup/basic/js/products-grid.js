var show_page = function(page) {	   
$("#productsGrid").yxgrid("reload");
};
$(function() {			
$("#productsGrid").yxgrid({				      
				model : 'stockup_basic_products',
               	title : '备货产品信息',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'productName',
										display : '产品名称',
										sortable : true
								  },{
											name : 'productCode',
										display : '产品编码',
										sortable : true
								  },{
											name : 'createTime',
										display : '创建时间',
										sortable : true,
										width : 120
								  },{
											name : 'isClose',
										display : '是否关闭',
										sortable : true,
										process : function(v, row) 
										{
											if(row.isClose==1)
											{
												return '开启'
											}else
											{
												return '关闭'
											}
											  
										}
								  },{
											name : 'remark',
										display : '备注',
										sortable : true
								  }],
		
	buttonsEx : [{
			text : '导入产品',
			icon : 'excel',
			action : function(row, rows, grid) {
				showThickboxWin("?model=stockup_basic_products&action=toEportExcelIn"
			          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
		}/*,{
			text : '导出日志',
			icon : 'excel',
			action : function(row, rows, grid) {
				showThickboxWin("?model=engineering_worklog_esmworklog&action=toExportMyLog"
			          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
		}*/],
			menusEx: [{
            text: '关闭',
            icon: 'delete',
            showMenuFn: function(row)
            {
                if (row.isClose==2) 
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
                        url: '?model=stockup_basic_products&action=updateStatus',
                        data: 
                        {
                            'id': row.id,'flag':2
                        },
                        async: false,
                        success: function(data)
                        {
                            if (data == 1) 
                            {
                                alert('关闭成功');
								show_page();
                            }
                            else 
                            {
                                alert('关闭失败');
                                
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
            text: '开启',
            icon: 'delete',
            showMenuFn: function(row)
            {
                if (row.isClose==1) 
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
                        url: '?model=stockup_basic_products&action=updateStatus',
                        data: 
                        {
                            'id': row.id,'flag':1
                        },
                        async: false,
                        success: function(data)
                        {
                            if (data == 1) 
                            {
                                alert('开启成功');
								show_page();
                            }
                            else 
                            {
                                alert('开启失败');
                                
                            }
                        }
                    });
                }
                else 
                {
                    alert("请选中一条数据");
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
						display : "产品名称",
						name : 'productName'
					},{
						display : "产品编码",
						name : 'productCode'
					}]
 		});
 });