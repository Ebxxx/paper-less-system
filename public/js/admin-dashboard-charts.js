// document.addEventListener('DOMContentLoaded', function() {
//     // User Type Distribution Chart
//     const userTypeCtx = document.getElementById('userTypeChart').getContext('2d');
//     new Chart(userTypeCtx, {
//         type: 'pie',
//         data: {
//             labels: ['Admins', 'Regular Users'],
//             datasets: [{
//                 data: [adminCount, regularUserCount],
//                 backgroundColor: ['#3B82F6', '#10B981']
//             }]
//         },
//         options: {
//             responsive: true,
//             plugins: {
//                 legend: {
//                     position: 'top',
//                 },
//                 title: {
//                     display: false
//                 }
//             }
//         }
//     });

//     // User Growth Chart
//     const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
//     new Chart(userGrowthCtx, {
//         type: 'bar',
//         data: {
//             labels: userGrowthLabels,
//             datasets: [{
//                 label: 'New Users',
//                 data: userGrowthData,
//                 backgroundColor: 'rgba(75, 192, 192, 0.6)'
//             }]
//         },
//         options: {
//             responsive: true,
//             scales: {
//                 y: {
//                     beginAtZero: true
//                 }
//             },
//             plugins: {
//                 legend: {
//                     display: false
//                 },
//                 title: {
//                     display: false
//                 }
//             }
//         }
//     });
// });


document.addEventListener('DOMContentLoaded', function() {
    // User Type Chart
    const userTypeCtx = document.getElementById('userTypeChart').getContext('2d');
    new Chart(userTypeCtx, {
        type: 'pie',
        data: {
            labels: ['Admins', 'Regular Users'],
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
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'User Type Distribution'
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
        type: 'bar',
        data: {
            labels: userGrowthLabels,
            datasets: [{
                label: 'Users ' + selectedYear,
                data: userGrowthData,
                backgroundColor: monthColors.slice(0, userGrowthLabels.length),
                borderColor: monthColors.slice(0, userGrowthLabels.length).map(color => color.replace('0.7)', '1)')),
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
                        text: 'Number of Users'
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Monthly User Growth in ' + selectedYear
                }
            }
        }
    });
});