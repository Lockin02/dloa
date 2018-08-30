$(document).ready(function() {

})

//分数计算
function countAll(thisRowNum){
	//计算所在行的统计信息
	var mainScore = $("#mainScore" + thisRowNum).val()*1;
	var weights = $("#weights" + thisRowNum).val()*1;
	//最大分数，最小分数，平均分差，加权得分，平均分差，最大分差，总评分，成员评分
	var maxScore,minScore,averageScore,weightScore,averageDifference,maxDifference,allScore,memberScore,allMemberNum,memberNum,memberAverageScore = "";

	//初始化金额部分
	minScore = maxScore = averageScore = allScore = mainScore;
	averageDifference = maxDifference = memberScore = weightScore = memberNum = memberAverageScore = 0;
	allMemberNum = 1;

	$("input[id^='memberScore" +  thisRowNum + "']").each(function(i,n){
			//获取最大分数
			if( this.value*1 > maxScore){
				maxScore = this.value*1;
			}
			//获取最小分数
			if( this.value*1 < minScore){
				minScore = this.value*1;
			}
			//计算总得分
			allScore = accAdd(allScore,this.value,2);
			//计算其他评委得分
			memberScore = accAdd(memberScore,this.value,2);

			//评审人员组计算
			allMemberNum++;
			memberNum++;
		}
	);
	//计算平均得分
	averageScore = accDiv(allScore,allMemberNum,2);
	$("#averageScore" + thisRowNum).val(averageScore);

	//计算加权得分
	weightScore = accDiv(accMul(averageScore,weights,2),100,2);
	$("#weightScore" + thisRowNum).val(weightScore);

	//主评委和评委分差
	memberAverageScore = accDiv(memberScore,memberNum,2);
	averageDifference = Math.abs(accSub(mainScore,memberAverageScore,2));
	$("#averageDifference" + thisRowNum).val(averageDifference);

	//评委最大分差
	maxDifference = Math.abs(accSub(minScore,maxScore,2));
	$("#maxDifference" + thisRowNum).val(maxDifference);


	//行数
	countNum = $("#countNum").val()*1;
	//表单总加权得分
	var scoreAll = 0;
	for(var i = 1;i<= countNum;i ++){
		scoreAll = accAdd(scoreAll,$("#weightScore" + i).val()*1,2);
	}
	$("#scoreAll").val(scoreAll);
}