var show_page = function(page) {
	$("#materialGrid").yxgrid("reload");
};
$(function() {
	$("#materialGrid").yxgrid({
	model : 'purchase_material_material',
               	title : '����Э�����Ϣ',
               	bodyAlign : 'center',
               	isDelAction : false,
               	showcheckbox : false,
			//����Ϣ
				colModel : [{
 								display : 'id',
 								name : 'id',
 								sortable : true,
 								hide : true
                      },{
            				name : 'productCode',
      					display : '���ϱ��',
      					sortable : true,
      					   width : 120,
						process : function(v,row){
								return "<a href='#' onclick='showOpenWin(\"?model=purchase_material_material&action=toView&id="
										+ row.id +"\",1)'>" + v + "</a>";
						}
                  },{
        					name : 'productName',
      					display : '��������',
      					sortable : true,
      					   width : 300,
						process : function(v,row){
								return "<a href='#' onclick='showThickboxWin(\"?model=stock_productinfo_productinfo&action=View&id="
										+ row.productId +"&placeValuesBefore&TB_iframe=true&modal=false&height=590&width=900\")'>"
										+ v + "</a>";
						}
                  },{
        					name : 'productId',
      					display : '����id',
      					sortable : true,
 							hide : true
                  },{
        					name : 'protocolType',
      					display : 'Э������',
      					sortable : true,
      						width : 60
                  },{
            				name : 'createName',
      					display : '¼����',
      					sortable : true,
      						width : 80
                  },{
        					name : 'createTime',
      					display : '¼��ʱ��',
      					sortable : true,
      					   width : 130
                  },{
        					name : 'remark',
      					display : '��ע˵��',
      					sortable : true,
      					   width : 300,
      						align : 'left'
                  }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=purchase_material_NULL&action=pageItemJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'XXX',
						display : '�ӱ��ֶ�'
					}]
		},

		menusEx : [{
			text : 'ɾ��',
			icon : 'delete',
			action : function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=purchase_material_material&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg) {
								alert('ɾ���ɹ���');
								$("#materialGrid").yxgrid("reload");
							}else {
							     alert("ɾ��ʧ�ܣ�")
							}
						}
					});
				}
			}
		}],

		comboEx:[{
			text:'Э������',
			key:'protocolType',
			data:[{
			   text:'Э��۸�',
			   value:'Э��۸�'
			},{
			   text:'���ݼ۸�',
			   value:'���ݼ۸�'
			}]
		}],

		toEditConfig : {
			formWidth:990,
			action : 'toEdit'
		},
		toViewConfig : {
			formWidth:980,
			action : 'toView'
		},
		toAddConfig : {
			formWidth:990
		},
		searchitems : [{
					display : "���ϱ��",
					name : 'productCode'
				},{
					display : "��������",
					name : 'productName'
				},{
					display : "¼����",
					name : 'createName'
				}]
 		});
 });