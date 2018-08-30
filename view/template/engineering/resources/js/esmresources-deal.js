$(document).ready(function() {

	$("#esmresourcesTable").yxeditgrid({
		objName : 'esmresources',
		url : '?model=engineering_resources_esmresources&action=listJson',
		param : {
			'ids' : $("#ids").val()
		},
		// type:'edit',
		isAddAndDel : false,
		param : {
			projectId : $("#projectId").val(),
			ids : $("#ids").val()
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden',
			readonly : true
		},{
			display : '��Դ����',
			name : 'resourceName',
			readonly : true,
			tclass : 'readOnlyTxtMiddle'
		}, {
			display : '����',
			name : 'number',
			readonly : true,
			tclass : 'readOnlyTxtShort'
		}, {
			display : '��λ',
			name : 'unit',
			readonly : true,
			tclass : 'readOnlyTxtShort'
		}, {
			display : '����ʼ����',
			name : 'planBeginDate',
			readonly : true,
			tclass : 'readOnlyTxtItem'
		}, {
			display : '�����������',
			name : 'planEndDate',
			readonly : true,
			tclass : 'readOnlyTxtItem'
		}, {
			display : 'ʹ������',
			name : 'useDays',
			readonly : true,
			tclass : 'readOnlyTxtShort'
		}, {
			display : '������',
			name : 'dealStatus',
			type : 'select',
			datacode : 'GCZYCLZT'
		}, {
			display : '������',
			name : 'dealResult',
			tclass : 'txt'
		}]
	})

	/**
	 * ��֤��Ϣ(�õ��ӱ���֤ǰ��������ʹ��validate)
	 */
	validate({

	});
});
