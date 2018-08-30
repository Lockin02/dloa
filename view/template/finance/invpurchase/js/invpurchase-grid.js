var show_page = function(page) {
	$("#invpurchaseGrid").yxsubgrid("reload");
};

$(function() {
	var listType = $("#listType").val();
	var paramArr = (listType != '')? {'listType' : listType} : {};

	var titleName = '�ɹ���Ʊ';
	switch (listType){
		case'assetPurOnly':
			titleName = '�̶��ʲ���Ʊ';
			break;
		default:
			titleName = '�ɹ���Ʊ';
			break;
	}

	$("#invpurchaseGrid").yxsubgrid({
		model: 'finance_invpurchase_invpurchase',
		param: {'listType' : listType},
		title: titleName,
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		showcheckbox :false,
		customCode : 'invpurchaseGrid',
		noCheckIdValue : 'noId',
		//����Ϣ
		colModel: [{
				display: 'id',
				name: 'id',
				sortable: true,
				hide: true,
				process : function(v,row){
					return v + "<input type='hidden' id='isBreak"+ row.id+"' value='unde'>";
				}
			},
			{
				name: 'objCode',
				display: '���ݱ��',
				sortable: true,
				width : 130,
				process : function(v,row){
					if(row.formType == "blue"){
						return v;
					}else if(row.formType == "red"){
						return "<span class='red'>"+ v +"</span>";
					}else{
						return v;
					}
				}
			},
			{
				name: 'objNo',
				display: '��Ʊ����',
				sortable: true,
				process : function(v,row){
					if(row.formType == "blue"){
						return v;
					}else if(row.formType == "red"){
						return "<span class='red'>"+ v +"</span>";
					}
				}
			},
			{
				name: 'supplierName',
				display: '��Ӧ������',
				sortable: true,
				width : 150
			},
			{
				name: 'invType',
				display: '��Ʊ����',
				sortable: true,
				width : 80,
				datacode : 'FPLX'
			},
			{
				name: 'formNumber',
				display: '����',
				sortable: true,
				width : 60
			},
			{
				name: 'taxRate',
				display: '˰��(%)',
				sortable: true,
				width : 60
			},
			{
				name: 'formAssessment',
				display: '����˰��',
				sortable: true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},
			{
				name: 'amount',
				display: '�ܽ��',
				sortable: true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},
			{
				name: 'formCount',
				display: '��˰�ϼ�',
				sortable: true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},
			{
				name: 'formDate',
				display: '��������',
				sortable: true,
				width : 80
			},
			{
				name: 'payDate',
				display: '��������',
				sortable: true,
				width : 80
			},{
				name : 'purcontCode',
				display : '�ɹ��������',
				width : 130,
				hide : true
			},
			{
				name: 'departments',
				display: '����',
				sortable: true,
				width : 80
			},
			{
				name: 'salesman',
				display: 'ҵ��Ա',
				sortable: true,
				width : 80
			},
			{
				name : 'businessBelongName',
				display : '������˾',
				sortable : true,
				width : 80
			},
			{
				name: 'ExaStatus',
				display: '���״̬',
				sortable: true,
				width : 60,
				process : function(v){
					if(v == 1){
						return '�����';
					}else if(v == "0"){
						return 'δ���';
					}
				}
			},
			{
				name: 'exaMan',
				display: '�����',
				sortable: true,
				width : 80
			},
			{
				name: 'ExaDT',
				display: '�������',
				sortable: true,
				width : 80
			},
			{
				name: 'status',
				display: '����״̬',
				sortable: true,
				width : 60,
				process : function(v){
					if(v == 1){
						return '�ѹ���';
					}else if(v == "0"){
						return 'δ����';
					}
				}
			},{
				name : 'createName',
				display : '������',
				width : 90,
				hide : true
			},
			{
				name: 'belongId',
				display: '����ԭ��Ʊid',
				hide: true
			},
			{
				name: 'updateTime',
				display: '�������ʱ��',
				hide : true,
				width : 130
			}
		],

		// ���ӱ������
		subGridOptions : {
			url : '?model=finance_invpurchase_invpurdetail&action=pageJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [
				{
					paramId : 'invPurId',// ���ݸ���̨�Ĳ�������
					colId : 'id'// ��ȡ���������ݵ�������
				}
			],
			// ��ʾ����
			colModel : [{
					name : 'productNo',
					display : '���ϱ��',
					width : 80
				},{
					name : 'productName',
					display : '��������',
					width : 140
				},{
				    name : 'number',
				    display : '����',
				    width : 50
				},{
					name : 'price',
					display : '����',
					process : function(v,row,parentRow){
						return moneyFormat2(v,6,6);
					}
				},{
					name : 'taxPrice',
					display : '��˰����',
					process : function(v){
						return moneyFormat2(v,6,6);
					}
				},{
				    name : 'assessment',
				    display : '˰��',
					process : function(v){
						return moneyFormat2(v);
					},
				    width : 70
				},{
				    name : 'amount',
				    display : '���',
					process : function(v){
						return moneyFormat2(v);
					},
				    width : 80
				},{
				    name : 'allCount',
				    display : '��˰�ϼ�',
					process : function(v){
						return moneyFormat2(v);
					},
				    width : 80
				},{
				    name : 'objCode',
				    display : 'Դ�����',
				    width : 120
				},{
				    name : 'contractCode',
				    display : '�������',
				    width : 120
				}
			]
		},
		toAddConfig : {
			toAddFn : function(p) {
				showModalWin("?model=finance_invpurchase_invpurchase&action=toAdd",1);
			}
		},
		buttonsEx : [{
			name : 'Add',
			text : "�ϲ�",
			icon : 'search',
			action : function(row, rows, idArr) {
				if (row) {
					if(idArr.length >1 ) {
						alert('һ��ֻ�ܶ�һ����¼�����ϲ�');
						return false;
					}
					$.ajax({
					    type: "POST",
					    url: "?model=common_search_searchSource&action=checkUp",
					    data: {"objId" : row.id , 'objType' : 'invpurchase'},
					    async: false,
					    success: function(data){
					   		if(data != ""){
					   			var dataObj = eval("(" + data +")");
					   			for(t in dataObj){
					   				var thisType = t;
					   				var thisIds = dataObj[t];
					   			}
								showModalWin("?model=common_search_searchSource&action=upList&objType=invpurchase&orgObj="+ thisType +"&ids=" + thisIds);
					   	    }else{
								alert('û��������ĵ���');
					   	    }
						}
					});
				} else {
					alert('����ѡ���¼');
				}
			}
		},{
			name : 'Add',
			text : "�²�",
			icon : 'search',
			action : function(row, rows, idArr) {
				if (row) {
					if(idArr.length >1 ) {
						alert('һ��ֻ�ܶ�һ����¼�����²�');
						return false;
					}

					$.ajax({
					    type: "POST",
					    url: "?model=common_search_searchSource&action=checkDown",
					    data: {"objId" : row.id , 'objType' : 'invpurchase'},
					    async: false,
					    success: function(data){
					   		if(data != ""){
								showModalWin("?model=common_search_searchSource&action=downList&objType=invpurchase&orgObj="+data+"&objId=" + row.id);
					   	    }else{
								alert('û��������ĵ���');
					   	    }
						}
					});
				} else {
					alert('����ѡ���¼');
				}
			}
		},{
			name : 'add',
			text : "�߼�����",
			icon : 'search',
			action : function(row) {
				showThickboxWin("?model=finance_invpurchase_invpurchase&action=toSearch"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700")
			}
		},{
			name : 'add',
			text : "���б�",
			icon : 'search',
			action : function(row) {
				showModalWin('?model=finance_invpurchase_invpurchase&action=viewlist',1);
			}
		}],
		menusEx : [
			{
				text: "�鿴",
				icon: 'view',
				showMenuFn : function(row){
					if(row.id == 'noId'){
						return false;
					}
				},
				action: function(row) {
					showModalWin('?model=finance_invpurchase_invpurchase&action=init&perm=view&id=' + row.id + "&skey=" + row.skey_);
				}
			},
			{
				text: "���",
				icon: 'edit',
				showMenuFn : function(row){
					if(row.ExaStatus == 0){//�ж��Ƿ������Ȩ��
                        var  auditLimit = 0;
                        $.ajax({
                            type: "POST",
                            url: "?model=finance_invpurchase_invpurchase&action=hasLimitToAudit",
                            data: "",
                            async: false,
                            success: function(data){
                                if(data == 1){
                                    auditLimit=1;
                                }
                            }
                        });

                        if(auditLimit*1 == 1){
                            return true;
                        }else{
                            return false;
                        }
						return true;
					}
					return false;
				},
				action: function(row) {
					showModalWin('?model=finance_invpurchase_invpurchase&action=init&id=' + row.id + "&skey=" + row.skey_ );
				}
			},
			{
				text: "ɾ��",
				icon: 'delete',
				showMenuFn : function(row){
					if(row.ExaStatus == 0){
                        var  deleteLimit = 0;
                        $.ajax({
                            type: "POST",
                            url: "?model=finance_invpurchase_invpurchase&action=hasLimitToDelete",
                            data: "",
                            async: false,
                            success: function(data){
                                if(data == 1){
                                    deleteLimit=1;
                                }
                            }
                        });

                        if(deleteLimit*1 == 1){
                            return true;
                        }else{
                            return false;
                        }
					}
					return false;
				},
				action: function(row) {
					if (window.confirm(("ȷ��Ҫɾ��?"))) {
						$.ajax({
							type : "POST",
							url : "?model=finance_invpurchase_invpurchase&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('ɾ���ɹ���');
									show_page(1);
								}else{
									alert("ɾ��ʧ��! ");
								}
							}
						});
					}
				}
			},
//			{
//				text: "���",
//				icon: 'edit',
//				showMenuFn : function(row){
//					if(row.ExaStatus ==0){
//						return true;
//					}
//					return false;
//				},
//				action: function(row) {
//					if (window.confirm(("ȷ��Ҫ���?"))) {
//						$.ajax({
//							type : "POST",
//							url : "?model=finance_invpurchase_invpurchase&action=audit",
//							data : {
//								"id" : row.id
//							},
//							success : function(msg) {
//								if (msg == 1) {
//									alert('��˳ɹ���');
//									show_page(1);
//								}else{
//									alert('���ʧ��!');
//								}
//							}
//						});
//					}
//				}
//			},
			{
				text: "�����",
				icon: 'edit',
				showMenuFn : function(row){
					if(row.ExaStatus == 1 && row.status == 0 && row.belongId == ""){
						//�ж��Ƿ������Ȩ��
						unAudit = $('#unAudit').length;
						if(unAudit == 0){
							$.ajax({
							    type: "POST",
							    url: "?model=finance_invpurchase_invpurchase&action=hasLimitToUnaudit",
							    data: "",
							    async: false,
							    success: function(data){
							   		if(data == 1){
							   	   		$("#invpurchaseGrid").after("<input type='hidden' id='unAudit' value='1'/>");
									}else{
							   	   		$("#invpurchaseGrid").after("<input type='hidden' id='unAudit' value='0'/>");
									}
								}
							});
						}

						if($('#unAudit').val()*1 == 1){
							return true;
						}else{
							return false;
						}
						//�ж��Ƿ�Ϊ����ֲɹ���Ʊ
						isBreak = $("#isBreak" + row.id);
						if( isBreak.val() == 'unde' ){
							$.ajax({
							    type: "POST",
							    url: "?model=finance_invpurchase_invpurchase&action=isBreak",
							    data: {"id" : row.id},
							    async: false,
							    success: function(data){
							   	   if(data == 1){
							   	   		isBreak.val(1);
									}else{
							   	   		isBreak.val(0);
									}
								}
							});
						}

						if(isBreak.val() == 1){
							return false;
						}
					}
					return false;
				},
				action: function(row) {
					if (window.confirm(("ȷ��Ҫ�����?"))) {
						$.ajax({
							type : "POST",
							url : "?model=finance_invpurchase_invpurchase&action=unaudit",
							data : {
								"id" : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('����˳ɹ���');
									show_page(1);
								}else{
									alert('�����ʧ��!');
								}
							}
						});
					}
				}
			},{
				text: "���ݲ��",
				icon: 'edit',
				showMenuFn : function(row){
					if(row.ExaStatus == 1 && row.status == 0 && row.belongId == ""){

						isBreak = $("#isBreak" + row.id);
						if( isBreak.val() == 'unde' ){
							$.ajax({
							    type: "POST",
							    url: "?model=finance_invpurchase_invpurchase&action=isBreak",
							    data: {"id" : row.id},
							    async: false,
							    success: function(data){
							   	   if(data == 1){
							   	   		isBreak.val(1);
									}else{
							   	   		isBreak.val(0);
									}
								}
							});
						}
						if(isBreak.val() == 1){
							return false;
						}else{
							return true;
						}
					}
					return false;
				},
				action: function(row,rows) {
					showModalWin('?model=finance_invpurchase_invpurchase&action=init&perm=break&id=' + row.id + "&skey=" + row.skey_ );
				}
			},{
				text: "���ݺϲ�",
				icon: 'edit',
				showMenuFn : function(row){
					if(row.id == 'noId'){
						return false;
					}
					if(row.belongId != ''){
						return true;
					}
					return false;
				},
				action: function(row) {
					if(confirm('ȷ��Ҫ�ϲ�?')){
						$.ajax({
							type : "POST",
							url : "?model=finance_invpurchase_invpurchase&action=merge",
							data : {
								"id" : row.id,
								"belongId" : row.belongId
							},
							success : function(msg) {
								if (msg == 1) {
									alert('�ϲ��ɹ���');
									show_page(1);
								}else{
									alert('�ϲ�ʧ��!');
								}
							}
						});
					}
				}
			},
			{
				text: "����",
				icon: 'edit',
				showMenuFn : function(row){
					if(row.status == 0 && row.ExaStatus == 1){
						return true;
					}
					return false;
				},
				action: function(row) {
					showModalWin('?model=finance_invpurchase_invpurchase&action=toHook&id=' + row.id );
				}
			},
			{
				text: "������־",
				icon: 'edit',
				showMenuFn : function(row){
					if(row.status == 1){
						return true;
					}
					return false;
				},
				action: function(row) {
					showOpenWin('?model=finance_related_baseinfo&action=toUnhook&invPurId=' + row.id );
				}
			},
			{
				text: "������",
				icon: 'delete',
				showMenuFn : function(row){
					if(row.status == 1){
                        var  unHookLimit = 0;
                        $.ajax({
                            type: "POST",
                            url: "?model=finance_invpurchase_invpurchase&action=hasLimitToUnHook",
                            data: "",
                            async: false,
                            success: function(data){
                                if(data == 1){
                                    unHookLimit=1;
                                }
                            }
                        });

                        if(unHookLimit*1 == 1){
                            return true;
                        }else{
                            return false;
                        }
					}
					return false;
				},
				action: function(row) {
					if(confirm('ȷ��Ҫ������?')){
						$.ajax({
							type : "POST",
							url : "?model=finance_related_baseinfo&action=unHookByInv",
							data : {
								"invPurId" : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('�������ɹ���');
									show_page(1);
								}else{
									alert('������ʧ��!');
								}
							}
						});
					}
				}
			}
		],
		comboEx:
		[
			{
				text: "���״̬",
				key: 'ExaStatus',
				data: [{
					text : '�����',
					value : '1'
				},{
					text : 'δ���',
					value : '0'
				}]
			},{
				text: "����״̬",
				key: 'status',
				data: [{
					text : '�ѹ���',
					value : '1'
				},{
					text : 'δ����',
					value : '0'
				}]
			}
		],
		searchitems:[
	        {
	            display:'��Ʊ����',
	            name:'objNo'
	        },
	        {
	            display:'��Ӧ������',
	            name:'supplierName'
	        },
	        {
	            display:'���ݱ��',
	            name:'objCodeSearch'
	        },
	        {
	            display:'Դ�����',
	            name:'objCodeSearchDetail'
	        },
	        {
	            display:'�ɹ��������',
	            name:'contractCodeSearch'
	        },
	        {
	            display:'���ϱ��',
	            name:'productNoSearch'
	        },
	        {
	            display:'��������',
	            name:'productNameSearch'
	        },
	        {
	            display:'�����ͺ�',
	            name:'productModelSearch'
	        }
        ],
        sortname : 'c.updateTime'
	});
});