<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// 검색 바가 커뮤니티에 있는지 쇼핑에 있는지 확인
$searchbar_mode = isset($searchbar_mode) ? (string) $searchbar_mode : 'shop';
if ($searchbar_mode !== 'community') {
    $searchbar_mode = 'shop';
}

$is_community_mode = ($searchbar_mode === 'community');

// 커뮤니티 검색 바일 경우 bbs/search.php 아니면 shop/search.php
$search_action = $is_community_mode
    ? G5_BBS_URL . '/search.php'
    : G5_SHOP_URL . '/search.php';

// 사용자 검색어 파라미터
$search_input_name = $is_community_mode ? 'stx' : 'q';

$search_input_id = $is_community_mode ? 'community_searchbar_input' : 'shop_searchbar_input';
$search_input_value = '';

if ($is_community_mode && isset($stx)) {
    $search_input_value = stripslashes(get_text(get_search_string($stx)));
} else if (!$is_community_mode && isset($q)) {
    $search_input_value = stripslashes(get_text(get_search_string($q)));
}
?>

<div>
    <form
        name="frmsearch_modern"
        class="flex items-center gap-2 w-full px-4 py-2 border-2 border-[var(--color-primary)] rounded-full bg-white"
        action="<?php echo $search_action; ?>"
        onsubmit="return searchbar_modern_submit(this, '<?php echo $searchbar_mode; ?>');">
        <label for="<?php echo $search_input_id; ?>" class="sound_only">검색어<span class="sound_only"> 필수</span></label>
        <?php if ($is_community_mode) { ?>
            <input type="hidden" name="sfl" value="wr_subject||wr_content">
            <input type="hidden" name="sop" value="and">
        <?php } ?>
        <input
            type="search"
            name="<?php echo $search_input_name; ?>"
            id="<?php echo $search_input_id; ?>"
            value="<?php echo $search_input_value; ?>"
            class="text-sm flex-auto focus-visible:outline-0"
            required
            placeholder="검색어를 입력해주세요">
        <button type="submit" id="shop_searchbar_submit" class="text-[var(--color-primary)]" value="검색">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search">
                <path d="m21 21-4.34-4.34" />
                <circle cx="11" cy="11" r="8" />
            </svg>
            <span class="sound_only">검색</span>
        </button>
    </form>
</div>
<script>
    // 제출 검증 함수
    function searchbar_modern_submit(form, mode) {
        const inputName = mode === 'community' ? 'stx' : 'q';
        const raw = form[inputName] ? form[inputName].value : '';
        const keyword = raw.trim();

        if (keyword.length < 2) {
            alert("검색어는 두글자 이상 입력하십시오.");
            if (form[inputName]) {
                form[inputName].select();
                form[inputName].focus();
            }
            return false;
        }

        if (mode === 'community') {
            let spaceCount = 0;
            for (let i = 0; i < keyword.length; i++) {
                if (keyword.charAt(i) === ' ') {
                    spaceCount++;
                }
            }

            if (spaceCount > 1) {
                alert("빠른 검색을 위하여 검색어에 공백은 한개만 입력할 수 있습니다.");
                if (form[inputName]) {
                    form[inputName].select();
                    form[inputName].focus();
                }
                return false;
            }
        }

        if (form[inputName]) {
            form[inputName].value = keyword;
        }

        return true;
    }
</script>
