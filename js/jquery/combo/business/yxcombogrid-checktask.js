/**
 * �̵��������
 */

(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_checktask', {
		options : {
			hiddenId : 'id',

			nameCol : 'billNo',

			gridOptions : {
				model : 'asset_checktask_checktask',

					// ��
				 colModel : [
					            {
					                display : 'id',
					                name : 'id',
					                sortable : true,
					                hide : true
					            },
					            {
					                display : '������',
					                name : 'billNo',
					                sortable : true
					            },
					            {
					                display : '�̵�ʱ��',
					                name : 'checkDate',
					                sortable : true
					            },
					            {
					                display : 'Ԥ���̵����ʱ��',
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
					                name : 'deptName',
					                sortable : true
					            },{
								   display:'������id',
								   name : 'initiatorId',
					               sortable : true,
					                hide:true

								},{
								   display:'������',
								   name : 'initiator',
					               sortable : true

								},{
								    display:'������id',
									name : 'participantId',
					                sortable : true,
					                hide:true

								},{
								    display:'������',
									name : 'participant',
					                sortable : true

								},
					            {
					                display : '����˵��',
					                name : 'remark',
					                sortable : true
					            }],

				// ��������
							searchitems : [{
								display : '������',
								name : 'billNo'
							}, {
								display : '�̵�ʱ��',
								name : 'checkDate'
							}, {
								display : '�̵㲿��',
								name : 'deptName'
							}],
							sortorder : "ASC"
			}
		}
	});
})(jQuery);