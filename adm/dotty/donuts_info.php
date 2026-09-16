<?php
$sub_menu = '730200';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '도넛 정보 관리';
require_once '../admin.head.php';
?>

<section>
    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-3">
        <p class="mt-1 text-gray-400">도트가 확인하는 한 줄 소개와 더보기 콘텐츠, 가입·공개 조건을 관리합니다.</p>
        <div class="flex items-center gap-2">
            <button type="button" class="shrink-0 border border-gray-300 rounded-lg bg-white px-3 py-2 text-gray-900 font-bold">
                도넛 미리보기
            </button>
            <button type="button" class="shrink-0 rounded-lg bg-amber-300 px-3 py-2 text-gray-900 font-bold">
                변경사항 저장
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 pc:grid-cols-2 gap-4 mt-4">
        <div class="pc:col-span-2 grid grid-cols-1 pc:grid-cols-[6fr_4fr] gap-4">
            <div class="grid grid-cols-1 pc:grid-cols-2 gap-3 border border-gray-300 rounded-lg bg-white p-3">
                <div>
                    <label class="block font-bold mb-2">도넛 이름</label>
                    <input type="text" class="w-full border border-gray-300 rounded-lg p-3" value="테니스 커뮤니티">
                </div>

                <div>
                    <label class="block font-bold mb-2">카테고리</label>
                    <input type="text" class="w-full border border-gray-300 rounded-lg p-3" value="테니스 커뮤니티">
                </div>

                <div class="pc:col-span-2">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block font-bold">한 줄 소개</label>
                        <span class="block text-2xs text-gray-400">가입 안내 팝업에 표시됩니다.</span>
                    </div>

                    <textarea class="w-full border border-gray-300 rounded-lg p-3"></textarea>
                </div>

                <div class="pc:col-span-2">
                    <label class="block font-bold mb-2">대표 이미지</label>
                    <div class="flex items-center justify-between rounded-lg bg-gray-100 px-3 py-2">
                        <input type="file" id="donuts-cover-image" class="hidden" accept=".jpg,.jpeg,.png">
                        <input type="text" id="donuts-cover-image-view" readonly class="min-w-0 flex-1 bg-transparent text-gray-500 outline-none" placeholder="선택된 파일이 없습니다.">
                        <label for="donuts-cover-image" class="shrink-0 cursor-pointer rounded-lg border border-gray-300 bg-white px-3 py-2 font-bold text-gray-900 hover:bg-gray-50">
                            이미지 변경
                        </label>
                    </div>
                    <span class="mt-1 block text-2xs text-gra y-400">권장 비율 16:7 · JPG, PNG 형식을 사용합니다.</span>
                </div>
            </div>

            <div class="border border-gray-300 rounded-lg bg-white p-3">
                <div class="border border-gray-300 rounded-lg bg-blue-50">
                    <div class="px-3 pt-3">
                        <div class="h-40 rounded-lg bg-green-800"></div>
                    </div>

                    <div class="p-3">
                        <div class="flex items-end gap-2 justify-between">
                            <div>
                                <h3 class="text-base font-bold text-gray-900">테니스 커뮤니티</h3>

                                <p class="mt-1 truncate text-2xs text-gray-400">
                                    테니스를 사랑하는 도트들의 즐거운 소통 공간입니다.
                                </p>
                            </div>
                            <button type="button" class="donuts-preview-modal-open shrink-0 rounded-full border border-gray-300 px-2 py-1 text-2xs font-bold text-gray-900">
                                더보기
                            </button>
                        </div>

                        <div class="mt-3 flex items-center justify-between gap-3 rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs text-gray-900">
                            <span class="min-w-0 flex-1 truncate">[공지] 오프라인 모임 신청 안내</span>
                            <span class="shrink-0 font-bold">+2</span>
                        </div>
                    </div>

                    <ul id="preview-tabs" class="grid grid-cols-4 border-t border-gray-300">
                        <li>
                            <button type="button" aria-pressed="false" class="w-full border-b-2 border-transparent px-1 py-3 text-2xs text-gray-700">
                                추천상품
                            </button>
                        </li>
                        <li>
                            <button type="button" aria-pressed="false" class="w-full border-b-2 border-transparent px-1 py-3 text-2xs text-gray-700">
                                전체공지
                            </button>
                        </li>
                        <li>
                            <button type="button" aria-pressed="true" class="w-full border-b-2 border-gray-900 px-1 py-3 text-2xs font-bold text-gray-900">
                                커뮤니티 01
                            </button>
                        </li>
                        <li>
                            <button type="button" aria-pressed="false" class="w-full border-b-2 border-transparent px-1 py-3 text-2xs text-gray-700">
                                커뮤니티 02
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="pc:col-span-2 mt-4 border border-gray-300 rounded-lg bg-white p-3">
            <div class="flex items-center justify-between gap-3 border-b border-gray-300 pb-3">
                <div>
                    <h3 class="text-base font-bold text-gray-900">커뮤니티 소개 · 활동 규칙</h3>
                    <p class="mt-1 text-2xs text-gray-400">
                        도넛의 한 줄 소개 옆 ‘더 보기’를 눌렀을 때 팝업으로 표시되는 내용입니다.
                    </p>
                </div>

                <button type="button" class="donuts-preview-modal-open shrink-0 rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                    도넛 미리보기
                </button>
            </div>

            <div class="mt-4">
                <div class="flex items-center justify-between gap-3">
                    <label class="block font-bold">커뮤니티 소개</label>
                    <span class="text-2xs text-gray-400">커뮤니티의 목적과 주요 활동을 설명해 주세요.</span>
                </div>

                <textarea class="mt-2 h-30 w-full rounded-lg border border-gray-300 p-3">테니스를 사랑하는 도트들의 즐거운 소통 공간입니다.</textarea>

                <span class="mt-1 block text-2xs text-gray-400">
                    최대 500자 · 첫 화면의 한 줄 소개와 별도로 표시됩니다.
                </span>
            </div>

            <div class="mt-4">
                <div class="flex items-center justify-between gap-3">
                    <label class="block font-bold">활동 규칙</label>
                    <span class="text-2xs text-gray-400">한 줄에 한 항목씩 입력해 주세요.</span>
                </div>

                <textarea class="mt-2 h-40 w-full rounded-lg border border-gray-300 p-3"></textarea>

                <span class="mt-1 block text-2xs text-gray-400">
                    팝업에서는 입력 순서대로 번호가 붙어 표시됩니다.
                </span>
            </div>

            <div class="mt-4 rounded-lg bg-gray-100 text-2xs text-gray-500 p-3">
                <span class="font-bold text-gray-900">노출 방식</span>
                별도 탭을 만들지 않고, 도넛의 한 줄 소개 옆 ‘더 보기’를 누르면 커뮤니티 소개와 활동 규칙만 팝업으로 표시됩니다.
            </div>
        </div>

        <div class="border border-gray-300 rounded-lg bg-white p-3">
            <h3 class="text-base font-bold text-gray-900">가입 · 공개 설정</h3>
            <p class="mt-1 text-2xs text-gray-400">
                가입 절차와 도넛이 노출되는 범위를 각각 설정합니다.
            </p>

            <div class="mt-4">
                <p class="font-bold text-gray-900">가입 방식</p>

                <div class="donuts-setting-options mt-2 grid grid-cols-1 gap-3 pc:grid-cols-2">
                    <button type="button" aria-pressed="true" class="rounded-lg border-2 border-amber-400 bg-amber-50 p-3 text-left">
                        <span class="block font-bold text-gray-900">승인형</span>
                        <span class="mt-1 block text-2xs text-gray-400">
                            가입 신청 내용을 도티 또는 권한을 가진 운영자가 승인합니다.
                        </span>
                    </button>

                    <button type="button" aria-pressed="false" class="rounded-lg border border-gray-300 p-3 text-left">
                        <span class="block font-bold text-gray-900">즉시 가입형</span>
                        <span class="mt-1 block text-2xs text-gray-400">
                            도트가 가입 버튼을 누르면 즉시 가입됩니다.
                        </span>
                    </button>
                </div>
            </div>

            <div class="mt-4 border-t border-gray-300 pt-4">
                <p class="font-bold text-gray-900">공개 범위</p>

                <div class="donuts-setting-options mt-2 grid grid-cols-1 gap-3 pc:grid-cols-2">
                    <button type="button" aria-pressed="true" class="rounded-lg border-2 border-amber-400 bg-amber-50 p-3 text-left">
                        <span class="block font-bold text-gray-900">공개</span>
                        <span class="mt-1 block text-2xs text-gray-400">
                            검색과 도넛 목록 등 일반 탐색 영역에 노출됩니다.
                        </span>
                    </button>

                    <button type="button" aria-pressed="false" class="rounded-lg border border-gray-300 p-3 text-left">
                        <span class="block font-bold text-gray-900">비공개</span>
                        <span class="mt-1 block text-2xs text-gray-400">
                            일반 탐색에서는 숨기고 유효한 초대 링크·QR로만 진입합니다.
                        </span>
                    </button>
                </div>
            </div>

            <div class="mt-4 rounded-lg bg-gray-100 p-3 text-2xs text-gray-500">
                <span class="font-bold text-gray-900">설정 변경 시</span>
                새 가입자부터 변경한 방식이 적용됩니다. 승인형에서 즉시 가입형으로 바꿔도 이미 접수된 승인 대기 신청은 자동 처리하지 않고 목록에 보관합니다.
            </div>
        </div>

        <div class="border border-gray-300 rounded-lg bg-white p-3">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h3 class="text-base font-bold text-gray-900">초대 링크 · QR</h3>
                    <p class="mt-1 text-2xs text-gray-400">
                        이 도넛 전용 초대 수단입니다. 공개 여부와 관계없이 발급할 수 있습니다.
                    </p>
                </div>

                <div class="flex shrink-0 gap-1">
                    <span class="rounded-full bg-gray-100 px-2 py-1 text-2xs font-bold text-gray-700">● 공개</span>
                    <span class="rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">● 사용 중</span>
                </div>
            </div>

            <div class="mt-4 flex flex-col pc:flex-row gap-4">
                <div class="w-40 h-40 border border-gray-300 rounded-lg bg-gray-100"></div>

                <div class="flex-1 min-w-0">
                    <p class="font-bold text-gray-900">현재 초대 링크</p>

                    <div class="mt-2 flex gap-2">
                        <input type="text" readonly class="min-w-0 flex-1 rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-2xs text-gray-500" value="https://donuts.co.kr/join/DONUT-TENNIS?invite=TENNIS-1-548pv812i343">
                        <button type="button" class="shrink-0 rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                            복사
                        </button>
                    </div>

                    <div class="mt-3 grid grid-cols-2 gap-2">
                        <div class="rounded-lg bg-gray-100 p-2">
                            <span class="block text-2xs text-gray-400">발급 일시</span>
                            <span class="mt-1 block text-2xs font-bold text-gray-900">2026.08.18 10:30</span>
                        </div>

                        <div class="rounded-lg bg-gray-100 p-2">
                            <span class="block text-2xs text-gray-400">발급 버전</span>
                            <span class="mt-1 block text-2xs font-bold text-gray-900">v1 · 현재 유효</span>
                        </div>
                    </div>

                    <div class="mt-3 grid grid-cols-1 gap-2 pc:grid-cols-3">
                        <button type="button" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                            QR 저장
                        </button>
                        <button type="button" class="donuts-invite-modal-open rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                            가입 화면 확인
                        </button>
                        <button type="button" class="rounded-lg border border-red-300 bg-white px-3 py-2 text-2xs font-bold text-red-600">
                            링크·QR 재발급
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-4 rounded-lg bg-amber-50 p-3 text-2xs text-amber-800">
                <span class="block font-bold">재발급 정책</span>
                링크와 QR은 다음 재발급 전까지 계속 유효합니다. 재발급을 완료하는 즉시 이전 링크와 이전 QR은 무효화되고, 가장 최근에 발급한 1개만 사용할 수 있습니다.
            </div>
        </div>

        <div class="pc:col-span-2 border border-gray-300 rounded-lg bg-white p-3">
            <h3 class="text-base font-bold text-gray-900">가입 신청 질문</h3>
            <p class="mt-1 text-2xs text-gray-400">
                승인형 가입 시 도트에게 받을 질문입니다.
            </p>

            <ul class="mt-3 space-y-2">
                <li class="flex items-center gap-3 rounded-lg border border-gray-300 px-3 py-2">
                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-2xs font-bold text-amber-700">
                        Q1
                    </span>
                    <span class="min-w-0 flex-1 text-sm text-gray-900">
                        주로 즐기는 테니스 경력을 알려주세요.
                    </span>
                    <button type="button" aria-label="Q1 질문 삭제" class="shrink-0 text-2xs text-red-500">
                        삭제
                    </button>
                </li>

                <li class="flex items-center gap-3 rounded-lg border border-gray-300 px-3 py-2">
                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-2xs font-bold text-amber-700">
                        Q2
                    </span>
                    <span class="min-w-0 flex-1 text-sm text-gray-900">
                        가입 후 함께하고 싶은 활동은 무엇인가요?
                    </span>
                    <button type="button" aria-label="Q2 질문 삭제" class="shrink-0 text-2xs text-red-500">
                        삭제
                    </button>
                </li>
            </ul>

            <div class="mt-3 flex gap-2">
                <input type="text" class="min-w-0 flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm" placeholder="새 가입 질문을 입력하세요">
                <button type="button" class="shrink-0 rounded-lg bg-amber-300 px-3 py-2 font-bold text-gray-900">
                    질문 추가
                </button>
            </div>
        </div>
    </div>
