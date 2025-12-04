document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.attendanceData !== 'undefined') {
        const ctx = document.getElementById('attendanceBarChart').getContext('2d');
        const labels = window.attendanceData.map(item => item.month_year); // Now "Jan-2025", etc.
        const presentData = window.attendanceData.map(item => item.present);
        const absentData = window.attendanceData.map(item => item.absent);
        const leaveData = window.attendanceData.map(item => item.leave);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    { label: 'Present', data: presentData, backgroundColor: 'rgba(75, 192, 192, 0.8)', borderColor: 'rgba(75, 192, 192, 1)', borderWidth: 1 },
                    { label: 'Absent', data: absentData, backgroundColor: 'rgba(255, 99, 132, 0.8)', borderColor: 'rgba(255, 99, 132, 1)', borderWidth: 1 },
                    { label: 'Leave', data: leaveData, backgroundColor: 'rgba(255, 206, 86, 0.8)', borderColor: 'rgba(255, 206, 86, 1)', borderWidth: 1 }
                ]
            },
            options: {
                scales: {
                    x: {
                        stacked: true,
                        title: {
                            display: true,
                            text: 'Month' // Updated for readability
                        }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'No of days'
                        }
                    }
                },
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    title: { display: false, text: 'Monthly Attendance Breakdown' }
                }
            }
        });
    } else {
        console.error('Attendance data not found!');
    }
});
