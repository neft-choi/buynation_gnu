<?php
$sub_menu = '710100';
require_once './_common.php';

$g5['title'] = '대시보드';
$title_sub = '러닝 메이트 운영 현황을 한눈에 확인합니다.';

require_once './dotty.head.php';
?>

<section class="content" id="page-content">
    <div class="notice-strip"><b>● 운영 요약</b> 가입 신청 2건이 검토를 기다리고 있으며, 고정 공지 2개가 노출 중입니다.</div>

    <div class="notice-strip yellow"><b>▣ 사업자 전환 서류 미제출</b> 전환 신청 서류를 제출해 주세요. 전환 신청의 별도 제출 기한은 정책 확정 전입니다.<button class="btn small" style="margin-left:auto" data-nav="business">서류 등록</button></div>
    <div class="stats">
        <article class="stat"><span class="label">가입 도트</span><strong>4,360<small>명</small></strong><span class="delta up">▲ 지난달 대비 128명</span></article>
        <article class="stat"><span class="label">가입 승인 대기</span><strong>2<small>건</small></strong><span class="delta warn">가장 오래된 대기 3시간</span></article>
        <article class="stat"><span class="label">이번 달 기여토핑</span><strong>1,286,400<small>토핑</small></strong><span class="delta blue">플랫폼 집계 기준</span></article>
        <article class="stat"><span class="label">지급 가능 토핑</span><strong>420,000<small>토핑</small></strong><span class="delta up">정상 사용 가능</span></article>
    </div>
    <div class="grid-2 wide-left">
        <article class="card">
            <div class="card-head">
                <div>
                    <h3>가입 신청 대기</h3>
                    <p>최근 접수된 승인형 가입 신청</p>
                </div><button data-nav="applications">전체 보기 ›</button>
            </div>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>신청자</th>
                            <th>신청일</th>
                            <th>대기</th>
                            <th>상태</th>
                            <th>처리</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="name">지민런데이</span><span class="sub">APP-RUN-240803-021</span></td>
                            <td>2026.08.03 15:04</td>
                            <td>대기 40분</td>
                            <td><span class="badge yellow">승인 대기</span></td>
                            <td><button class="btn small" data-action="application-detail" data-id="APP-RUN-240803-021">검토</button></td>
                        </tr>
                        <tr>
                            <td><span class="name">현우페이스</span><span class="sub">APP-RUN-240803-018</span></td>
                            <td>2026.08.03 12:30</td>
                            <td>대기 3시간</td>
                            <td><span class="badge yellow">승인 대기</span></td>
                            <td><button class="btn small" data-action="application-detail" data-id="APP-RUN-240803-018">검토</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </article>
        <article class="card">
            <div class="card-head">
                <div>
                    <h3>주간 신규 가입</h3>
                    <p>최근 7일 승인 완료 기준</p>
                </div><span class="badge green">총 128명</span>
            </div>
            <div class="chart">
                <div class="bar-wrap">
                    <div class="bar " style="height:48%"></div><span>월</span>
                </div>
                <div class="bar-wrap">
                    <div class="bar " style="height:64%"></div><span>화</span>
                </div>
                <div class="bar-wrap">
                    <div class="bar " style="height:44%"></div><span>수</span>
                </div>
                <div class="bar-wrap">
                    <div class="bar " style="height:78%"></div><span>목</span>
                </div>
                <div class="bar-wrap">
                    <div class="bar " style="height:87%"></div><span>금</span>
                </div>
                <div class="bar-wrap">
                    <div class="bar active" style="height:96%"></div><span>토</span>
                </div>
                <div class="bar-wrap">
                    <div class="bar " style="height:72%"></div><span>일</span>
                </div>
            </div>
        </article>
    </div>
    <div class="grid-3" style="margin-top:16px">
        <article class="card">
            <div class="card-head">
                <div>
                    <h3>최근 콘텐츠</h3>
                    <p>새 게시글과 주요 반응</p>
                </div><button data-nav="content">관리 ›</button>
            </div>
            <div class="feed-list">
                <div class="feed-row"><span class="feed-icon">01</span>
                    <div><b>8월 한강 정기런 참가 안내</b>
                        <p>도티 김도윤 · 댓글 29</p>
                    </div><time>08.03</time>
                </div>
                <div class="feed-row"><span class="feed-icon">01</span>
                    <div><b>폭염 시간대 러닝 안전수칙</b>
                        <p>운영자 윤해진 · 댓글 11</p>
                    </div><time>08.02</time>
                </div>
                <div class="feed-row"><span class="feed-icon">02</span>
                    <div><b>첫 10km 완주 기록을 공유해요</b>
                        <p>오지수 · 댓글 17</p>
                    </div><time>08.01</time>
                </div>
            </div>
        </article>
        <article class="card">
            <div class="card-head">
                <div>
                    <h3>공지 고정 현황</h3>
                    <p>커뮤니티 상단 노출</p>
                </div><button data-nav="notices">관리 ›</button>
            </div>
            <div class="feed-list">
                <div class="feed-row"><span class="feed-icon">📌</span>
                    <div><b>8월 정기런 집결 장소 안내</b>
                        <p>고정 순서 1 · 조회 713</p>
                    </div><time>08.03</time>
                </div>
                <div class="feed-row"><span class="feed-icon">📌</span>
                    <div><b>여름철 러닝 안전 가이드</b>
                        <p>고정 순서 2 · 조회 984</p>
                    </div><time>07.31</time>
                </div>
            </div>
        </article>
        <article class="card">
            <div class="card-head">
                <div>
                    <h3>추천상품 현황</h3>
                    <p>현재 노출 중인 상품</p>
                </div><button data-nav="products">관리 ›</button>
            </div>
            <div class="feed-list">
                <div class="feed-row"><span class="feed-icon">👟</span>
                    <div><b>에어 줌 페가수스 러닝화</b>
                        <p>그린테이블 · 18,900원</p>
                    </div><time>일반</time>
                </div>
                <div class="feed-row"><span class="feed-icon">🧢</span>
                    <div><b>드라이핏 러닝 캡</b>
                        <p>그린테이블 · 21,900원</p>
                    </div><time>일반</time>
                </div>
                <div class="feed-row"><span class="feed-icon">🥗</span>
                    <div><b>프리미엄 냉장 샐러드 6팩</b>
                        <p>그린테이블 · 32,900원</p>
                    </div><time>추가 토핑</time>
                </div>
            </div>
        </article>
    </div>
    <p class="footer-note">클릭형 시안 · 변경 데이터는 이 브라우저에 저장되며 계정 전환 메뉴에서 초기화할 수 있습니다.</p>
</section>

<?php
require_once './dotty.tail.php';
