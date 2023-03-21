$(document).ready(function () {
  $('#submit').on('click', function () {
    var query = $('#search-query').val();
    var categories = [];
    $('.category-items input[type="checkbox"]:checked').each(function () {
      categories.push($(this).val());
    });
    $.ajax({
      url: $('#url').val(),
      type: 'POST',
      dataType: 'html',
      data: {
        'tx_filter_filterajax[query]': query,
        'tx_filter_filterajax[categories]': categories,
      },
      success: function (data) {
        $('#cards-container').empty();

        const fakeData = $("<div></div>");
        fakeData.html(data);
        // const cards = $(data);

        const cardList = $(fakeData).find('#cards-result');
        $('#cards-container').html($(cardList));
      },
      error: function (jqXHR, textStatus, errorThrown) {
      },
    });
  });
});
