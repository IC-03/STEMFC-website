// Delete Payment
$(document).on('click', '.delete-payment', function() {
    let paymentId = $(this).data('id');
    let studentId = $('#paymentForm').data('student-id');
    let row = $(this).closest('tr'); // Get the row to remove

    if (confirm('Are you sure you want to delete this payment?')) {
        $.ajax({
            url: `/admin/student/profile/${studentId}/payments/${paymentId}`,
            type: 'DELETE',
            success: function(response) {
                console.log('Delete Response:', response);
                if (response.success) {
                    row.remove(); // Remove the row from the table
                    $('#responseMessage').show().html(
                        '<div class="alert alert-success">' + response.message + '</div>'
                    );
                    setTimeout(() => {
                        $('#responseMessage').fadeOut();
                    }, 3000);
                } else {
                    $('#responseMessage').show().html(
                        '<div class="alert alert-warning">Payment deleted, but response format unexpected.</div>'
                    );
                }
            },
            error: function(xhr) {
                console.log('Delete Error:', xhr.responseJSON);
                let errorMsg = xhr.responseJSON?.message || 'Failed to delete payment!';
                $('#responseMessage').show().html(
                    '<div class="alert alert-danger">' + errorMsg + '</div>'
                );
            }
        });
    }
});
