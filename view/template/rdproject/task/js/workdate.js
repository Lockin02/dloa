//���㹤���գ��������������������
function calculateWorkDays(fromStr, toStr){
var from = new Date();
var to = new Date();
var reg=new RegExp("-","g");
var nfromStr = fromStr.replace(reg,"/");
var ntoStr = toStr.replace(reg,"/");
var fromTime = Date.parse(nfromStr);
var toTime = Date.parse(ntoStr);
from.setTime(fromTime);
to.setTime(toTime);
if(from.getTime() > to.getTime()){
return 0;
}

//����ʼ�ն������������� javascript�����ڴ�0��ʼ������+1������
var sDayofWeek = from.getDay()+1;
var workdays=0;
//������������֮��������������ķǼ���
if(sDayofWeek > 1 && sDayofWeek < 7)
{
   from.setDate(from.getDate()-(sDayofWeek%7));
   workdays-=((sDayofWeek-2)>0)?sDayofWeek-2:0;
}
var totalDays = (to.getTime()-from.getTime())/(1000*60*60*24)+1;
workdays+=Math.floor(totalDays/7)*5;
//�������ʣ������
if((totalDays%7-2)>0){
workdays+=(totalDays%7-2);
}
return workdays;
}