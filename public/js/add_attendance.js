$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('change', '.status-radio', function() {
        let userId = $(this).closest('tr').find('.user-id').val();
        let groupId = $('#groupSelect').val();
        let date = $('#dateSelect').val();
        let status = $(this).val();

        let url = $(this).data('url');

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                group_id: groupId,
                date: date,
                user_id: userId,
                attendance_status: status
            },
            success: function(response) {
                if (response.success) {
                    alert(response.message); // Replace with a better UI feedback if desired
                }
            },
            error: function(xhr) {
                let errorMsg = xhr.responseJSON?.message || 'Failed to save attendance!';
                alert(errorMsg);
            }
        });
    });
});
