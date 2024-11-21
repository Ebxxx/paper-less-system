document.addEventListener('DOMContentLoaded', function() {
    // User Type Distribution Chart
    const userTypeCtx = document.getElementById('userTypeChart').getContext('2d');
    new Chart(userTypeCtx, {
        type: 'pie',
        data: {
            labels: ['Admins', 'Regular Users'],
            datasets: [{
                data: [adminCount, regularUserCount],
                backgroundColor: ['#3B82F6', '#10B981']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: false
                }
            }
        }
    });

    // User Growth Chart
    const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
    new Chart(userGrowthCtx, {
        type: 'bar',
        data: {
            labels: userGrowthLabels,
            datasets: [{
                label: 'New Users',
                data: userGrowthData,
                backgroundColor: 'rgba(75, 192, 192, 0.6)'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: false
                }
            }
        }
    });
});