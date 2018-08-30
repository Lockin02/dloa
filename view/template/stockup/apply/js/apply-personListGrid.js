var show_page = function(page) {
	$("#personListGrid").yxsubgrid("reload");
};
$(function() {
	$("#personListGrid").yxsubgrid({
				model : 'stockup_apply_apply',
               	title : '���������',
				action : 'personListJson',
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
						width : 120
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
      menusEx: [{
            text: '�༭',
            icon: 'edit',
            showMenuFn: function(row)
            {
                if (row.ExaStatus=='��������'||row.ExaStatus=='���')
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
                    alert("��ѡ��һ������");
                }
            }
        },{
            name: 'status',
			text: '�ύ����',
			icon: 'add',
			showMenuFn: function(row) {
				if (row.ExaStatus == "���" || row.ExaStatus == "��������") {
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
            text: 'ɾ��',
            icon: 'delete',
            showMenuFn: function(row)
            {
               if (row.ExaStatus=='��������'||row.ExaStatus=='���')
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
                                alert('ɾ���ɹ�');
								show_page();
                            }
                            else
                            {
                                alert('ɾ��ʧ��');

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
			name: 'aduit',
			text: '�������',
			icon: 'view',
			showMenuFn: function(row) {
				if (row.ExaStatus != ""&&row.ExaStatus != "���ύ") {
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
				}],
		// ����״̬���ݹ���
        comboEx: [
        {
            text: '����״̬',
            key: 'ExaStatus',
            data: [
            {
                text: '���',
                value: '���'
            },{
                text: '��������',
                value: '��������'
            },
            {
                text: '���',
                value: '���'
            },
            {
                text: '���ύ',
                value: '���ύ'
            }]
        },{
            text: '�������״̬',
            key: 'status',
            data: [
            {
                text: '�����',
                value: '3'
            },{
                text: '���������',
                value: '4'
            },
            {
                text: '���',
                value: '5'
            },
            {
                text: 'δ���',
                value: '1'
            }]
        }]
 		});
 });