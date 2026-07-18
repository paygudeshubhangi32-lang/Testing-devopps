/**
 * DataTables Initialization (placeholder)
 * Applied when jQuery & DataTables are loaded on admin pages
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTables if jQuery is available
    if (typeof jQuery !== 'undefined' && typeof jQuery.fn.DataTable !== 'undefined') {
        jQuery('.data-table').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            language: {
                search: '<i class="bi bi-search"></i>',
                searchPlaceholder: 'Search...',
                lengthMenu: 'Show _MENU_ entries',
                info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                emptyTable: '<div class="empty-state"><i class="bi bi-inbox"></i><p>No data available</p></div>',
                zeroRecords: '<div class="empty-state"><i class="bi bi-search"></i><p>No matching records found</p></div>'
            },
            dom: '<"d-flex justify-content-between align-items-center mb-3"lf>t<"d-flex justify-content-between align-items-center mt-3"ip>',
            drawCallback: function() {
                // Re-apply tooltips after table redraw
                var tooltipList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipList.map(function(el) { return new bootstrap.Tooltip(el); });
            }
        });
    }
});
