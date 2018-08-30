var show_page = function(page) {
	$("#stampapplyGrid").yxgrid("reload");
};
$(function() {
	$("#stampapplyGrid").yxgrid({
		model : 'contract_stamp_stampapply',
		action : 'myPageJson',
		title : '�ҵĸ�������',
		isDelAction : false,
		isOpButton : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'status',
				display : '����',
				sortable : true,
				width : 50,
				align : 'center',
				process : function(v,row){
					if(v=="1"){
						return '<img title="�Ѹ���" src="images/icon/ok3.png" style="width:15px;height:15px;">';
					}else if(v=='2'){
						return "�ѹر�";
					}else{
						return "δ����";
					}
				}
			}, {
				name : 'applyDate',
				display : '��������',
				sortable : true,
            	width : 75
			}, {
				name : 'contractType',
				display : '�ļ�����',
				sortable : true,
            	width : 70,
            	datacode : 'HTGZYD'
			}, {
				name : 'fileName',
				display : '�ļ���',
				sortable : true,
                process : function(v){
                    if(v == ""){return "��";}
                    else{return v;}
                }
			},{
                name : 'printDoubleSide',
                display : '�Ƿ�˫��ӡˢ',
                sortable : true,
                process : function(v){
                    if(v == "n"){return "��";}
                    else if(v == "y"){return "��";}
                    else{return "δ����";}
                }
            },{
                name : 'fileNum',
                display : '�ļ�����',
                sortable : true
            },{
                name : 'filePageNum',
                display : 'ÿ���ļ�ҳ��',
                width : 120,
                sortable : true
            },{
				name : 'signCompanyName',
				display : '�ļ�������λ',
				sortable : true,
            	width : 130
			}, {
				name : 'contractMoney',
				display : '��ͬ���',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				hide : true
			}, {
				name : 'applyUserId',
				display : '������id',
				sortable : true,
				hide : true
			}, {
				name : 'applyUserName',
				display : '������',
				sortable : true,
            	width : 80,
				hide : true
			}, {
				name : 'stampType',
				display : '��������',
				sortable : true
			},{
				name : 'stampCompanyId',
				display : '��˾ID',
				sortable : true,
				hide : true
			}, {
				name : 'stampCompany',
				display : '��˾��',
				sortable : true
			},{
				name : 'useMatters',
				display : 'ʹ������',
				sortable : true	
			}, {
				name : 'useMattersId',
				display : 'ʹ������id',
				sortable : true,
				hide : true
			}, {
				name : 'attn',
				display : 'ҵ�񾭰���',
				sortable : true,
				width : 80
			}, {
				name : 'attnId',
				display : 'ҵ�񾭰���Id',
				sortable : true,
				hide : true
			}, {
				name : 'attnDept',
				display : 'ҵ�񾭰��˲���',
				sortable : true,
				hide : true
			}, {
				name : 'attnDeptId',
				display : 'ҵ�񾭰��˲���Id',
				sortable : true,
				hide : true
			}, {
				name : 'isNeedAudit',
				display : '�Ƿ���Ҫ����',
				sortable : true,
				hide : true
			}, {
				name : 'ExaStatus',
				display : '����״̬',
				sortable : true,
				width : 70
			}, {
				name : 'objCode',
				display : 'ҵ����',
				width : 120,
				sortable : true,
				hide : true
			}, {
				name : 'batchNo',
				display : '��������',
				sortable : true,
				hide : true
			}, {
				name : 'contractId',
				display : '��ͬid',
				sortable : true,
				hide : true
			}, {
				name : 'contractCode',
				display : '��ͬ���',
            	width : 130,
				sortable : true
			}, {
				name : 'contractName',
				display : '��ͬ����',
				sortable : true,
            	width : 130
			}, {
				name : 'remark',
				display : '��ע˵��',
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
				if (row.ExaStatus == '���ύ'||row.ExaStatus == '���') {
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
				text : '�ύ����',
				icon : 'add',
				showMenuFn : function(row) {
					if (row.ExaStatus == '���ύ' && row.isNeedAudit == 1) {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					//�Ǻ�ͬ����£��ж��Ƿ���Ҫ����
					if(row.contractType == "HTGZYD-05" && row.isNeedAudit == 1){
						showThickboxWin('controller/contract/stamp/ewf_index.php?actTo=ewfSelect&billId=' + row.id
								+ '&billDept=' + row.attnDeptId
                                + '&categoryId=' + row.categoryId
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
					}else if(row.contractType == 'HTGZYD-04'){ // ��ͬ������Ҫ����
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
						 if (window.confirm(("ȷ���ύ����?"))) {
                            $.ajax({
                                type : "POST",
                                url : "?model=contract_stamp_stampapply&action=ajaxStamp",
                                data : {
                                    id : row.id
                                },
                                success : function(msg) {
                                    if (msg == 1) {
                                        alert('�ύ�ɹ���');
                                        show_page();
                                    }else{
                                        alert('�ύʧ�ܣ�');
                                    }
                                }
                            });
						}
					}
				}
			},
			{
				text : '�ύ',
				icon : 'add',
				showMenuFn : function(row) {
					if (row.ExaStatus == '���ύ' && row.isNeedAudit == 0) {
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
				text : 'ɾ��',
				icon : 'delete',
				showMenuFn : function(row) {
					if (row.ExaStatus == '���ύ') {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
			        if (window.confirm(("ȷ��Ҫɾ��?"))) {
						$.ajax({
							type : "POST",
							url : "?model=contract_stamp_stampapply&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('ɾ���ɹ���');
									show_page();
								}else{
									alert('ɾ��ʧ�ܣ�');
								}
							}
						});
					}
				}
			},
			{
				text : '��������',
				icon : 'edit',
				showMenuFn : function(row) {
					if (row.ExaStatus == '��������') {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					if (row) {
						if(row.businessBelong == 'bx'){//��Ѷ�ĺ�ͬ��Ҫ�ߺ�ͬ��������������
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
									alert('�����Ѿ�����������Ϣ�����ܳ���������');
							    	show_page();
									return false;
								}else{
									if(confirm('ȷ��Ҫ����������')){
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
						alert("��ѡ��һ������");
					}
				}
			}],
		searchitems : [{
			display : "��ͬ���",
			name : 'contractCodeSer'
		},{
			display : "������",
			name : 'applyUserNameSer'
		}],
		// ����״̬���ݹ���
		comboEx : [{
			text: "��ͬ����",
			key: 'contractType',
			datacode : 'HTGZYD'
		},{
			text: "����״̬",
			key: 'status',
			value :'0',
			data : [{
				text : 'δ����',
				value : '0'
			}, {
				text : '�Ѹ���',
				value : '1'
			}, {
				text : '�ѹر�',
				value : '2'
			}]
		}]
	});
});