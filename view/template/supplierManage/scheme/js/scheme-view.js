$(function() {
	$("#schemeTable").yxeditgrid({
		objName : 'scheme[schemeItem]',
		delTagName : 'isDelTag',
		type : 'view',
		url : '?model=supplierManage_scheme_schemeItem&action=listJson',
		param : {
			parentId : $("#parentId").val()
		},
		colModel : [ {
            display : '��������',
            name : 'assesDept'
        },{
			display : '������Ŀ',
			name : 'assesProName',
			validation : {
				required : true
			}
		}, {
			display : '����ָ��',
			name : 'assesStandard',
			validation : {
				required : true
			}
		}, {
            display : '������',
            name : 'assesMan'
        },{
			display : 'ָ��Ȩ��',
			name : 'assesProportion',
			tclass : 'txtshort',
			validation : {
				custom : ['onlyNumber']
			}
		}, {
			display : '����˵��',
			name : 'assesExplain',
			tclass : 'txt'
		}]
	})

	// �ж��Ƿ���ʾ�رհ�ť
	// alert($("#showBtn").val());
	if ($("#showBtn").val() == 1) {
		$("#btn").hide();
		$("#hiddenF").hide();
	}
});