</section>

<!-- 도넛 미리보기 모달 -->
<div id="donuts-preview-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="donuts-preview-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="donuts-preview-modal-container" class="relative z-10 w-full max-w-240 max-h-[80vh] overflow-y-auto rounded-lg bg-white">
        <div id="donuts-preview-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="donuts-preview-modal-title" class="text-base font-bold text-gray-900">
                도넛 미리보기
            </h3>

            <button type="button" aria-label="도넛 미리보기 모달 닫기" class="donuts-preview-modal-close flex h-8 w-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="donuts-preview-modal-body" class="p-4">
            <div class="overflow-hidden rounded-lg border border-gray-300 bg-white">
                <div class="bg-amber-100 text-2xs text-amber-800 px-3 py-2">
                    <span class="font-bold mr-2">편집 중 미리보기</span>
                    아직 저장하지 않은 도넛 이름·카테고리·소개·가입 방식·공개 범위까지 반영합니다.
                </div>

                <div class="flex flex-col pc:flex-row">
                    <div class="flex w-90 h-64 items-center justify-center bg-green-800"></div>

                    <div class="p-4">
                        <div class="flex gap-2">
                            <span class="rounded-full bg-gray-100 px-2 py-1 text-2xs font-bold text-gray-700">● 공개 도넛</span>
                            <span class="rounded-full bg-gray-100 px-2 py-1 text-2xs font-bold text-gray-700">취미 · 스포츠</span>
                        </div>

                        <h3 class="mt-4 text-2xl font-bold text-gray-900">테니스 커뮤니티</h3>

                        <p class="mt-2 text-sm text-gray-500">
                            테니스를 사랑하는 도트들의 즐거운 소통 공간입니다.
                            <span class="font-bold text-gray-900">더 보기 ›</span>
                        </p>

                        <button type="button" class="mt-4 rounded-lg bg-gray-900 px-4 py-3 text-sm font-bold text-white">
                            가입 신청하기
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between border-t border-gray-300 px-3 py-3 text-2xs text-gray-900">
                    <span>[공지] 오프라인 모임 신청 안내</span>
                    <span class="font-bold text-amber-700">+2</span>
                </div>

                <ul id="donuts-preview-modal-tabs" role="tablist" aria-label="도넛 미리보기 탭 리스트" class="grid grid-cols-4 border-t border-gray-300">
                    <li>
                        <button type="button" id="donuts-preview-modal-products-tab" role="tab" aria-selected="true" aria-controls="donuts-preview-modal-products-panel" class="w-full border-b-2 border-gray-900 px-1 py-3 text-2xs font-bold text-gray-900">
                            추천상품
                        </button>
                    </li>
                    <li>
                        <button type="button" id="donuts-preview-modal-notice-tab" role="tab" aria-selected="false" aria-controls="donuts-preview-modal-notice-panel" class="w-full border-b-2 border-transparent px-1 py-3 text-2xs text-gray-700">
                            전체공지
                        </button>
                    </li>
                    <li>
                        <button type="button" id="donuts-preview-modal-community-01-tab" role="tab" aria-selected="false" aria-controls="donuts-preview-modal-community-01-panel" class="w-full border-b-2 border-transparent px-1 py-3 text-2xs text-gray-700">
                            커뮤니티 01
                        </button>
                    </li>
                    <li>
                        <button type="button" id="donuts-preview-modal-community-02-tab" role="tab" aria-selected="false" aria-controls="donuts-preview-modal-community-02-panel" class="w-full border-b-2 border-transparent px-1 py-3 text-2xs text-gray-700">
                            커뮤니티 02
                        </button>
                    </li>
                </ul>

                <!-- 도넛 미리보기 모달 탭 내용 -->
                <section id="donuts-preview-modal-products-panel" role="tabpanel" aria-labelledby="donuts-preview-modal-products-tab" class="bg-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-bold text-gray-900">도티 추천상품</h3>
                        <span class="text-2xs text-gray-400">5개 노출</span>
                    </div>

                    <div class="mt-3 grid grid-cols-1 gap-3 pc:grid-cols-2">
                        <div class="flex gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-3xl">🥣</div>

                            <div class="min-w-0 flex-1">
                                <span class="text-2xs text-gray-400">그린테이블 · 일반 상품</span>
                                <p class="mt-1 text-sm font-bold text-gray-900">유기농 그래놀라 500g</p>
                                <p class="mt-1 font-bold text-gray-900">18,900원</p>
                                <span class="mt-2 block text-2xs font-bold text-amber-700">도티의 추천 이유</span>
                                <p class="mt-1 text-2xs text-gray-500">활동 전후 편하게 활용할 수 있어 테니스 커뮤니티 도트들에게 추천합니다.</p>
                            </div>
                        </div>

                        <div class="flex gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-3xl">🍫</div>

                            <div class="min-w-0 flex-1">
                                <span class="text-2xs text-gray-400">그린테이블 · 핫딜 상품</span>
                                <p class="mt-1 text-sm font-bold text-gray-900">저당 단백질바 12개입</p>
                                <p class="mt-1 font-bold text-gray-900">21,900원</p>
                                <span class="mt-2 block text-2xs font-bold text-amber-700">도티의 추천 이유</span>
                                <p class="mt-1 text-2xs text-gray-500">활동 전후 편하게 활용할 수 있어 테니스 커뮤니티 도트들에게 추천합니다.</p>
                            </div>
                        </div>

                        <div class="flex gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-3xl">🎁</div>

                            <div class="min-w-0 flex-1">
                                <span class="text-2xs text-gray-400">그린테이블 · 일반 상품</span>
                                <p class="mt-1 text-sm font-bold text-gray-900">선물용 대형 패키지</p>
                                <p class="mt-1 font-bold text-gray-900">48,000원</p>
                                <span class="mt-2 block text-2xs font-bold text-amber-700">도티의 추천 이유</span>
                                <p class="mt-1 text-2xs text-gray-500">활동 전후 편하게 활용할 수 있어 테니스 커뮤니티 도트들에게 추천합니다.</p>
                            </div>
                        </div>

                        <div class="flex gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-3xl">🧃</div>

                            <div class="min-w-0 flex-1">
                                <span class="text-2xs text-gray-400">그린테이블 · 일반 상품</span>
                                <p class="mt-1 text-sm font-bold text-gray-900">콜드프레스 주스 12병</p>
                                <p class="mt-1 font-bold text-gray-900">39,900원</p>
                                <span class="mt-2 block text-2xs font-bold text-amber-700">도티의 추천 이유</span>
                                <p class="mt-1 text-2xs text-gray-500">활동 전후 편하게 활용할 수 있어 테니스 커뮤니티 도트들에게 추천합니다.</p>
                            </div>
                        </div>

                        <div class="flex gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-3xl">📦</div>

                            <div class="min-w-0 flex-1">
                                <span class="text-2xs text-gray-400">그린테이블 · 일반 상품</span>
                                <p class="mt-1 text-sm font-bold text-gray-900">정기배송 혼합박스 24개입</p>
                                <p class="mt-1 font-bold text-gray-900">69,000원</p>
                                <span class="mt-2 block text-2xs font-bold text-amber-700">도티의 추천 이유</span>
                                <p class="mt-1 text-2xs text-gray-500">활동 전후 편하게 활용할 수 있어 테니스 커뮤니티 도트들에게 추천합니다.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="donuts-preview-modal-notice-panel" role="tabpanel" aria-labelledby="donuts-preview-modal-notice-tab" class="bg-gray-100 p-4" hidden>
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-bold text-gray-900">전체공지</h3>
                        <span class="text-2xs text-gray-400">6건</span>
                    </div>

                    <div class="mt-3 space-y-2">
                        <div class="flex items-center gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <span class="shrink-0 block w-10 h-10 rounded-lg bg-gray-100"></span>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-900">오프라인 모임 신청 안내</p>
                                <span class="mt-1 block text-2xs text-gray-400">2026.08.03 · 조회 1,204</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <span class="shrink-0 block w-10 h-10 rounded-lg bg-gray-100"></span>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-900">2026 여름 테니스 캠프 모집 안내</p>
                                <span class="mt-1 block text-2xs text-gray-400">2026.08.01 · 조회 987</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <span class="shrink-0 block w-10 h-10 rounded-lg bg-gray-100"></span>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-900">커뮤니티 운영 가이드라인 안내</p>
                                <span class="mt-1 block text-2xs text-gray-400">2026.07.29 · 조회 2,101</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <span class="shrink-0 block w-10 h-10 rounded-lg bg-gray-100"></span>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-900">코트 이용 매너 및 안전 수칙</p>
                                <span class="mt-1 block text-2xs text-gray-400">2026.07.24 · 조회 742</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <span class="shrink-0 block w-10 h-10 rounded-lg bg-gray-100"></span>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-900">회원 등급 운영 기준 안내</p>
                                <span class="mt-1 block text-2xs text-gray-400">2026.07.18 · 조회 681</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <span class="shrink-0 block w-10 h-10 rounded-lg bg-gray-100"></span>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-900">7월 정기 모임 사진 공유</p>
                                <span class="mt-1 block text-2xs text-gray-400">2026.07.12 · 조회 524</span>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="donuts-preview-modal-community-01-panel" role="tabpanel" aria-labelledby="donuts-preview-modal-community-01-tab" class="bg-gray-100 p-4" hidden>
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-bold text-gray-900">커뮤니티 01</h3>
                        <span class="text-2xs text-gray-400">도티·운영자 콘텐츠</span>
                    </div>

                    <div class="mt-3 space-y-2">
                        <div class="flex items-center gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <span class="shrink-0 block w-10 h-10 rounded-lg bg-gray-100"></span>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-900">8월 정기 모임 참가 신청 안내</p>
                                <span class="mt-1 block text-2xs text-gray-400">도티 김도윤 · 댓글 38 · 좋아요 126</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <span class="shrink-0 block w-10 h-10 rounded-lg bg-gray-100"></span>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-900">여름철 코트 이용 매너를 안내드립니다</p>
                                <span class="mt-1 block text-2xs text-gray-400">운영자 김도현 · 댓글 12 · 좋아요 74</span>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="donuts-preview-modal-community-02-panel" role="tabpanel" aria-labelledby="donuts-preview-modal-community-02-tab" class="bg-gray-100 p-4" hidden>
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-bold text-gray-900">커뮤니티 02</h3>
                        <span class="text-2xs text-gray-400">도트 자유게시판</span>
                    </div>

                    <div class="mt-3 space-y-2">
                        <div class="flex items-center gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <span class="shrink-0 block w-10 h-10 rounded-lg bg-gray-100"></span>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-900">새 라켓 사용 후기 남겨봐요</p>
                                <span class="mt-1 block text-2xs text-gray-400">최서진 · 댓글 21 · 좋아요 57</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <span class="shrink-0 block w-10 h-10 rounded-lg bg-gray-100"></span>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-900">주말 번개 복식 멤버 모집합니다</p>
                                <span class="mt-1 block text-2xs text-gray-400">이유진 · 댓글 8 · 좋아요 31</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <span class="shrink-0 block w-10 h-10 rounded-lg bg-gray-100"></span>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-900">초보자 포핸드 팁 공유</p>
                                <span class="mt-1 block text-2xs text-gray-400">박성훈 · 댓글 14 · 좋아요 83</span>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <div id="donuts-preview-modal-footer" class="sticky bottom-0 z-10 flex justify-end border-t border-gray-300 bg-white p-4">
            <button type="button" class="donuts-preview-modal-close rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-bold text-gray-900">
                닫기
            </button>
        </div>
    </div>
