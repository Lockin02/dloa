     /**验证任务名称 **/
     $("#name").formValidator({
         onshow:"请输入任务名称",
         onfocus:"项目名称至少5个字符，最多50个字符",
         oncorrect:"您输入的任务名称有效"
     }).inputValidator({
         min:5,
         max:50,
         empty:{
            leftempty:false,
            rightempty:false,
            emptyerror:"任务名称两边不能有空符号"
         },
         onerror:"您输入的名称不合法，请重新输入"
     });
