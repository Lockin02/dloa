(function($) {
	$.woo.yxsubgrid.subclass('woo.yxgrid_changeLog', {
				options : {
					model : 'common_changeLog',
					showcheckbox : false,
					isToolBar : false,
					/**
					 * �Ƿ���ʾ�Ҽ��˵������Ϊflase�����Ҽ��˵�ʧЧ
					 *
					 * @type Boolean
					 */
					isRightMenu : false,
					// ��
					colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
							}, {
								display : '�����',
								name : 'changeManName',
								width : 200,
								process : function(v, row) {
									if (row['tempId'] == $("#originalId").val()) {
										return "<font color='red'>" + v
												+ "</font>";
									}
									return v;
								}
							}, {
								display : '���ʱ��',
								name : 'changeTime',
								width : 200,
								process : function(v, row) {
									if (row['tempId'] == $("#originalId").val()) {
										return "<font color='red'>" + v
												+ "</font>";
									}
									return v;
								}
							}, {
								display : '���ԭ��',
								name : 'changeReason',
								width : 300
							}, {
								display : '����״̬',
								name : 'ExaStatus',
								process : function(v,row){
									if(row.tempId=='0'){
									   return "��������";
									}else{
									   return v;
									}
								}
							}, {
								display : '����ʱ��',
								name : 'ExaDT'
							}, {
								display : 'tempId',
								name : 'tempId',
								hide : true
							}],
					subGridOptions : {
						url : '?model=common_changeLog&action=pageJsonDetailMerge',// ��ȡ�ӱ�����url
						// ��ʾ����
						colModel : [{
									name : 'detailTypeCn',
									width:'80',
									display : '��������'
								},
								/**
								 * { name : 'changeField', width : 150, display :
								 * '����ֶ�' },
								 */
								{
									name : 'detailId',
									width : 30,
									display : '��־',
									process : function(v) {
										if (v != 0) {
											return v;
										}
										return "";
									}
								}, {
									name : 'objField',
									width : 150,
									display : '��������'
								}, {
									name : 'changeFieldCn',
									width : 150,
									display : '�������'
								}, {
									name : 'oldValue',
									width : 150,
									display : '���ǰֵ'
								}, {
									name : 'newValue',
									width : 150,
									display : '�����ֵ'
								}]
					},
					sortorder : "DESC",
					sortname : "id",
					title : '�����Ϣ'
				}
			});
})(jQuery);