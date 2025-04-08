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
            --sonar: #4b9fd5;
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
        
        .quality-gate {
            margin-top: 2rem;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            text-align: center;
        }
        
        .sonar-logo {
            width: 200px;
            margin: 0 auto 1rem;
        }
        
        .sonar-logo svg {
            width: 100%;
            height: auto;
        }
        
        .sonar-link {
            display: inline-block;
            background: var(--sonar);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 1rem;
            transition: background 0.2s ease;
        }
        
        .sonar-link:hover {
            background: #3d84b0;
        }
        
        .status-badge {
            background: #27ae60;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
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
        
        <div class="quality-gate">
            <div class="sonar-logo">
                <svg viewBox="0 0 290 80" xmlns="http://www.w3.org/2000/svg">
                    <path d="M32.1,10.8L32.1,10.8c-4.1-4.2-9.6-6.5-15.5-6.5C7.4,4.3,0,11.7,0,20.9c0,0,0,0,0,0v0c0,9.2,7.4,16.6,16.6,16.6
                    c5.9,0,11.4-2.3,15.5-6.5l0,0c0.5-0.5,0.9-1.1,1.3-1.7l13.4,13.4c1.1,1.1,2.9,1.1,4.1,0c1.1-1.1,1.1-2.9,0-4.1L37.4,25.3
                    c-0.4-0.6-0.8-1.2-1.3-1.7c-0.5-0.5-1.1-1-1.7-1.4L20.9,8.7c-1.1-1.1-2.9-1.1-4.1,0C15.7,9.8,15.7,11.7,16.8,12.8l12.6,12.6
                    c0,0.1,0,0.2,0,0.3c0,3.9-3.2,7.1-7.1,7.1c-3.9,0-7.1-3.2-7.1-7.1v0c0-3.9,3.2-7.1,7.1-7.1c0.1,0,0.2,0,0.3,0l0,0
                    c0.6-0.1,1.2-0.4,1.7-0.8c1.1-1.1,1.1-2.9,0-4.1c-0.6-0.6-1.4-0.9-2.2-0.9c-0.2,0-0.3,0-0.5,0c-5.6,0.3-10.1,4.8-10.4,10.4
                    c0,0.2,0,0.3,0,0.5v0c0,0.2,0,0.3,0,0.5c0.3,5.6,4.8,10.1,10.4,10.4c0.2,0,0.3,0,0.5,0c0.2,0,0.3,0,0.5,0
                    c5.6-0.3,10.1-4.8,10.4-10.4c0-0.2,0-0.3,0-0.5v0c0-0.2,0-0.3,0-0.5C33,12.2,32.7,11.4,32.1,10.8z" fill="#4b9fd5"/>
                    <path d="M117.9,32c0,10.3-8.5,18.7-19.2,18.7c-6.8,0-14.2-3.9-16-10.2l5.1-3.3c1.1,4.7,4.9,8.2,10.7,8.2
                    c6.3,0,11.3-4.2,12.1-9.5c0-7.4-5.9-13.1-12.7-13.1c-4.3,0-7.1,1.8-9.6,4.1l-5.7-2.9v-17h25.5v5.3h-19.6v9.1c2.2-1.4,5.4-2.5,9-2.5
                    C109,18.9,117.9,22.8,117.9,32z" fill="#4b9fd5"/>
                    <path d="M147.3,36.9h-14.9V16.7h5.4v14.9h9.5V36.9z" fill="#4b9fd5"/>
                    <polygon points="149,36.9 149,16.7 166.7,16.7 166.7,21.8 154.5,21.8 154.5,24 166.7,24 166.7,29.1 154.5,29.1 154.5,31.8 166.7,31.8 166.7,36.9" fill="#4b9fd5"/>
                    <path d="M185.8,36.9h-5.3v-3.1c-1.3,2.4-4.1,3.7-7.5,3.7c-6.5,0-9.1-4.3-9.1-9.9V16.7h5.4v9.8c0,3.2,0.7,6.3,4.7,6.3
                    c4.8,0,6.5-3.3,6.5-7.5v-8.6h5.3V36.9z" fill="#4b9fd5"/>
                    <path d="M199.2,47.6h-5.4V16.7h5.3v2.8c1.7-2.7,4.9-3.4,7.6-3.4c7.3,0,12.1,6.5,12.1,13.4c0,7.2-5.3,13.1-12.4,13.1
                    c-2.9,0-5.4-0.7-7.2-3.1V47.6z M211.9,29.5c0-4.5-2.4-8.4-7.4-8.4c-5.1,0-7.4,3.9-7.4,8.4c0,4.1,2.2,8.2,7.4,8.2
                    C209.4,37.7,211.9,33.9,211.9,29.5z" fill="#4b9fd5"/>
                    <path d="M224.2,28.2c0-11.7,9.5-13.5,15.1-13.5c5.7,0,15.1,1.8,15.1,13.5v8.7h-24.7c0.4,3.1,2.9,5.2,8.1,5.2
                    c2.9,0,6.1-1.1,7.9-3l3.6,3.3c-3,3.3-7.5,4.6-11.9,4.6c-7.8,0-14.8-3.4-14.8-13.3v-5.7H224.2z M248.9,27c-0.4-3.3-2.9-5.4-8.1-5.4
                    c-4.5,0-7.5,1.7-8.3,5.4H248.9z" fill="#4b9fd5"/>
                    <path d="M289.5,36.9h-5.3v-3.1c-1.3,2.4-4.1,3.7-7.5,3.7c-6.5,0-9.1-4.3-9.1-9.9V16.7h5.4v9.8c0,3.2,0.7,6.3,4.7,6.3
                    c4.8,0,6.5-3.3,6.5-7.5v-8.6h5.3V36.9z" fill="#4b9fd5"/>
                </svg>
            </div>
            <h2>Quality Gate Status</h2>
            <div class="status-badge">OK</div>
            <p>SonarQube analysis results for PHP CRUD Application</p>
            <a href="http://54.196.217.149:9000/dashboard?id=php-crud-app" class="sonar-link" target="_blank">View SonarQube Dashboard</a>
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
