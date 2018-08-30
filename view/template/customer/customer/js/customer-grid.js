function updateUsingState(id){
	var sltVal = $("#useStateUpdateBtn_"+id).val();
	$.ajax({
		type : 'POST',
		url : '?model=customer_customer_customer&action=updateUsingState',
		data : {
			id : id,
			newVal : sltVal
		},
		success : function(data) {
			if(data == 'true'){
				alert("���³ɹ�!");
			}else{
				alert("����ʧ��!")
			}
		}
	});
}

(function($) {

	// ��ʼ����ͷ��ť����
	buttonsArr = [{
		// ����EXCEL�ļ���ť
		name : 'import',
		text : "����EXCEL",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=customer_customer_customer&action=toUploadExcel"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=500")
		}
	}];

	mergeArr = {
		text : "�ϲ��ͻ�",
		icon : 'edit',
		action : function(rowData, rows, rowIds, g) {
			if (rowData && rows.length > 1) {
				if (confirm("ȷ��Ҫ�ϲ�ѡ�еĿͻ�?�ϲ��󽫸������й�����ҵ�񵥾���Ϣ!�˲������ɻָ�,�����������ݿⱸ��.")) {
					var objectCode = prompt("������ϲ���Ŀͻ�����.", rowData.objectCode);
					if (objectCode) {
						$.ajax({
							type : 'POST',
							url : '?model=customer_customer_customer&action=mergerCustomer',
							data : {
								objectCode : objectCode,
								mergerIdArr : rowIds
							},
							success : function(data) {
								if (data == 1) {
									alert("�ϲ��ͻ��ɹ�.");
									g.reload();
								} else if (data == 2) {
									alert("û��Ȩ�޶Կͻ����кϲ�,����ϵ����Ա���Ȩ��.");
								} else {
									alert("�ϲ��ͻ�ʧ��.ʧ��ԭ��:" + data);
								}
							}
						});
					}
				}
			} else {
				alert("������ѡ�������ͻ���¼���кϲ�.");

			}
		}
	};

	$.ajax({
				type : 'POST',
				url : '?model=customer_customer_customer&action=getLimits',
				data : {
					'limitName' : '�ϲ��ͻ�'
				},
				async : false,
				success : function(data) {
					if (data == 1) {
						buttonsArr.push(mergeArr);
					}
				}
			});

	updateCodeArr = {
		// ����EXCEL�ļ���ť
		text : "���¿ͻ�����",
		icon : 'edit',
		action : function(rowData, rows, rowIds, g) {
			$.ajax({
				url : '?model=customer_customer_customer&action=updateCustomersCode',
				success : function(data) {
					if (data == '1') {
						alert("���¿ͻ�����ɹ�.");
						g.reload();
					} else {
						alert('���¿ͻ�����ʧ��');
					}
				}
			});
		}
	}

	$.ajax({
				type : 'POST',
				url : '?model=customer_customer_customer&action=getLimits',
				data : {
					'limitName' : '���¿ͻ�����'
				},
				async : false,
				success : function(data) {
					if (data == 1) {
						buttonsArr.push(updateCodeArr);
					}
				}
			});

	$.woo.yxgrid.subclass('woo.yxgrid_customer', {
		options : {
			model : 'customer_customer_customer',
			// ��
			colModel : [{
						display : 'id',
						name : 'id',
						sortable : true,
						hide : true
					}, {
						display : '�ͻ����',
						name : 'objectCode',
						sortable : true
					}, {
						display : '�ͻ�����',
						name : 'Name',
						sortable : true
					}, {
						display : '���۹���ʦ',
						name : 'SellMan',
						sortable : true,
						width : 150
					}, {
						display : '�ͻ�����',
						name : 'TypeOne',
						sortable : true,
						datacode : 'KHLX'
					}, {
						display : '����',
						name : 'Country',
						sortable : true
					}, {
						display : 'ʡ��',
						name : 'Prov',
						sortable : true
					}, {
						display : '����',
						name : 'City',
						sortable : true
					}, {
						display : 'ʹ��״̬',
						name : 'isUsing',
						// align: center,
						process: function (v,row) {
							var optStr = "";
							optStr += (v == 1)? "<option value='1' selected>����</option>" : "<option value='1'>����</option>";
							optStr += (v == 1)? "<option value='0'>�ر�</option>" : "<option value='0' selected>�ر�</option>";
							return "<div style='text-align: center;'><select class='useStateUpdateBtn' id='useStateUpdateBtn_"+row.id+"' onchange='javascript:updateUsingState("+row.id+")'>"+optStr+"</select></div>";
						},
						sortable : true
					}],
			/**
			 * ��������
			 */
			searchitems : [{
						display : '�ͻ�����',
						name : 'Name'
					}, {
						display : '�ͻ����',
						name : 'objectCodeLike'
					}],
			toAddConfig : {
				formWidth : 900,
				formHeight : 500
			},
			buttonsEx : buttonsArr,
			toViewConfig : {
				formWidth : 900,
				formHeight : 500,
				action : 'viewTab'
			},
			toEditConfig : {
				formWidth : 900,
				formHeight : 500
			},
			menusEx : [{
				text : '������ϵ��',
				icon : 'add',
				action : function(row) {
					showThickboxWin('?model=customer_linkman_linkman&action=toAdd&id='
							+ row.id
							+ "&customerName="
							+ row.Name
							+ "&skey="
							+ row['skey_']
							+ '&isFromCustomer=1&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
				}

			},{
				text : '����',
				icon : 'edit',
				showMenuFn : function(row){
					//�ж��Ƿ��и���Ȩ��
					unAudit = $('#unAudit').length;
					if(unAudit == 0){
						$.ajax({
						    type: "POST",
						    url: "?model=customer_customer_customer&action=getLimits",
						    data : {
								'limitName' : '����'
							},
						    async: false,
						    success: function(data){
						   		if(data == 1){
						   	   		unAudit = 1;
						   	   		$("#customerGrid").after("<input type='hidden' id='unAudit' value='1'/>");
								}else{
						   	   		$("#customerGrid").after("<input type='hidden' id='unAudit' value='0'/>");
								}
							}
						});
					}
					if($('#unAudit').val()*1 == 0){
						return false;
					}
					return true;
				},
				action : function(row) {
					showThickboxWin('?model=customer_customer_customer&action=toUpdate&id='
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ '&isFromCustomer=1&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
				}
			}, {
				text : '������',
				icon : 'view',
				action : function(row) {
					showThickboxWin('?model=customer_customer_customer&action=toViewRelation&id='
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ '&isFromCustomer=1&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=900');
				}
			}

			],
			sortorder : "DESC",
			sortname : "id",
			title : '�ͻ���Ϣ'
		}
	});
})(jQuery);