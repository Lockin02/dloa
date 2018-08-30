$(document).ready(function() {

	//�ӱ���
	var itemTableObj = $("#suppInfo");
	$("#suppInfo").yxeditgrid({
		objName : 'rentalcar[supp]',
//		dir : 'ASC',
//		url : '?model=outsourcing_vehicle_rentalcarequ&action=listJson',
//		param : {
//			dir : 'ASC',
//			parentId : $("#id").val()
//		},
		initAddRowNum : 0,
//		isAdd : false,
		colModel : [{
			name : 'deptName',
			display : '����',
			width : 80,
			process : function ($input ,row) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxselect_dept({
					hiddenId : 'suppInfo_cmp_deptId' + rowNum
				});
			},
			validation : {
				required : true
			},
			readonly : true
		},{
			name : 'deptId',
			display : '����Id',
			type : 'hidden'
		},{
			name : 'suppName',
			display : '��Ӧ������',
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_outsuppvehicle({
					hiddenId : 'suppInfo_cmp_suppName' + rowNum,
					isShowButton : false,
					width : 615,
					isFocusoutCheck : false,
					gridOptions : {
						event : {
							row_dblclick : function(e, row, data) {
								itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"suppId").val(data.id);
								itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"suppName").val(data.suppName);
								itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"suppCode").val(data.suppCode);
							}
						}
					}
				});
			},
			validation : {
				required : true
			}
		},{
			name : 'suppCode',
			display : '��Ӧ�̱��',
			type : 'hidden'
		},{
			name : 'suppId',
			display : '��Ӧ��ID',
			type : 'hidden'
		},{
			name : 'linkManName',
			display : '��ϵ������',
			width : 60,
			validation : {
				required : true
			}
		},{
			name : 'linkManPhone',
			display : '��ϵ�˵绰',
			width : 80,
			validation : {
				required : true,
				custom : ['onlyNumber']
			}
		},{
			name : 'useCarAmount',
			display : '�ó�����',
			width : 40,
			validation : {
				required : true,
				custom : ['onlyNumber']
			}
		},{
			name : 'certificate',
			display : '֤�����'
		},{
			name : 'powerSupply',
			display : '�����������',
			width : 110,
			type : 'select',
			options : [{
				name : "������Ŀ����",
				value : "1"
			},{
				name : "��������Ŀ����",
				value : "0"
			}]
		},{
			name : 'paymentCycleCode',
			display : '��������',
			width : 90,
			type : 'select',
			datacode : 'WBFKZQ' // �����ֵ����
		},{
			name : 'invoiceCode',
			display : '��Ʊ����',
			width : 90,
			type : 'select',
			datacode : 'FPLX' // �����ֵ����
		},{
			name : 'taxPoint',
			display : '��Ʊ˰��',
			width : 60,
			type : 'select',
			datacode : 'WBZZSD' // �����ֵ����
		},{
			name : 'rentalFee',
			display : '�⳵�ѣ�����˾�����ʣ�',
			width : 150
		},{
			name : 'gasolineFee',
			display : '�ͷ�'
		},{
			name : 'catering',
			display : '������'
		},{
			name : 'accommodationFee',
			display : 'ס�޷�'
		},{
			name : 'remark',
			display : '��ע',
			type : 'textarea',
			rows : 3,
			width : 300
		}]
	});

	$.ajax({
		type : "POST",
		url : '?model=outsourcing_vehicle_rentalcarequ&action=listJson',
		data : {
			parentId : $("#id").val()
		},
		success : function(data) {
			if (data) {
				data = eval("(" + data + ")");
				userId = $("#userId").val();

				var $tab = $("#suppInfo > table > tbody");
				for (var i = 0; i < data.length; i++) {
					//ͨ�����ñ������������ɾ���������к�
					itemTableObj.yxeditgrid("addRow" ,i ,data[i]); //Ĭ��Ϊ�ɱ༭
					if (userId != data[i].createId) { //�����½�˲�Ϊ�����˵Ļ����ܱ༭
						itemTableObj.yxeditgrid("removeRow" ,i); //���м�ɾ��

						if (data[i].powerSupply == 1) {
							data[i].powerSupply =  "������Ŀ����";
						}else {
							data[i].powerSupply = "��������Ŀ����";
						}
						htmlArr = '<tr class="tr_even" rownum="' + i
								+ '"><td>' + '<input type="hidden" name="rentalcar[supp][' + (i) + '][rowNum_]" value="' + i
								+ '"></td><td type="rowNum">' + (i+1)
								+ '</td><td>' + data[i].deptName
								+ '</td><td>' + data[i].suppName
								+ '</td><td>' + data[i].linkManName
								+ '</td><td>' + data[i].linkManPhone
								+ '</td><td>' + data[i].useCarAmount
								+ '</td><td>' + data[i].certificate
								+ '</td><td>' + data[i].powerSupply
								+ '</td><td>' + data[i].paymentCycle
								+ '</td><td>' + data[i].invoice
								+ '</td><td>' + data[i].taxPoint
								+ '%</td><td>' + data[i].rentalFee
								+ '</td><td>' + data[i].gasolineFee
								+ '</td><td>' + data[i].catering
								+ '</td><td>' + data[i].accommodationFee
								+ '</td><td>' + data[i].remark + '</td></tr>';
						$tab.append(htmlArr);
					}
				}
			}
		}
	});

	//����Ĭ�ϲ���
	itemTableObj.yxeditgrid("setColValue" ,"deptId" ,$("#deptId").val());
	itemTableObj.yxeditgrid("setColValue" ,"deptName" ,$("#deptName").val());
});

//ֱ���ύ
function toSubmit(){
	document.getElementById('form1').action="?model=outsourcing_vehicle_rentalcar&action=dealEdit&isSubmit=1";
}