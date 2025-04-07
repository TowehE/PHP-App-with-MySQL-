<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project 19 - DevOps Team Dashboard</title>
    <style>
        :root {
            --primary: #0066cc;
            --secondary: #2c3e50;
            --accent: #27ae60;
            --light: #ecf0f1;
            --dark: #1a1a1a;
            --light-gray: #f5f5f5;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light);
            color: var(--dark);
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        header {
            background: var(--primary);
            color: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            margin: 0;
            font-size: 2.5rem;
            text-align: center;
        }
        
        .team-members {
            display: flex;
            justify-content: space-between;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .team-member {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            flex: 1;
            transition: transform 0.2s ease;
            border-top: 4px solid var(--primary);
        }
        
        .team-member:hover {
            transform: translateY(-5px);
        }
        
        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 1rem;
            background: var(--secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
        }
        
        .team-member h3 {
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .team-member p {
            margin: 0;
            color: var(--secondary);
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .data-table th {
            background: var(--secondary);
            color: white;
            padding: 1rem;
            text-align: left;
        }
        
        .data-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--light-gray);
        }
        
        .data-table tr:hover {
            background: var(--light-gray);
        }
        
        .app-info {
            text-align: center;
            margin-top: 3rem;
            padding: 1rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .devops-badge {
            display: inline-block;
            background: var(--accent);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }
        
        .project-badge {
            background: var(--primary);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 1rem;
        }
        
        @media (max-width: 768px) {
            .team-members {
                flex-direction: column;
                align-items: center;
            }
            
            .team-member {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1><span class="project-badge">Project 19</span> DevOps Dashboard</h1>
        </header>
        
        <div class="team-members">
            <div class="team-member">
                <div class="avatar">E</div>
                <h3>Elizabeth</h3>
                <p>Team Member</p>
                <div class="devops-badge">Almost DevOps Engineer</div>
            </div>
            
            <div class="team-member">
                <div class="avatar">L</div>
                <h3>Light</h3>
                <p>Team Member</p>
                <div class="devops-badge">Almost DevOps Engineer</div>
            </div>
            
            <div class="team-member">
                <div class="avatar">M</div>
                <h3>Mercy</h3>
                <p>Team Member</p>
                <div class="devops-badge">Almost DevOps Engineer</div>
            </div>
        </div>
        
        <div class="app-info">
            <h2>PHP CRUD Application</h2>
            <p>Project 19 - Student Database Management System</p>
        </div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Elizabeth T.</td>
                    <td>alice@example.com</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Light N.</td>
                    <td>bob@example.com</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Mercy A.</td>
                    <td>alice@example.com</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
