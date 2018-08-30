/**
 * Ԥ����Ŀ����combogrid
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_budget', {
		options : {
			hiddenId : 'id',
			nameCol : 'budgetName',
			gridOptions : {
				model : 'engineering_baseinfo_budget',
				// ��
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							display : 'Ԥ�����',
							name : 'budgetCode',
							sortable : true,
							width : 80
						}, {
							display : 'Ԥ������',
							name : 'budgetName',
							sortable : true
						}, {
							display : '�ϼ�����',
							name : 'parentName',
							sortable : true
						}, {
							display : '���ҵ�λ',
							name : 'currencyUnit',
							sortable : true,
							width : 50,
				            hide:true
						}, {
							display : '��������',
							name : 'budgetType',
							datacode : 'FYLX',
							sortable : true,
							width : 80
						},{
				            name : 'subjectName',
				            display : '��Ŀ����',
				            sortable : true,
				            hide:true
				        },{
				            name : 'subjectCode',
				            display : '��Ŀ����',
				            sortable : true,
				            hide:true
				        } ,{
				            name : 'remark',
				            display : '��ע',
				            sortable : true,
							width : 120
				        }],

				/**
				 * ��������
				 */
				searchitems : [{
							display : 'Ԥ�����',
							name : 'budgetCode'
						}],
				pageSize : 10,
				sortorder : "ASC",
				sortname : "budgetCode",
				title : 'Ԥ����Ŀ'
			}
		}
	});
})(jQuery);