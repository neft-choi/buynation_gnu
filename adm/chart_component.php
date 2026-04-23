<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function chart_escape($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function render_admin_chart($props = array())
{
    $id = isset($props['id']) ? (string) $props['id'] : '';
    $title = isset($props['title']) ? (string) $props['title'] : '';
    $type = isset($props['type']) ? (string) $props['type'] : 'line';
    $labels = isset($props['labels']) && is_array($props['labels']) ? $props['labels'] : array();
    $datasets = isset($props['datasets']) && is_array($props['datasets']) ? $props['datasets'] : array();
    $height = isset($props['height']) ? (int) $props['height'] : 280;
    $empty_text = isset($props['empty_text']) ? (string) $props['empty_text'] : '표시할 데이터가 없습니다.';

    if ($id === '') {
        return '';
    }

    $has_data = count($labels) > 0 && count($datasets) > 0;
    $payload = array(
        'type' => $type,
        'labels' => $labels,
        'datasets' => $datasets,
    );

    ob_start();
?>
    <section class="bg-white md:border border-y border-gray-200 md:rounded rounded-none p-4">
        <h3 class="mb-4 text-xs font-black text-gray-900"><?php echo chart_escape($title); ?></h3>

        <?php if (!$has_data) { ?>
            <div class="py-10 text-center text-sm text-gray-500"><?php echo chart_escape($empty_text); ?></div>
        <?php } else { ?>
            <div style="height: <?php echo $height; ?>px;">
                <canvas
                    id="<?php echo chart_escape($id); ?>"
                    class="js-admin-chart"
                    data-chart="<?php echo chart_escape(json_encode($payload, JSON_UNESCAPED_UNICODE)); ?>"></canvas>
            </div>
        <?php } ?>
    </section>
<?php
    return ob_get_clean();
}


// Chart.js 로더 + 공통 초기화 스크립트
function render_admin_chart_bootstrap()
{
    ob_start();
?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const chartNodes = Array.from(document.querySelectorAll('.js-admin-chart'));

            chartNodes.forEach((canvas) => {
                if (!window.Chart) {
                    return;
                }

                const raw = canvas.getAttribute('data-chart') || '{}';
                let config;
                try {
                    config = JSON.parse(raw);
                } catch (error) {
                    return;
                }

                const labels = Array.isArray(config.labels) ? config.labels : [];
                const datasets = Array.isArray(config.datasets) ? config.datasets : [];

                new Chart(canvas, {
                    type: config.type || 'line',
                    data: {
                        labels,
                        datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        plugins: {
                            // 범례
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    color: '#101828',
                                    font: {
                                        size: 8,
                                        weight: '500'
                                        },
                                    boxWidth: 8,
                                    boxHeight: 8,
                                    padding: 16
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                border: { display: false },
                                ticks: {
                                    color: '#101828', // gray-900
                                    font: {
                                        size: 10
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    color: '#101828',
                                    font: {
                                        size: 10
                                    }
                                },
                                grid: { display: false },
                                border: { display: false }
                            }
                        }
                    }
                });
            });
        });
    </script>
<?php
    return ob_get_clean();
}
