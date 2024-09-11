<!DOCTYPE html>
<html lang="en">

<head>
    <base href="https://analytics.socialdashboard.com/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media Analytics Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/analytics-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard-style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="font-sans">
    <!-- Wrapper to create flex layout for sidebar and content -->
    <div class="flex min-h-screen">
        @include('layouts.sidebar')
        <div class="container mx-auto px-4 py-8">
            <!-- Heading -->
            <h1 class="text-4xl font-bold text-dark-blue mb-8 text-center relative">
                Analytics
                <span class="heading-underline"></span>
            </h1>

            <!-- Filter and Date Range -->
            <div class="mb-8 flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0 w-full md:w-auto">
                    <label for="dateRange" class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                    <select id="dateRange" class="block w-full bg-white border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-light-blue focus:border-light-blue">
                        <option>Last 7 days</option>
                        <option>Last 30 days</option>
                        <option>Last 3 months</option>
                        <option>Custom range</option>
                    </select>
                </div>
                <div class="flex flex-wrap justify-center md:justify-end space-x-2 space-y-2 md:space-y-0">
                    <button class="btn-facebook">
                        <i class="fab fa-facebook-f mr-2"></i>Facebook
                    </button>
                    <button class="btn-twitter">
                        <i class="fab fa-twitter mr-2"></i>Twitter
                    </button>
                    <button class="btn-instagram">
                        <i class="fab fa-instagram mr-2"></i>Instagram
                    </button>
                    <button class="btn-linkedin">
                        <i class="fab fa-linkedin-in mr-2"></i>LinkedIn
                    </button>
                </div>
            </div>

            <!-- Key Metrics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="metric-card">
                    <h3 class="metric-title">Total Reach</h3>
                    <p class="metric-value">1,234,567</p>
                    <p class="metric-change">
                        <span class="text-green-500"><i class="fas fa-arrow-up mr-1"></i>5.7%</span>
                        from last period
                    </p>
                </div>
                <div class="metric-card">
                    <h3 class="metric-title">Engagement Rate</h3>
                    <p class="metric-value">3.2%</p>
                    <p class="metric-change">
                        <span class="text-red-500"><i class="fas fa-arrow-down mr-1"></i>0.5%</span>
                        from last period
                    </p>
                </div>
                <div class="metric-card">
                    <h3 class="metric-title">Total Likes</h3>
                    <p class="metric-value">98,765</p>
                    <p class="metric-change">
                        <span class="text-green-500"><i class="fas fa-arrow-up mr-1"></i>12.3%</span>
                        from last period
                    </p>
                </div>
                <div class="metric-card">
                    <h3 class="metric-title">Total Comments</h3>
                    <p class="metric-value">12,345</p>
                    <p class="metric-change">
                        <span class="text-green-500"><i class="fas fa-arrow-up mr-1"></i>8.1%</span>
                        from last period
                    </p>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <div class="chart-card">
                    <h3 class="chart-title">Engagement Over Time</h3>
                    <canvas id="engagementChart"></canvas>
                </div>
                <div class="chart-card">
                    <h3 class="chart-title">Audience Growth</h3>
                    <canvas id="audienceChart"></canvas>
                </div>
            </div>

            <!-- Top Posts Table -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h3 class="text-xl font-semibold mb-4 text-dark-blue">Top Performing Posts</h3>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">Post</th>
                                <th class="py-3 px-6 text-center">Platform</th>
                                <th class="py-3 px-6 text-center">Likes</th>
                                <th class="py-3 px-6 text-center">Comments</th>
                                <th class="py-3 px-6 text-center">Shares</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">New product launch!</span>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <i class="fab fa-facebook-f text-blue-600"></i>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <span class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs">5,678</span>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <span class="bg-blue-200 text-blue-600 py-1 px-3 rounded-full text-xs">1,234</span>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <span class="bg-purple-200 text-purple-600 py-1 px-3 rounded-full text-xs">987</span>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">Customer success story</span>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <i class="fab fa-linkedin-in text-blue-700"></i>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <span class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs">3,456</span>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <span class="bg-blue-200 text-blue-600 py-1 px-3 rounded-full text-xs">789</span>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <span class="bg-purple-200 text-purple-600 py-1 px-3 rounded-full text-xs">543</span>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">Behind the scenes video</span>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <i class="fab fa-instagram text-pink-500"></i>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <span class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs">8,901</span>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <span class="bg-blue-200 text-blue-600 py-1 px-3 rounded-full text-xs">2,345</span>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <span class="bg-purple-200 text-purple-600 py-1 px-3 rounded-full text-xs">1,234</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script>
            // Engagement Over Time Chart
            var ctxEngagement = document.getElementById('engagementChart').getContext('2d');
            var engagementChart = new Chart(ctxEngagement, {
                type: 'line',
                data: {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    datasets: [{
                        label: 'Engagement Rate',
                        data: [3.1, 3.5, 3.2, 3.7],
                        backgroundColor: 'rgba(88, 132, 252, 0.2)',
                        borderColor: 'rgba(88, 132, 252, 1)',
                        borderWidth: 1,
                        fill: true
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Audience Growth Chart
            var ctxAudience = document.getElementById('audienceChart').getContext('2d');
            var audienceChart = new Chart(ctxAudience, {
                type: 'bar',
                data: {
                    labels: ['Facebook', 'Twitter', 'Instagram', 'LinkedIn'],
                    datasets: [{
                        label: 'New Followers',
                        data: [1200, 950, 1300, 780],
                        backgroundColor: [
                            'rgba(66, 103, 178, 0.6)',
                            'rgba(29, 161, 242, 0.6)',
                            'rgba(225, 48, 108, 0.6)',
                            'rgba(10, 102, 194, 0.6)'
                        ],
                        borderColor: [
                            'rgba(66, 103, 178, 1)',
                            'rgba(29, 161, 242, 1)',
                            'rgba(225, 48, 108, 1)',
                            'rgba(10, 102, 194, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
</body>

</html>