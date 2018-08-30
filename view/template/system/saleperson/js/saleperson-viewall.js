/**
 * info
 */
$(function() {
	$("#info").yxeditgrid({
		objName : 'saleperson[info]',
		url:'?model=system_saleperson_saleperson&action=listJson',
		type:'view',
		param:{
            "ids" : $("#ids").val()
		},
		isAddOneRow : false,
		isAdd : false,
		tableClass : 'form_in_table',
		colModel : [{
			display : '国家',
			name : 'country',
			type : 'statictext',
			isSubmit : true
		},{
			display : '国家id',
			name : 'countryId',
			type : 'hidden'
		}, {
			display : '省份',
			name : 'province',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '省份id',
			name : 'provinceId',
			type : 'hidden'
		}, {
			display : '城市',
			name : 'city',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '城市id',
			name : 'cityId',
			type : 'hidden'
		}, {
			display : '客户类型',
			name : 'customerTypeName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '客户类型Code',
			name : 'customerType',
			type : 'hidden'
		}, {
			display : '是否启用',
			name : 'isUse',
			process: function(v){
			  if(v == "0"){
			    return "启用";
			  }else{
			    return "禁用";
			  }
			}
		}]

	});
});


