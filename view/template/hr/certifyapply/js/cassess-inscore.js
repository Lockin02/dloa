$(document).ready(function() {

})

//��������
function countAll(thisRowNum){
	//���������е�ͳ����Ϣ
	var mainScore = $("#mainScore" + thisRowNum).val()*1;
	var weights = $("#weights" + thisRowNum).val()*1;
	//����������С������ƽ���ֲ��Ȩ�÷֣�ƽ���ֲ���ֲ�����֣���Ա����
	var maxScore,minScore,averageScore,weightScore,averageDifference,maxDifference,allScore,memberScore,allMemberNum,memberNum,memberAverageScore = "";

	//��ʼ������
	minScore = maxScore = averageScore = allScore = mainScore;
	averageDifference = maxDifference = memberScore = weightScore = memberNum = memberAverageScore = 0;
	allMemberNum = 1;

	$("input[id^='memberScore" +  thisRowNum + "']").each(function(i,n){
			//��ȡ������
			if( this.value*1 > maxScore){
				maxScore = this.value*1;
			}
			//��ȡ��С����
			if( this.value*1 < minScore){
				minScore = this.value*1;
			}
			//�����ܵ÷�
			allScore = accAdd(allScore,this.value,2);
			//����������ί�÷�
			memberScore = accAdd(memberScore,this.value,2);

			//������Ա�����
			allMemberNum++;
			memberNum++;
		}
	);
	//����ƽ���÷�
	averageScore = accDiv(allScore,allMemberNum,2);
	$("#averageScore" + thisRowNum).val(averageScore);

	//�����Ȩ�÷�
	weightScore = accDiv(accMul(averageScore,weights,2),100,2);
	$("#weightScore" + thisRowNum).val(weightScore);

	//����ί����ί�ֲ�
	memberAverageScore = accDiv(memberScore,memberNum,2);
	averageDifference = Math.abs(accSub(mainScore,memberAverageScore,2));
	$("#averageDifference" + thisRowNum).val(averageDifference);

	//��ί���ֲ�
	maxDifference = Math.abs(accSub(minScore,maxScore,2));
	$("#maxDifference" + thisRowNum).val(maxDifference);


	//����
	countNum = $("#countNum").val()*1;
	//���ܼ�Ȩ�÷�
	var scoreAll = 0;
	for(var i = 1;i<= countNum;i ++){
		scoreAll = accAdd(scoreAll,$("#weightScore" + i).val()*1,2);
	}
	$("#scoreAll").val(scoreAll);
}