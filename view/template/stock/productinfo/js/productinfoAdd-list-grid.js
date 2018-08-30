/** ������Ϣ�б�* */

var show_page = function(page) {
	$("#proTypeTree").yxtree("reload");
	$("#productinfoGrid").yxgrid("reload");
};

$(function() {

	$("#tree").yxtree({
		url : '?model=stock_productinfo_producttype&action=getTreeDataByParentId',
		event : {
			"node_click" : function(event, treeId, treeNode) {
				var productinfoGrid = $("#productinfoGrid").data('yxgrid');

				productinfoGrid.options.extParam['proTypeId'] = treeNode.id;
				$("#proTypeId").val(treeNode.id);
				$("#proType").val(treeNode.name);
				$("#arrivalPeriod").val(treeNode.submitDay);
				productinfoGrid.reload();
				// productinfoGrid.options.extParam['proTypeId'] = null;
			}
		}
	});

	$("#productinfoGrid").yxgrid({
		customCode : 'productinfolistgrid',
		model : 'stock_productinfo_productinfoAdd',
		action : 'myPageJson',
		title : '������Ϣ����',
		isToolBar : true,
		isViewAction : false,
		showcheckbox : false,
		isEditAction : false,
		isDelAction : false,
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '��������',
					name : 'proType',
					sortable : true
				}, {
					display : '���ϱ���',
					name : 'productCode',
					sortable : true
				}, {
					display : 'k3����',
					name : 'ext2',
					sortable : true,
					hide:true
				}, {
					display : '��������',
					name : 'productName',
					sortable : true,
					width : 250
				}, {
					display : '״̬',
					name : 'ext1',
					process : function(v, row, g) {
//						alert(g)
						if (v == "WLSTATUSKF") {
							return "����";
						} else {
//							alert($(this).html());
							return "�ر�";
						}
					},
					sortable : true,
					width : 50
				}, 				
				{
					name : 'pattern',
					display : '����ͺ�',
					sortable : true
				},{
					name : 'status',
					display : '�Ƿ��ύ',
					sortable : true,
					process : function(v){
					if(v=='0')
						return 'δ�ύ';
					else if(v=='1')
						return '���ύ';
				}
				}
				,{
					name : 'state',
					display : '�Ƿ�ȷ��',
					sortable : true
				}
				, {
					name : 'priCost',
					display : '����',
					sortable : true,
					hide : true
				}, {
					name : 'unitName',
					display : '��λ',
					sortable : true
				}, {
					name : 'aidUnit',
					display : '������λ',
					sortable : true,
					hide : true
				}, {
					name : 'converRate',
					display : '������',
					sortable : true,
					hide : true
				}, {
					name : 'warranty',
					display : '������(��)',
					hide : true,
					sortable : true
				}, {
					name : 'arrivalPeriod',
					display : '��������(��)',
					hide : true,
					sortable : true
				}, {
					name : 'purchPeriod',
					display : '�ɹ�����(��)',
					sortable : true,
					hide : true
				}, {
					name : 'accountingCode',
					display : '��ƿ�Ŀ����',
					sortable : true,
					datacode : 'KJKM',
					hide : true
				}, {
					name : 'changeProductCode',
					display : '������ϱ���',
					sortable : true,
					hide : true
				}, {
					name : 'changeProductName',
					display : '�����������',
					sortable : true,
					hide : true
				}, {
					name : 'closeReson',
					display : '�ر�ԭ��',
					sortable : true,
					hide : true
				}, {
					name : 'leastPackNum',
					display : '��С��װ��',
					sortable : true,
					hide : true
				}, {
					name : 'leastOrderNum',
					display : '��С������',
					sortable : true,
					hide : true
				}, {
					name : 'material',
					display : '����',
					sortable : true,
					hide : true
				}, {
					name : 'brand',
					display : 'Ʒ��',
					sortable : true,
					hide : true
				}, {
					name : 'color',
					display : '��ɫ',
					sortable : true,
					hide : true
				}, {
					name : 'purchUserName',
					display : '�ɹ�������',
					sortable : true,
					hide : true
				}, {
					display : '��������',
					name : 'esmCanUse',
					process : function(v) {
						if (v == "1") {
							return "��";
						} else {
							return "��";
						}
					},
					sortable : true,
					width : 50,
					hide : true
				}, {
					name : 'createName',
					display : '������',
					sortable : true,
					hide : true
				}, {
					name : 'updateName',
					display : '�޸���',
					sortable : true,
					hide : true
				}],
		toAddConfig : {
			toAddFn : function(p) {
				showThickboxWin("?model=stock_productinfo_productinfoAdd&action=toAdd&proType="
						+ $("#proType").val()
						+ "&proTypeId="
						+ $("#proTypeId").val()
						+ "&arrivalPeriod="
						+ $("#arrivalPeriod").val()
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=590&width=900");
			}
		},
		menusEx : [{
			name : 'view',
			text : "�鿴",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=stock_productinfo_productinfoAdd&action=view&id="
						+ row.id
						+ "&productId=$rs[productId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
			}
		},{
			name : 'submit',
			text : "�ύ",
			icon : 'edit',
			showMenuFn : function(row) {
				if(row.state=='���')
					return false;
				if(row.status=='0')
					return true;			
				return false;
			},
			action : function(row, rows, grid) {
				$.ajax({
					url:"?model=stock_productinfo_productinfoAdd&action=submitStatus&id="+ row.id,
					type:'get',
					dataType:'json',
					success:function(msg){
						if(msg==1){
							alert('�ύ�ɹ���');
							show_page();
						}else{
							alert('�ύʧ�ܣ�');
							//show_page();
						}
					}					
				});				
			}
		},{
			name : 'edit',
			text : "�޸�",
			icon : 'edit',
			showMenuFn : function(row) {
				if(row.status=='0')
					return true;				
				return false;
			},
			action : function(row, rows, grid) {
				var editTypeResult = false;
				$.ajax({
					type : "POST",
					async : false,
					url : "?model=stock_productinfo_productinfoAdd&action=checkProType",
					data : {
						typeId : row.proTypeId
					},
					success : function(cresult) {
						if (cresult == 1)
							editTypeResult = true;
					}
				})
				showThickboxWin("?model=stock_productinfo_productinfoAdd&action=init&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
				/*if (editTypeResult) {// ��������Ȩ��
					if ($("#productUpdate").val() != '1') {// ����Ȩ��
						var editReresult = false;
						$.ajax({
							type : "POST",
							async : false,
							url : "?model=stock_productinfo_productinfoAdd&action=checkExistBusiness",
							data : {
								id : row.id
							},
							success : function(result) {
								if (result == 1)
									editReresult = true;
							}
						})
						if (editReresult) {// �޸�Ȩ��
							showThickboxWin("?model=stock_productinfo_productinfoAdd&action=init&id="
									+ row.id
									+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");

						} else {
							alert("�������Ѿ����ڹ���ҵ����Ϣ,�������޸�,����ϵ����Ա!");
						}
					} else {
						showThickboxWin("?model=stock_productinfo_productinfoAdd&action=init&id="
								+ row.id
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");

					}
				} else {
					alert("������������û�й���Ȩ��!");
				}*/

			}
		},{
			name : 'del',
			text : "ɾ��",
			icon : 'delete',
			showMenuFn : function(row) {
				if(row.status=='0')
					return true;
				if(row.state=='���')
					return true;
				return false;
			},
			action : function(row, rows, grid) {
				$.ajax({
					type : "POST",
					async : false,
					url : "?model=stock_productinfo_productinfoAdd&action=ajaxdeletes",
					data : {
						id : row.id
					},
					success : function(result) {
						if (result == 1){
							show_page();
							alert('ɾ���ɹ���');
						}
							
						else
							alert('ɾ��ʧ�ܣ�');
					}
				})
			}
		}/*, {
			name : 'view',
			text : "������־",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=syslog_operation_logoperation&action=businessView&pkValue="
						+ row.id
						+ "&tableName=oa_stock_product_info"
						+ "&productId=$rs[productId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
			}
		}, {
			text : '����',
			icon : 'edit',
			showMenuFn : function(row) {
				if ($("#productUpdate").val() == '1') {
					return true;
				}
				return false;
			},
			action : function(row) {
				var editTypeResult = false;
				$.ajax({
					type : "POST",
					async : false,
					url : "?model=stock_productinfo_productinfo&action=checkProType",
					data : {
						typeId : row.proTypeId
					},
					success : function(cresult) {
						if (cresult == 1)
							editTypeResult = true;
					}
				})
				if (editTypeResult) {// ��������Ȩ��
					showThickboxWin('?model=stock_productinfo_productinfo&action=toUpdate&id='
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
				} else {
					alert("��û�д��������͵Ĺ���Ȩ��!")

				}
			}
		}, {
			text : '������',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=stock_productinfo_productinfo&action=toViewRelation&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=900');
			}
		}, {
			text : '��������',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.esmCanUse == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if(confirm('ȷ������ô��')){
					$.ajax({
						type : "POST",
						async : false,
						url : "?model=stock_productinfo_productinfo&action=openEsmCanUse",
						data : {
							id : row.id,
							typeId : row.proTypeId
						},
						success : function(data) {
							if (data == 1){
								alert('���óɹ�');
								$("#productinfoGrid").yxgrid("reload");
							}else{
								alert('����ʧ��');
							}
						}
					})
				}
			}
		}, {
			text : '���̹ر�',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.esmCanUse == '1') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if(confirm('�رպ�������Ŀ�н���ѡ��ȷ�Ϲر���')){
					$.ajax({
						type : "POST",
						async : false,
						url : "?model=stock_productinfo_productinfo&action=closeEsmCanUse",
						data : {
							id : row.id,
							typeId : row.proTypeId
						},
						success : function(data) {
							if (data == 1){
								alert('�رճɹ�');
								$("#productinfoGrid").yxgrid("reload");
							}else{
								alert('�ر�ʧ��');
							}
						}
					})
				}
			}
		}*/],
		buttonsEx : [/*{
			name : 'import',
			text : "��������",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=stock_productinfo_productinfo&action=toUploadExcel"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=500")
			}
		},{
			name : 'import',
			text : "��������",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=stock_productinfo_productinfo&action=toUploadUpdateExcel"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=500")
			}
		},{
			name : 'importprice',
			text : "���³ɱ�",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=stock_productinfo_productinfo&action=toUpdatePriceExcel"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=500")
			}
		},{
			name : 'importk3',
			text : "����K3",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=stock_productinfo_productinfo&action=toUploadK3Excel"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=500")
			}			
		}, {
			name : 'expport',
			text : "��������",
			icon : 'excel',
			action : function(row) {
				window
						.open(
								"?model=stock_productinfo_productinfo&action=toExportExcel",
								"", "width=200,height=200,top=200,left=200");
			}
		}, {
			name : 'expport',
			text : "�����������",
			icon : 'excel',
			action : function(row) {
				var proTypeId = $("#proTypeId").val();
				window.open(
						"?model=stock_productinfo_productinfo&action=toArmatureExcel&proTypeId="
								+ proTypeId, "",
						"width=800,height=800,top=200,left=200");
			}
		},{
			name : 'editaccess',
			text : "�������",
			icon : 'edit',
			action : function(row) {
				if($("#proTypeId").val()!=""){
					showThickboxWin("?model=stock_productinfo_productinfo&action=toEditProAccess&proTypeId="+$("#proTypeId").val()
							+"&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");					
				}else{
					alert("��ѡ�����Ϸ��࣡");
				}
			}
		}*/],
		// �߼�����
		advSearchOptions : {
			modelName : 'productinfolist',
			searchConfig : [{
				name : '���ϱ��',
				value : 'c.productCode'
			},{
				name : '��������',
				value : 'c.productName'
			},{
				name:'����ͺ�',
				value:'c.pattern'
			},{
				name:'��������',
				value:'c.proType'
			},{
				name:'����״̬',
				value:'c.ext1',
				type:'select',
				datacode:'WLSTATUS'
			},{
				name:'K3����',
				value:'c.ext2'					
			}]
		},
		/*
		event:{
			afterloaddata:function(e,data,g){
//				alert(g)
//				alert(e)
				var rows=g.getRows();
//				alert(rows)
				rows.each(function(){
//					alert($(this).data("data"))
					var data=$(this).data("data");
					if(data.ext1=="WLSTATUSKF"){
//						alert()
						$(this).addClass("trSelected");
					}
//
				})
			}
		},
		*/
		
		comboEx : [ {
			text : '�ύ״̬',
			key : 'status',
			data : [ {
				text : 'δ�ύ',
				value : '0'
			}, {
				text : '���ύ',
				value : '1'
			} ]
		} ,{
			text : 'ȷ��״̬',
			key : 'state',
			data : [ {
				text : 'δȷ��',
				value : 'δȷ��'
			}, {
				text : '��ȷ��',
				value : '��ȷ��'
			} ]
		} ],
		searchitems : [{
					display : '���ϱ���',
					name : 'productCode'
				}, {
					display : '��������',
					name : 'productName'

				}, {
					display : '��������',
					name : 'ext3'
				},{
					display : 'Ʒ��',
					name : 'brand'
				},{
					display : '����ͺ�',
					name : 'pattern'
				},{
					name:'ext2',
					display:'K3����'					
				}],
				sortname : "updateTime",
				// Ĭ������˳��
				sortorder : "desc"
	});
});