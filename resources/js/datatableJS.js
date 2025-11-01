$(document).ready(function () {

    // Apply to every DataTable automatically
    $(document).on('init.dt', function (e, settings) {
        var table = new $.fn.dataTable.Api(settings);
        var tableNode = table.table().container();

        // Observe parent width changes
        if (window.ResizeObserver) {
            let ro = new ResizeObserver(() => {
                table.columns.adjust();
                if (table.responsive && typeof table.responsive.recalc === 'function') {
                    table.responsive.recalc();
                }
            });

            // Observe the closest visible parent (for width changes)
            const parent = $(tableNode).closest(':visible')[0];
            if (parent) {
                ro.observe(parent);
            }
        }

        // Fallback for window resize (and zoom)
        $(window).on('resize.dt-' + settings.sInstance, function () {
            table.columns.adjust();
            if (table.responsive && typeof table.responsive.recalc === 'function') {
                table.responsive.recalc();
            }
        });
    });

    // Handle zoom changes (for completeness)
    if (window.visualViewport) {
        let lastZoom = window.visualViewport.scale;
        window.visualViewport.addEventListener('resize', function () {
            if (window.visualViewport.scale !== lastZoom) {
                lastZoom = window.visualViewport.scale;
                $('table.dataTable').each(function () {
                    var t = $(this).DataTable();
                    t.columns.adjust();
                    if (t.responsive && typeof t.responsive.recalc === 'function') {
                        t.responsive.recalc();
                    }
                });
            }
        });
    }
});