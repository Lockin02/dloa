$(document).ready(function() {

		$("#assessName").yxcombogrid_scheme({
			hiddenId : 'assessId',
			width : 500,
			gridOptions : {
				showcheckbox : false,
				event : {
					'row_dblclick' : function(e, row, data) {
						$("#assessId").val(data.id);
						$("#schemeTable").html("");
						$("#schemeTable").yxeditgrid({
							objName : 'examine[schemeTable]',
							url : '?model=hr_permanent_schemelist&action=listJson',
							param : {
								parentId : data.id
							},
							isAddAndDel : false,
							colModel : [{
								name : 'standardId',
								type : 'hidden'
							},{
								display : '������Ŀ',
								name : 'standard',
								readonly : 'readonly'
							}, {
								display : '��������',
								name : 'standardContent',
								readonly : 'readonly'
							}, {
								display : '����Ҫ��',
								name : 'standardPoint',
								tclass : 'txtlong',
								readonly : 'readonly'
							}, {
								display : '����Ȩ��',
								name : 'standardProportion',
								type : 'hidden'
							}, {
								display : '����',
								name : 'selfScore',
								validation : {
									custom : ['onlyNumber']
								},
								event : {
									blur : function(){
										caculate();
									}
								}
							}]
						});
						
					}
				}
			}
		});
		validate();
 })
 function caculate() {
	var rowAmountVa = 0;
	var cmps = $("#schemeTable").yxeditgrid("getCmpByCol", "selfScore");
	var portions = $("#schemeTable").yxeditgrid("getCmpByCol", "standardProportion");
	for(var i=0;i<cmps.length;i++)
	if(parseInt(cmps[i].value)>parseInt(portions[i].value))
		alert("�����ֲ��ܸ���Ȩ��");
	cmps.each(function() {
		rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
	});
	if(rowAmountVa>100)alert("�ܺͲ��ܳ���100��")
	$("#selfScore").val(rowAmountVa);
}