var show_page = function(page) {
	$("#applicationGrid").yxsubgrid("reload");
};

$(function() {
		$("#applicationGrid").yxsubgrid({
				model : 'stockup_application_application',
               	title : '��Ʒ��������',
				action : 'personListJson',
				isAddAction:false,
				isEditAction:false,
               	isDelAction:false,
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
										width:140
								  },{
                    					name : 'createName',
										display : '��������',
										sortable : true
								  },{
                    					name : 'batchNum',
										display : '���κ�',
										sortable : true
								  },/*{
                    					name : 'stockNum',
										display : '�������',
										sortable : true
								  },{
                    					name : 'needsNum',
										display : '�ۼ���������',
										sortable : true
								  },*/{
                    					name : 'createTime',
										display : '����ʱ��',
										sortable : true
								  }/*,{
                    					name : 'stockupNum',
										display : '���뱸������',
										sortable : true
								  },{
                    					name : 'expectAmount',
										display : 'Ԥ�Ʒ������',
										sortable : true
								  }*/,{
                    					name : 'ExaStatus',
										display : '����״̬',
										sortable : true,
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
								  }/*,{
                    					name : 'status',
										display : '��״̬',
										sortable : true
								  },{
                    					name : 'remark',
										display : '��ע',
										sortable : true
								  }*/],
		// ���ӱ������
		subGridOptions : {
			url : '?model=stockup_application_applicationMatter&action=pageItemJson',
			param : [{
						paramId : 'appId',
						colId : 'id'
					}],
			colModel : [{
						display : '��Ʒ����',
						name : 'productName',
						type : 'txt',
						width : 120
						},{
						display : '��Ʒ����',
						name : 'productCode',
						type : 'txt',
						width : 120
						},{
							display : '��������',
							name : 'stockupNum',
							type : 'txt',
							width : 80
						},{
							display : 'Ԥ�Ʒ������',
							name : 'expectAmount',
							type : 'txt',
							width : 80,
							process : function(v) {
								return moneyFormat2(v);
							}
						},{
							display : '�ۼ�������',
							name : 'stockNum',
							type : 'txt',
							width : 80
						},{
							display : '�������',
							name : 'needsNum',
							type : 'txt',
							width : 80
						}]
		},
		//��ͷ��ť
	buttonsEx : [{
			name : 'searchExport',
			text : "����",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=stockup_application_application&action=toExport"
			          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800")
				}
			}],
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
                    showThickboxWin("?model=stockup_application_application&action=toEdit&id=" +
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
			icon: 'view',
			showMenuFn: function(row) {
				if (row.ExaStatus == "���" || row.ExaStatus == "��������") {
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
					display : "��������",
					name : 'createName'
				},{
					display : "���κ�",
					name : 'batchNum'
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
        }]
 		});
 });