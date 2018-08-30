$(document).ready(function() {

	//从表部分
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
			display : '部门',
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
			display : '部门Id',
			type : 'hidden'
		},{
			name : 'suppName',
			display : '供应商名称',
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
			display : '供应商编号',
			type : 'hidden'
		},{
			name : 'suppId',
			display : '供应商ID',
			type : 'hidden'
		},{
			name : 'linkManName',
			display : '联系人姓名',
			width : 60,
			validation : {
				required : true
			}
		},{
			name : 'linkManPhone',
			display : '联系人电话',
			width : 80,
			validation : {
				required : true,
				custom : ['onlyNumber']
			}
		},{
			name : 'useCarAmount',
			display : '用车数量',
			width : 40,
			validation : {
				required : true,
				custom : ['onlyNumber']
			}
		},{
			name : 'certificate',
			display : '证件情况'
		},{
			name : 'powerSupply',
			display : '车辆供电情况',
			width : 110,
			type : 'select',
			options : [{
				name : "满足项目需求",
				value : "1"
			},{
				name : "不满足项目需求",
				value : "0"
			}]
		},{
			name : 'paymentCycleCode',
			display : '付款周期',
			width : 90,
			type : 'select',
			datacode : 'WBFKZQ' // 数据字典编码
		},{
			name : 'invoiceCode',
			display : '发票属性',
			width : 90,
			type : 'select',
			datacode : 'FPLX' // 数据字典编码
		},{
			name : 'taxPoint',
			display : '发票税点',
			width : 60,
			type : 'select',
			datacode : 'WBZZSD' // 数据字典编码
		},{
			name : 'rentalFee',
			display : '租车费（包含司机工资）',
			width : 150
		},{
			name : 'gasolineFee',
			display : '油费'
		},{
			name : 'catering',
			display : '餐饮费'
		},{
			name : 'accommodationFee',
			display : '住宿费'
		},{
			name : 'remark',
			display : '备注',
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
					//通过调用表格组件里的增加删除来处理行号
					itemTableObj.yxeditgrid("addRow" ,i ,data[i]); //默认为可编辑
					if (userId != data[i].createId) { //如果登陆人不为创建人的话则不能编辑
						itemTableObj.yxeditgrid("removeRow" ,i); //进行假删除

						if (data[i].powerSupply == 1) {
							data[i].powerSupply =  "满足项目需求";
						}else {
							data[i].powerSupply = "不满足项目需求";
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

	//设置默认部门
	itemTableObj.yxeditgrid("setColValue" ,"deptId" ,$("#deptId").val());
	itemTableObj.yxeditgrid("setColValue" ,"deptName" ,$("#deptName").val());
});

//直接提交
function toSubmit(){
	document.getElementById('form1').action="?model=outsourcing_vehicle_rentalcar&action=dealEdit&isSubmit=1";
}