</div>

<!-- 초대 링크 가입 화면 모달 -->
<div id="donuts-invite-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="donuts-invite-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="donuts-invite-modal-container" class="relative z-10 w-full max-w-160 max-h-[80vh] overflow-y-auto rounded-lg bg-white">
        <div id="donuts-invite-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="donuts-invite-modal-title" class="text-base font-bold text-gray-900">
                초대 링크 가입 화면
            </h3>

            <button type="button" aria-label="초대 링크 가입 화면 모달 닫기" class="donuts-invite-modal-close flex w-8 h-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="donuts-invite-modal-body" class="p-4">
            <div class="rounded-lg border border-gray-300 bg-white">
                <div class="p-4">
                    <div class="flex w-full h-40 items-center justify-center rounded-lg bg-green-800"></div>
                </div>

                <div class="px-4 pb-4">
                    <div class="flex gap-2">
                        <span class="rounded-full bg-gray-100 px-2 py-1 text-2xs font-bold text-gray-700">● 공개 도넛</span>
                        <span class="rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">● 유효한 초대</span>
                    </div>

                    <h3 class="mt-4 text-sm font-bold text-gray-900">테니스 커뮤니티</h3>

                    <p class="mt-1 text-xs text-gray-500">
                        테니스를 사랑하는 도트들의 즐거운 소통 공간입니다.
                    </p>

                    <div class="mt-2 rounded-lg bg-gray-100 p-3">
                        <span class="block text-2xs text-gray-500">가입 방식</span>
                        <p class="mt-1 text-xs text-gray-500">승인형 · 질문 작성 후 운영자 승인을 기다립니다.</p>
                    </div>

                    <button type="button" class="mt-4 w-full rounded-lg bg-gray-900 px-4 py-3 text-sm font-bold text-white">
                        가입 신청하기
                    </button>
                </div>
            </div>

            <div class="mt-4 rounded-lg bg-amber-50 p-3 text-2xs text-amber-800">
                <span class="block text-green-600">현재 발급 버전 v1의 초대 링크로 진입한 화면입니다. 재발급된 이전 주소로는 이 화면에 들어올 수 없습니다.</span>
            </div>
        </div>

        <div id="donuts-invite-modal-footer" class="sticky bottom-0 z-10 flex justify-end border-t border-gray-300 bg-white p-4">
            <button type="button" class="donuts-invite-modal-close rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-bold text-gray-900">
                닫기
            </button>
        </div>
    </div>
