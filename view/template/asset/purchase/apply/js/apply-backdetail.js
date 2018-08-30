$(function() {
	$("#purchaseProductTable").yxeditgrid({
		objName : 'apply[applyItem]',
		url : '?model=asset_purchase_apply_applyItem&action=listJson',
		param : {
			applyId : $("#id").val(),
			"isDel" : '0'
		},
		isAddAndDel : false,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'inputProductName',
			tclass : 'txt'
		}, {
			display : '����id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'applyAmount',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : ' ���´�����',
			name : 'issuedAmount',
			tclass : 'readOnlyTxtShort',
			process:function($input,row){
				if(row.issuedAmount==""){
					$input.val(0);
				}
			},
			readonly : true
		}, {
			display : '�ɳ�������',
			name : 'backAmount',
			tclass : 'txtshort',
			process:function($input,row){
				if(row.issuedAmount==""){
					$input.val(row.applyAmount);
				}else{
					$input.val(row.applyAmount - row.issuedAmount);
				}
			},
			event : {
				blur : function(e) {
					var rownum = $(this).data('rowNum');// �ڼ���
					var grid = $(this).data('grid');// ������

					var applyAmount = grid.getCmpByRowAndCol(rownum, 'applyAmount').val();
					var issuedAmount = grid.getCmpByRowAndCol(rownum, 'issuedAmount').val();
					var canBackAmount = applyAmount - issuedAmount;

					if($(this).val() *1 < 0){
						alert("������������С��0��");
						$(this).val(canBackAmount);
					}

					if($(this).val() *1 > canBackAmount *1){
						alert("�����������ܴ����������� - ���´�������");
						$(this).val(canBackAmount);
					}
				}
			}
		}]
	})
});

// ���ݴӱ�Ľ�̬�����ܽ��
function checkform() {
	return confirm('ȷ�ϳ���������');
}
