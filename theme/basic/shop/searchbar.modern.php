<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
?>
<div class="shop-searchbar-block">
    <form
        name="frmsearch_modern"
        class="shop-searchbar-form"
        action="<?php echo G5_SHOP_URL; ?>/search.php"
        onsubmit="return shop_searchbar_submit(this);">
        <label for="shop_searchbar_input" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
        <input
            type="text"
            name="q"
            value="<?php echo stripslashes(get_text(get_search_string($q))); ?>"
            id="shop_searchbar_input"
            class="shop-searchbar-input"
            required
            placeholder="검색어를 입력해주세요">
        <button type="submit" id="shop_searchbar_submit" class="shop-searchbar-submit" value="검색">
            <i class="fa fa-search" aria-hidden="true"></i>
            <span class="sound_only">검색</span>
        </button>
    </form>
</div>
<script>
    function shop_searchbar_submit(form) {
        if (form.q.value.length < 2) {
            alert("검색어는 두글자 이상 입력하십시오.");
            form.q.select();
            form.q.focus();
            return false;
        }
        return true;
    }
</script>