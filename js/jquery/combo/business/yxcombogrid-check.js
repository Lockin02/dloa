/**
 * �̵����ݸ����
 */

(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_check', {
		options : {
			hiddenId : 'id',

			nameCol : 'taskNo',

			gridOptions : {
				model : 'asset_checktask_check',

					// ��
				  colModel : [
					            {
					                display : 'id',
					                name : 'id',
					                sortable : true,
					                hide : true
					            }, {
					                display : '����id',
					                name : 'taskId',
					                sortable : true,
					                hide : true
					            },
					            {
					                display : '������',
					                name : 'taskNo',
					                sortable : true
					            },
					            {
					                display : '��ʼʱ��',
					                name : 'beginDate',
					                sortable : true
					            },
					            {
					                display : '����ʱ��',
					                name : 'endDate',
					                sortable : true
					            },
					            {
					                display : '�̵㲿��id',
					                name : 'deptId',
					                sortable : true,
					                hide:true
					            },
					            {
					                display : '�̵㲿��',
					                name : 'dept',
					                sortable : true
					            },{
								   display:'�̵���id',
								   name : 'manId',
					               sortable : true,
					                hide:true

								},{
								   display:'�̵���',
								   name : 'man',
					               sortable : true

								},
					            {
					                display : '��ע',
					                name : 'remark',
					                sortable : true
					            }],




							// ��������
							searchitems : [{
								display : '������',
								name : 'taskNo'
							}, {
								display : '��ʼʱ��',
								name : 'beginDate'
							}, {
								display : '�̵㲿��',
								name : 'dept'
							}],
							sortorder : "ASC"


			}
		}
	});
})(jQuery);