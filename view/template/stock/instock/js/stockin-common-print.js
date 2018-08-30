/**入库打印公用方法*/
function printForStockIn(){
    var itemCount=document.getElementById("itemCount").value;//物料条目数
    LODOP= getLodop(document.getElementById('LODOP_OB'), document
        .getElementById('LODOP_EM'));
    LODOP.PRINT_INITA(-23,0,777,421,"入库单打印预览");
    for(i=0;i<itemCount;i++){
        var j=i%5;
        if(j==0){
            var tallyName = (document.getElementById("tallyName"))? document.getElementById("tallyName").value : '';// 记账名称
            LODOP.NewPage();
            LODOP.ADD_PRINT_SETUP_BKIMG("<img border='0' src='images/入库单.jpg'>");
            LODOP.SET_SHOW_MODE("BKIMG_IN_PREVIEW",true);
            LODOP.ADD_PRINT_TEXT(70,560,100,20,document.getElementById("docCode").value);//单号
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(70,208,45,20,document.getElementById("year").value);//年
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(70,277,27,20,document.getElementById("month").value);//月
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(70,321,27,20,document.getElementById("day").value);//日
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(153+35*j,12,67,20,document.getElementById("productCode"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(153+35*j,75,150,35,document.getElementById("productName"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(153+35*j,220,35,20,document.getElementById("unitName"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(153+35*j,250,125,35,document.getElementById("pattern"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(153+35*j,380,47,20,document.getElementById("actNum"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(320,90,100,20,tallyName);// 记账名称
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(320,250,100,20,document.getElementById("stockMan").value);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(320,580,100,20,document.getElementById("createName").value);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
        }else{
            LODOP.ADD_PRINT_TEXT(153+35*j,12,67,20,document.getElementById("productCode"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(153+35*j,75,150,35,document.getElementById("productName"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(153+35*j,220,35,20,document.getElementById("unitName"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(153+35*j,250,125,35,document.getElementById("pattern"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(153+35*j,380,47,20,document.getElementById("actNum"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);

        }
    }
    LODOP.PREVIEW();//打印预览

}


/**进仓打印公用方法*/
function printForInStock(){
    var itemCount=document.getElementById("itemCount").value;//物料条目数
    LODOP= getLodop(document.getElementById('LODOP_OB'), document
        .getElementById('LODOP_EM'));
    LODOP.PRINT_INITA(-26,0,717,190,"进仓单打印预览");
    for(i=0;i<itemCount;i++){
        var j=i%5;
        if(j==0){
            var supplierName = (document.getElementById("supplierName"))? document.getElementById("supplierName").value : '';//供应商
            LODOP.NewPage();
            LODOP.ADD_PRINT_SETUP_BKIMG("<img border='0' src='images/进仓单.jpg'>");
            LODOP.SET_SHOW_MODE("BKIMG_IN_PREVIEW",true);
            LODOP.ADD_PRINT_TEXT(85,130,120,20,supplierName);//供应商
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(73,570,100,20,document.getElementById("docCode").value);//单号
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(85,333,45,20,document.getElementById("sortyear").value);//年
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(85,387,27,20,document.getElementById("month").value);//月
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(85,441,27,20,document.getElementById("day").value);//日
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(153+35*j,32,67,20,document.getElementById("productCode"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(153+35*j,95,250,35,document.getElementById("productName"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(153+35*j,350,35,20,document.getElementById("unitName"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(153+35*j,430,47,20,document.getElementById("actNum"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(320,260,100,20,document.getElementById("finance").value);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(320,540,100,20,document.getElementById("purchaserName").value);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(320,400,100,20,document.getElementById("stockMan").value);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
        }else{
            LODOP.ADD_PRINT_TEXT(153+35*j,32,67,20,document.getElementById("productCode"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(153+35*j,95,250,35,document.getElementById("productName"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(153+35*j,350,35,20,document.getElementById("unitName"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(153+35*j,430,47,20,document.getElementById("actNum"+i).value);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);

        }
    }
    LODOP.PREVIEW();//打印预览

}