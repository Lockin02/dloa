
 $(function() {
			$("#initiator").yxselect_user({
						hiddenId : 'initiatorId'

					});

					$("#participant").yxselect_user({
						hiddenId : 'participantId',
						mode : 'check'
					});
					$("#deptName").yxselect_dept({
						hiddenId : 'deptId',
						mode : 'check'
					});

					/**
			 * ��֤��Ϣ
			 */
			validate({
						"billNo" : {
							required : true

						},
						"checkDate" : {
							custom : ['date']
						},
						"endDate" : {
							custom : ['date']

						},
						"deptName" : {
							required : true

						},
						"initiator" : {
							required : true

						},"participant" : {
							required : true

						}
					});



	});

//��֤��ʼʱ�����ʱ���Ⱥ�
function Check() {

 var start=$("#checkDate").val();
 var end=$("#endDate").val();

   if(start!=''&&end!=''){
     start=start.split('-');
     end=end.split('-');
     var start1=new Date(start[0],start[1]-1,start[2]);
     var end1=new Date(end[0],end[1]-1,end[2]);


     if(start1>end1){
       alert("Ԥ���̵����ʱ�䲻�����̵�ʱ��֮ǰ��");
       return false;
     }
   }
}