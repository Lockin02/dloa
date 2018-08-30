
// �鿴Ա������
function viewPersonnel(id, userNo, userAccount) {
	var skey = "";
	$.ajax({
				type : "POST",
				url : "?model=hr_personnel_personnel&action=md5RowAjax",
				data : {
					"id" : id
				},
				async : false,
				success : function(data) {
					skey = data;
				}
			});
	showModalWin("?model=hr_personnel_personnel&action=toTabView&id=" + id
					+ "&userNo=" + userNo + "&userAccount=" + userAccount
					+ "&skey=" + skey, 1);
}
$(document).ready(function() {
	var inventoryId = $("#inventoryId").val();
	var templateId = $("#templateId").val();

	// ��ȡ��ǰģ����Ҫ�õ�����������ѡ��

	// ����ģ��id��ȡ���Ե�ֵ
	var attrvals = $.ajax({
				url : "?model=hr_inventory_attrval&action=getDetailsByTemplateId",
				data : {
					templateId : templateId
				},
				async : false
			}).responseText;
	attrvals = eval("(" + attrvals + ")");
	var attrvalObj = {};
	for (var i = 0; i < attrvals.length; i++) {
		var attr = attrvals[i];
		var o = attrvalObj["attr" + attr.attrId];
		var item = {
			name : attr.valName,
			value : attr.valName
		};
		if (o) {
			o.push(item)
		} else {
			attrvalObj["attr" + attr.attrId] = [item];
		}

	}

	// ����ģ��id��ȡ����
	var attrs = $.ajax({
				url : "?model=hr_inventory_templateattr&action=listJson",
				data : {
					templateId : templateId
				},
				async : false
			}).responseText;
	attrs = eval("(" + attrs + ")");
	var colModelArr = [{
				display : 'id',
				name : 'id',
				type : 'hidden'
			}, {
				display : 'personId',
				name : 'personId',
				type : 'hidden'
			}, {
				display : 'companyId',
				name : 'companyId',
				type : 'hidden'
			}, {
				display : 'deptId',
				name : 'deptId',
				type : 'hidden'
			}, {
				display : 'deptCode',
				name : 'deptCode',
				type : 'hidden'
			}, {
				display : 'deptName',
				name : 'deptName',
				type : 'hidden'
			}, {
				display : 'deptCode',
				name : 'deptCode',
				type : 'hidden'
			}, {
				display : 'deptCodeS',
				name : 'deptCodeS',
				type : 'hidden'
			}, {
				display : 'deptCodeT',
				name : 'deptCodeT',
				type : 'hidden'
			}, {
				display : 'deptIdS',
				name : 'deptIdS',
				type : 'hidden'
			}, {
				display : 'deptIdT',
				name : 'deptIdT',
				type : 'hidden'
			}, {
				display : 'jobId',
				name : 'jobId',
				type : 'hidden'
			}, {
				display : 'deptId',
				name : 'deptId',
				type : 'hidden'
			}, {
				display : 'deptCode',
				name : 'deptCode',
				type : 'hidden'
			}, {
				display : 'deptName',
				name : 'deptName',
				type : 'hidden'
			}, {
				display : 'staffName',
				name : 'staffName',
				type : 'hidden'
			}, {
				display : 'userAccount',
				name : 'userAccount',
				type : 'hidden'
			}, {
				name : 'userNo',
				display : 'Ա�����',
				type : 'statictext',
				noChangeLine : true,
				isSubmit : true,
				process : function(v, row) {
					return "<a href='#' title='����鿴Ա����Ϣ' onclick='viewPersonnel(\""
							+ row.personId
							+ "\",\""
							+ row.userNo
							+ "\",\""
							+ row.userAccount + "\")' >" + v + "</a>";
				}
			}, {
				name : 'userName',
				display : '����',
				type : 'statictext',
				noChangeLine : true,
				isSubmit : true,
				process : function(v, row) {
					return "<a href='#' title='����鿴Ա����Ϣ' onclick='viewPersonnel(\""
							+ row.personId
							+ "\",\""
							+ row.userNo
							+ "\",\""
							+ row.userAccount + "\")' >" + v + "</a>";
				}
			}, {
				name : 'sex',
				display : '�Ա�',
				type : 'statictext',
				isSubmit : true,
				width : 60
			}, {
				name : 'companyName',
				display : '��˾',
				type : 'statictext',
				noChangeLine : true,
				isSubmit : true
			}, {
				name : 'deptNameS',
				display : '��������',
				type : 'statictext',
				noChangeLine : true,
				isSubmit : true
			}, {
				name : 'deptNameT',
				display : '��������',
				type : 'statictext',
				noChangeLine : true,
				isSubmit : true
			}, {
				name : 'jobName',
				display : 'ְλ',
				type : 'statictext',
				noChangeLine : true,
				isSubmit : true
			}, {
				display : '��ְ����',
				name : 'entryDate',
				type : 'statictext',
				noChangeLine : true,
				isSubmit : true
			}, {
				display : '�̵�����',
				name : 'inventoryDate',
				type : 'statictext',
				noChangeLine : true,
				isSubmit : true
			}];
	var attrIds = [];
	var attrNames = [];

	for (var i = 0; i < attrs.length; i++) {
		var col = attrs[i];
		var type = "textarea";
		var options = [];
		attrIds.push(col.attrId);
		attrNames.push(col.attrName);
		if (col.attrType == 1) {
			type = "select";
			options = attrvalObj["attr" + col.attrId];
		}
		if ($("#perm").val()) {
			type = "statictext";
		}
		colModelArr.push({
					display : col.attrName,
					name : "attr_" + col.attrId,
					type : type,
					options : options,
					cols : 30,
					rows : 3,
					isSubmit : true
				});
	}
	var feedbackType = "statictext";
	if ($("#perm").val()) {
		feedbackType = "textarea";
	}
	colModelArr.push({
				display : '<font color="red">���ʷ�����Ϣ</font>',
				name : 'feedback',
				type : feedbackType,
				cols : 30,
				rows : 3
			});
	$("#attrIds").val(attrIds.toString());
	$("#attrNames").val(attrNames.toString());
	var param = {
		dir : 'ASC',
		templateId : templateId
	};
	if (inventoryId) {
		param.inventoryId = inventoryId;
	}

	if ($("#stageId").val() && $("#viewAll").val()) {
		param.stageId = $("#stageId").val();
	}
	var gridparam = {
		objName : 'inventory[details]',
		url : '?model=hr_inventory_inventorydetail&action=getDetails',
		isAddAndDel : false,
		param : param,
		colModel : colModelArr
	};

	$("#detailGrid").yxeditgrid(gridparam);

	/**
	 * ��֤��Ϣ
	 */
	validate({
				"inventoryName" : {
					required : true
				}
			});

	// ���淴����Ϣ
	$("#saveBt").click(function() {
		var g = $("#detailGrid").data("yxeditgrid");
		var $feekback = g.getCmpByCol("feedback");

		var arr = {};
		$feekback.each(function(i) {
					var id = g.getCmpByRowAndCol(i, "id").val();
					arr[id] = $(this).val();
				});
		$.ajax({
					url : "?model=hr_inventory_inventorydetail&action=saveFeedback",
					type : "POST",
					data : {
						"feedbacks" : arr
					},
					success : function(data) {
						alert("����ɹ�.")
					}
				});
	});

})