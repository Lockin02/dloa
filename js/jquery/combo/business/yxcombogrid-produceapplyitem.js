/**
 * �����������뵥��Ʒ������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_produceapplyitem', {
			options : {
				hiddenId : 'materialId',
				nameCol : 'materialName',
				gridOptions : {
				showcheckbox : false,
				model : 'quality_apply_produceapplyitem',
				pageSize : 10,
				// ����Ϣ
				colModel : [{
							name : 'id',
							header : 'id',
							hide : true
						},{
							display : '��Ʒid',
							name : 'materialId',
							width : 180,
							hide : true
						},{
							display : '��Ʒ����',
							name : 'materialName',
							width : 140
						}, {
							display : '��Ʒ���',
							name : 'materialCode',
							width : 100
						}, {
							display : '����ͺ�',
							name : 'pattern'
						}, {
							display : '����',
							name : 'batchNum',
							hide : true
						}, {
							display : '��������',
							name : 'storageNum'
						}, {
							display : '�ۼƼ�������',
							name : 'subCheckNum'
						}],
				// ��������
				searchitems : [{
							display : '��Ʒ����',
							name : 'materialName'
						}],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);