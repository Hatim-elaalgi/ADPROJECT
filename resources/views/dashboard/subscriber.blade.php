<!DOCTYPE html>
<html>
<head>
    <title>Subscriber Dashboard</title>
    <link rel="stylesheet" href="{{asset('css/dashboard/subscriber.css')}}">
</head>
<body>
    <div class="dashboard-container">
        <div class="header">
            <h1>Subscriber Dashboard</h1>
            <div class="user-info">
                <span>Welcome, {{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </div>

        <div class="message-section">
            <div class="message-card">
                <h2>Create a New Theme</h2>
                <p>As a subscriber, you can create new themes to share your knowledge and interests with others.</p>
                <p>Start contributing to our community by creating your own theme!</p>
            </div>
        </div>
    </div>
</body>
</html>
