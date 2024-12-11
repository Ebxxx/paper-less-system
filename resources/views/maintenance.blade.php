<!-- resources/views/maintenance.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Maintenance</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
    @vite(['resources/css/app.css'])
    <style>
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes reverse-spin {
            0% { transform: rotate(360deg); }
            100% { transform: rotate(0deg); }
        }

        .gear-container {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
        }

        .gear-large {
            position: absolute;
            font-size: 80px;
            color: #4B5563;
            animation: spin 10s linear infinite;
            left: 0;
            top: 0;
        }

        .gear-small {
            position: absolute;
            font-size: 50px;
            color: #6B7280;
            animation: reverse-spin 8s linear infinite;
            right: 0;
            bottom: 0;
        }

        .maintenance-card {
            transform: translateY(20px);
            opacity: 0;
            animation: slideUp 0.8s ease forwards;
        }

        @keyframes slideUp {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .message {
            opacity: 0;
            animation: fadeIn 0.8s ease forwards 0.5s;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        .pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .5;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-100 to-gray-200">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white rounded-xl shadow-2xl p-8 text-center maintenance-card">
            <div class="gear-container mb-6">
                <i class="fas fa-cog gear-large"></i>
                <i class="fas fa-cog gear-small"></i>
            </div>
            
            <h3 class="text-2xl font-bold text-gray-800 mb-4">System Under Maintenance</h3>
            
            <div class="message">
                <p class="text-gray-600 mb-4">
                    We're currently performing system maintenance to serve you better.
                </p>
                <div class="flex items-center justify-center space-x-2 text-sm text-gray-500">
                    <span class="pulse">●</span>
                    <span>Please check back later</span>
                    <span class="pulse">●</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>