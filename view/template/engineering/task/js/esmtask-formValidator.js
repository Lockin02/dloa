     /**��֤�������� **/
     $("#name").formValidator({
         onshow:"��������������",
         onfocus:"��Ŀ��������5���ַ������50���ַ�",
         oncorrect:"�����������������Ч"
     }).inputValidator({
         min:5,
         max:50,
         empty:{
            leftempty:false,
            rightempty:false,
            emptyerror:"�����������߲����пշ���"
         },
         onerror:"����������Ʋ��Ϸ�������������"
     });
