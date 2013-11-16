/**
 * @author Hao.Cai
 */
$(document).ready(function() {
    $("#search_musician_btn").click(function() {
        window.location.href = $(this).data('url');
    });
    $("#manage_project_btn").click(function() {
        window.location.href = $(this).data('url');
    });
});
