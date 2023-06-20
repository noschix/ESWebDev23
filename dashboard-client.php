<!---Copyright Alfaro Mendoza, Alberto; Bernal, Isabella; Sacranie, Kabir Ahmed; Wenzler, Leonhard Paul Hariolf; Raissi, Noah Aria -->
<?php
// Assuming you have a database connection established
include('db.php'); 
// Fetch all agent data from the database
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search term
$searchTerm = $_GET['term'];

$sql = "SELECT * FROM agents WHERE agent_firstname LIKE '%{$searchTerm}%' OR agent_lastname LIKE '%{$searchTerm}%' OR agent_city LIKE '%{$searchTerm}%' OR agent_country LIKE '%{$searchTerm}%' OR agent_exp LIKE '%{$searchTerm}%' OR agent_about LIKE '%{$searchTerm}%'";
$result = $conn->query($sql);

$agents = [];
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $agents[] = $row;
    }
} 

echo json_encode($agents);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name=”robots” content=”noindex”>

    <title>Client Dashboard - SecretAgent</title>
    <link rel="stylesheet" href="styles.css" type="text/css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/4bebb4b770.js" crossorigin="anonymous"></script>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="logo-container">
                <a href="index.html"><img src="images/logo.png" alt="SecretAgent Logo" class="logo"></a>
            </div>
            <nav class="nav-menu">
                <ul class="menu-list">
                    <li class="menu-item"><a href="#">Clients</a></li>
                    <li class="menu-item"><a href="#agents">Agents</a></li>
                    <li class="menu-item"><a href="index.html" class="login">Sign Out</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <section class="text-section text-section-subpage">
        <div class="text-content text-content-subpage">
            <div class="text-details">
                <h2 class="text-title">Search for your Agent</h2>
                <div class="search-input-container">
                    <input type="search" class="search-input" placeholder="Search..." oninput="searchAgents(this.value)">
                </div>
            </div>
        </div>
    </section>

    <section class="agent-grid2 agent-grid-subpage" id="agent-results">
        <!-- Agent cards will be dynamically populated here -->
    </section>


    <section class="agent-grid agent-grid-subpage">
        <!-- Agent Card 1 -->
        <div class="agent-card agent-card-subpage">
            <div style="background-image: url('https://source.unsplash.com/featured/300x201?nature');" class="agent-cover">
            <span class="pro">PRO</span>
            </div>
            <div class="agent-content">
                <img src="https://xsgames.co/randomusers/avatar.php?g=female&" alt="profile" class="agent-profile" />
                <h2>Samantha Baker<span>Life Insurance</span></h2>
                <p>Offering custom life insurance policies to safeguard your future and give you peace of mind. </p>
                <div class="agent-actions">
                    <a href="#" class="agent-action hire-agent">Hire</a>
                    <a href="#" class="agent-action more-info">More Info</a>
                </div>
            </div>
        </div>
        <!-- Agent Card 2 -->
        <div class="agent-card agent-card-subpage">
            <div style="background-image: url('https://source.unsplash.com/featured/300x202?beach');" class="agent-cover">
                <span class="pro">PRO</span>
            </div>
            <div class="agent-content">
                <img src="https://xsgames.co/randomusers/avatar.php?g=male&" alt="profile" class="agent-profile" />
                <h2>Robert Jones<span>Car Insurance</span></h2>
                <p>Specializes in providing car insurance that covers all aspects of risk and damage. </p>
                <a href="#" class="agent-action hire-agent">Hire</a>
                <a href="#" class="agent-action more-info">More Info</a>
            </div>
        </div>
        <!-- Agent Card 3 -->
        <div class="agent-card agent-card-subpage">
            <div style="background-image: url('https://source.unsplash.com/featured/300x203?landscape');" class="agent-cover">
                <span class="pro">PRO</span>
            </div>
            <div class="agent-content">
                <img src="https://xsgames.co/randomusers/avatar.php?g=female&1" alt="profile" class="agent-profile" />
                <h2>Emily Smith<span>Health Insurance</span></h2>
                <p>Delivers personalized health insurance plans to secure you and your family's healthcare needs. </p>
                <a href="#" class="agent-action hire-agent">Hire</a>
                <a href="#" class="agent-action more-info">More Info</a>
            </div>
        </div>
        <!-- Agent Card 4 -->
        <div class="agent-card agent-card-subpage">
            <div style="background-image: url('https://source.unsplash.com/featured/300x204?city');" class="agent-cover">
                <span class="pro">PRO</span>
            </div>
            <div class="agent-content">
                <img src="https://xsgames.co/randomusers/avatar.php?g=male&1" alt="profile" class="agent-profile" />
                <h2>Thomas Brown<span>Home Insurance</span></h2>
                <p>Committed to securing your home and belongings with comprehensive home insurance policies. </p>
                <a href="#" class="agent-action hire-agent">Hire</a>
                <a href="#" class="agent-action more-info">More Info</a>
            </div>
        </div>
        <!-- Agent Card 5 -->
        <div class="agent-card agent-card-subpage">
            <div style="background-image: url('https://source.unsplash.com/featured/300x205?bowling');" class="agent-cover">
                <span class="pro">PRO</span>
            </div>
            <div class="agent-content">
                <img src="https://xsgames.co/randomusers/avatar.php?g=female" alt="profile" class="agent-profile" />
                <h2>Laura Johnson<span>Travel Insurance</span></h2>
                <p>Experienced in providing broad travel insurance plans to make your journeys worry-free. </p>
                <a href="#" class="agent-action hire-agent">Hire</a>
                <a href="#" class="agent-action more-info">More Info</a>
            </div>
        </div>
        <!-- Agent Card 6 -->
        <div class="agent-card agent-card-subpage">
            <div style="background-image: url('https://source.unsplash.com/featured/300x206?car');" class="agent-cover">
                <span class="pro">PRO</span>
            </div>
            <div class="agent-content">
                <img src="https://xsgames.co/randomusers/avatar.php?g=male" alt="profile" class="agent-profile" />
                <h2>Michael Wilson<span>Business Insurance</span></h2>
                <p>Specializes in business insurance plans to protect your assets and cover potential liabilities. </p>
                <a href="#" class="agent-action hire-agent">Hire</a>
                <a href="#" class="agent-action more-info">More Info</a>
            </div>
        </div>
    </section>
    <section class="contact-section">
        <h1 class="section-title">Need help finding an agent?</h1>
        <p class="contact-description">We're here to help and answer any question you might have. We look forward to hearing from you 🤝 
        </p>
        <div class="contact-info">
            <div class="contact-phone">
                <i class="fa-solid fa-phone fa-2xl"></i>
                <div><h3>Hotline</h3><p>+34 123 45 67</p></div>
            </div>
            <div class="contact-email">
                <i class="fa-solid fa-envelope fa-2xl"></i>
                <div>
                    <h3>Email</h3>
                    <p>info@SecretAgent.com</p>
                </div>
            </div>
            <div class="contact-address">
                <i class="fa-solid fa-map-marker-alt fa-2xl"></i>
                <div>
                    <h3>Office Address</h3>
                    <p>Carrer del Rossello 20, Barcelona, ES</p>
                </div>
            </div>
            <div class="contact-hours">
                <i class="fa-solid fa-clock fa-2xl"></i>
                <div>
                    <h3>Contact Hours</h3>
                    <p>9:00 - 15:00</p>
                </div>
            </div>
        </div>
    </section>
    <footer>
        <p>Made with <svg viewBox="0 0 1792 1792" preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg" style="height: 0.8rem;"><path d="M896 1664q-26 0-44-18l-624-602q-10-8-27.5-26T145 952.5 77 855 23.5 734 0 596q0-220 127-344t351-124q62 0 126.5 21.5t120 58T820 276t76 68q36-36 76-68t95.5-68.5 120-58T1314 128q224 0 351 124t127 344q0 221-229 450l-623 600q-18 18-44 18z" fill="#e25555"></path></svg> in our Web-Dev Class @ Esade</p>
        <span style="text-align: center; margin: 0 auto; width: 100%; font-size: xx-small; display: block; padding-bottom: 10px; color: lightslategrey;">Version 1.3</span>
    </footer>

    <script>
    $(document).ready(function() {
        console.log("jQuery is loaded and ready!");
        $('.search-input').on('keyup', function() {
            var searchTerm = $(this).val();
            $.getJSON('agent_search.php', { term: searchTerm }, function(data) {
                var html = '';
                $.each(data, function(i, agent) {
                    html += '<div class="agent-card agent-card-subpage">';
                    html += '<div style="background-image: url(\'https://source.unsplash.com/featured/300x201?nature\');" class="agent-cover"><span class="pro">PRO</span></div>';
                    html += '<div class="agent-content">';
                    html += '<img src="https://xsgames.co/randomusers/avatar.php?g=female&" alt="profile" class="agent-profile" />';
                    html += '<h2>' + agent.agent_firstname + ' ' + agent.agent_lastname + '<span>' + agent.agent_exp + '</span></h2>';
                    html += '<p>' + agent.agent_about + '</p>';
                    html += '<div class="agent-actions">';
                    html += '<a href="#" class="agent-action hire-agent">Hire</a>';
                    html += '<a href="#" class="agent-action more-info">More Info</a>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                });
                $('.agent-grid').html(html);
            });
        });
    });
    </script>
    <script src="js/main.js"></script>
</body>
</html>
