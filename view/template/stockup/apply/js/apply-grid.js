var show_page = function(page) {
	$("#applyGrid").yxsubgrid("reload");
};
$(function() {
	$("#applyGrid").yxsubgrid({
				model : 'stockup_apply_apply',
               	title : '���������',
				isAddAction:false,
               	isEditAction:false,
               	isDelAction:false,
		        showcheckbox:false,
				bodyAlign:'center',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'listNo',
										display : '������',
										sortable : true,
										width : 120
								  },{
										name : 'projectName',
										display : '��Ŀ����',
										sortable : true
								  },{
										name : 'chanceCode',
										display : '�̻����',
										sortable : true,
										width : 120
								  },{
										name : 'chanceName',
										display : '�̻�����',
										sortable : true,
										width : 120
								  },{
										name : 'appUserName',
										display : '������',
										sortable : true,
										width : 70
								  },{
											name : 'appDate',
										display : '����ʱ��',
										sortable : true,
										width : 70
								  },{
											name : 'status',
											display : '�������״̬',
											sortable : true,
											width : 80,
											process : function(v, row)
											{
												if(row.status==1||row.status==2)
												{
													return 'δ���'
												}else if(row.status==3)
												{
													return '�����'
												}else if(row.status==4)
												{
													return '���������'
												}else if(row.status==5)
												{
													return '���'
												}

											}
								  },{
											name : 'ExaStatus',
											display : '����״̬',
											sortable : true,
											width : 60,
											process : function(v, row)
											{
												if(row.ExaStatus=='���')
												{
													return '���'
												}else if(row.ExaStatus=='��������')
												{
													return '��������'
												}else if(row.ExaStatus=='���')
												{
													return '���'
												}else
												{
													return '���ύ'
												}

											}
								  },{
											name : 'description',
										display : '˵��',
										sortable : true,
										align:'left',
										width : 220
								  }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=stockup_apply_applyProducts&action=pageItemJson',
			param : [{
						paramId : 'appId',
						colId : 'id'
					}],
			colModel : [{
						display : '�������ƣ���Ʒ��',
						name : 'productName',
						type : 'txt',
						width : 120,
						},{
							display : '����',
							name : 'productNum',
							type : 'txt',
							width : 50
						},{
							display : '��Ʒ����',
							name : 'productConfig',
							type : 'txt',
							width : 250
						},{
							display : '������������',
							name : 'exDeliveryDate',
							type : 'date',
							width : 80
						},{
							display : '��ע',
							name : 'remark',
							type : 'txt',
							width : 140
						},{
								name : 'isClose',
								display : '�Ƿ�ر�',
								sortable : true,
								process : function(v, row)
								{
									if(row.isClose==1)
									{
										return '��'
									}else
									{
										return '��'
									}

								}
					  }]
		},
		/*
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
                        url: '?model=stockup_apply_apply&action=updateStatus',
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
                        url: '?model=stockup_apply_apply&action=updateStatus',
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
        }],*/
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "��Ŀ����",
					name : 'projectName'
				},{
					display : "������",
					name : 'listNo'
				},{
					display : "�̻����",
					name : 'chanceCode'
				},{
					display : "�̻�����",
					name : 'chanceName'
				},{
					display : "������",
					name : 'appUserName'
				}]
 		});
 });