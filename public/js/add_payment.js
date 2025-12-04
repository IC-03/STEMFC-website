// $(document).ready(function() {
//             // Set up CSRF token for AJAX
//             $.ajaxSetup({
//                 headers: {
//                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                 }
//             });

//             // Handle form submission
//             $('#paymentForm').on('submit', function(e) {
//                 e.preventDefault();

//                 let url = $(this).data('url');
//                 let studentId = $(this).data('student-id');

//                 let formData = {
//                     id: $('#id').val(),
//                     month: $('#month').val(),
//                     amount: $('#amount').val(),
//                     paid: $('#paid').val(),
//                     date: $('#date').val()
//                 };

//                 $.ajax({
//                     // url: "route('admin.student.temp')",
//                     url: url,
//                     type: "POST",
//                     data: formData,
//                     success: function(response) {
//                         if (response.success) {
//                             // Display success message
//                             $('#responseMessage').html(
//                                 '<div class="alert alert-success">' + response.message + '</div>'
//                             );

//                             // Append new record to table (optional)
//                             let newRow = `
//                                 <tr>
//                                     <td>${response.data.id}</td>
//                                     <td>${response.data.month}</td>
//                                     <td>${response.data.amount}</td>
//                                     <td>${response.data.paid}</td>
//                                     <td>${response.data.date}</td>
//                                 </tr>`;
//                             $('#example1_wrapper1').append(newRow);

//                             // Clear form
//                             $('#paymentForm')[0].reset();
//                         }
//                     },
//                     error: function(xhr) {
//                         let errorMsg = xhr.responseJSON?.message || 'Something went wrong!';
//                         $('#responseMessage').html(
//                             '<div class="alert alert-danger">' + errorMsg + '</div>'
//                         );
//                     }
//                 });
//             });
//         });






// public/js/payments.js
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#paymentForm').on('submit', function(e) {
        e.preventDefault();

        let url = $(this).data('url');
        let studentId = $(this).data('student-id');

        let formData = {
            // student_id: studentId,
            id: $('#id').val(),
            month: $('#month').val(),
            amount: $('#amount').val(),
            paid: $('#paid').val(),
            date: $('#date').val()
        };

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Show success message
                    $('#responseMessage').html(
                        '<div class="alert alert-success">' + response.message + '</div>'
                    );

                    const inputDate = `${response.data.month}`;
                    const [year, month] = inputDate.split('-');
                    const date = new Date(year, month - 1);
                    const monthAbbreviations = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                    const monthAbbrev = monthAbbreviations[date.getMonth()];
                    const formattedDate = `${monthAbbrev}-${year}`;

                    // get date in correct format
                    const paymentDate = `${response.data.payment_date}`;
                    const [dy, mn, yr] = paymentDate.split('/');
                    const date1 = new Date(yr, mn - 1, dy);

                    const mymonth = monthAbbreviations[date1.getMonth()];
                    const paymentDateformatted = `${mymonth} ${String(date1.getDate()).padStart(2, '0')}, ${date1.getFullYear()}`;

                    // Append new row to table
                    let newRow = `
                        <tr>
                            <td>${response.data.id}</td>
                            <td>${formattedDate}</td>
                            <td>${response.data.amount_to_pay}</td>
                            <td>${response.data.amount_paid}</td>
                            <td>${response.data.balance}</td>
                            <td>${paymentDateformatted}</td>
                        </tr>`;
                    $('#paymentTable').append(newRow); // Append to tbody

                    // Clear form
                    $('#paymentForm')[0].reset();

                    // Optional: Fade out success message after 3 seconds
                    setTimeout(() => {
                        $('#responseMessage').fadeOut();
                    }, 3000);
                } else {
                    $('#responseMessage').html(
                        '<div class="alert alert-warning">Data saved, but response format unexpected.</div>'
                    );
                }
            },
            error: function(xhr) {
                let errorMsg = xhr.responseJSON?.message || 'Something went wrong!';
                if (xhr.responseJSON?.errors) {
                    errorMsg += '<ul>';
                    for (let field in xhr.responseJSON.errors) {
                        errorMsg += `<li>${xhr.responseJSON.errors[field][0]}</li>`;
                    }
                    errorMsg += '</ul>';
                }
                $('#responseMessage').html(
                    '<div class="alert alert-danger">' + errorMsg + '</div>'
                );
            }
        });
    });
});
