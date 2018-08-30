//页面代码缓存数组 -- 为了减少字段隐藏的工作做的缓存，未用

var expandinfoArr = [
	{
		'1' : '<tr id="feeDeptInfo">' +
				'<td class="form_text_left_three"><span class="blue">费用归属公司</span></td>' +
				'<td class="form_text_right_three">' +
					'<input type="text" class="txt" readonly="readonly" name="expense[CostBelongCom]" id="CostBelongCom" value="{CostBelongCom}" />' +
					'<input type="hidden" name="expense[CostBelongComId]" id="CostBelongComId" value="{CostBelongComId}"/>' +
				'</td>' +
				'<td class="form_text_left_three"><span class="blue">费用归属部门</span></td>' +
				'<td class="form_text_right" colspan="3">' +
				'<input type="text" class="txt" readonly="readonly" name="expense[CostBelongDeptName]" id="CostBelongDeptName" value="{deptName}" />' +
				'<input type="hidden" name="expense[CostBelongDeptId]" id="CostBelongDeptId" value="{deptId}"/>' +
				'</td>' +
			'</tr>'
	},{
		'2' : '<tr id="projectInfo" style="display:none">' +
				'<td class="form_text_left_three"><span class="blue">项目编号</span></td>' +
				'<td class="form_text_right_three">' +
				'<input type="text" class="txt" name="expense[ProjectNo]" id="projectCode"/>' +
					'<input type="hidden" name="expense[projectId]" id="projectId"/>' +
				'</td>' +
				'<td class="form_text_left_three"><span class="blue">项目名称</span></td>' +
				'<td class="form_text_right_three">' +
					'<input type="text" class="txt" name="expense[projectName]" id="projectName"/>' +
					'</td>' +
					'<td class="form_text_left_three">项目经理</td>' +
					'<td class="form_text_right_three">' +
						'<input type="text" class="readOnlyTxtNormal" name="expense[proManagerName]" id="proManagerName" readonly="readonly"/>' +
						'<input type="hidden" name="expense[proManagerId]" id="proManagerId" />' +
					'</td>' +
				'</tr>'
	}
];