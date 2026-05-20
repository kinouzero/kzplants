import $ from 'jquery';

export function initPlantDetail() {
  const commentEditButtons = document.querySelectorAll('.comment-edit');
  if (!commentEditButtons.length) return;

  $('.comment-edit').click(function () {
    $(this)
      .toggleClass('btn-outline-secondary btn-outline-danger')
      .find('i')
      .toggleClass('fa-pencil-alt fa-times');

    $(this).closest('.comment-actions').find('.comment-save').toggleClass('d-none');

    const commentId = $(this).closest('form').find('input[name="comment_id"]').val();
    const commentDom = $(this).closest('.card').find('.comment-value');

    if ($(this).hasClass('btn-outline-danger')) {
      const commentValue = commentDom.html();
      commentDom.html(
        '<div class="form-floating"><textarea class="form-control" required style="height:8rem" name="comment" id="comment-' +
          commentId +
          '">' +
          commentValue +
          '</textarea><label for="comment-' +
          commentId +
          '">Comment</label></div>'
      );
    } else {
      const commentValue = commentDom.find('textarea').html();
      commentDom.html(commentValue);
    }
  });
}

initPlantDetail();
