<?php
$sub_menu = '500210';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, "r");

$g5['title'] = '가격비교사이트';
include_once(G5_ADMIN_PATH . '/admin.head.php');
$pg_anchor = '<ul class="anchor">
<li><a href="#anc_pricecompare_info" data-tab-target="anc_pricecompare_info">가격비교사이트 연동 안내</a></li>
<li><a href="#anc_pricecompare_engine" data-tab-target="anc_pricecompare_engine">사이트별 엔진페이지 URL</a></li>
</ul>';
?>

<?php echo $pg_anchor; ?>

<section id="anc_pricecompare_info">
    <h2>가격비교사이트 연동 안내</h2>

    <div class="local_desc01 local_desc">
        <ol>
            <li>가격비교사이트는 네이버 지식쇼핑, 다음 쇼핑하우 등이 있습니다.</li>
            <li>앞서 나열한 가격비교사이트 중 희망하시는 사이트에 입점합니다.</li>
            <li><strong>사이트별 엔진페이지 URL</strong>을 참고하여 해당 엔진페이지 URL 을 입점하신 사이트에 알려주시면 됩니다.</li>
        </ol>
    </div>
</section>

<section id="anc_pricecompare_engine">
    <h2>사이트별 엔진페이지 URL</h2>

    <div class="local_desc01 local_desc">
        <p>사이트 명을 클릭하시면 해당 사이트로 이동합니다.</p>

        <dl class="price_engine">
            <dt><a href="http://shopping.naver.com/" target="_blank">네이버쇼핑</a></dt>
            <dd>
                <ul>
                    <li>입점 안내 : <a href="http://join.shopping.naver.com/join/intro.nhn" target="_blank">http://join.shopping.naver.com/join/intro.nhn</a></li>
                    <li>전체상품 URL : <a href="<?php echo G5_SHOP_URL; ?>/price/naver.php" target="_blank"><?php echo G5_SHOP_URL; ?>/price/naver.php</a></li>
                    <li>요약상품 URL : <a href="<?php echo G5_SHOP_URL; ?>/price/naver_summary.php" target="_blank"><?php echo G5_SHOP_URL; ?>/price/naver_summary.php</a></li>
                </ul>
            </dd>

            <dt><a href="" target="_blank">구글 쇼핑</a></dt>
            <dd>
                <ul>
                    <li>구글 Merchant Center : <a href="https://www.google.com/intl/ko_kr/retail/solutions/merchant-center" target="_blank">https://www.google.com/intl/ko_kr/retail/solutions/merchant-center</a></li>
                    <li>파일 이름 : google_feed.php</a></li>
                    <li>파일 URL : <a href="<?php echo G5_SHOP_URL; ?>/price/google_feed.php" target="_blank"><?php echo G5_SHOP_URL; ?>/price/google_feed.php</a></li>
                </ul>
            </dd>
            <dt>Feed 설명</dt>
            <dd>
                <ul>
                    <li>판매국가 <b>대한민국</b>, 언어 <b>한국어</b> 설정 기준입니다.</li>
                    <li>기본 피드 이름 : 쇼핑몰피드</li>
                    <li>상품 설명 : <b>it_basic</b> (상품기본설명을 필수 입력해주세요. HTML 태그는 자동 제거됩니다.)</li>
                </ul>
            </dd>

            <dt><a href="http://shopping.daum.net/" target="_blank">다음 쇼핑하우</a></dt>
            <dd>
                <ul>
                    <li>입점 안내 : <a href="https://shopping.biz.daum.net/join/main" target="_blank">https://shopping.biz.daum.net/join/main</a></li>
                    <li>전체상품 URL : <a href="<?php echo G5_SHOP_URL; ?>/price/daum.php" target="_blank"><?php echo G5_SHOP_URL; ?>/price/daum.php</a></li>
                    <li>요약상품 URL : <a href="<?php echo G5_SHOP_URL; ?>/price/daum_summary.php" target="_blank"><?php echo G5_SHOP_URL; ?>/price/daum_summary.php</a></li>
                </ul>
            </dd>
        </dl>
    </div>
</section>

<script>
    // 페이지 앵커 탭 제어
    const tabIds = Object.freeze([
        "anc_pricecompare_info",
        "anc_pricecompare_engine"
    ]);

    const getTabPanels = () =>
        tabIds
        .map((id) => document.getElementById(id))
        .filter((panel) => panel !== null);

    const getTabLinks = () =>
        Array.from(document.querySelectorAll('.anchor a[data-tab-target]'));

    const isValidTabId = (tabId) => tabIds.indexOf(tabId) > -1;

    const getHashTabId = () => window.location.hash.replace("#", "");

    const getInitialTabId = () => {
        const hashTabId = getHashTabId();

        if (isValidTabId(hashTabId)) {
            return hashTabId;
        }

        return tabIds[0];
    };

    const renderTab = (activeId) => {
        const panels = getTabPanels();
        const links = getTabLinks();

        panels.forEach((panel) => {
            const isActive = panel.id === activeId;
            if (isActive) {
                panel.style.visibility = "visible";
                panel.style.position = "";
                panel.style.height = "";
                panel.style.overflow = "";
                panel.style.pointerEvents = "";
            } else {
                panel.style.visibility = "hidden";
                panel.style.position = "absolute";
                panel.style.height = "0";
                panel.style.overflow = "hidden";
                panel.style.pointerEvents = "none";
            }
            panel.setAttribute("aria-hidden", isActive ? "false" : "true");
        });

        links.forEach((link) => {
            const targetId = link.getAttribute("data-tab-target") || "";
            const isActive = targetId === activeId;

            link.setAttribute("aria-selected", isActive ? "true" : "false");
            link.classList.toggle("tab-active", isActive);
        });
    };

    const activateTab = (tabId, syncHash = true) => {
        if (!isValidTabId(tabId)) {
            return;
        }

        renderTab(tabId);

        if (syncHash && window.location.hash !== "#" + tabId) {
            history.replaceState(null, "", "#" + tabId);
        }
    };

    const bindTabEvents = () => {
        const links = getTabLinks();

        links.forEach((link) => {
            link.addEventListener("click", (event) => {
                const tabId = link.getAttribute("data-tab-target") || "";

                if (!isValidTabId(tabId)) {
                    return;
                }

                event.preventDefault();
                activateTab(tabId);
                window.scrollTo(0, 0);
            });
        });

        window.addEventListener("hashchange", () => {
            const hashTabId = getHashTabId();

            if (isValidTabId(hashTabId)) {
                renderTab(hashTabId);
            }
        });
    };

    const initTabs = () => {
        bindTabEvents();
        activateTab(getInitialTabId(), false);
    };

    initTabs();
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');
