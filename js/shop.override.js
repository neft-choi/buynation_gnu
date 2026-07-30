jQuery(function ($) {

    $(".2017_renewal_itemform select.it_supply").on("shop_sel_supply_process", function (e, param) {

        var add_exec = param.add_exec;
        var $el = $(this);
        var val = $el.val();

        //블랙캣77님이 해당 코드에 도움을 주셨습니다.
        var eq = $("select.it_supply").index($(this));
        var item = $el.closest(".sit_option").find("label").eq(eq).text();

        if (!val) {
            alert(item + "을(를) 선택해 주십시오.");
            return false;
        }

        var info = val.split(",");

        // 재고체크
        if (parseInt(info[2]) < 1) {
            alert(info[0] + "은(는) 재고가 부족하여 구매할 수 없습니다.");
            return false;
        }

        var id = item + chr(30) + info[0];
        var option = item + ":" + info[0];
        var price = info[1];
        var stock = info[2];

        // 금액 음수 체크
        if (parseInt(price) < 0) {
            alert("구매금액이 음수인 상품은 구매할 수 없습니다.");
            return false;
        }

        if (add_exec) {
            if (same_option_check(option))
                return false;

            add_sel_option(1, id, option, price, stock);
        }

        return false;
    });

    if (typeof add_sel_option === "function") {

        add_sel_option = (function () {
            var cached_function = add_sel_option;

            return function () {

                if ($(".2017_renewal_itemform").length) {
                    var a = arguments;
                    var type = a[0],
                        id = a[1],
                        option = a[2],
                        price = a[3],
                        stock = a[4];

                    var item_code = $("input[name='it_id[]']").val();
                    var opt = "";
                    var li_class = "sit_opt_list";
                    if (type)
                        li_class = "sit_spl_list";

                    var opt_prc;
                    if (parseInt(price) >= 0)
                        opt_prc = "+" + number_format(String(price)) + "원";
                    else
                        opt_prc = number_format(String(price)) + "원";

                    var opt = `
                                <li class="${li_class} text-xs pc:text-sm !p-3">
                                    <input type="hidden" name="io_type[${item_code}][]" value="${type}">
                                    <input type="hidden" name="io_id[${item_code}][]" value="${id}">
                                    <input type="hidden" name="io_value[${item_code}][]" value="${option}">
                                    <input type="hidden" class="io_price" value="${price}">
                                    <input type="hidden" class="io_stock" value="${stock}">

                                    <div class="flex items-center justify-between">
                                        <div class="opt_name">
                                            <span class="sit_opt_subj">${option}</span>
                                        </div>

                                        <button type="button" class="sit_opt_del">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="lucide lucide-x-icon lucide-x" aria-hidden="true">
                                                <path d="M18 6 6 18" />
                                                <path d="m6 6 12 12" />
                                            </svg>
                                            <span class="sound_only">삭제</span>
                                        </button>
                                    </div>

                                    <div class="opt_count mt-2 flex items-center justify-between">
                                        <div class="flex items-center gap-2 border border-gray-300 bg-white">
                                            <button type="button"
                                                class="sit_qty_minus shrink-0 inline-flex items-center justify-center w-8 h-8">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="lucide lucide-minus-icon lucide-minus w-4 h-4"
                                                    aria-hidden="true">
                                                    <path d="M5 12h14" />
                                                </svg>
                                                <span class="sound_only">감소</span>
                                            </button>

                                            <input type="text" name="ct_qty[${item_code}][]" value="1"
                                                class="num_input text-center w-6 !p-0 !rounded-none !border-0" size="1" readonly>

                                            <button type="button"
                                                class="sit_qty_plus shrink-0 inline-flex items-center justify-center w-8 h-8">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus w-4 h-4"
                                                    aria-hidden="true">
                                                    <path d="M5 12h14" />
                                                    <path d="M12 5v14" />
                                                </svg>
                                                <span class="sound_only">증가</span>
                                            </button>
                                        </div>

                                        <span class="sit_opt_prc">(${opt_prc})</span>
                                    </div>
                                </li>
                            `;

                    if ($("#sit_sel_option > ul").length < 1) {
                        $("#sit_sel_option").html("<ul id=\"sit_opt_added\"></ul>");
                        $("#sit_sel_option > ul").html(opt);
                    } else {
                        if (type) {
                            if ($("#sit_sel_option .sit_spl_list").length > 0) {
                                $("#sit_sel_option .sit_spl_list:last").after(opt);
                            } else {
                                if ($("#sit_sel_option .sit_opt_list").length > 0) {
                                    $("#sit_sel_option .sit_opt_list:last").after(opt);
                                } else {
                                    $("#sit_sel_option > ul").html(opt);
                                }
                            }
                        } else {
                            if ($("#sit_sel_option .sit_opt_list").length > 0) {
                                $("#sit_sel_option .sit_opt_list:last").after(opt);
                            } else {
                                if ($("#sit_sel_option .sit_spl_list").length > 0) {
                                    $("#sit_sel_option .sit_spl_list:first").before(opt);
                                } else {
                                    $("#sit_sel_option > ul").html(opt);
                                }
                            }
                        }
                    }

                    price_calculate();

                    $("#sit_sel_option").trigger("add_sit_sel_option", [opt]);

                } else {

                    cached_function.apply(this, arguments); // use .apply() to call it

                }   //end if

            };
        }());
    }   //end if check function

    if (typeof price_calculate === "function") {
        price_calculate = (function () {
            var cached_function = price_calculate;

            return function () {

                if ($(".2017_renewal_itemform").length) {

                    var it_price = parseInt($("input#it_price").val());

                    if (isNaN(it_price))
                        return;

                    var $el_prc = $("input.io_price");
                    var $el_qty = $("input[name^=ct_qty]");
                    var $el_type = $("input[name^=io_type]");
                    var price, type, qty, total = 0;

                    $el_prc.each(function (index) {
                        price = parseInt($(this).val());
                        qty = parseInt($el_qty.eq(index).val());
                        type = $el_type.eq(index).val();

                        if (type == "0") { // 선택옵션
                            total += (it_price + price) * qty;
                        } else { // 추가옵션
                            total += price * qty;
                        }
                    });

                    $("#sit_tot_price").empty().html("<span>총 금액 :</span><strong>" + number_format(String(total)) + "</strong> 원");

                    $("#sit_tot_price").trigger("price_calculate", [total]);
                } else {
                    cached_function.apply(this, arguments); // use .apply() to call it
                }

            };
        }());
    }   //end if check function

});