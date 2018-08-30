/**
 * �����ڲ��Ƽ����
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_recommend', {
		options : {
			hiddenId : 'recommendId',
			nameCol : 'formCode',
			width : 500,
			isFocusoutCheck : true,
			gridOptions : {
				showcheckbox : false,
				model : 'hr_recruitment_recommend',
				action : 'pageJsonSelect',
				param : {
					noInterviewId : true,
					state : 2
				},
				//����Ϣ
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				},{
					name : 'formCode',
					display : '���ݱ��',
					width : 120,
					sortable : true,
					process : function(v, row) {
						return '<a href="javascript:void(0)" title="����鿴" onclick="javascript:showOpenWin(\'?model=hr_recruitment_recommend&action=toView&id='
							+ row.id
							+ '\',1)">'
							+ "<font color = '#4169E1'>"
							+ v
							+ "</font>"
							+ '</a>';
					}
				},{
					name : 'isRecommendName',
					display : '������',
					width : 90,
					sortable : true
				},{
					name : 'positionName',
					display : '�Ƽ�ְλ',
					width : 100,
					sortable : true
				},{
					name : 'recommendName',
					display : '�Ƽ���',
					width : 90,
					sortable : true
				}],

				// ��������
				searchitems : [{
					display : '������',
					name : 'isRecommendName'
				},{
					display : '�Ƽ���',
					name : 'recommendName'
				}],

				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "DESC"
			}
		}
	});
})(jQuery);