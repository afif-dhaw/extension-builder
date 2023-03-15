$(document).ready(function () {
  $('#submit').on('click', function () {
    var query = $('#search-query').val();
    var categories = [];
    $('.category-items input[type="checkbox"]:checked').each(function () {
      categories.push($(this).val());
    });
    $.ajax({
      url: $('#url1').val(),
      type: 'POST',
      dataType: 'html',
      data: {
        'tx_filter_filterajax[query]': query,
        'tx_filter_filterajax[categories]': categories
      },
      success: function (data) {
      },
      error: function (jqXHR, textStatus, errorThrown) {
      },
    });
    return false;
  });
});
