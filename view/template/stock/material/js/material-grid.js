var show_page = function(page) {
	$("#materialGrid").yxgrid("reload");
};
$(function() {
			$("#materialGrid").yxgrid({
				model : 'stock_material_material',
               	title : '����BOM�嵥',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'proTypeId',
                  					display : '��������id',
                  					sortable : true
                              },{
                    					name : 'proType',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'productCode',
                  					display : '���ϱ���',
                  					sortable : true
                              },{
                    					name : 'productName',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'pattern',
                  					display : '����ͺ�',
                  					sortable : true
                              },{
                    					name : 'unitName',
                  					display : '��λ',
                  					sortable : true
                              },{
                    					name : 'parentProductID',
                  					display : '��߽ڵ�����ID',
                  					sortable : true
                              },{
                    					name : 'parentProductName',
                  					display : '��߽ڵ���������',
                  					sortable : true
                              },{
                    					name : 'parentProductCode',
                  					display : '��߽ڵ����ϱ���',
                  					sortable : true
                              },{
                    					name : 'parentName',
                  					display : '���ڵ�����',
                  					sortable : true
                              },{
                    					name : 'parentId',
                  					display : '���ڵ�id',
                  					sortable : true
                              },{
                    					name : 'lft',
                  					display : '�ڵ���ֵ',
                  					sortable : true
                              },{
                    					name : 'rgt',
                  					display : '�ڵ���ֵ',
                  					sortable : true
                              },{
                    					name : 'isLeaf',
                  					display : '�Ƿ�Ҷ�ӽڵ�',
                  					sortable : true
                              },{
                    					name : 'materialNum',
                  					display : '�嵥����',
                  					sortable : true
                              },{
                    					name : 'parentMaterialNum',
                  					display : '���ڵ��嵥����',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '��ע',
                  					sortable : true
                              },{
                    					name : 'createName',
                  					display : '������',
                  					sortable : true
                              },{
                    					name : 'createId',
                  					display : '������id',
                  					sortable : true
                              },{
                    					name : 'createTime',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'updateName',
                  					display : '�޸���',
                  					sortable : true
                              },{
                    					name : 'updateId',
                  					display : '�޸���id',
                  					sortable : true
                              },{
                    					name : 'updateTime',
                  					display : '�޸�����',
                  					sortable : true
                              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=stock_material_NULL&action=pageItemJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'XXX',
						display : '�ӱ��ֶ�'
					}]
		},

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "�����ֶ�",
					name : 'XXX'
				}]
 		});
 });