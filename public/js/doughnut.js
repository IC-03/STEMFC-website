document.addEventListener('DOMContentLoaded', function() {
    // Ensure attendanceStats is available
    if (typeof window.attendanceStats !== 'undefined') {
        const ctx = $('#attendanceChart').get(0).getContext('2d');
        // const ctx = document.getElementById('attendanceChart').getContext('2d');

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Present', 'Absent', 'Leave'], // Add 'Weekend' if needed
                datasets: [{
                    label: 'Attendance Stats',
                    data: [
                        window.attendanceStats.Present,
                        window.attendanceStats.Absent,
                        window.attendanceStats.Leave,
                        // window.attendanceStats.Weekend // Uncomment if included
                    ],
                    backgroundColor: [
                        'rgba(56, 147, 56, 0.8)',  // Present
                        'rgba(255, 99, 132, 0.8)',   // Absent
                        'rgba(255, 206, 86, 0.8)',   // Leave
                        // 'rgba(54, 162, 235, 0.8)' // Weekend (optional)
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        // 'rgba(54, 162, 235, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'none',
                    },
                    title: {
                        display: true,
                    }
                }
            }
        });
    } else {
        // console.error('Attendance stats data not found!');
    }
});
