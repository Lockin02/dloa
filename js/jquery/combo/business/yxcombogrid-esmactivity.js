/**
 * Ԥ����Ŀ����combogrid
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_esmactivity', {
		options : {
			hiddenId : 'id',
			nameCol : 'activityName',
			gridOptions : {
				model : 'engineering_activity_esmactivity',
				action : 'pageJsonOrg',
				// ��
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							display : '��������',
							name : 'activityName',
							sortable : true
						}, {
							display : 'Ԥ�ƿ�ʼ',
							name : 'planBeginDate',
							sortable : true,
							width : 80
						}, {
							display : 'Ԥ�ƽ���',
							name : 'planEndDate',
							sortable : true,
							width : 80
						}, {
							display : 'Ԥ�ƹ���',
							name : 'days',
							sortable : true,
							width : 70
						}, {
							display : '������',
							name : 'workload',
							sortable : true,
							process : function(v,row){
								return v + " " + row.workloadUnitName;
							},
							width : 80
						}, {
							display : '�������',
							name : 'process',
							sortable : true,
							process : function(v,row){
								return v + " %";
							},
							width : 80
						}, {
							display : 'ʵ�ʿ�ʼ',
							name : 'actBeginDate',
							sortable : true,
							width : 80,
							hide : true
						}, {
							display : 'ʵ�ʽ���',
							name : 'actEndDate',
							sortable : true,
							width : 80,
							hide : true
						}, {
				            name : 'remark',
				            display : '��ע',
				            sortable : true,
							width : 150,
							hide : true
				        }],

				/**
				 * ��������
				 */
				searchitems : [{
					display : '��������',
					name : 'activityName'
				}],
				pageSize : 10,
				sortorder : "ASC",
				sortname : "id",
				title : '��Ŀ����'
			}
		}
	});
})(jQuery);
