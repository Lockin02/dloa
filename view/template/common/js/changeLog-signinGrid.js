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
								display : 'ǩ����',
								name : 'changeManName',
								width : 380,
								process : function(v, row) {
									if (row['tempId'] == $("#originalId").val()) {
										return "<font color='red'>" + v
												+ "</font>";
									}
									return v;
								}
							}, {
								display : 'ǩ��ʱ��',
								name : 'changeTime',
								width : 380,
								process : function(v, row) {
									if (row['tempId'] == $("#originalId").val()) {
										return "<font color='red'>" + v
												+ "</font>";
									}
									return v;
								}
							}, {
								display : 'tempId',
								name : 'tempId',
								hide : true
							}],
					subGridOptions : {
						url : '?model=common_changeLog&action=pageJsonDetail',// ��ȡ�ӱ�����url
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
					title : 'ǩ����Ϣ'
				}
			});
})(jQuery);