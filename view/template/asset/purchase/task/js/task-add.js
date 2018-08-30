$(function() {
	$("#taskTable").yxeditgrid({
		objName : 'task[taskItem]',
		url : '?model=asset_purchase_task_taskItem&action=getApplyItemPage',
		param : {
			"applyId" : $("#applyId").val()
		},
		isAdd : false,
		colModel : [{
			display : '�ɹ�����id',
			name : 'applyId',
			type : 'hidden'
		}, {
			display : '�ɹ��������',
			name : 'applyCode',
			type : 'hidden'
		}, {
			display : '�ɹ�������ϸ��id',
			name : 'applyEquId',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'productName',
			process : function($input,row){
				if(row.productName==''){
					$input.val( row.inputProductName )
				}else{
					$input.val( row.productName )
				}
			},
			tclass : 'readOnlyTxtItem'
		}, {
			display : '���',
			name : 'pattem',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '��λ',
			name : 'unitName',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '��Ӧ��',
			name : 'supplierName',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '�ɹ�����',
			name : 'purchAmount',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '�´�����',
			name : 'issuedAmount',
			type : 'hidden',
			process:function($input,row){
				$input.val(row.purchAmount-row.issuedAmount);
			}
		}, {
			display : '��������',
			name : 'taskAmount',
			tclass : 'txtshort',
			process:function($input,row){
				$input.val(row.purchAmount-row.issuedAmount);
			},
			event : {
				blur : function(e) {
					var rownum = $(this).data('rowNum');// �ڼ���
					var colnum = $(this).data('colNum');// �ڼ���
					var grid = $(this).data('grid');// ������
//					var purchAmount = grid.getCmpByRowAndCol(rownum,'purchAmount').val();
					var issuedAmount = grid.getCmpByRowAndCol(rownum, 'issuedAmount').val();
					var taskAmount = $(this).val();
					taskAmount = parseFloat(taskAmount);
					issuedAmount = parseFloat(issuedAmount);
					if (taskAmount > issuedAmount) {
						alert("�����������ܳ����´�����"+issuedAmount+" !");
						$(this).val(issuedAmount);
//						$issuedAmount.val(purchAmount);
					}
					var price = grid.getCmpByRowAndCol(rownum, 'price').val();
					var $moneyAll = grid.getCmpByRowAndCol(rownum, 'moneyAll');
					var taskAmount = $(this).val();
					$moneyAll.val(accMul(price,taskAmount));

					var $moneyAllv = $("#"+$moneyAll.attr('id')+'_v');
					$moneyAllv.val(moneyFormat2(accMul(price,taskAmount)));
//					$issuedAmount.val(taskAmount);
				}
			},
			validation : {
				custom : ['onlyNumber']
			}
		}, {
			display : '����',
			name : 'price',
			tclass : 'readOnlyTxtItem',
			type : 'money'
		}, {
			display : '���',
			name : 'moneyAll',
			tclass : 'readOnlyTxtItem',
			type : 'money'
			,
			process:function($input,row){
//				alert((row.purchAmount-row.issuedAmount)*row.price);
				$input.val((row.purchAmount-row.issuedAmount)*row.price);
			}
		}, {
			display : 'ϣ����������',
			name : 'dateHope',
			type : 'date',
			tclass : 'txtshort datehope'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}]
	});
	// ѡ����Ա���
	$("#purcherName").yxselect_user({
		hiddenId : 'purcherId'
	});

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"purcherName" : {
			required : true
		},
		"acceptDate" : {
			custom : ['date']
		},
		"arrivaDate" : {
			custom : ['date']
		}
	});

	//��������
	$("#arrivaDate").bind("focusout",function(){
		var dateHope=$(this).val();
		$.each($(':input[class^="txtshort datehope"]'),function(i,n){
			$(this).val(dateHope);
		})
	});

});
