var show_page = function(page) {
	$("#recordGrid").yxgrid("reload");
};
$(function() {
	$("#recordGrid").yxgrid({
		model : 'projectmanagent_track_track',
		title : '����������¼',
		param : {
//			"trackName" : $('#trackName').val(),
			"cluesId" : $('#cluesId').val()
		},
		showcheckbox : false, // �Ƿ���ʾcheckbox
		/**
		 * �Ƿ���ʾ������
		 *
		 * @type Boolean
		 */
		isToolBar : false,
		/**
		 * �Ƿ���ʾ�޸İ�ť/�˵�
		 *
		 * @type Boolean
		 */
		isEditAction : false,
		/**
		 * �Ƿ���ʾɾ����ť/�˵�
		 *
		 * @type Boolean
		 */
		isDelAction : false,
		/**
		 * �Ƿ���ʾ��Ӱ�ť/�˵�
		 *
		 * @type Boolean
		 */
		isAddAction : false,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'trackName',
			display : '����������',
			sortable : true
		}, {
			name : 'trackDate',
			display : '��������',
			sortable : true
		}, {
			name : 'trackType',
			display : '��������',
			sortable : true,
			datacode : 'GZLX'
		}, {
			name : 'linkmanName',
			display : '��ϵ������',
			sortable : true
		}, {
			name : 'trackPurpose',
			display : '����Ŀ��',
			sortable : true,
			width : 150
		}, {
			name : 'customerFocus',
			display : '�ͻ���ע��',
			sortable : true,
			width : 150
		}, {
			name : 'result',
			display : '�Ӵ����',
			sortable : true,
			width : 150
		}],

		menusEx : [

          	{
			text : '�鿴',
			icon : 'view',
			action: function(row){
               parent.location="?model=projectmanagent_track_track&action=init&id="
						+ row.id
                        + '&perm=view'

			}
		   }],
		toAddConfig : {
			action : 'toCluesTrack',
			plusUrl : '&id=' + $('#cluesId').val()
		}
	});
});