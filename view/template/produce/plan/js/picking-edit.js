$(document).ready(function() {

	var productObj = $("#productItem")
	productObj.yxeditgrid({
		objName : 'picking[item]',
		url : '?model=produce_plan_pickingitem&action=listJson',
		param : {
			pickingId : $("#id").val(),
			taskId : $("#taskId").val(),
			planId : $("#planId").val(),
			type : 'edit',
			dir : 'ASC'
		},
		isAdd : false,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '���ϱ���',
			name : 'productCode',
			process : function ($input) {
				var rowNum = $input.data("rowNum");
				$input.yxcombogrid_product({
					hiddenId : 'productItem_cmp_productId' + rowNum,
					gridOptions : {
						showcheckbox : false,
						event : {
							row_dblclick : function(e ,row ,data) {
								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productCode").val(data.productCode);
								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productName").val(data.productName);
								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"pattern").val(data.pattern);
								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"unitName").val(data.unitName);

								//�������������Ϊ�˷�ֹ�����첽��ȡǰ����Ŀ��ʾ����
								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"JSBC").html('');
								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"KCSP").html('');
								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"SCC").html('');
								//��ȡ���豸�֡������Ʒ�ֺ�����������
								$.ajax({
									type : 'POST',
									url : "?model=produce_plan_picking&action=getProductNum",
									data : {
										productCode : data.productCode
									},
									success : function (result) {
										var obj = eval("(" + result + ")");
										productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"JSBC").html(obj.JSBC);
										productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"KCSP").html(obj.KCSP);
										productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"SCC").html(obj.SCC);
									}
								});
							}
						}
					}
				});
			}
		},{
			display : '��������',
			name : 'productName',
			tclass : 'readOnlyText',
			width : '25%',
			readonly : true
		},{
			display : '����ͺ�',
			name : 'pattern',
			tclass : 'readOnlyTxtMiddle',
			width : '10%',
			readonly : true
		},{
			display : '��λ',
			name : 'unitName',
			tclass : 'readOnlyTxtShort',
			width : '8%',
			readonly : true
		},{
			display : '���豸��',
			name : 'JSBC',
			type : 'statictext',
			width : '5%'
		},{
			display : '�����Ʒ',
			name : 'KCSP',
			type : 'statictext',
			width : '5%'
		},{
			display : '������',
			name : 'SCC',
			type : 'statictext',
			width : '5%'
		},{
			display : '��������',
			name : 'applyNum',
			validation : {
				custom : ['onlyNumber']
			},
			event : {
				blur : function(e){
					var rownum = $(this).data('rowNum');// �ڼ���
					var grid = $(this).data('grid');// ������

					var maxNum = grid.getCmpByRowAndCol(rownum, 'maxNum').val();

					if($(this).val() *1 <= 0){
						alert("���������������0");
						$(this).val(maxNum);
					}

					if($(this).val() *1 > maxNum *1){
						alert("�����������ܴ���" + maxNum);
						$(this).val(maxNum);
					}
				}
			},
			width : '5%'
		},{
			display : '�������',
			name : 'maxNum',
			type : 'hidden'
		},{
			display : '�ƻ���������',
			name : 'planDate',
			width : '10%',
			type : 'date',
			readonly : true,
			validation : {
				required : true
			}
		},{
			display : '��ע',
			name : 'remark',
			type : 'textarea',
			rows : 2,
			width : '20%'
		}]
	});
});

//ֱ���ύ
function toSubmit(){
	document.getElementById('form1').action="?model=produce_plan_picking&action=edit&actType=audit";
}

//�ύʱ��֤
function checkForm(){
	if($("#productItem").yxeditgrid('getCurShowRowNum') === 0){
		alert("�������ϲ���Ϊ��");
		return false;
	}
}