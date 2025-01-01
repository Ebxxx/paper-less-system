document.addEventListener('DOMContentLoaded', function() {
    // User Type Chart
    const userTypeCtx = document.getElementById('userTypeChart').getContext('2d');
    new Chart(userTypeCtx, {
        type: 'doughnut',
        data: {
            labels: [
                `Admins: ${adminCount}`,
                `Users: ${regularUserCount}`
            ],
            datasets: [{
                data: [adminCount, regularUserCount],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',  // Vibrant Pink for Admins
                    'rgba(54, 162, 235, 0.7)'   // Bright Blue for Regular Users
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'User Type Distribution',
                    font: {
                        size: 15
                    }
                },
                legend: {
                    position: 'top'
                }
            }
        }
    });

    // Color palette for months (12 distinct colors)
    const monthColors = [
        'rgba(255, 99, 132, 0.7)',   // Red
        'rgba(54, 162, 235, 0.7)',   // Blue
        'rgba(255, 206, 86, 0.7)',   // Yellow
        'rgba(75, 192, 192, 0.7)',   // Teal
        'rgba(153, 102, 255, 0.7)',  // Purple
        'rgba(255, 159, 64, 0.7)',   // Orange
        'rgba(199, 199, 199, 0.7)',  // Grey
        'rgba(83, 102, 255, 0.7)',   // Indigo
        'rgba(40, 159, 64, 0.7)',    // Green
        'rgba(210, 99, 132, 0.7)',   // Coral
        'rgba(90, 162, 235, 0.7)',   // Sky Blue
        'rgba(255, 69, 0, 0.7)'      // Crimson
    ];

    // User Growth Chart
    const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
    new Chart(userGrowthCtx, {
        type: 'line',
        data: {
            labels: userGrowthLabels,
            datasets: [{
                label: 'Users ' + selectedYear,
                data: userGrowthData,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Users',
                        font: {
                            size: 14
                        }
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Monthly User Growth in ' + selectedYear,
                    font: {
                        size: 18
                    }
                }
            }
        }
    });

    // Message Statistics Chart
    const messageStatsCtx = document.getElementById('messageStatsChart').getContext('2d');
    new Chart(messageStatsCtx, {
        type: 'bar',
        data: {
            labels: ['Total Messages', 'Read', 'Unread', 'Urgent', 'Important', 'Attachments'],
            datasets: [{
                label: 'Message Statistics',
                data: [
                    messageStats.total,
                    messageStats.read,
                    messageStats.unread,
                    messageStats.urgent,
                    messageStats.important,
                    messageStats.attachments
                ],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.7)',   // Teal
                    'rgba(54, 162, 235, 0.7)',    // Blue
                    'rgba(255, 99, 132, 0.7)',    // Red
                    'rgba(255, 159, 64, 0.7)',    // Orange
                    'rgba(255, 206, 86, 0.7)',    // Yellow
                    'rgba(153, 102, 255, 0.7)'    // Purple (for attachments)
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(153, 102, 255, 1)'      // Purple border for attachments
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Items',
                        font: {
                            size: 14
                        }
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Message Statistics Overview',
                    font: {
                        size: 18
                    }
                },
                legend: {
                    display: false
                }
            }
        }
    });
});