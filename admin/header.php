<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Include any additional stylesheets or scripts here -->
    <style>
        /* Additional styles specific to admin header */
        .admin-header {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .admin-header h1 a{
            text-decoration: none;
            color: #fff;
        }

        .admin-header .logout-link {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            padding: 5px 10px;
            border: 2px solid #fff;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .admin-header .logout-link:hover {
            background-color: #fff;
            color: #333;
        }
        @media (max-width: 768px) {
            th, td {
                padding: 10px;
                font-size: 14px;
            }
            a {
                width: 100%;
                padding: 15px 20px;
            }
        }
        @media (max-width: 480px) {
            th, td {
                padding: 8px;
                font-size: 12px;
            }
            a {
                width: 100%;
                padding: 12px 15px;
            }
        }

    </style>
</head>

<body>
    <header class="admin-header">
        <h1><a href="admin_dashboard.php">Admin Dashboard</a></h1>
        <a href="logout.php" class="logout-link">Logout</a>
    </header>
    <nav class="admin-nav">
        <!-- Add any additional navigation links here if needed -->
    </nav>
    <div class="admin-content">
        <!-- Start of main content, will be closed in footer.php -->
        <main class="admin-main">
            <!-- Placeholder for the main content area -->
