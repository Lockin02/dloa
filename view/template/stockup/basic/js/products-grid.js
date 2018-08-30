var show_page = function(page) {	   
$("#productsGrid").yxgrid("reload");
};
$(function() {			
$("#productsGrid").yxgrid({				      
				model : 'stockup_basic_products',
               	title : '������Ʒ��Ϣ',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'productName',
										display : '��Ʒ����',
										sortable : true
								  },{
											name : 'productCode',
										display : '��Ʒ����',
										sortable : true
								  },{
											name : 'createTime',
										display : '����ʱ��',
										sortable : true,
										width : 120
								  },{
											name : 'isClose',
										display : '�Ƿ�ر�',
										sortable : true,
										process : function(v, row) 
										{
											if(row.isClose==1)
											{
												return '����'
											}else
											{
												return '�ر�'
											}
											  
										}
								  },{
											name : 'remark',
										display : '��ע',
										sortable : true
								  }],
		
	buttonsEx : [{
			text : '�����Ʒ',
			icon : 'excel',
			action : function(row, rows, grid) {
				showThickboxWin("?model=stockup_basic_products&action=toEportExcelIn"
			          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
		}/*,{
			text : '������־',
			icon : 'excel',
			action : function(row, rows, grid) {
				showThickboxWin("?model=engineering_worklog_esmworklog&action=toExportMyLog"
			          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
		}*/],
			menusEx: [{
            text: '�ر�',
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
                                alert('�رճɹ�');
								show_page();
                            }
                            else 
                            {
                                alert('�ر�ʧ��');
                                
                            }
                        }
                    });

                    
                }
                else 
                {
                    alert("��ѡ��һ������");
                }
            }
        },{
            text: '����',
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
                                alert('�����ɹ�');
								show_page();
                            }
                            else 
                            {
                                alert('����ʧ��');
                                
                            }
                        }
                    });
                }
                else 
                {
                    alert("��ѡ��һ������");
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
						display : "��Ʒ����",
						name : 'productName'
					},{
						display : "��Ʒ����",
						name : 'productCode'
					}]
 		});
 });