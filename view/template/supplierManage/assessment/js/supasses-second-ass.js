$(document).ready(function() {
	    var parentId=$("#id").val();
		$("#suppassesSecondTable").yxeditgrid({
				objName : 'supasses[assesmentitem]',
				url : '?model=supplierManage_assessment_assesmentitem&action=listJson',
				param:{'parentId':parentId},
				isAddAndDel : false,
				colModel : [{
					display : 'id',
					name : 'id',
					type : 'hidden'
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
					type : 'statictext'
				}, {
					display : '����˵��',
					name : 'assesExplain',
					type : 'hidden'
				}, {
					display : '�÷�[*]',
					name : 'assesScore',
					tclass : 'txtshort',
					validation : {
						custom : ['percentageNum']
					},
					event : {
						blur : function() {
							var re = /^(?:[1-9][0-9]*|0)(?:\.[0-9]+)?$/;
							if (!re.test(this.value)) { //�ж��Ƿ�Ϊ����
								if (isNaN(this.value)) {
									this.value = "";
//															return false;
								}
							}
							var rownum = $(this).data('rowNum');// �ڼ���
							var colnum = $(this).data('colNum');// �ڼ���
							var grid = $(this).data('grid');// ������
							var assesProportion = grid.getCmpByRowAndCol(rownum, 'assesProportion').val();
							var assesScore = $(this).val();
							assesProportion = parseFloat(assesProportion);
							assesScore = parseFloat(assesScore);
							if (assesScore > assesProportion) {
								alert("�÷ֲ��ܳ���ָ��Ȩ�أ�"+assesProportion);
								$(this).val("");
							}
							check_all();
						}
					}
				}, {
					display : '����˵��',
					name : 'assesRemark'
				}]
			});
	$("#menberName").yxselect_user({
		hiddenId : 'menberId',
		mode:"check"
			});
	uploadfile = createSWFUpload({
		"serviceType": "oa_supp_suppasses"
	});


//	$("#newsupp").hide();
//	$("#yearsupp").hide();
	var assessType=$("#assessType").val();
	if(assessType=="gysjd"||assessType=="gysnd"){//��Ӧ�̼��Ⱥ���ȿ���
		$("#newsupp").hide();
		$("#yearsupp").show();

	}else if(assessType=="xgyspg"){//�¹�Ӧ������
		$("#newsupp").show();
		$("#yearsupp").hide();
	}
  });

  // �����ܷ���
	function check_all() {
		var totalNum = 0;
		var cmps = $("#suppassesSecondTable").yxeditgrid("getCmpByCol", "assesScore");
		cmps.each(function() {
			totalNum = accAdd(totalNum, $(this).val(),2);
		});
		$("#totalNum").val(totalNum);
		checkGrade(totalNum);//���ֹ�Ӧ�̵ȼ�
	//	return false;
	}

	//���ֹ�Ӧ�̵ȼ�
	function checkGrade(totalNum){
		var assessType=$("#assessType").val();
		if(assessType=="gysjd"||assessType=="gysnd"){
			if(totalNum>90){
				$("#suppGrade").val("A");
			}else if(totalNum==75||totalNum>75){
				$("#suppGrade").val("B");
			}else if(totalNum>60||totalNum==60){
				$("#suppGrade").val("C");
			}else if(totalNum<60){
				$("#suppGrade").val("D");
			}
		}else if(assessType=="xgyspg"){
			if(totalNum>60||totalNum==60){
				$("#suppGrade").val("C");
			}
		}
	}

  //ֱ���ύ����
function toSubmit(){
	document.getElementById('form1').action = "index1.php?model=supplierManage_assessment_supasses&action=addSecondAss&actType=audit";
}