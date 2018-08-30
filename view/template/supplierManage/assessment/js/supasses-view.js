$(function() {
	if($("#isFirst").val()==2){
		$("#showFirst").show();
	}
	if($("#viewType").val()=="aduit"){
		$("#closeBtn").hide();
	}
	//根据不同的评估类型，订制不同的查看页面
	var assessType=$("#assessType").val();
	if(assessType!="xgyspg"){
		$("#newsupp").hide();
	}else{
		$("#yearsupp").hide();
	}
	$("#suppassesTable").yxeditgrid({
			objName : 'supasses[assesmentitem]',
			url : '?model=supplierManage_assessment_assesmentitem&action=listJson',
			isAddAndDel : false,
			param : {
				parentId : $("#id").val()
			},
			colModel : [{
                display : '评估部门',
                name : 'assesDept',
                type : 'statictext'
            },{
				display : '评估项目',
				name : 'assesProName',
				type : 'statictext'
			},{
				display : '评估项目',
				name : 'assesProName',
				type : 'hidden'
			}, {
				display : '评估指标',
				name : 'assesStandard',
				type : 'statictext'
			},  {
				display : '评估指标',
				name : 'assesStandard',
				type : 'hidden'
			}, {
				display : '指标权重',
				name : 'assesProportion',
				type : 'statictext'
			},  {
				display : '指标权重',
				name : 'assesProportion',
				type : 'hidden'
			}, {
				display : '评估说明',
				name : 'assesExplain',
				type : 'statictext',
				width:550
			}, {
				display : '评估说明',
				name : 'assesExplain',
				type : 'hidden'
			}, {
                display : '负责人',
                name : 'assesMan',
                type : 'statictext'
            }, {
				display : '得分',
				name : 'assesScore',
				type : 'statictext'
			}, {
				display : '评分说明',
				name : 'assesRemark',
				type : 'statictext'
			}]
		});
});

//查看供应商首次评估信息
function viewFirstSup(parentId){
	var assesKey=$("#assesKey").val();
	window.open ("?model=supplierManage_assessment_supasses&action=toView&id="+ parentId+"&skey="+assesKey);
}