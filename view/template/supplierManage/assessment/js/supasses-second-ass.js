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
					type : 'statictext'
				}, {
					display : '评估说明',
					name : 'assesExplain',
					type : 'hidden'
				}, {
					display : '得分[*]',
					name : 'assesScore',
					tclass : 'txtshort',
					validation : {
						custom : ['percentageNum']
					},
					event : {
						blur : function() {
							var re = /^(?:[1-9][0-9]*|0)(?:\.[0-9]+)?$/;
							if (!re.test(this.value)) { //判断是否为数字
								if (isNaN(this.value)) {
									this.value = "";
//															return false;
								}
							}
							var rownum = $(this).data('rowNum');// 第几行
							var colnum = $(this).data('colNum');// 第几列
							var grid = $(this).data('grid');// 表格组件
							var assesProportion = grid.getCmpByRowAndCol(rownum, 'assesProportion').val();
							var assesScore = $(this).val();
							assesProportion = parseFloat(assesProportion);
							assesScore = parseFloat(assesScore);
							if (assesScore > assesProportion) {
								alert("得分不能超过指标权重："+assesProportion);
								$(this).val("");
							}
							check_all();
						}
					}
				}, {
					display : '评分说明',
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
	if(assessType=="gysjd"||assessType=="gysnd"){//供应商季度和年度考核
		$("#newsupp").hide();
		$("#yearsupp").show();

	}else if(assessType=="xgyspg"){//新供应商评估
		$("#newsupp").show();
		$("#yearsupp").hide();
	}
  });

  // 计算总分数
	function check_all() {
		var totalNum = 0;
		var cmps = $("#suppassesSecondTable").yxeditgrid("getCmpByCol", "assesScore");
		cmps.each(function() {
			totalNum = accAdd(totalNum, $(this).val(),2);
		});
		$("#totalNum").val(totalNum);
		checkGrade(totalNum);//划分供应商等级
	//	return false;
	}

	//划分供应商等级
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

  //直接提交审批
function toSubmit(){
	document.getElementById('form1').action = "index1.php?model=supplierManage_assessment_supasses&action=addSecondAss&actType=audit";
}