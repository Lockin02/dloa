$(function() {
	if($("#isFirst").val()==2){
		$("#showFirst").show();
	}
	if($("#viewType").val()=="aduit"){
		$("#closeBtn").hide();
	}
	//���ݲ�ͬ���������ͣ����Ʋ�ͬ�Ĳ鿴ҳ��
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
                display : '��������',
                name : 'assesDept',
                type : 'statictext'
            },{
				display : '������Ŀ',
				name : 'assesProName',
				type : 'statictext'
			},{
				display : '������Ŀ',
				name : 'assesProName',
				type : 'hidden'
			}, {
				display : '����ָ��',
				name : 'assesStandard',
				type : 'statictext'
			},  {
				display : '����ָ��',
				name : 'assesStandard',
				type : 'hidden'
			}, {
				display : 'ָ��Ȩ��',
				name : 'assesProportion',
				type : 'statictext'
			},  {
				display : 'ָ��Ȩ��',
				name : 'assesProportion',
				type : 'hidden'
			}, {
				display : '����˵��',
				name : 'assesExplain',
				type : 'statictext',
				width:550
			}, {
				display : '����˵��',
				name : 'assesExplain',
				type : 'hidden'
			}, {
                display : '������',
                name : 'assesMan',
                type : 'statictext'
            }, {
				display : '�÷�',
				name : 'assesScore',
				type : 'statictext'
			}, {
				display : '����˵��',
				name : 'assesRemark',
				type : 'statictext'
			}]
		});
});

//�鿴��Ӧ���״�������Ϣ
function viewFirstSup(parentId){
	var assesKey=$("#assesKey").val();
	window.open ("?model=supplierManage_assessment_supasses&action=toView&id="+ parentId+"&skey="+assesKey);
}