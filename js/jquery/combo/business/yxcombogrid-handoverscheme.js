/**
 * ��ϵ�˱�����
 */

(function($) {

	$.woo.yxcombogrid.subclass('woo.yxcombogrid_handoverscheme', {
				options : {
					hiddenId : 'id',
					nameCol : 'schemeName',
					title : '��ְ�嵥����',
					showcheckbox : false,
					gridOptions : {
						model : 'hr_leave_handoverScheme&action=page',

						// ��
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'formCode',
									display : '���ݱ���',
									sortable : true,
									hide : true
								}, {
									name : 'formDate',
									display : '��������',
									sortable : true,
									hide : true
								}, {
									name : 'schemeCode',
									display : '�������',
									sortable : true,
									hide : true
								}, {
									name : 'schemeTypeCode',
									display : '��������Code',
									sortable : true,
									hide : true
								}, {
									name : 'schemeTypeName',
									display : '������������',
									sortable : true,
									hide : true
								}, {
									name : 'schemeName',
									display : '��������',
									sortable : true
								}, {
									name : 'jobName',
									display : 'ְλ����',
									sortable : true
								}, {
									name : 'companyName',
									display : '���ƣ���˾��',
									sortable : true
								}, {
									name : 'companyId',
									display : '����id',
									sortable : true,
									hide : true
								}, {
									name : 'leaveTypeCode',
									display : '��ְ����',
									sortable : true,
									hide : true
								}, {
									name : 'leaveTypeName',
									display : '��ְ����',
									sortable : true
								}, {
									name : 'state',
									display : '״̬',
									sortable : true,
									hide : true
								}, {
									name : 'remark',
									display : '��ע',
									width : 250,
									sortable : true
								}, {
									name : 'ExaStatus',
									display : '���״̬',
									sortable : true,
									hide : true
								}, {
									name : 'ExaDT',
									display : '�������',
									sortable : true,
									hide : true
								}],

						/**
						 * ��������
						 */
						searchitems : [{
									display : "��������",
									name : 'schemeName'
								}],
						sortname : "id",
						// Ĭ������˳��
						sortorder : "ASC"
					}
				}
			});
})(jQuery);