    (function() {
        const $ = (id) => document.getElementById(id);

        const overlay = $('overlay');
        const drawers = [...document.querySelectorAll('.drawer')];
        const conditionDrawer = $('conditionDrawer');
        const conditionProductDrawer = $('conditionProductDrawer');
        const applyDrawer = $('applyDrawer');
        const groupDrawer = $('groupDrawer');
        const productGroupDrawer = $('productGroupDrawer');

        const conditionProductTitle = $('conditionProductTitle');
        const conditionProductSub = $('conditionProductSub');
        const conditionProductConditionId = $('conditionProductConditionId');
        const conditionProductSearch = $('conditionProductSearch');
        const conditionProductFilter = $('conditionProductFilter');
        const conditionProductList = $('conditionProductList');
        const conditionProductSelectAll = $('conditionProductSelectAll');
        const conditionProductVisibleCount = $('conditionProductVisibleCount');
        const conditionProductSelectedCount = $('conditionProductSelectedCount');
        const conditionProductEmpty = $('conditionProductEmpty');
        const conditionProductSelectedHidden = $('conditionProductSelectedHidden');

        const conditionAppliedList = $('conditionAppliedList');
        const conditionAppliedEmpty = $('conditionAppliedEmpty');
        const conditionAppliedCount = $('conditionAppliedCount');

        const conditionSelectedIds = new Set();
        let conditionSearchTimer = null;
        let conditionSearchController = null;

        const openCreate = $('openCreate');
        const openGroupCreate = $('openGroupCreate');
        const conditionSearch = $('conditionSearch');
        const typeFilter = $('typeFilter');
        const emptySearch = $('emptySearch');

        const dcId = $('dc_id');
        const dcType = $('dc_type');
        const conditionName = $('conditionName');
        const baseFee = $('baseFee');
        const freeThreshold = $('freeThreshold');
        const repeatQuantity = $('repeatQuantity');
        const jejuUse = $('jejuUse');
        const jejuPrice = $('jejuPrice');
        const islandUse = $('islandUse');
        const islandPrice = $('islandPrice');
        const amountRangeRows = $('amountRangeRows');
        const addAmountRange = $('addAmountRange');
        const drawerTitle = $('drawerTitle');
        const feePreview = $('feePreview');

        const applyIds = $('applyProductIds');
        const applyCondition = $('applyCondition');
        const applyGroup = $('applyGroup');
        const openApplySecond = $('openApplySecond');
        const selectAllUngrouped = $('selectAllUngrouped');

        const moveGroupId = $('moveGroupId');
        const productGroupTitle = $('productGroupTitle');
        const productPickSearch = $('productPickSearch');
        const productSourceFilter = $('productSourceFilter');
        const productPickList = $('productPickList');
        const productPickCount = $('productPickCount');
        const emptyProductPick = $('emptyProductPick');
        const moveSelectedProductIds = $('moveSelectedProductIds');
        const moveSelectedCount = $('moveSelectedCount');

        const groupAppliedList = $('groupAppliedList');
        const groupAppliedEmpty = $('groupAppliedEmpty');
        const groupAppliedCount = $('groupAppliedCount');

        const moveSelectedIds = new Set();
        let moveSearchTimer = null;
        let moveSearchController = null;
        const moveForm = $('moveForm');

        function openDrawer(el) {
            if (!el || !overlay) return;
            drawers.forEach(d => d.classList.remove('open'));
            overlay.classList.add('open');
            el.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeDrawers() {
            if (overlay) overlay.classList.remove('open');
            drawers.forEach(d => d.classList.remove('open'));
            document.body.style.overflow = '';
        }

        document.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', closeDrawers));
        if (overlay) overlay.addEventListener('click', closeDrawers);

        document.querySelectorAll('.tab').forEach(b => b.addEventListener('click', () => {
            document.querySelectorAll('.tab').forEach(x => x.classList.remove('active'));
            document.querySelectorAll('.panel').forEach(x => x.classList.remove('active'));
            b.classList.add('active');
            const panel = $('panel-' + b.dataset.tab);
            if (panel) panel.classList.add('active');
        }));

        let activeFee = 'conditional';

        function setFeeType(type) {
            activeFee = type;
            if (dcType) dcType.value = type;
            document.querySelectorAll('.fee-type').forEach(b => b.classList.toggle('active', b.dataset.fee === type));
            const feeFields = $('feeFields');
            const amountRangeFields = $('amountRangeFields');
            const thresholdWrap = $('thresholdWrap');
            const quantityWrap = $('quantityWrap');
            if (feeFields) feeFields.style.display = (type === 'free' || type === 'amount_range') ? 'none' : 'block';
            if (amountRangeFields) amountRangeFields.style.display = type === 'amount_range' ? 'block' : 'none';
            if (thresholdWrap) thresholdWrap.style.display = type === 'conditional' ? 'block' : 'none';
            if (quantityWrap) quantityWrap.style.display = type === 'quantity' ? 'block' : 'none';
            updatePreview();
        }

        /*
         * 배송조건 목록 > 지역 추가비 > 보기
         *
         * 서버에서 미리 조회한 sendcost_items_json을 그대로 사용하므로
         * 보기 클릭 시 별도 AJAX 없이 즉시 표시합니다.
         */
        const sendcostViewOverlay = $('sendcostViewOverlay');
        const sendcostViewTitle = $('sendcostViewTitle');
        const sendcostViewSub = $('sendcostViewSub');
        const sendcostViewList = $('sendcostViewList');
        const sendcostViewEmpty = $('sendcostViewEmpty');
        const closeSendcostView = $('closeSendcostView');

        function escapeSendcostHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function closeSendcostViewModal() {
            if (!sendcostViewOverlay) return;

            sendcostViewOverlay.classList.remove('open');
            sendcostViewOverlay.setAttribute('aria-hidden', 'true');
        }

        function openSendcostViewModal(button) {
            if (
                !sendcostViewOverlay ||
                !sendcostViewList ||
                !sendcostViewEmpty
            ) {
                return;
            }

            let items = [];

            try {
                items = JSON.parse(
                    button.dataset.sendcostItems || '[]'
                );
            } catch (e) {
                items = [];
            }

            if (!Array.isArray(items)) {
                items = [];
            }

            const conditionName =
                String(button.dataset.conditionName || '').trim();

            if (sendcostViewTitle) {
                sendcostViewTitle.textContent =
                    conditionName
                        ? conditionName + ' · 지역 추가비'
                        : '지역 추가비';
            }

            if (sendcostViewSub) {
                sendcostViewSub.textContent =
                    '적용된 지역 추가비 ' +
                    items.length.toLocaleString('ko-KR') +
                    '건';
            }

            sendcostViewList.innerHTML = '';

            if (items.length === 0) {
                sendcostViewEmpty.style.display = 'block';
            } else {
                sendcostViewEmpty.style.display = 'none';

                items.forEach(item => {
                    const row = document.createElement('div');
                    row.className = 'sendcost-view-item';

                    const name = escapeSendcostHtml(item.name || '-');
                    const zip1 = escapeSendcostHtml(item.zip1 || '');
                    const zip2 = escapeSendcostHtml(item.zip2 || '');
                    const price = Number(item.price || 0)
                        .toLocaleString('ko-KR');

                    row.innerHTML = `
                        <div>
                            <strong>${name}</strong>
                            <small>우편번호 ${zip1} ~ ${zip2}</small>
                        </div>
                        <div class="sendcost-view-price">
                            + ${price}원
                        </div>
                    `;

                    sendcostViewList.appendChild(row);
                });
            }

            sendcostViewOverlay.classList.add('open');
            sendcostViewOverlay.setAttribute('aria-hidden', 'false');
        }

        document.querySelectorAll('.view-sendcosts').forEach(button => {
            button.addEventListener('click', () => {
                openSendcostViewModal(button);
            });
        });

        if (closeSendcostView) {
            closeSendcostView.addEventListener(
                'click',
                closeSendcostViewModal
            );
        }

        if (sendcostViewOverlay) {
            sendcostViewOverlay.addEventListener('click', event => {
                if (event.target === sendcostViewOverlay) {
                    closeSendcostViewModal();
                }
            });
        }

        document.addEventListener('keydown', event => {
            if (
                event.key === 'Escape' &&
                sendcostViewOverlay &&
                sendcostViewOverlay.classList.contains('open')
            ) {
                closeSendcostViewModal();
            }
        });

        document.querySelectorAll('.fee-type').forEach(b => b.addEventListener('click', () => setFeeType(b.dataset.fee)));

        function money(v) {
            return Number(v || 0).toLocaleString('ko-KR') + '원';
        }

        function updatePreview() {
            if (!feePreview) return;
            let t = '';
            if (activeFee === 'free') t = '항상 무료배송';
            else if (activeFee === 'paid') t = '주문 1건에 ' + money(baseFee ? baseFee.value : 0) + ' 부과';
            else if (activeFee === 'conditional') t = money(freeThreshold ? freeThreshold.value : 0) + ' 미만 ' + money(baseFee ? baseFee.value : 0) + ', 이상 무료';
            else if (activeFee === 'quantity') t = ((repeatQuantity && repeatQuantity.value) || 1) + '개마다 ' + money(baseFee ? baseFee.value : 0) + ' 반복';
            else t = '상품 주문금액 구간에 따라 배송비 계산';
            feePreview.innerHTML = '<strong>미리보기</strong><br>' + t;
        }

        ['baseFee', 'freeThreshold', 'repeatQuantity'].forEach(id => {
            const el = $(id);
            if (el) el.addEventListener('input', updatePreview);
        });

        function addRange(min = 0, max = '', fee = 0) {
            if (!amountRangeRows) return;
            const row = document.createElement('div');
            row.className = 'amount-range-row';
            row.innerHTML = `<label class="range-input"><input name="dc_range_min[]" type="number" min="0" value="${min}"><span>원</span></label><label class="range-input"><input name="dc_range_max[]" type="number" min="0" value="${max ?? ''}" placeholder="제한 없음"><span>원</span></label><label class="range-input"><input name="dc_range_price[]" type="number" min="0" value="${fee}"><span>원</span></label><button class="range-remove" type="button">×</button>`;
            row.querySelector('.range-remove').addEventListener('click', () => row.remove());
            amountRangeRows.appendChild(row);
        }

        if (addAmountRange) addAmountRange.addEventListener('click', () => addRange());

        function syncSendcostRowState() {
            document.querySelectorAll('#conditionDrawer .sendcost-row').forEach(row => {
                const checkbox = row.querySelector('.sendcost-check');
                const badge = row.querySelector('.sendcost-selected-badge');
                const selected = !!(checkbox && checkbox.checked);

                row.classList.toggle('selected', selected);

                if (badge) {
                    badge.style.display = selected ? 'inline-flex' : 'none';
                }
            });
        }

        function clearSendcostSelection() {
            document.querySelectorAll('#conditionDrawer .sendcost-check').forEach(check => {
                check.checked = false;
            });
            syncSendcostRowState();
        }

        function applySendcostSelection(ids) {
            const selected = new Set(
                (Array.isArray(ids) ? ids : []).map(v => String(v))
            );

            document.querySelectorAll('#conditionDrawer .sendcost-check').forEach(check => {
                check.checked = selected.has(String(check.value));
            });

            syncSendcostRowState();
        }

        document.querySelectorAll('#conditionDrawer .sendcost-check').forEach(check => {
            check.addEventListener('change', syncSendcostRowState);
        });

        function resetCondition() {
            if (dcId) dcId.value = '';
            if (conditionName) conditionName.value = '';
            if (baseFee) baseFee.value = 3000;
            if (freeThreshold) freeThreshold.value = 50000;
            if (repeatQuantity) repeatQuantity.value = 1;
            if (jejuUse) jejuUse.checked = false;
            if (islandUse) islandUse.checked = false;
            if (jejuPrice) jejuPrice.value = 3000;
            if (islandPrice) islandPrice.value = 5000;
            if (amountRangeRows) amountRangeRows.innerHTML = '';
            addRange(0, '', 0);
            clearSendcostSelection();
            if (drawerTitle) drawerTitle.textContent = '배송조건 추가';
            setFeeType('conditional');
        }

        if (openCreate) openCreate.addEventListener('click', () => {
            resetCondition();
            openDrawer(conditionDrawer);
        });

        document.querySelectorAll('.edit-condition').forEach(b => b.addEventListener('click', () => {
            resetCondition();
            if (dcId) dcId.value = b.dataset.id;
            if (conditionName) conditionName.value = b.dataset.name;
            if (baseFee) baseFee.value = b.dataset.price;
            if (freeThreshold) freeThreshold.value = b.dataset.minimum;
            if (repeatQuantity) repeatQuantity.value = b.dataset.qty;
            if (jejuUse) jejuUse.checked = b.dataset.jejuUse === '1';
            if (jejuPrice) jejuPrice.value = b.dataset.jejuPrice;
            if (islandUse) islandUse.checked = b.dataset.islandUse === '1';
            if (islandPrice) islandPrice.value = b.dataset.islandPrice;
            if (amountRangeRows) amountRangeRows.innerHTML = '';
            let ranges = [];
            try {
                ranges = JSON.parse(b.dataset.ranges || '[]');
            } catch (e) {}
            (ranges.length ? ranges : [{
                min: 0,
                max: null,
                fee: 0
            }]).forEach(r => addRange(r.min, r.max ?? '', r.fee));

            let sendcostIds = [];
            try {
                sendcostIds = JSON.parse(b.dataset.sendcosts || '[]');
            } catch (e) {
                sendcostIds = [];
            }

            /*
             * 수정 시 기존에 저장된 sendcostlist 선택값을 그대로 체크.
             */
            applySendcostSelection(sendcostIds);

            if (drawerTitle) drawerTitle.textContent = '배송조건 수정';
            setFeeType(b.dataset.type);
            openDrawer(conditionDrawer);
        }));


        /* 배송조건 목록의 "적용 상품 N개" 클릭 -> AJAX 상품 검색/선택 */
        function conditionProductItems() {
            return [...document.querySelectorAll('#conditionProductList .condition-product-item')];
        }

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function syncConditionSelectedHidden() {
            if (!conditionProductSelectedHidden) return;

            conditionProductSelectedHidden.innerHTML = '';

            conditionSelectedIds.forEach(itId => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'it_ids[]';
                input.value = itId;
                conditionProductSelectedHidden.appendChild(input);
            });

            if (conditionProductSelectedCount) {
                conditionProductSelectedCount.textContent = conditionSelectedIds.size;
            }
        }

        function refreshConditionProductPicker() {
            const items = conditionProductItems();
            let visible = 0;
            let visibleSelected = 0;

            items.forEach(item => {
                const checkbox = item.querySelector('input[type="checkbox"]');
                if (!checkbox) return;

                visible++;
                checkbox.checked = conditionSelectedIds.has(checkbox.value);
                item.classList.toggle('selected', checkbox.checked);

                if (checkbox.checked) {
                    visibleSelected++;
                }
            });

            if (conditionProductVisibleCount) {
                conditionProductVisibleCount.textContent = visible;
            }

            if (conditionProductSelectAll) {
                conditionProductSelectAll.checked = visible > 0 && visibleSelected === visible;
                conditionProductSelectAll.indeterminate = visibleSelected > 0 && visibleSelected < visible;
            }

            syncConditionSelectedHidden();
        }

        function renderConditionProductResults(items) {
            if (!conditionProductList || !conditionProductEmpty) return;

            conditionProductList.innerHTML = '';

            if (!Array.isArray(items) || items.length === 0) {
                conditionProductEmpty.style.display = 'block';
                conditionProductEmpty.innerHTML = '검색 결과가 없습니다.';
                if (conditionProductVisibleCount) conditionProductVisibleCount.textContent = '0';
                refreshConditionProductPicker();
                return;
            }

            conditionProductEmpty.style.display = 'none';

            items.forEach(item => {
                const itId = String(item.it_id || '');
                const currentConditionId = String(item.condition_id || '0');
                const targetConditionId = String(conditionProductConditionId ? conditionProductConditionId.value : '');

                // 현재 이 배송조건을 사용 중인 상품은 검색 결과에 나타날 때 선택 상태로 표시
                if (currentConditionId === targetConditionId) {
                    conditionSelectedIds.add(itId);
                }

                const label = document.createElement('label');
                label.className = 'product-pick condition-product-item';
                label.dataset.conditionId = currentConditionId;
                console.log(item)

                const checked = conditionSelectedIds.has(itId) ? ' checked' : '';
                const conditionName = item.dc_name ? escapeHtml(item.dc_name) : '미설정';
                const price = Number(item.it_price || 0).toLocaleString();
                label.innerHTML = `
                <input type="checkbox" value="${escapeHtml(itId)}"${checked}>
                <span class="product-thumb">${item.it_image || ''}</span>
                <span class="product-pick-copy">
                    <strong>${escapeHtml(item.it_name || '')}</strong>
                    <small>${escapeHtml(itId)} · ${price}원 · 현재 조건: ${conditionName}</small>
                </span>
                <span class="product-source ${currentConditionId === '0' ? 'ungrouped' : ''}">
                    ${conditionName}
                </span>
            `;

                conditionProductList.appendChild(label);
            });

            refreshConditionProductPicker();
        }


        /*
         * 현재 적용 상품은 별도 AJAX로 조회합니다.
         * 적용 상품 숫자와 동일한 donuts_delivery_product_settings 기준입니다.
         */
        function renderAppliedProductsAjax(items) {
            if (!conditionAppliedList || !conditionAppliedEmpty) return;

            conditionAppliedList.innerHTML = '';

            if (!Array.isArray(items) || items.length === 0) {
                conditionAppliedEmpty.style.display = 'block';
                conditionAppliedEmpty.textContent = '현재 적용된 상품이 없습니다.';
                if (conditionAppliedCount) conditionAppliedCount.textContent = '0';
                return;
            }

            conditionAppliedEmpty.style.display = 'none';
            if (conditionAppliedCount) conditionAppliedCount.textContent = String(items.length);

            items.forEach(item => {
                const row = document.createElement('div');
                row.className = 'product-pick';

                const price = Number(item.it_price || 0).toLocaleString();
                console.log(item, 'ttt');
                row.innerHTML = `
                 <span class="product-thumb">${item.it_image || ''}</span>
                <span class="product-pick-copy">
                    <strong>${escapeHtml(item.it_name || '(상품명 없음)')}</strong>
                    <small>${escapeHtml(item.it_id || '')} · ${price}원</small>
                </span>
            `;

                conditionAppliedList.appendChild(row);
            });
        }

        async function loadAppliedProductsAjax(conditionId) {
            if (!conditionAppliedList || !conditionAppliedEmpty) return;

            conditionAppliedList.innerHTML = '';
            conditionAppliedEmpty.style.display = 'block';
            conditionAppliedEmpty.textContent = '현재 적용 상품을 불러오는 중입니다.';
            if (conditionAppliedCount) conditionAppliedCount.textContent = '0';

            const params = new URLSearchParams({
                condition_id: String(conditionId || ''),
                brand_id: <?php echo json_encode($manage_brand_id, JSON_UNESCAPED_UNICODE); ?>
            });

            try {
                const response = await fetch(
                    './ajax.delivery_applied_products.php?' + params.toString(), {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    }
                );

                const raw = await response.text();
                let data;

                try {
                    data = JSON.parse(raw);
                } catch (e) {
                    console.error('적용 상품 AJAX 응답:', raw);
                    throw new Error('JSON_PARSE_ERROR');
                }

                if (!response.ok || !data.success) {
                    conditionAppliedList.innerHTML = '';
                    conditionAppliedEmpty.style.display = 'block';
                    conditionAppliedEmpty.textContent = data.message || '현재 적용 상품을 불러오지 못했습니다.';
                    if (conditionAppliedCount) conditionAppliedCount.textContent = '0';
                    return;
                }

                renderAppliedProductsAjax(data.items || []);
            } catch (e) {
                console.error(e);
                conditionAppliedList.innerHTML = '';
                conditionAppliedEmpty.style.display = 'block';
                conditionAppliedEmpty.textContent = '현재 적용 상품 조회 중 오류가 발생했습니다.';
                if (conditionAppliedCount) conditionAppliedCount.textContent = '0';
            }
        }

        async function searchConditionProducts() {
            if (!conditionProductSearch || !conditionProductConditionId) return;

            const keyword = conditionProductSearch.value.trim();
            const conditionId = conditionProductConditionId.value;
            const filterValue = conditionProductFilter ? conditionProductFilter.value : 'all';

            if (keyword === '') {
                if (conditionSearchController) {
                    conditionSearchController.abort();
                    conditionSearchController = null;
                }

                if (conditionProductList) conditionProductList.innerHTML = '';
                if (conditionProductEmpty) {
                    conditionProductEmpty.style.display = 'block';
                    conditionProductEmpty.innerHTML = '상품명 또는 상품코드를 입력하면 검색 결과가 이곳에 표시됩니다.';
                }
                if (conditionProductVisibleCount) conditionProductVisibleCount.textContent = '0';
                refreshConditionProductPicker();
                return;
            }

            if (conditionSearchController) {
                conditionSearchController.abort();
            }

            conditionSearchController = new AbortController();

            if (conditionProductEmpty) {
                conditionProductEmpty.style.display = 'block';
                conditionProductEmpty.innerHTML = '상품을 검색하고 있습니다...';
            }

            const params = new URLSearchParams({
                keyword: keyword,
                condition_id: conditionId,
                filter: filterValue,
                brand_id: <?php echo json_encode($manage_brand_id, JSON_UNESCAPED_UNICODE); ?>
            });

            try {
                const response = await fetch(
                    './ajax.delivery_product_search.php?' + params.toString(), {
                        method: 'GET',
                        credentials: 'same-origin',
                        signal: conditionSearchController.signal,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }
                );

                const data = await response.json();

                if (!data.success) {
                    if (conditionProductList) conditionProductList.innerHTML = '';
                    if (conditionProductEmpty) {
                        conditionProductEmpty.style.display = 'block';
                        conditionProductEmpty.innerHTML = escapeHtml(data.message || '상품 검색 중 오류가 발생했습니다.');
                    }
                    if (conditionProductVisibleCount) conditionProductVisibleCount.textContent = '0';
                    return;
                }

                renderConditionProductResults(data.items || []);
            } catch (error) {
                if (error && error.name === 'AbortError') return;

                if (conditionProductList) conditionProductList.innerHTML = '';
                if (conditionProductEmpty) {
                    conditionProductEmpty.style.display = 'block';
                    conditionProductEmpty.innerHTML = '상품 검색 중 통신 오류가 발생했습니다.';
                }
                if (conditionProductVisibleCount) conditionProductVisibleCount.textContent = '0';
            }
        }

        function scheduleConditionProductSearch() {
            if (conditionSearchTimer) {
                clearTimeout(conditionSearchTimer);
            }

            conditionSearchTimer = setTimeout(searchConditionProducts, 250);
        }

        function openConditionProductPicker(button) {
            if (!conditionProductDrawer || !conditionProductConditionId) return;

            const targetId = String(button.dataset.conditionId || '');
            const targetName = button.dataset.conditionName || '배송조건';

            conditionProductConditionId.value = targetId;
            conditionSelectedIds.clear();
            syncConditionSelectedHidden();

            if (conditionProductTitle) conditionProductTitle.textContent = `‘${targetName}’ 적용 상품`;
            if (conditionProductSub) {
                conditionProductSub.textContent = '상품명 또는 상품코드를 검색한 뒤 체크하여 배송조건을 적용합니다.';
            }

            if (conditionProductSearch) conditionProductSearch.value = '';
            if (conditionProductFilter) conditionProductFilter.value = 'all';
            if (conditionProductList) conditionProductList.innerHTML = '';
            if (conditionProductEmpty) {
                conditionProductEmpty.style.display = 'block';
                conditionProductEmpty.innerHTML = '상품명 또는 상품코드를 입력하면 검색 결과가 이곳에 표시됩니다.';
            }
            if (conditionProductVisibleCount) conditionProductVisibleCount.textContent = '0';

            openDrawer(conditionProductDrawer);

            loadAppliedProductsAjax(targetId);

            setTimeout(() => {
                if (conditionProductSearch) conditionProductSearch.focus();
            }, 50);
        }

        document.querySelectorAll('.condition-products-btn').forEach(button => {
            button.addEventListener('click', () => openConditionProductPicker(button));
        });

        if (conditionProductSearch) {
            conditionProductSearch.addEventListener('input', scheduleConditionProductSearch);
            conditionProductSearch.addEventListener('keydown', event => {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    if (conditionSearchTimer) clearTimeout(conditionSearchTimer);
                    searchConditionProducts();
                }
            });
        }

        if (conditionProductFilter) {
            conditionProductFilter.addEventListener('change', searchConditionProducts);
        }

        if (conditionProductList) {
            conditionProductList.addEventListener('change', event => {
                if (!event.target.matches('input[type="checkbox"]')) return;

                const itId = event.target.value;

                if (event.target.checked) {
                    conditionSelectedIds.add(itId);
                } else {
                    conditionSelectedIds.delete(itId);
                }

                refreshConditionProductPicker();
            });
        }

        if (conditionProductSelectAll) {
            conditionProductSelectAll.addEventListener('change', () => {
                conditionProductItems().forEach(item => {
                    const checkbox = item.querySelector('input[type="checkbox"]');
                    if (!checkbox) return;

                    checkbox.checked = conditionProductSelectAll.checked;

                    if (checkbox.checked) {
                        conditionSelectedIds.add(checkbox.value);
                    } else {
                        conditionSelectedIds.delete(checkbox.value);
                    }
                });

                refreshConditionProductPicker();
            });
        }

        const conditionProductForm = $('conditionProductForm');
        if (conditionProductForm) {
            conditionProductForm.addEventListener('submit', event => {
                syncConditionSelectedHidden();

                if (conditionSelectedIds.size === 0) {
                    event.preventDefault();
                    showToast('적용할 상품을 하나 이상 선택해 주세요.');
                    return false;
                }

                return true;
            });
        }

        function filterConditions() {
            if (!conditionSearch || !typeFilter) return;
            const q = conditionSearch.value.trim().toLowerCase();
            const t = typeFilter.value;
            let n = 0;
            document.querySelectorAll('#conditionRows tr').forEach(r => {
                const show = (r.dataset.name || '').toLowerCase().includes(q) && (t === 'all' || r.dataset.type === t);
                r.style.display = show ? '' : 'none';
                if (show) n++;
            });
            if (emptySearch) emptySearch.style.display = n ? 'none' : 'block';
        }

        if (conditionSearch) conditionSearch.addEventListener('input', filterConditions);
        if (typeFilter) typeFilter.addEventListener('change', filterConditions);

        if (openGroupCreate) openGroupCreate.addEventListener('click', () => openDrawer(groupDrawer));
        document.querySelectorAll('.choice-card').forEach(c => c.addEventListener('click', () => {
            c.parentNode.querySelectorAll('.choice-card').forEach(x => x.classList.remove('selected'));
            c.classList.add('selected');
            const radio = c.querySelector('input');
            if (radio) radio.checked = true;
        }));

        /* 그룹 미지정 상품 -> 배송조건/그룹 적용 */
        function openApply(ids, conditionId) {
            if (!applyIds || !applyDrawer) return;
            applyIds.innerHTML = '';
            ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'it_ids[]';
                input.value = id;
                applyIds.appendChild(input);
            });
            if (conditionId && applyCondition) applyCondition.value = conditionId;
            openDrawer(applyDrawer);
        }

        document.querySelectorAll('.row-shipping-btn').forEach(b => b.addEventListener('click', e => {
            e.stopPropagation();
            const r = b.closest('.product-row');
            if (r) openApply([r.dataset.itId], r.dataset.conditionId);
        }));

        function selectedRows() {
            return [...document.querySelectorAll('#ungroupedProductList .row-check:checked')].map(x => x.value);
        }

        function refreshSelected() {
            const ids = selectedRows();
            if (openApplySecond) {
                openApplySecond.disabled = !ids.length;
                openApplySecond.textContent = '선택 상품 배송설정 (' + ids.length + ')';
            }
            const all = [...document.querySelectorAll('#ungroupedProductList .row-check')];
            if (selectAllUngrouped) {
                selectAllUngrouped.checked = all.length > 0 && ids.length === all.length;
                selectAllUngrouped.indeterminate = ids.length > 0 && ids.length < all.length;
            }
            all.forEach(c => {
                const row = c.closest('.product-row');
                if (row) row.classList.toggle('selected', c.checked);
            });
        }

        document.querySelectorAll('#ungroupedProductList .row-check').forEach(c => c.addEventListener('change', refreshSelected));

        if (selectAllUngrouped) selectAllUngrouped.addEventListener('change', () => {
            document.querySelectorAll('#ungroupedProductList .row-check').forEach(c => c.checked = selectAllUngrouped.checked);
            refreshSelected();
        });

        if (openApplySecond) openApplySecond.addEventListener('click', () => {
            const ids = selectedRows();
            if (!ids.length) {
                alert('배송비를 적용할 상품을 한 개 이상 선택해 주세요.');
                return;
            }
            openApply(ids, 0);
        });

        /* 묶음배송 그룹 -> 기존 상품 추가/이동 : AJAX 상품검색 */
        function escapeMoveHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function syncMoveSelectedInputs() {
            if (!moveSelectedProductIds) return;

            moveSelectedProductIds.innerHTML = '';

            moveSelectedIds.forEach(itId => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'it_ids[]';
                input.value = itId;
                moveSelectedProductIds.appendChild(input);
            });

            if (moveSelectedCount) {
                moveSelectedCount.textContent = String(moveSelectedIds.size);
            }
        }

        function renderMoveProducts(items) {
            if (!productPickList || !emptyProductPick) return;

            productPickList.innerHTML = '';

            if (!Array.isArray(items) || items.length === 0) {
                emptyProductPick.style.display = 'block';
                emptyProductPick.textContent = '검색 결과가 없습니다.';
                if (productPickCount) productPickCount.textContent = '0';
                return;
            }

            emptyProductPick.style.display = 'none';
            if (productPickCount) productPickCount.textContent = String(items.length);

            items.forEach(item => {
                const itId = String(item.it_id || '');
                const groupId = String(item.group_id || '0');
                const groupName = item.dg_name || '그룹 미지정';
                const conditionName = item.dc_name || '배송조건 미설정';

                const label = document.createElement('label');
                label.className = 'product-pick';
                label.dataset.group = groupId;

                const checked = moveSelectedIds.has(itId);

                label.innerHTML = `
                <input type="checkbox"
                       value="${escapeMoveHtml(itId)}"
                       ${checked ? 'checked' : ''}>
                 <span class="product-thumb">${item.it_image || ''}</span>
                <span class="product-pick-copy">
                    <strong>${escapeMoveHtml(item.it_name || '')}</strong>
                    <small>
                        ${escapeMoveHtml(itId)} ·
                        ${escapeMoveHtml(conditionName)}
                    </small>
                </span>
                <span class="product-source ${groupId === '0' ? 'ungrouped' : ''}">
                    ${escapeMoveHtml(groupName)}
                </span>
            `;

                label.classList.toggle('selected', checked);
                productPickList.appendChild(label);
            });
        }


        function renderGroupAppliedProducts(items) {
            if (!groupAppliedList || !groupAppliedEmpty) return;

            groupAppliedList.innerHTML = '';

            if (!Array.isArray(items) || items.length === 0) {
                groupAppliedEmpty.style.display = 'block';
                groupAppliedEmpty.textContent = '현재 이 묶음배송 그룹에 적용된 상품이 없습니다.';
                if (groupAppliedCount) groupAppliedCount.textContent = '0';
                return;
            }

            groupAppliedEmpty.style.display = 'none';
            if (groupAppliedCount) {
                groupAppliedCount.textContent = String(items.length);
            }

            items.forEach(item => {
                const row = document.createElement('div');
                row.className = 'product-pick';

                const price = Number(item.it_price || 0).toLocaleString();

                row.dataset.itId = String(item.it_id || '');

                row.innerHTML = `
                 <span class="product-thumb">${item.it_image || ''}</span>
                <span class="product-pick-copy">
                    <strong>${escapeMoveHtml(item.it_name || '(상품명 없음)')}</strong>
                    <small>
                        ${escapeMoveHtml(item.it_id || '')} ·
                        ${price}원 ·
                        ${escapeMoveHtml(item.dc_name || '배송조건 미설정')}
                    </small>
                </span>
                <button
                    type="button"
                    class="btn btn-small group-applied-remove"
                    data-it-id="${escapeMoveHtml(item.it_id || '')}"
                >적용 해제</button>
            `;

                groupAppliedList.appendChild(row);
            });
        }

        async function loadGroupAppliedProducts(groupId) {
            if (!groupAppliedList || !groupAppliedEmpty) return;

            groupAppliedList.innerHTML = '';
            groupAppliedEmpty.style.display = 'block';
            groupAppliedEmpty.textContent = '현재 적용 상품을 불러오는 중입니다.';
            if (groupAppliedCount) groupAppliedCount.textContent = '0';

            const params = new URLSearchParams({
                group_id: String(groupId || ''),
                brand_id: <?php echo json_encode($manage_brand_id, JSON_UNESCAPED_UNICODE); ?>
            });

            try {
                const response = await fetch(
                    './ajax.delivery_group_applied_products.php?' + params.toString(), {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    }
                );

                const raw = await response.text();
                let data;

                try {
                    data = JSON.parse(raw);
                } catch (e) {
                    console.error('묶음배송 현재 적용상품 응답:', raw);
                    throw new Error('JSON_PARSE_ERROR');
                }

                if (!response.ok || !data.success) {
                    groupAppliedList.innerHTML = '';
                    groupAppliedEmpty.style.display = 'block';
                    groupAppliedEmpty.textContent =
                        data.message || '현재 적용 상품을 불러오지 못했습니다.';
                    if (groupAppliedCount) groupAppliedCount.textContent = '0';
                    return;
                }

                renderGroupAppliedProducts(data.items || []);
            } catch (e) {
                console.error(e);

                groupAppliedList.innerHTML = '';
                groupAppliedEmpty.style.display = 'block';
                groupAppliedEmpty.textContent =
                    '현재 적용 상품 조회 중 오류가 발생했습니다.';
                if (groupAppliedCount) groupAppliedCount.textContent = '0';
            }
        }

        /*
         * 묶음배송 현재 적용상품 -> 적용 해제
         *
         * 상품의 배송조건(condition_id)은 유지하고
         * group_id만 NULL로 변경합니다.
         */
        if (groupAppliedList) {
            groupAppliedList.addEventListener('click', event => {
                const button = event.target.closest('.group-applied-remove');
                if (!button) return;

                event.preventDefault();
                event.stopPropagation();

                const itId = String(button.dataset.itId || '').trim();
                const groupId = moveGroupId ? String(moveGroupId.value || '').trim() : '';

                if (!itId || !groupId) {
                    alert('해제할 상품 또는 묶음배송 그룹 정보가 없습니다.');
                    return;
                }

                if (!confirm('이 상품을 현재 묶음배송 그룹에서 해제하시겠습니까?\\n배송조건은 유지되고 묶음배송 그룹만 해제됩니다.')) {
                    return;
                }

                const form = document.createElement('form');
                form.method = 'post';
                form.action = './deliverymanage_update.php';
                form.style.display = 'none';

                const fields = {
                    token: <?php echo json_encode($admin_token, JSON_UNESCAPED_UNICODE); ?>,
                    brand_id: <?php echo json_encode($manage_brand_id, JSON_UNESCAPED_UNICODE); ?>,
                    action: 'remove_group_product',
                    group_id: groupId,
                    it_id: itId
                };

                Object.keys(fields).forEach(name => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = name;
                    input.value = fields[name];
                    form.appendChild(input);
                });

                document.body.appendChild(form);
                form.submit();
            });
        }

        async function searchMoveProducts() {
            if (!productPickSearch || !productSourceFilter || !moveGroupId) return;

            const keyword = productPickSearch.value.trim();
            const source = productSourceFilter.value || 'all';
            const targetGroupId = String(moveGroupId.value || '');

            if (keyword === '') {
                if (moveSearchController) {
                    moveSearchController.abort();
                    moveSearchController = null;
                }

                if (productPickList) productPickList.innerHTML = '';
                if (emptyProductPick) {
                    emptyProductPick.style.display = 'block';
                    emptyProductPick.textContent = '상품명 또는 상품코드를 입력하면 검색 결과가 표시됩니다.';
                }
                if (productPickCount) productPickCount.textContent = '0';
                return;
            }

            if (moveSearchController) {
                moveSearchController.abort();
            }

            moveSearchController = new AbortController();

            if (emptyProductPick) {
                emptyProductPick.style.display = 'block';
                emptyProductPick.textContent = '상품을 검색하고 있습니다...';
            }

            const params = new URLSearchParams({
                keyword: keyword,
                source_group: source,
                target_group_id: targetGroupId,
                brand_id: <?php echo json_encode($manage_brand_id, JSON_UNESCAPED_UNICODE); ?>
            });

            try {
                const response = await fetch(
                    './ajax.delivery_group_product_search.php?' + params.toString(), {
                        method: 'GET',
                        credentials: 'same-origin',
                        signal: moveSearchController.signal,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    }
                );

                const raw = await response.text();
                let data;

                try {
                    data = JSON.parse(raw);
                } catch (e) {
                    console.error('묶음배송 상품검색 응답:', raw);
                    throw new Error('JSON_PARSE_ERROR');
                }

                if (!response.ok || !data.success) {
                    if (productPickList) productPickList.innerHTML = '';
                    if (emptyProductPick) {
                        emptyProductPick.style.display = 'block';
                        emptyProductPick.textContent = data.message || '상품 검색 중 오류가 발생했습니다.';
                    }
                    if (productPickCount) productPickCount.textContent = '0';
                    return;
                }

                renderMoveProducts(data.items || []);
            } catch (e) {
                if (e && e.name === 'AbortError') return;

                console.error(e);

                if (productPickList) productPickList.innerHTML = '';
                if (emptyProductPick) {
                    emptyProductPick.style.display = 'block';
                    emptyProductPick.textContent = '상품 검색 중 통신 오류가 발생했습니다.';
                }
                if (productPickCount) productPickCount.textContent = '0';
            }
        }

        function scheduleMoveProductSearch() {
            if (moveSearchTimer) {
                clearTimeout(moveSearchTimer);
            }

            moveSearchTimer = setTimeout(searchMoveProducts, 250);
        }

        if (productPickList) {
            productPickList.addEventListener('change', event => {
                const checkbox = event.target.closest('input[type="checkbox"]');
                if (!checkbox) return;

                const itId = String(checkbox.value || '');
                const row = checkbox.closest('.product-pick');

                if (checkbox.checked) {
                    moveSelectedIds.add(itId);
                } else {
                    moveSelectedIds.delete(itId);
                }

                if (row) {
                    row.classList.toggle('selected', checkbox.checked);
                }

                syncMoveSelectedInputs();
            });

            productPickList.addEventListener('click', event => {
                if (event.target.matches('input[type="checkbox"]')) return;

                const row = event.target.closest('.product-pick');
                if (!row) return;

                const checkbox = row.querySelector('input[type="checkbox"]');
                if (!checkbox) return;

                event.preventDefault();
                checkbox.checked = !checkbox.checked;
                checkbox.dispatchEvent(new Event('change', {
                    bubbles: true
                }));
            });
        }

        document.querySelectorAll('.group-products').forEach(button => {
            button.addEventListener('click', () => {
                const card = button.closest('.group-card');
                if (!card || !moveGroupId) return;

                moveGroupId.value = card.dataset.groupId || '';

                if (productGroupTitle) {
                    productGroupTitle.textContent =
                        '‘' + (card.dataset.groupName || '') + '’으로 상품 이동';
                }

                moveSelectedIds.clear();
                syncMoveSelectedInputs();

                if (productPickSearch) productPickSearch.value = '';
                if (productSourceFilter) productSourceFilter.value = 'all';
                if (productPickList) productPickList.innerHTML = '';
                if (productPickCount) productPickCount.textContent = '0';

                if (emptyProductPick) {
                    emptyProductPick.style.display = 'block';
                    emptyProductPick.textContent =
                        '상품명 또는 상품코드를 입력하면 검색 결과가 표시됩니다.';
                }

                openDrawer(productGroupDrawer);

                // 선택한 묶음배송 그룹에 현재 적용된 상품 목록 표시
                loadGroupAppliedProducts(moveGroupId.value);

                setTimeout(() => {
                    if (productPickSearch) productPickSearch.focus();
                }, 50);
            });
        });

        if (productPickSearch) {
            productPickSearch.addEventListener('input', scheduleMoveProductSearch);

            productPickSearch.addEventListener('keydown', event => {
                if (event.key === 'Enter') {
                    event.preventDefault();

                    if (moveSearchTimer) {
                        clearTimeout(moveSearchTimer);
                    }

                    searchMoveProducts();
                }
            });
        }

        if (productSourceFilter) {
            productSourceFilter.addEventListener('change', () => {
                if (productPickSearch && productPickSearch.value.trim() !== '') {
                    searchMoveProducts();
                }
            });
        }

        if (moveForm) {
            moveForm.addEventListener('submit', event => {
                syncMoveSelectedInputs();

                if (moveSelectedIds.size === 0) {
                    event.preventDefault();
                    alert('이동할 상품을 한 개 이상 선택해 주세요.');
                    return false;
                }

                return true;
            });
        }

        const applyForm = $('applyForm');
        if (applyForm) applyForm.addEventListener('submit', e => {
            if (!applyIds || !applyIds.querySelector('input[name="it_ids[]"]')) {
                e.preventDefault();
                alert('배송비를 적용할 상품을 한 개 이상 선택해 주세요.');
            }
        });

        refreshSelected();
        updatePreview();
    })();
