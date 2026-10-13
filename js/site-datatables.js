// DataTables 적용 함수
function createSiteTable(tableId, options = {}) {
    const {
        csv = false,
        search = true,
        paging = true,
        pageLength = 10,
        csvFilename = 'csv 파일',
        csvColumns = ':visible',
        language = {}
    } = options;

    // 한국어 설정
    const defaultLanguage = {
        search: '_INPUT_',
        searchPlaceholder: '검색어를 입력하세요',
        lengthMenu: '_MENU_ 개씩 보기',
        info: '전체 _TOTAL_건 중 _START_~_END_건 표시',
        infoEmpty: '표시할 데이터가 없습니다.',
        infoFiltered: '(전체 _MAX_건에서 검색)',
        emptyTable: '등록된 데이터가 없습니다.',
        zeroRecords: '검색 결과가 없습니다.',
        paginate: {
            first: '처음',
            last: '마지막',
            next: '다음',
            previous: '이전'
        }
    };

    // 상단 컨트롤 구성
    const topControls = [];

    if (paging) {
        topControls.push('pageLength');
    }

    if (csv) {
        topControls.push({
            buttons: [{
                extend: 'csvHtml5',
                text: 'CSV 다운로드',
                // filename: csvFilename,
                bom: true,
                exportOptions: {
                    columns: csvColumns,
                    escapeExcelFormula: true
                }
            }]
        });
    }

    // DataTables 초기화
    return new DataTable(`#${tableId}`, {
        autoWidth: true,
        scrollX: true,

        searching: search,
        ordering: true,
        paging: paging,
        pageLength: pageLength,

        layout: {
            topStart: topControls.length ? topControls : null,
            topEnd: search ? 'search' : null,
            bottomStart: 'info',
            bottomEnd: paging ? 'paging' : null
        },

        language: {
            ...defaultLanguage,
            ...language,
            paginate: {
                ...defaultLanguage.paginate,
                ...(language.paginate || {})
            }
        }
    });
}
