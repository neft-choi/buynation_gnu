<?php
if (!defined("_GNUBOARD_")) exit;
?>

<!-- 최근 본 상품 -->
<div id="recent-viewed-panel"
    class="pointer-events-auto absolute top-0 right-0 z-50 flex flex-col h-full w-full max-w-110 translate-x-full bg-white">
    <div class="flex items-center justify-between border-b border-zinc-200 p-5">
        <p class="text-lg font-semibold text-gray-900">최근 본 상품</p>

        <button type="button" id="recent-viewed-close"
            class="inline-flex items-center justify-center w-9 h-9 rounded text-gray-700 hover:bg-gray-300 cursor-pointer"
            aria-label="최근 본 상품 닫기">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-x-icon lucide-x w-6 h-6">
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
            </svg>
        </button>
    </div>

    <?php if (get_view_today_items(true)) { ?>
        <div id="recent-viewed-actions" class="flex justify-end px-5 pt-4">
            <button type="button" id="recent-viewed-clear" class="text-sm text-gray-600 hover:underline cursor-pointer disabled:opacity-50">전체 삭제</button>
        </div>
    <?php } ?>

    <div id="recent-viewed-list" class="min-h-0 flex-1 overflow-y-auto p-5">
        <?php include(G5_SHOP_SKIN_PATH . '/boxtodayview.skin.php'); // 최근 본 상품
        ?>
    </div>
</div>

<script>
    // 최근 본 상품 패널 열기/닫기 제어
    $(function() {

        // 쇼핑몰 공용 오버레이 영역
        const $overlayRoot = $('#shop-overlay-root');

        $('html').addClass('gutter-stable');

        $(document).on('click', '#recent-viewed-clear', function() {
            const $button = $(this);
            $button.prop('disabled', true);

            $.ajax({
                url: g5_theme_shop_url + '/ajax.action.php',
                type: 'POST',
                data: {
                    action: 'clear_recent_viewed'
                },
                dataType: 'text'
            }).done(function(response) {
                if (response !== 'OK') {
                    alert(response);
                    return;
                }

                $('#recent-viewed-actions').remove();
                $('#recent-viewed-list').html(`
            <div class="flex h-full items-center justify-center text-gray-300">
                <p>최근 본 상품이 없습니다</p>
            </div>
        `);
                $('#recent-viewed-open').html(`
            <div class="w-6 h-6 border-2 rounded"></div>
            <div class="w-4 border rounded"></div>
        `);
            }).fail(function() {
                alert('최근 본 상품 삭제 요청에 실패했습니다. 다시 시도해 주세요.');
            }).always(function() {
                $button.prop('disabled', false);
            });
        });

        // 최근 본 상품 아이콘 클릭 시
        $(document).on('click', '#recent-viewed-open', function() {
            $overlayRoot.attr('data-active-panel', 'recent-viewed');
            $('#shop-overlay-backdrop').removeClass('hidden');
            $('#recent-viewed-panel').removeClass('translate-x-full');
            $('html').addClass('!overflow-hidden');
        });

        $(document).on('click', '#recent-viewed-close, #shop-overlay-backdrop', function() {
            if ($overlayRoot.attr('data-active-panel') !== 'recent-viewed') {
                return;
            }

            $('#shop-overlay-backdrop').addClass('hidden');
            $('#recent-viewed-panel').addClass('translate-x-full');
            $('html').removeClass('!overflow-hidden');
            $overlayRoot.removeAttr('data-active-panel');
        });
    });
</script>