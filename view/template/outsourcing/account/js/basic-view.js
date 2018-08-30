$(document).ready(function() {

	if($("#actType").val() == 'audit'){
		$("#buttonTable").hide();
	}
	//变更外包类型
	outsourType();

        });

function itemDetail() {
	var obj = $("#itemTable");
	if(obj.children().length == 0){
		obj.yxeditgrid({
			objName : 'basic[personList]',
			tableClass : 'form_in_table',
			url : '?model=outsourcing_account_persron&action=listJson',
			param : {
				dir : 'ASC',
				mainId :$("#id").val()
			},
			type : 'view',
		   event: {
	            'removeRow': function() {
	            	checkOrderMoney();
	            	checkDeductMoney();
	            }
	        },
			colModel : [{
				name : 'personLevel',
				display : '人员级别',
				type : "hidden"
			}, {
				name : 'personLevelName',
				display : '级别',
				width : 60,
				readonly : true,
				type : "hidden"
			}, {
				name : 'pesonName',
				display : '姓名',
				width : 80,
				validation : {
					required : true
				}
			}, {
				name : 'beginDate',
				display : '开始日期',
				width : 80,
				type : 'date'
			}, {
				name : 'endDate',
				display : '结束日期',
				width : 80,
				type : 'date'
			}, {
				name : 'totalDay',
				display : '工时(天)',
				width : 60,
				tclass:'txtshort',
				validation : {
					required : true
				}
			},{
				name : 'outBudgetPrice',
				display : '工价(元/天)',
				width : 80,
				type : 'money'
			}, {
				name : 'trafficMoney',
				display : '交通费(元)',
				width : 80,
				type : 'money'
			}, {
				name : 'otherMoney',
				display : '其他费用(元)',
				width : 80,
				type : 'money'
			},{
				name : 'customerDeduct',
				display : '客户扣款',
				type : 'money'
			}, {
				name : 'examinDuduct',
				display : '考核扣款',
				type : 'money',
				width : 80
			}, {
				name : 'rentalPrice',
				display : '合计(元)',
				width : 80,
//				type : 'money',
				readonly : true
			},{
				name : 'remark',
				display : '备注',
				width : 150
			}]
		});
		tableHead();
	}
}