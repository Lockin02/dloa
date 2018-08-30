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
			display : '����',
			name : 'country',
			type : 'statictext',
			isSubmit : true
		},{
			display : '����id',
			name : 'countryId',
			type : 'hidden'
		}, {
			display : 'ʡ��',
			name : 'province',
			type : 'statictext',
			isSubmit : true
		}, {
			display : 'ʡ��id',
			name : 'provinceId',
			type : 'hidden'
		}, {
			display : '����',
			name : 'city',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '����id',
			name : 'cityId',
			type : 'hidden'
		}, {
			display : '�ͻ�����',
			name : 'customerTypeName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '�ͻ�����Code',
			name : 'customerType',
			type : 'hidden'
		}, {
			display : '�Ƿ�����',
			name : 'isUse',
			process: function(v){
			  if(v == "0"){
			    return "����";
			  }else{
			    return "����";
			  }
			}
		}]

	});
});


