<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quiz') - {{ config('app.name') }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png') }}">
    
    <!-- Wayground Quiz Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            overflow-x: hidden;
            background: #0a0e27;
        }
        
        .wayground-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }
        
        .wayground-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            pointer-events: none;
        }
        
        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }
        
        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 20s infinite ease-in-out;
        }
        
        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 15%;
            animation-delay: -5s;
        }
        
        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
            animation-delay: -10s;
        }
        
        .shape:nth-child(4) {
            width: 100px;
            height: 100px;
            top: 10%;
            right: 25%;
            animation-delay: -15s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-30px) rotate(120deg); }
            66% { transform: translateY(20px) rotate(240deg); }
        }
        
        .quiz-header-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            z-index: 1000;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .quiz-logo {
            display: flex;
            align-items: center;
            color: white;
            font-weight: 600;
            font-size: 18px;
        }
        
        .quiz-logo img {
            width: 32px;
            height: 32px;
            margin-right: 10px;
            border-radius: 6px;
        }
        
        .quiz-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .quit-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .quit-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        .main-content {
            position: relative;
            z-index: 2;
            padding-top: 60px;
            min-height: 100vh;
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .quiz-header-bar {
                padding: 0 20px;
            }
            
            .quiz-logo {
                font-size: 16px;
            }
            
            .quit-btn {
                padding: 6px 12px;
                font-size: 14px;
            }
            
            .shape {
                display: none; /* Hide floating shapes on mobile for performance */
            }
        }
        
        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .wayground-container {
                background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            }
        }
        
        /* High contrast mode support */
        @media (prefers-contrast: high) {
            .quiz-header-bar {
                background: rgba(0, 0, 0, 0.8);
                border-bottom: 2px solid white;
            }
        }
        
        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            .shape {
                animation: none;
            }
            
            * {
                transition: none !important;
                animation: none !important;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="wayground-container">
        <!-- Floating Background Shapes -->
        <div class="floating-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
        
        <!-- Quiz Header Bar -->
        <div class="quiz-header-bar">
            <div class="quiz-logo">
                <img src="{{ asset('assets/img/logo/logo.png') }}" alt="Logo">
                Quiz Platform
            </div>
            <div class="quiz-actions">
                <button class="quit-btn" onclick="quitQuiz()">
                    <i class="fas fa-times"></i> Quit Quiz
                </button>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            @yield('content')
        </div>
    </div>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <script>
        // Global quiz functions
        function quitQuiz() {
            if (confirm('Are you sure you want to quit this quiz? Your progress will be lost.')) {
                window.location.href = '/quiz';
            }
        }
        
        // Prevent accidental page refresh
        window.addEventListener('beforeunload', function(e) {
            e.preventDefault();
            e.returnValue = '';
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Disable F5 refresh
            if (e.key === 'F5' || (e.ctrlKey && e.key === 'r')) {
                e.preventDefault();
                return false;
            }
            
            // Escape key to quit
            if (e.key === 'Escape') {
                quitQuiz();
            }
        });
        
        // Disable right-click context menu during quiz
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            return false;
        });
        
        // Disable text selection during quiz
        document.addEventListener('selectstart', function(e) {
            if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA') {
                e.preventDefault();
                return false;
            }
        });
        
        // Focus management for accessibility
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-focus first interactive element
            const firstInteractive = document.querySelector('button, input, select, textarea, a[href]');
            if (firstInteractive) {
                firstInteractive.focus();
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
