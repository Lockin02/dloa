var show_page = function(page) {
	$("#workVerifyGrid").yxgrid("reload");
};
$(function() {
			$("#workVerifyGrid").yxgrid({
				model : 'outsourcing_workverify_workVerify',
				isEditAction:false,
				isDelAction:false,
				showcheckbox:false,
				param:{'createId':$("#createId").val()},
				bodyAlign:'center',
               	title : '������ȷ�ϵ�',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'formCode',
                  					display : '���ݱ��',
                  					width:150,
                  					sortable : true,
									process : function(v,row){
											return "<a href='#' onclick='showOpenWin(\"?model=outsourcing_workverify_workVerify&action=toView&id=" + row.id +"\")'>" + v + "</a>";
									}
                              },{
                    					name : 'status',
                  					display : '״̬',
                  					width:70,
                  					sortable : true,
									process:function(v){
											if(v=="1"){
												return "�ύ����";
											}else if(v=="2"){
												return "����ȷ��";
											}else if(v=="3"){
												return "��ȷ��";
											}else if(v=="4"){
												return "�ر�";
											}else if(v=="5"){
												return "�������";
											}else {
												return "δ�ύ";
											}
									}
                              },{
                    					name : 'formDate',
                  					display : '����ʱ��',
                  					sortable : true
                              },{
                    					name : 'beginDate',
                  					display : '���ڿ�ʼ����',
                  					sortable : true
                              },{
                    					name : 'endDate',
                  					display : '���ڽ�������',
                  					sortable : true
                              } ,{
                    					name : 'createName',
                  					display : '������',
                  					width:70,
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '��ע',
                  					width:450,
                  					sortable : true
                              }],

                              //��������
			comboEx : [{
				text : '״̬',
				key : 'status',
				data : [{
						text : 'δ�ύ',
						value : '0'
					},{
						text : '�ύ����',
						value : '1'
					},{
						text : '�������',
						value : '5'
					},{
						text : '����ȷ��',
						value : '2'
					},{
						text : '��ȷ��',
						value : '3'
					},{
						text : '�ر�',
						value : '4'
					}]
				}
			],
					// ��չ�Ҽ��˵�

		menusEx : [{
				text : '�༭',
				icon : 'edit',
				showMenuFn : function(row) {
					if (row.status == '0') {
						return true;
					}
					return false;
				},
				action : function(row) {
					showModalWin("?model=outsourcing_workverify_workVerify&action=toEdit&id=" +row.id);

				}

			},{
				text : '�ύ����',
				icon : 'add',
				showMenuFn : function(row) {
					if (row.status == '0') {
						return true;
					}
					return false;
				},
				action : function(row) {
					if (window.confirm(("ȷ��Ҫ�ύ?"))) {
						$.ajax({
							type : "POST",
							url : "?model=outsourcing_workverify_workVerify&action=changeState",
							data : {
								id : row.id,
								status:1
							},
							success : function(msg) {
								if (msg == 1) {
									alert('�ύ�ɹ���');
									$("#workVerifyGrid").yxgrid("reload");
								}
							}
						});
					}
				}

			},{
				text : '�༭',
				icon : 'edit',
				showMenuFn : function(row) {
					if (row.status == '1') {
						return true;
					}
					return false;
				},
				action : function(row) {
					showModalWin("?model=outsourcing_workverify_workVerify&action=toAuditEdit&id=" +row.id);

				}

			},{
				text : '�ύ��������',
				icon : 'edit',
				showMenuFn : function(row) {
					if (row.status == '5') {
						return true;
					}
					return false;
				},
				action : function(row) {
					if (window.confirm(("ȷ��Ҫ�ύ?"))) {
						$.ajax({
							type : "POST",
							url : "?model=outsourcing_workverify_workVerify&action=changeState",
							data : {
								id : row.id,
								status:2
							},
							success : function(msg) {
								if (msg == 1) {
									alert('�ύ�ɹ���');
									$("#workVerifyGrid").yxgrid("reload");
								}
							}
						});
					}
				}

			},{
				text : 'ɾ��',
				icon : 'delete',
				showMenuFn : function(row) {
					if (row.status == '0') {
						return true;
					}
					return false;
				},
				action : function(row) {
					if (window.confirm(("ȷ��Ҫɾ��?"))) {
						$.ajax({
							type : "POST",
							url : "?model=outsourcing_workverify_workVerify&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('ɾ���ɹ���');
									$("#workVerifyGrid").yxgrid("reload");
								}
							}
						});
					}
				}

			},
			{
				text : '����',
				icon : 'excel',
				action :function(row,rows,grid) {
					if(row){
						location="?model=outsourcing_workverify_workVerify&action=exportWorkVerify&id="+row.id+"&skey="+row['skey_'];
					}else{
						alert("��ѡ��һ������");
					}
				}

			}],

          toAddConfig : {
			formWidth : 1000,
			formHeight : 500,
			toAddFn : function(p, g) {
				showModalWin("?model=outsourcing_workverify_workVerify&action=toAdd");
			}
		},


		toEditConfig : {
			formWidth : 1000,
			formHeight : 500,
			toEditFn : function(p, g) {
					var get = g.getSelectedRow().data('data');
				showModalWin("?model=outsourcing_workverify_workVerify&action=toEdit&id=" + get[p.keyField]);
			}
		},
		toViewConfig : {
//			action : 'toView',
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showOpenWin("?model=outsourcing_workverify_workVerify&action=toView&id=" + get[p.keyField]);
				}
			}
		},
		searchitems : [{
					display : "���ݱ��",
					name : 'formCode'
				}]
 		});
 });