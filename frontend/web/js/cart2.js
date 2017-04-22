/*
@功能：购物车页面js
@作者：diamondwang
@时间：2013年11月14日
*/
$(function(){
	//收货人修改
	$("#address_modify").click(function(){
		$(this).hide();
		$(".address_info").hide();
		$(".address_select").show();
	});

	$(".new_address").click(function(){
		$("form[name=address_form]").show();
		$(this).parent().addClass("cur").siblings().removeClass("cur");

	}).parent().siblings().find("input").click(function(){
		$("form[name=address_form]").hide();
		$(this).parent().addClass("cur").siblings().removeClass("cur");
	});


	//>>点击收货人执行的事件
    $("input[name=address]").click(function(){
        $(this).parent().addClass("cur").siblings().removeClass("cur");
    });

	$("#consignee").click(function () {
		//>>1.  获取收货人信息id
		var li = $("#data-consignee").find(".cur");
		var id = li.find("input").val();
        //>>发起ajax请求
        $.post("/order/ajax?filter=consignee",{site_id:id},function (data) {
            console.debug(data);
        });
    });


	//送货方式修改
	$("#delivery_modify").click(function(){
		$(this).hide();
		$(".delivery_info").hide();
		$(".delivery_select").show();
	})


	//>>确认收货方式
	$("#confirm_deliver").click(function(){

        //>>获取到选中的收货方式id和收货方式名称,运费
        var tr = $("#deliver_goods").find('.cur');
        var id =tr.find("input").val();
        var name = tr.find("td:first").text();
        var delivery_price = tr.find("td:eq(1)").text();
        //>>计算应付总金额
        var total_price = parseInt($("#total_prices").text());
        var price = total_price-delivery_price;
        $("#price").text(price);
        //>>发起ajax请求
		$.post("/order/ajax?filter=deliver",{delivery_id:id,delivery_name:name,delivery_price:delivery_price,price:price},function (data) {
			console.debug(data);
        });

    });


    $("input[name=delivery]").click(function(){
		$(this).parent().parent().addClass("cur").siblings().removeClass("cur");

		//>>获取到点击的tr的运费
		var price = $(this).closest("tr").find("td:eq(1)").text();
		//>>显示运费
		$("#delivery_price").text(price);
        // console.debug(price);
    });

	//支付方式修改
	$("#pay_modify").click(function(){
		$(this).hide();
		$(".pay_info").hide();
		$(".pay_select").show();
	})

	//>>确认支付方式
	$("#pay").click(function () {
		//>>获取到tr
		var tr = $("#payment").find(".cur");
		//>>获取到支付id和名称
		var id = tr.find("input").val();
		var name = tr.find("td:first").text();

        //>>发起ajax请求
        $.post("/order/ajax?filter=pay",{pay_type_id:id,pay_type_name:name},function (data) {
            console.debug(data);
        });
    });

	$("input[name=pay]").click(function(){
		$(this).parent().parent().addClass("cur").siblings().removeClass("cur");
	});

	//发票信息修改
	$("#receipt_modify").click(function(){
		$(this).hide();
		$(".receipt_info").hide();
		$(".receipt_select").show();
	})

	$(".company").click(function(){
		$(".company_input").removeAttr("disabled");
	});

	$(".personal").click(function(){
		$(".company_input").attr("disabled","disabled");
	});


});