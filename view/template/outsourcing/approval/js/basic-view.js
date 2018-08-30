var pageAttr = 'view';//配置页面操作，用于渲染整包/人员租赁信息
$(document).ready(function() {

	if($("#actType").val() == 'audit'){
		$("#buttonTable").hide();
	}
	//变更外包类型
	outsourType();
});

   //人员租赁
function itemDetail() {
	var obj = $("#itemTable");
	if(obj.children().length == 0){
		obj.yxeditgrid({
			objName : 'basic[personList]',
			url : '?model=outsourcing_approval_persronRental&action=listJson',
			param : {
				dir : 'ASC',
				mainId :$("#id").val()
			},
			type : 'view',
			tableClass : 'form_in_table',
			colModel : [{
				name : 'personLevel',
				display : '人员级别',
				type : "hidden"
			}, {
				name : 'personLevelName',
				display : '级别',
				width : 60,
				readonly : true
			}, {
				name : 'pesonName',
				display : '姓名',
				width : 60
			}, {
				name : 'suppId',
				display : '归属外包供应商Id',
				type : "hidden"
			},{
				name : 'suppName',
				display : '归属外包供应商',
				width : 80
			}, {
				name : 'beginDate',
				display : '租赁开始日期',
				width : 80,
				type : 'date'
			}, {
				name : 'endDate',
				display : '租赁结束日期',
				width : 80,
				type : 'date'
			}, {
				name : 'totalDay',
				display : '天数',
				width : 60
			},{
				name : 'inBudgetPrice',
				display : '服务人力成本单价(元/天)',
				width : 80,
				tclass : 'readOnlyTxtShort',
				readonly : true
			}, {
				name : 'selfPrice',
				display : '服务人力成本',
				width : 80,
				tclass : 'readOnlyTxtShort',
				readonly : true
			}, {
				name : 'outBudgetPrice',
				display : '外包单价(元/天)',
				width : 60
			},{
				name : 'rentalPrice',
				display : '外包价格',
				width : 80,
				tclass : 'readOnlyTxtShort',
				readonly : true
			}, {
				name : 'isAddContract',
				display : '生成合同',
				width : 50,
				process : function(v){
					if(v == 1){
						return '是';
					}else{
						return '否';
					}
				}
			}, {
				name : 'skillsRequired',
				display : '工作技能要求',
				width : 120
			}, {
				name : 'remark',
				display : '备注',
				width : 120
			}]
		});
		tableHead();
	}
}