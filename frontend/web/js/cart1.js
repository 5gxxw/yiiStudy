/*
@功能：购物车页面js
@作者：diamondwang
@时间：2013年11月14日
*/
function totalPrice() {
    //总计金额
    var total = 0;
    $(".col5 span").each(function(){
        total += parseFloat($(this).text());
    });

    $("#total").text(total.toFixed(2));
}

$(function(){
	
	//减少
	$(".reduce_num").click(function(){

		var tr = $(this).closest("tr");
		//>>获取数量和商品id
        var amount = $(this).parent().find(".amount");
        var num = parseInt($(amount).val()) - 1;
        var goods_id = tr.attr('data-goods-id');
		//>>发起ajax请求
		$.post('/cart/ajax?filter=modify',{goods_id:goods_id,num:num},function (data) {
			if (data == 'success'){
                if (parseInt($(amount).val()) <= 1){
                    alert("商品数量最少为1");
                } else {
                    $(amount).val(num);
                }
                //小计
                var subtotal = parseFloat(tr.find(".col3 span").text()) * parseInt($(amount).val());
                tr.find(".col5 span").text(subtotal.toFixed(2));
                //总计金额
                var total = 0;
                $(".col5 span").each(function(){
                    total += parseFloat($(this).text());
                });
                $("#total").text(total.toFixed(2));
			}
        });


	});

	//增加
	$(".add_num").click(function(){
        var tr = $(this).closest("tr");
        //>>获取数量和商品id
        var amount = $(this).parent().find(".amount");
        var num = parseInt($(amount).val()) + 1;
        var goods_id = tr.attr('data-goods-id');
        //>>发起ajax请求
        $.post('/cart/ajax?filter=modify',{goods_id:goods_id,num:num},function (data) {
            if (data == 'success'){
                //修改商品数量
                $(amount).val(num);
                //小计
                var subtotal = parseFloat(tr.find(".col3 span").text()) * parseInt($(amount).val());
                tr.find(".col5 span").text(subtotal.toFixed(2));
                //总计金额
                var total = 0;
                $(".col5 span").each(function(){
                    total += parseFloat($(this).text());
                });
                $("#total").text(total.toFixed(2));
            }else{
                console.log('修改失败：'+data);
            }
        });
	});

	//直接输入
	$(".amount").blur(function(){
        var tr = $(this).closest("tr");
        //>>获取数量和商品id
        var amount = $(this).parent().find(".amount");
        var num = parseInt($(amount).val());
        var goods_id = tr.attr('data-goods-id');
        //>>发起ajax请求
        $.post('/cart/ajax?filter=modify',{goods_id:goods_id,num:num},function (data) {
            if(data == 'success'){
                if (parseInt($(amount).val()) < 1){
                    alert("商品数量最少为1");
                    $(amount).val(1);
                }
                //小计
                var subtotal = parseFloat(tr.find(".col3 span").text()) * parseInt($(amount).val());
                tr.find(".col5 span").text(subtotal.toFixed(2));
                //总计金额
                var total = 0;
                $(".col5 span").each(function(){
                    total += parseFloat($(this).text());
                });

                $("#total").text(total.toFixed(2));
            }
        });
	});

    //点击删除执行的事件
    $(".btn_del").click(function(data){
        //>>获取商品id,
        var tr = $(this).closest("tr");
        var goods_id = tr.attr('data-goods-id');
        //>>发起ajax请求
        $.post('/cart/ajax?filter=del',{'goods_id':goods_id},function (data) {
            if (data == 'success'){
                //>>删除该行tr
                tr.remove();
                totalPrice();
            }
        })

    });
});