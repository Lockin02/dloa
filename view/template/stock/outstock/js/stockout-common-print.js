/**���ִ�ӡ���÷���*/
function printForOutStock(){
    var itemCount=document.getElementById("itemCount").value;//������Ŀ��
    LODOP= getLodop(document.getElementById('LODOP_OB'), document
        .getElementById('LODOP_EM'));
    LODOP.PRINT_INITA(-23,0,717,190,"���ֵ���ӡԤ��");
    for(i=0;i<itemCount;i++){
        var j=i%5;
        if(j==0){
            LODOP.NewPage();
            LODOP.ADD_PRINT_SETUP_BKIMG("<img border='0' src='images/���ֵ�.jpg'>");
            LODOP.SET_SHOW_MODE("BKIMG_IN_PREVIEW",true);
            LODOP.ADD_PRINT_TEXT(73,570,100,20,document.getElementById("docCode").value);//����
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(90,105,250,20,document.getElementById("customerName").value);//�ͻ�
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(90,330,45,20,document.getElementById("sortyear").value);//��
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(90,387,27,20,document.getElementById("month").value);//��
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(90,441,27,20,document.getElementById("day").value);//��
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(152+35*j,42,67,20,document.getElementById("productCode"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(153+35*j,105,250,35,document.getElementById("productName"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(153+35*j,360,35,20,document.getElementById("unitName"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(153+35*j,420,47,20,document.getElementById("actOutNum"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(330,100,100,20,document.getElementById("detpLeader").value);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(330,220,100,20,document.getElementById("finance").value);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(330,415,100,20,document.getElementById("stockMan").value);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(330,535,100,20,document.getElementById("pickName").value);//�����
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
        }else{
            LODOP.ADD_PRINT_TEXT(152+35*j,42,67,20,document.getElementById("productCode"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(153+35*j,105,250,35,document.getElementById("productName"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(153+35*j,360,35,20,document.getElementById("unitName"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(153+35*j,420,47,20,document.getElementById("actOutNum"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
        }
    }
    LODOP.PREVIEW();//��ӡԤ��

}

/**���ϴ�ӡ���÷���*/
function printForPickStockout(){
    var itemCount=document.getElementById("itemCount").value;//������Ŀ��
    LODOP= getLodop(document.getElementById('LODOP_OB'), document
        .getElementById('LODOP_EM'));
    LODOP.PRINT_INITA(-23,0,717,411,"���ϵ���ӡԤ��");
    for(i=0;i<itemCount;i++){
        var j=i%9;
        if(j==0){
            LODOP.NewPage();
            LODOP.ADD_PRINT_SETUP_BKIMG("<img border='0' src='images/���ϵ�.jpg'>");
            LODOP.SET_SHOW_MODE("BKIMG_IN_PREVIEW",true);
            LODOP.ADD_PRINT_TEXT(83,580,100,20,document.getElementById("docCode").value);//����
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(100,105,250,20,document.getElementById("deptName").value);//���ϲ���
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(100,310,45,20,document.getElementById("year").value);//��
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(100,377,27,20,document.getElementById("month").value);//��
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(100,431,27,20,document.getElementById("day").value);//��
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(170+35*j,28,67,20,document.getElementById("productCode"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(170+35*j,95,210,35,document.getElementById("productName"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(170+35*j,310,35,20,document.getElementById("unitName"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(170+35*j,420,47,20,document.getElementById("actOutNum"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(330,60,70,20,document.getElementById("detpLeader").value);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            //LODOP.ADD_PRINT_TEXT(510,290,70,20,document.getElementById("finance").value);//���
            //LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(460,290,70,20,document.getElementById("finance").value);//���
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(460,440,100,20,document.getElementById("stockMan").value);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(460,610,100,20,document.getElementById("pickName").value);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
        }else{
            LODOP.ADD_PRINT_TEXT(170+35*j,28,67,20,document.getElementById("productCode"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(170+35*j,95,210,35,document.getElementById("productName"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(170+35*j,310,35,20,document.getElementById("unitName"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(170+35*j,420,47,20,document.getElementById("actOutNum"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);

        }
    }
    LODOP.PREVIEW();//��ӡԤ��

}

/**���ִ�ӡ���ϵ��÷���*/
function printForAllocationOutStock(){
    var itemCount=document.getElementById("itemCount").value;//������Ŀ��
    LODOP= getLodop(document.getElementById('LODOP_OB'), document
        .getElementById('LODOP_EM'));
    LODOP.PRINT_INITA(-23,0,717,190,"���ֵ���ӡԤ��");
    for(i=0;i<itemCount;i++){
        var j=i%5;
        if(j==0){
            LODOP.NewPage();
            LODOP.ADD_PRINT_SETUP_BKIMG("<img border='0' src='images/���ֵ�.jpg'>");
            LODOP.SET_SHOW_MODE("BKIMG_IN_PREVIEW",true);
            LODOP.ADD_PRINT_TEXT(73,570,100,20,document.getElementById("docCode").value);//����
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(90,105,250,20,document.getElementById("customerName").value);//�ͻ�
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(90,330,45,20,document.getElementById("sortyear").value);//��
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(90,387,27,20,document.getElementById("month").value);//��
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(90,441,27,20,document.getElementById("day").value);//��
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(152+35*j,42,67,20,document.getElementById("productCode"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(153+35*j,105,250,35,document.getElementById("productName"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(153+35*j,360,35,20,document.getElementById("unitName"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(153+35*j,420,47,20,document.getElementById("actOutNum"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(330,100,100,20,document.getElementById("detpLeader").value);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(330,220,100,20,document.getElementById("finance").value);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(330,415,100,20,document.getElementById("stockMan").value);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(330,535,100,20,document.getElementById("pickName").value);//�����
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
        }else{
            LODOP.ADD_PRINT_TEXT(152+35*j,42,67,20,document.getElementById("productCode"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(153+35*j,105,250,35,document.getElementById("productName"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(153+35*j,360,35,20,document.getElementById("unitName"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(153+35*j,420,47,20,document.getElementById("actOutNum"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
        }
    }
    LODOP.PREVIEW();//��ӡԤ��

}