</div>

<script>
    // 대표 이미지 file input 표시
    $('#donuts-cover-image').on('change', function() {
        const file = this.files[0];

        $('#donuts-cover-image-view').val(file ? file.name : '');
    });

    // 미리보기 탭 선택
    const $tabs = $('#preview-tabs button');

    $tabs.on('click', function() {
        $tabs
            .attr('aria-pressed', 'false')
            .removeClass('border-gray-900 font-bold text-gray-900')
            .addClass('border-transparent text-gray-700');

        $(this)
            .attr('aria-pressed', 'true')
            .removeClass('border-transparent text-gray-700')
            .addClass('border-gray-900 font-bold text-gray-900');
    });
    // 도넛 미리보기 모달 열기
    const $donutsPreviewModal = $('#donuts-preview-modal');

    $('.donuts-preview-modal-open').on('click', function() {
        $donutsPreviewModal.prop('hidden', false);
    });

    // 도넛 미리보기 모달 닫기
    $('.donuts-preview-modal-close, #donuts-preview-modal-backdrop').on('click', function() {
        $donutsPreviewModal.prop('hidden', true);
    });

    // 도넛 미리보기 모달 내부 탭 선택
    const $modalTabs = $('#donuts-preview-modal-tabs [role="tab"]');
    const $modalPanels = $('#donuts-preview-modal-body [role="tabpanel"]');

    $modalTabs.on('click', function() {
        const panelId = $(this).attr('aria-controls');

        // 전체 초기화 후
        $modalTabs
            .attr('aria-selected', 'false')
            .removeClass('border-gray-900 font-bold text-gray-900')
            .addClass('border-transparent text-gray-700');

        // 클릭한 탭만 적용
        $(this)
            .attr('aria-selected', 'true')
            .removeClass('border-transparent text-gray-700')
            .addClass('border-gray-900 font-bold text-gray-900');

        // 전체 패널 hidden 후 클릭한 탭의 aria-controls 안의 panelId 만 hidden 제거
        $modalPanels.prop('hidden', true);
        $('#' + panelId).prop('hidden', false);
    });

    // 가입 공개 설정 UI
    $('.donuts-setting-options > button').on('click', function() {
        const $options = $(this).closest('.donuts-setting-options').find('button');

        $options
            .attr('aria-pressed', 'false')
            .removeClass('border-2 border-amber-400 bg-amber-50')
            .addClass('border border-gray-300');

        $(this)
            .attr('aria-pressed', 'true')
            .removeClass('border border-gray-300')
            .addClass('border-2 border-amber-400 bg-amber-50');
    });

    // 초대 링크 가입 화면 모달 열기
    const $donutsInviteModal = $('#donuts-invite-modal');

    $('.donuts-invite-modal-open').on('click', function() {
        $donutsInviteModal.prop('hidden', false);
    });

    // 초대 링크 가입 화면 모달 닫기
    $('.donuts-invite-modal-close, #donuts-invite-modal-backdrop').on('click', function() {
        $donutsInviteModal.prop('hidden', true);
    });
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');
