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
								display : '考核项目',
								name : 'standard',
								readonly : 'readonly'
							}, {
								display : '考核内容',
								name : 'standardContent',
								readonly : 'readonly'
							}, {
								display : '考核要点',
								name : 'standardPoint',
								tclass : 'txtlong',
								readonly : 'readonly'
							}, {
								display : '考核权重',
								name : 'standardProportion',
								type : 'hidden'
							}, {
								display : '自评',
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
		alert("自评分不能高于权重");
	cmps.each(function() {
		rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
	});
	if(rowAmountVa>100)alert("总和不能超过100！")
	$("#selfScore").val(rowAmountVa);
}