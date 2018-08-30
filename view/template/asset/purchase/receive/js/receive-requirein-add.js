$(function() {
	/**
	 * ��֤��Ϣ
	 */
	validate({
		"salvage" : {
			required : true
		},
		"limitYears" : {
			custom : ['date']
		},
		"result" : {
			required : true
		}
	});
	
	$("#receiveTable").yxeditgrid({
		objName : 'receive[receiveItem]',
		isAdd : false,
		url : '?model=asset_require_requireinitem&action=getReceiveDetail',
		param : {
			"requireId" : $("#requireinId").val()
		},
		event : {
			reloadData : function(e, g, data) {
				if(data.length == undefined){
					alert("������������Ҫ���յ�����")
					self.parent.tb_remove();
				}
			}
		},
		title : '�����嵥',
		colModel : [{
			display : 'requireinItemId',
			name : 'requireinItemId',
			process:function($input,row){
				$input.val(row.id);
			},
			type : 'hidden'
		}, {
			display : '����id',
			name : 'assetId',
			process:function($input,row){
				$input.val(row.productId);
			},
			type : 'hidden'
		}, {
			display : '��������',
			name : 'assetName',
			process:function($input,row){
				$input.val(row.productName);
			},
			tclass : 'readOnlyTxtItem',
			readonly : true
		}, {
			display : '���ϱ��',
			name : 'assetCode',
			process:function($input,row){
				$input.val(row.productCode);
			},
			tclass : 'readOnlyTxtItem',
			readonly : true
		}, {
			display : '���',
			name : 'spec',
			tclass : 'readOnlyTxtItem',
			readonly : true
		}, {
			display : 'Ʒ��',
			name : 'brand',
			tclass : 'readOnlyTxtItem',
			readonly : true
		}, {
			display : '�����������',
			name : 'shouldReceiveNum',
			type : 'hidden'
		}, {
			display : '����',
			name : 'checkAmount',
			process:function($input,row){
				$input.val(row.shouldReceiveNum)
			},
			tclass : 'txtshort',
			event : {
				blur : function() {
					var rownum = $(this).data('rowNum');// �ڼ���
					var grid = $(this).data('grid');// ������
					//��֤�����Ƿ�Ϸ�
					var maxNum = grid.getCmpByRowAndCol(rownum, 'shouldReceiveNum').val();
					var checkAmount = $(this).val();
					if(checkAmount != "" && !isNum(checkAmount)){
						alert("��������������");
						$(this).val(maxNum).focus();
						return false;
					}else if(accSub(checkAmount,maxNum) > 0){
						alert("�����������ܴ��ڡ�" + maxNum + "��");
						$(this).val(maxNum).focus();
						return false;
					}
				}
			},
			validation : {
				custom : ['onlyNumber']
			}
		}, {
			display : '����',
			name : 'productPrice',
			type : 'hidden'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}]
	});
	// ѡ����Ա���
	$("#salvage").yxselect_user({
		hiddenId : 'salvageId',
		isGetDept : [true, "deptId", "deptName"],
		event : {
			select : function(e, returnValue) {
				if (returnValue) {
					$('#company').val(returnValue.companyCode)
					$('#companyName').val(returnValue.companyName)
				}
			}
		}
	});
});

//�ύȷ��
function confirmAudit() {
	if($("#receiveTable").yxeditgrid('getCurShowRowNum') == 0){
		alert("�����嵥����Ϊ��");
		return false;
	}
	if (confirm("��ȷ��Ҫ�ύ���յ���?")) {
		$("#form1").attr("action","?model=asset_purchase_receive_receive&action=addByRequirein");
		$("#form1").submit();
	} else {
		return false;
	}
}