//������黹��js
$(function() {
	$("#borrowTable").yxeditgrid({
		objName : 'return[item]',
		url : '?model=asset_assetcard_assetcard&action=listJson',
		param : {
			id : $("#assetId").val()
		},
		colModel : [{
			display : '��Ƭ���',
			name : 'assetCode',
			validation : {
				required : true
			},
			readonly : true
		}, {
			display : '�ʲ�����',
			name : 'assetName',
			validation : {
				required : true
			},
			readonly : true
		}, {
			display : '�ʲ�Id',
			name : 'assetId',
			process : function($input,row){
				var assetId = row.id;
				$input.val(assetId);
			},
			type : 'txtshort',
			type : 'hidden'
		}, {
			display : '����ͺ�',
			name : 'spec',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '��������',
			name : 'buyDate',
			// type : 'date',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : 'Ԥ��ʹ���ڼ���',
			name : 'estimateDay',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '�Ѿ�ʹ���ڼ���',
			name : 'alreadyDay',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : 'ʣ��ʹ������',
			name : 'residueYears',
			tclass : 'txtshort',
			process : function($input,row){
				var residueYears = row.estimateDay*1-row.alreadyDay*1;
				$input.val(residueYears);
			},
			readonly : true
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}]
	});
	$("#borrowTable").yxeditgrid("hideAddBn");
	// ѡ����Ա���
	$("#returnMan").yxselect_user({
		hiddenId : 'returnManId',
		isGetDept : [true, "deptId", "deptName"]
	});

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"chargeMan" : {
			required : true
		},
		"chargeDate" : {
			required : true,
			custom : ['date']
		}
	});

});

/*
 * ���ȷ��
 */
function confirmAudit() {
	if (confirm("��ȷ��Ҫ�ύ�����?")) {
		$("#form1").attr("action",
				"?model=asset_daily_return&action=add&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}