<?php
if (!defined('_GNUBOARD_'))
    exit; // 개별 페이지 접근 불가

global $g5;

$current_ca_id = (string) $this->ca_id;
$current_depth1_ca_id = substr($current_ca_id, 0, 2);
$current_depth2_ca_id = substr($current_ca_id, 0, 4);

// 1차 카테고리
// $depth1_sql = " select ca_id, ca_name
//                 from {$g5['g5_shop_category_table']}
//                 where length(ca_id) = 2
//                 and ca_use = '1'
//                 order by ca_order, ca_id ";

// $depth1_result = sql_query($depth1_sql);

// $depth1_categories = [];

// while ($depth1_row = sql_fetch_array($depth1_result)) {
//     $depth1_categories[] = $depth1_row;
// }

// 2차 카테고리
$depth2_sql = " select ca_id, ca_name
                from {$g5['g5_shop_category_table']}
                where ca_id like '{$current_depth1_ca_id}%'
                and length(ca_id) = 4
                and ca_use = '1'
                order by ca_order, ca_id ";

$depth2_result = sql_query($depth2_sql);

$depth2_categories = [];

while ($depth2_row = sql_fetch_array($depth2_result)) {
    $depth2_categories[] = $depth2_row;
}

// 3차 카테고리
$depth3_sql = " select ca_id, ca_name
                from {$g5['g5_shop_category_table']}
                where ca_id like '{$current_depth1_ca_id}%'
                  and length(ca_id) = 6
                  and ca_use = '1'
                order by ca_order, ca_id ";

$depth3_result = sql_query($depth3_sql);

$depth3_categories = [];

while ($depth3_row = sql_fetch_array($depth3_result)) {
    $depth2_parent_ca_id = substr($depth3_row['ca_id'], 0, 4);
    $depth3_categories[$depth2_parent_ca_id][] = $depth3_row;
}
?>

<section class="category-menu p-0 pc:p-4">
    <h2 class="hidden pc:block text-base font-bold border-b pb-4">카테고리</h2>

    <ul class="pt-0 pc:pt-4 space-y-2 text-base pc:text-sm">
        <?php foreach ($depth2_categories as $depth2_category) { ?>
            <?php
            $is_depth2_active = ($current_ca_id === $depth2_category['ca_id']);
            $is_depth2_accordion_open = ($current_depth2_ca_id === $depth2_category['ca_id']);
            $depth3_list_id = 'depth3-category-' . $depth2_category['ca_id'];
            ?>
            <li>
                <div class="flex items-center justify-between">
                    <a href="<?php echo shop_category_url($depth2_category['ca_id']); ?>"
                        class="<?php echo $is_depth2_active ? 'font-semibold' : ''; ?>">
                        <?php echo $depth2_category['ca_name']; ?>
                    </a>

                    <?php if (isset($depth3_categories[$depth2_category['ca_id']])) { ?>
                        <button type="button" class="category-accordion-toggle inline-flex items-center justify-center w-6 h-6"
                            aria-expanded="<?php echo $is_depth2_accordion_open ? 'true' : 'false'; ?>"
                            aria-controls="<?php echo $depth3_list_id; ?>"
                            aria-label="3차 카테고리 아코디언">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="category-accordion-icon lucide lucide-chevron-down-icon lucide-chevron-down w-4 h-4 transition-transform <?php echo $is_depth2_accordion_open ? 'rotate-180' : ''; ?>">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>
                    <?php } ?>
                </div>

                <?php if (isset($depth3_categories[$depth2_category['ca_id']])) { ?>
                    <ul id="<?php echo $depth3_list_id; ?>" class="<?php echo $is_depth2_accordion_open ? '' : 'hidden'; ?> ml-4 space-y-1">
                        <?php foreach ($depth3_categories[$depth2_category['ca_id']] as $depth3_category) { ?>
                            <?php $is_depth3_active = ($current_ca_id === $depth3_category['ca_id']); ?>
                            <li class="first:mt-1">
                                <div>
                                    <a href="<?php echo shop_category_url($depth3_category['ca_id']); ?>"
                                        class="<?php echo $is_depth3_active ? 'font-semibold' : ''; ?>">
                                        <?php echo $depth3_category['ca_name']; ?>
                                    </a>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </li>
        <?php } ?>
    </ul>

    <script>
        $(function() {
            $('.category-menu').on('click', '.category-accordion-toggle', function() {
                const $button = $(this);
                const panelId = $button.attr('aria-controls');
                const $panel = $('#' + panelId);
                const isExpanded = $button.attr('aria-expanded') === 'true';

                $panel.toggleClass('hidden', isExpanded);
                $button.attr('aria-expanded', String(!isExpanded));
                $button.find('.category-accordion-icon').toggleClass('rotate-180', !isExpanded);
            });
        });
    </script>
</section>