var show_page = function(page) {
	$("#deductGrid").yxgrid("reload");};
$(function() {
		$("#deductGrid").yxgrid({
				model : 'outsourcing_deduct_deduct',
               	title : '����ۿ',
				isEditAction:false,
				isDelAction:false,
				showcheckbox:fals
				param:{'createId':$("#createId").val()},
				bodyAlign:'center',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'formCode',
                  					display : '���ݱ��',
                  					width:140,
                  					sortable : true,
									process : function(v,row){
											return "<a href='#' onclick='showThickboxWin(\"?model=outsourcing_deduct_deduct&action=toView&id=" + row.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>"+ v + "</a>";
									}
                              },{
                    					name : 'formDate',
                  					display : '����ʱ��',
                  					width:70,
                  					sortable : true
                              },{
                    					name : 'ExaStatus',
                  					display : '����״̬',
                  					width:60,
                  					sortable : true
                              },{
                    					name : 'outsourceSupp',
                  					display : '�����Ӧ��',
                  					width:120,
                  					sortable : true
                              },{
                    					name : 'projectCode',
                  					display : '��Ŀ���',
                  					width:120,
                  					sortable : true
                              },{
                    					name : 'projecttName',
                  					display : '��Ŀ����',
                  					width:120,
                  					sortable : true
                              }, {
                    					name : 'outsourceContractCode',
                  					display : '�����ͬ���',
                  					sortable : true
                              }  ,{
                    					name : 'deductTotal',
                  					display : '�ۿ���',
                  					width:70,
                  					sortable : true
                              }],

                              					// ��չ�Ҽ��˵�

		menusEx : [{
				text : '�༭',
				icon : 'edit',
				showMenuFn : function(row) {
					if (row.ExaStatus == 'δ�ύ'||row.ExaStatus == '���') {
						return true;
					}
					return false;
				},
				action : function(row) {
					showThickboxWin("?model=outsourcing_deduct_deduct&action=toEdit&id=" +row.id+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=800");

				}

			},{
				text : '�ύ����',
				icon : 'add',
				showMenuFn : function(row) {
					if (row.ExaStatus == 'δ�ύ'||row.ExaStatus == '���') {
						return true;
					}
					return false;
				},
				action : function(row) {
					showThickboxWin("controller/outsourcing/deduct/ewf_index.php?actTo=ewfSelect&billId=" +row.id+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}

			},{
				text : 'ɾ��',
				icon : 'delete',
				showMenuFn : function(row) {
					if (row.ExaStatus == 'δ�ύ'||row.ExaStatus == '���') {
						return true;
					}
					return false;
				},
				action : function(row) {
					if (window.confirm(("ȷ��Ҫɾ��?"))) {
						$.ajax({
							type : "POST",
							url : "?model=outsourcing_deduct_deduct&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('ɾ���ɹ���');
									$("#deductGrid").yxgrid("reload");
								}
							}
						});
					}
				}

			},{
					name : 'aduit',
					text : '�������',
					icon : 'view',
					showMenuFn : function(row) {
						if (row.ExaStatus == '���' || row.ExaStatus == '���' || row.ExaStatus == '��������') {
							return true;
						}
						return false;
					},
					action : function(row, rows, grid) {
						if (row) {
							showThickboxWin("controller/common/readview.php?itemtype=oa_outsourcing_deduct&pid="
									+ row.id
									+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
						}
					}
				}],

		comboEx:[{
			text:'����״̬',
			key:'ExaStatus',
			data:[{
			   text:'δ�ύ',
			   value:'δ�ύ'
			},{
			   text:'��������',
			   value:'��������'
			},{
			   text:'���',
			   value:'���'
			},{
			   text:'���',
			   value:'���'
			}]
		}],

		toEditConfig : {
			action : 'toEdit'
		},

		toAddConfig : {
			formHeight:600
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "���ݱ��",
					name : 'formCode'
				},{
					display : "����ʱ��",
					name : 'formDate'
				},{
					display : "�����Ӧ��",
					name : 'outsourceSupp'
				},{
					display : "��Ŀ���",
					name : 'projectCode'
				},{
					display : "��Ŀ����",
					name : 'projecttName'
				},{
					display : "�����ͬ���",
					name : 'outsourceContractCode'
				}]
 		});
 });