<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DocShare - File Sharing Dashboard</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <style>
        :root {
            --primary: #3a86ff;
            --secondary: #8338ec;
            --success: #06d6a0;
            --danger: #ef476f;
            --warning: #ffd166;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --sidebar-width: 250px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fb;
            color: var(--dark);
        }
        
        .dashboard {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background-color: #fff;
            border-right: 1px solid #e0e0e0;
            padding: 20px 0;
            position: fixed;
            height: 100%;
            overflow-y: auto;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05);
        }
        
        .logo {
            display: flex;
            align-items: center;
            padding: 0 20px 20px;
            border-bottom: 1px solid #f0f0f0;
            margin-bottom: 20px;
        }
        
        .logo i {
            font-size: 24px;
            color: var(--primary);
            margin-right: 10px;
        }
        
        .logo h1 {
            font-size: 20px;
            font-weight: 600;
            color: var(--dark);
        }
        
        .menu {
            list-style: none;
        }
        
        .menu li {
            margin-bottom: 5px;
        }
        
        .menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--gray);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        
        .menu a:hover, .menu a.active {
            background-color: rgba(58, 134, 255, 0.1);
            color: var(--primary);
            border-left: 3px solid var(--primary);
        }
        
        .menu a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        /* Main Content Styles */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .header h2 {
            font-size: 24px;
            font-weight: 600;
        }
        
        .search-bar {
            flex: 0 0 300px;
            position: relative;
        }
        
        .search-bar input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            outline: none;
            background-color: #fff;
        }
        
        .search-bar i {
            position: absolute;
            top: 12px;
            left: 15px;
            color: var(--gray);
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-menu .notification {
            position: relative;
            cursor: pointer;
        }
        
        .user-menu .notification i {
            font-size: 18px;
            color: var(--gray);
        }
        
        .user-menu .notification .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--danger);
            color: white;
            font-size: 10px;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }
        
        .user-profile img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .user-info span {
            display: block;
        }
        
        .user-info .name {
            font-weight: 600;
            font-size: 14px;
        }
        
        .user-info .role {
            font-size: 12px;
            color: var(--gray);
        }
        
        /* Stats Section */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card .icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            font-size: 20px;
            color: white;
        }
        
        .stat-card .title {
            font-size: 14px;
            color: var(--gray);
            margin-bottom: 5px;
        }
        
        .stat-card .value {
            font-size: 24px;
            font-weight: 600;
        }
        
        .blue {
            background-color: rgba(58, 134, 255, 0.2);
            color: var(--primary);
        }
        
        .purple {
            background-color: rgba(131, 56, 236, 0.2);
            color: var(--secondary);
        }
        
        .green {
            background-color: rgba(6, 214, 160, 0.2);
            color: var(--success);
        }
        
        .yellow {
            background-color: rgba(255, 209, 102, 0.2);
            color: var(--warning);
        }
        
        /* Document Section */
        .document-section {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .section-header h3 {
            font-size: 18px;
            font-weight: 600;
        }
        
        .section-header .actions {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 8px 15px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2a75e8;
        }
        
        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--gray);
            color: var(--gray);
        }
        
        .btn-outline:hover {
            background-color: #f0f0f0;
        }
        
        /* Documents Table */
        .document-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .document-table th {
            text-align: left;
            padding: 12px;
            background-color: rgba(58, 134, 255, 0.1);
            color: var(--dark);
            font-weight: 600;
        }
        
        .document-table td {
            padding: 12px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }
        
        .document-table tr:last-child td {
            border-bottom: none;
        }
        
        .document-table tr:hover {
            background-color: rgba(58, 134, 255, 0.03);
        }
        
        .document-name {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .document-icon {
            width: 36px;
            height: 36px;
            border-radius: 5px;
            background-color: rgba(58, 134, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
        }
        
        .document-info span {
            display: block;
        }
        
        .document-info .name {
            font-weight: 500;
        }
        
        .document-info .date {
            font-size: 12px;
            color: var(--gray);
        }
        
        .category span {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            background-color: rgba(58, 134, 255, 0.1);
            color: var(--primary);
        }
        
        .category span.marketing {
            background-color: rgba(6, 214, 160, 0.1);
            color: var(--success);
        }
        
        .category span.finance {
            background-color: rgba(255, 209, 102, 0.1);
            color: var(--warning);
        }
        
        .category span.legal {
            background-color: rgba(131, 56, 236, 0.1);
            color: var(--secondary);
        }
        
        .share-info {
            display: flex;
            gap: 5px;
            align-items: center;
        }
        
        .share-info i {
            color: var(--gray);
        }
        
        .document-actions {
            display: flex;
            gap: 15px;
        }
        
        .action-btn {
            width: 30px;
            height: 30px;
            border-radius: 5px;
            background-color: #f5f7fb;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--gray);
            transition: all 0.3s;
        }
        
        .action-btn:hover {
            background-color: var(--primary);
            color: white;
        }
        
        /* Recent Activity */
        .activity-list {
            list-style: none;
            margin-top: 15px;
        }
        
        .activity-item {
            display: flex;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(58, 134, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            flex-shrink: 0;
        }
        
        .activity-icon.upload {
            background-color: rgba(6, 214, 160, 0.1);
            color: var(--success);
        }
        
        .activity-icon.download {
            background-color: rgba(131, 56, 236, 0.1);
            color: var(--secondary);
        }
        
        .activity-icon.delete {
            background-color: rgba(239, 71, 111, 0.1);
            color: var(--danger);
        }
        
        .activity-content {
            flex: 1;
        }
        
        .activity-content .title {
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .activity-content .time {
            font-size: 12px;
            color: var(--gray);
        }
        
        /* Responsive */
        @media (max-width: 1200px) {
            .stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
                padding: 20px 0;
            }
            
            .logo {
                justify-content: center;
                padding: 0 10px 20px;
            }
            
            .logo h1 {
                display: none;
            }
            
            .menu a span {
                display: none;
            }
            
            .menu a {
                justify-content: center;
                padding: 12px;
            }
            
            .menu a i {
                margin-right: 0;
            }
            
            .main-content {
                margin-left: 70px;
            }
        }
        
        @media (max-width: 768px) {
            .stats {
                grid-template-columns: 1fr;
            }
            
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .search-bar {
                width: 100%;
                flex: auto;
            }
            
            .document-table {
                display: block;
                overflow-x: auto;
            }
        }
        
        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
        }
        
        .modal.active {
            display: flex;
        }
        
        .modal-content {
            background-color: #fff;
            width: 500px;
            max-width: 90%;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .modal-header h3 {
            font-size: 18px;
            font-weight: 600;
        }
        
        .close-modal {
            cursor: pointer;
            font-size: 20px;
            color: var(--gray);
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            outline: none;
        }
        
        .form-control:focus {
            border-color: var(--primary);
        }
        
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <i class="fas fa-file-alt"></i>
                <h1>DocShare</h1>
            </div>
            <ul class="menu">
                <li>
                    <a href="#" class="active">
                        <i class="fas fa-th-large"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-file-alt"></i>
                        <span>Documents</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-share-alt"></i>
                        <span>Shared</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-star"></i>
                        <span>Favorites</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-chart-line"></i>
                        <span>Analytics</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-trash"></i>
                        <span>Trash</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h2>Dashboard</h2>
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search documents...">
                </div>
                <div class="user-menu">
                    <div class="notification">
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
                    </div>
                    <div class="user-profile">
                        <img src="/api/placeholder/40/40" alt="User">
                        <div class="user-info">
                            <span class="name">John Doe</span>
                            <span class="role">Admin</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="stats">
                <div class="stat-card">
                    <div class="icon blue">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="title">Total Documents</div>
                    <div class="value">142</div>
                </div>
                <div class="stat-card">
                    <div class="icon purple">
                        <i class="fas fa-share-alt"></i>
                    </div>
                    <div class="title">Shared Documents</div>
                    <div class="value">38</div>
                </div>
                <div class="stat-card">
                    <div class="icon green">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="title">Views This Month</div>
                    <div class="value">1,247</div>
                </div>
                <div class="stat-card">
                    <div class="icon yellow">
                        <i class="fas fa-download"></i>
                    </div>
                    <div class="title">Downloads</div>
                    <div class="value">92</div>
                </div>
            </div>
            
            <!-- Recent Documents Section -->
            <div class="document-section">
                <div class="section-header">
                    <h3>Recent Documents</h3>
                    <div class="actions">
                        <button class="btn btn-outline">
                            <i class="fas fa-filter"></i>
                            Filter
                        </button>
                        <button class="btn btn-primary" id="uploadBtn">
                            <i class="fas fa-upload"></i>
                            Upload
                        </button>
                    </div>
                </div>
                
                <table class="document-table">
                    <thead>
                        <tr>
                            <th>Document</th>
                            <th>Category</th>
                            <th>Shared With</th>
                            <th>Last Modified</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="document-name">
                                    <div class="document-icon">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <div class="document-info">
                                        <span class="name">Annual Report 2024</span>
                                        <span class="date">PDF • 4.5 MB</span>
                                    </div>
                                </div>
                            </td>
                            <td class="category">
                                <span class="finance">Finance</span>
                            </td>
                            <td>
                                <div class="share-info">
                                    <i class="fas fa-users"></i>
                                    <span>5 people</span>
                                </div>
                            </td>
                            <td>May 10, 2025</td>
                            <td>
                                <div class="document-actions">
                                    <div class="action-btn" title="Share" onclick="openShareModal('Annual Report 2024')">
                                        <i class="fas fa-share-alt"></i>
                                    </div>
                                    <div class="action-btn" title="Download">
                                        <i class="fas fa-download"></i>
                                    </div>
                                    <div class="action-btn" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="document-name">
                                    <div class="document-icon">
                                        <i class="fas fa-file-word"></i>
                                    </div>
                                    <div class="document-info">
                                        <span class="name">Project Proposal</span>
                                        <span class="date">DOCX • 2.1 MB</span>
                                    </div>
                                </div>
                            </td>
                            <td class="category">
                                <span class="marketing">Marketing</span>
                            </td>
                            <td>
                                <div class="share-info">
                                    <i class="fas fa-users"></i>
                                    <span>3 people</span>
                                </div>
                            </td>
                            <td>May 8, 2025</td>
                            <td>
                                <div class="document-actions">
                                    <div class="action-btn" title="Share" onclick="openShareModal('Project Proposal')">
                                        <i class="fas fa-share-alt"></i>
                                    </div>
                                    <div class="action-btn" title="Download">
                                        <i class="fas fa-download"></i>
                                    </div>
                                    <div class="action-btn" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="document-name">
                                    <div class="document-icon">
                                        <i class="fas fa-file-excel"></i>
                                    </div>
                                    <div class="document-info">
                                        <span class="name">Budget Analysis Q2 2025</span>
                                        <span class="date">XLSX • 1.8 MB</span>
                                    </div>
                                </div>
                            </td>
                            <td class="category">
                                <span class="finance">Finance</span>
                            </td>
                            <td>
                                <div class="share-info">
                                    <i class="fas fa-users"></i>
                                    <span>2 people</span>
                                </div>
                            </td>
                            <td>May 5, 2025</td>
                            <td>
                                <div class="document-actions">
                                    <div class="action-btn" title="Share" onclick="openShareModal('Budget Analysis Q2 2025')">
                                        <i class="fas fa-share-alt"></i>
                                    </div>
                                    <div class="action-btn" title="Download">
                                        <i class="fas fa-download"></i>
                                    </div>
                                    <div class="action-btn" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="document-name">
                                    <div class="document-icon">
                                        <i class="fas fa-file-contract"></i>
                                    </div>
                                    <div class="document-info">
                                        <span class="name">Partnership Agreement</span>
                                        <span class="date">PDF • 3.2 MB</span>
                                    </div>
                                </div>
                            </td>
                            <td class="category">
                                <span class="legal">Legal</span>
                            </td>
                            <td>
                                <div class="share-info">
                                    <i class="fas fa-users"></i>
                                    <span>7 people</span>
                                </div>
                            </td>
                            <td>May 3, 2025</td>
                            <td>
                                <div class="document-actions">
                                    <div class="action-btn" title="Share" onclick="openShareModal('Partnership Agreement')">
                                        <i class="fas fa-share-alt"></i>
                                    </div>
                                    <div class="action-btn" title="Download">
                                        <i class="fas fa-download"></i>
                                    </div>
                                    <div class="action-btn" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="document-name">
                                    <div class="document-icon">
                                        <i class="fas fa-file-powerpoint"></i>
                                    </div>
                                    <div class="document-info">
                                        <span class="name">Q1 Results Presentation</span>
                                        <span class="date">PPTX • 6.7 MB</span>
                                    </div>
                                </div>
                            </td>
                            <td class="category">
                                <span>General</span>
                            </td>
                            <td>
                                <div class="share-info">
                                    <i class="fas fa-users"></i>
                                    <span>12 people</span>
                                </div>
                            </td>
                            <td>April 28, 2025</td>
                            <td>
                                <div class="document-actions">
                                    <div class="action-btn" title="Share" onclick="openShareModal('Q1 Results Presentation')">
                                        <i class="fas fa-share-alt"></i>
                                    </div>
                                    <div class="action-btn" title="Download">
                                        <i class="fas fa-download"></i>
                                    </div>
                                    <div class="action-btn" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Access Logs Section -->
            <div class="document-section">
                <div class="section-header">
                    <h3>Recent Activity</h3>
                    <div class="actions">
                        <button class="btn btn-outline">
                            <i class="fas fa-download"></i>
                            Export
                        </button>
                    </div>
                </div>
                
                <ul class="activity-list">
                    <li class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="activity-content">
                            <div class="title">Sarah Johnson viewed <strong>Annual Report 2024</strong></div>
                            <div class="time">Today, 10:45 AM</div>
                        </div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon upload">
                            <i class="fas fa-upload"></i>
                        </div>
                        <div class="activity-content">
                            <div class="title">You uploaded <strong>Project Proposal</strong></div>
                            <div class="time">Today, 09:12 AM</div>
                        </div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon download">
                            <i class="fas fa-download"></i>
                        </div>
                        <div class="activity-content">
                            <div class="title">Mike Richards downloaded <strong>Budget Analysis Q2 2025</strong></div>
                            <div class="time">Yesterday, 4:30 PM</div>
                        </div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-share-alt"></i>
                        </div>
                        <div class="activity-content">
                            <div class="title">You shared <strong>Partnership Agreement</strong> with 3 people</div>
                            <div class="time">Yesterday, 2:15 PM</div>
                        </div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon delete">
                            <i class="fas fa-trash"></i>
                        </div>
                        <div class="activity-content">
                            <div class="title">You deleted <strong>Old Marketing Materials</strong></div>
                            <div class="time">May 9, 2025, 11:20 AM</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Share Modal -->
    <div class="modal" id="shareModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Share Document</h3>
                <span class="close-modal">&times;</span>
            </div>
            <div id="shareDocName" style="margin-bottom: 15px; font-weight: 500;"></div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" class="form-control" placeholder="Enter email address">
            </div>
            <div class="form-group">
                <label>Permission</label>
                <select class="form-control">
                    <option value="view">Can view</option>
                    <option value="edit">Can edit</option>
                    <option value="comment">Can comment</option>
                </select>
            </div>
            <div class="form-group">
                <label>Expiration</label>
                <select class="form-control">
                    <option value="never">Never</option>
                    <option value="1day">1 day</option>
                    <option value="7days">7 days</option>
                    <option value="30days">30 days</option>
                </select>
            </div>
            <div class="form-group">
                <label>Add a note (optional)</label>
                <textarea class="form-control" rows="3" placeholder="Write a message..."></textarea>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeShareModal()">Cancel</button>
                <button class="btn btn-primary">Share</button>
            </div>
        </div>
    </div>
    
    <!-- Upload Modal -->
    <div class="modal" id="uploadModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Upload Document</h3>
                <span class="close-modal">&times;</span>
            </div>
            <div class="form-group">
                <label>Select File</label>
                <input type="file" class="form-control">
            </div>
            <div class="form-group">
                <label>Document Name</label>
                <input type="text" class="form-control" placeholder="Enter document name">
            </div>
            <div class="form-group">
                <label>Category</label>
                <select class="form-control">
                    <option value="general">General</option>
                    <option value="finance">Finance</option>
                    <option value="marketing">Marketing</option>
                    <option value="legal">Legal</option>
                    <option value="hr">HR</option>
                </select>
            </div>
            <div class="form-group">
                <label>Description (optional)</label>
                <textarea class="form-control" rows="3" placeholder="Write a description..."></textarea>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeUploadModal()">Cancel</button>
                <button class="btn btn-primary">Upload</button>
            </div>
        </div>
    </div>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        // Share Modal Functions
        const shareModal = document.getElementById('shareModal');
        const shareDocName = document.getElementById('shareDocName');
        
        function openShareModal(docName) {
            shareModal.classList.add('active');
            shareDocName.textContent = `Share "${docName}"`;
        }
        
        function closeShareModal() {
            shareModal.classList.remove('active');
        }
        
        // Close modal when clicking on X or outside the modal
        document.querySelectorAll('.close-modal').forEach(close => {
            close.addEventListener('click', function() {
                this.closest('.modal').classList.remove('active');
            });
        });
        
        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('active');
            }
        });
        
        // Upload Modal Functions
        const uploadModal = document.getElementById('uploadModal');
        const uploadBtn = document.getElementById('uploadBtn');
        
        uploadBtn.addEventListener('click', function() {
            uploadModal.classList.add('active');
        });
        
        function closeUploadModal() {
            uploadModal.classList.remove('active');
        }
        
        // Sample data for demonstration
        // In a real application, this would come from your backend
        const users = [
            { id: 1, name: 'John Doe', email: 'john@example.com', role: 'Admin' },
            { id: 2, name: 'Sarah Johnson', email: 'sarah@example.com', role: 'Editor' },
            { id: 3, name: 'Mike Richards', email: 'mike@example.com', role: 'Viewer' }
        ];
        
        const documents = [
            { 
                id: 1, 
                name: 'Annual Report 2024', 
                type: 'PDF', 
                size: '4.5 MB', 
                category: 'Finance',
                sharedWith: 5,
                modified: 'May 10, 2025'
            },
            { 
                id: 2, 
                name: 'Project Proposal', 
                type: 'DOCX', 
                size: '2.1 MB', 
                category: 'Marketing',
                sharedWith: 3,
                modified: 'May 8, 2025'
            },
            { 
                id: 3, 
                name: 'Budget Analysis Q2 2025', 
                type: 'XLSX', 
                size: '1.8 MB', 
                category: 'Finance',
                sharedWith: 2,
                modified: 'May 5, 2025'
            },
            { 
                id: 4, 
                name: 'Partnership Agreement', 
                type: 'PDF', 
                size: '3.2 MB', 
                category: 'Legal',
                sharedWith: 7,
                modified: 'May 3, 2025'
            },
            { 
                id: 5, 
                name: 'Q1 Results Presentation', 
                type: 'PPTX', 
                size: '6.7 MB', 
                category: 'General',
                sharedWith: 12,
                modified: 'April 28, 2025'
            }
        ];
        
        // This function would be expanded in a real application
        function searchDocuments(query) {
            if (!query) return documents;
            
            query = query.toLowerCase();
            return documents.filter(doc => 
                doc.name.toLowerCase().includes(query) || 
                doc.category.toLowerCase().includes(query)
            );
        }
        
        // Search functionality
        const searchInput = document.querySelector('.search-bar input');
        searchInput.addEventListener('input', function() {
            const query = this.value;
            const results = searchDocuments(query);
            // In a real app, you would update the table with the results
            console.log("Search results:", results);
        });
    </script>
</body>
</html>