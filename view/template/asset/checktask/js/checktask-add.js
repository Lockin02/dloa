
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
			 * 验证信息
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

//验证开始时间结束时间先后
function Check() {

 var start=$("#checkDate").val();
 var end=$("#endDate").val();

   if(start!=''&&end!=''){
     start=start.split('-');
     end=end.split('-');
     var start1=new Date(start[0],start[1]-1,start[2]);
     var end1=new Date(end[0],end[1]-1,end[2]);


     if(start1>end1){
       alert("预计盘点结束时间不能在盘点时间之前！");
       return false;
     }
   }
}