var labels = ['nature', 'beach', 'bowling', 'cars', 'city', 'landscape', 'architecture', 'abstract', 'minimalistic'];
var agents = [];
var hiredAgents = [];

//global function to run on page 
function loadAgents() { 
$.getJSON('../all_agents.php', function(data) {
    console.log("Received data from server:", data);

    agents = data;

    $.each(agents, function(index, agent) {
        // Determine gender and store on agent object
        agent.gender = (index % 2 == 0) ? 'male' : 'female';

        // Select a label for the background image URL and store on agent object
        agent.label = labels[index % labels.length];
        //assign pro status on random basis
        agent.pro = (Math.random() > 0.5) ? true : false;

        // Store index on agent object
        agent.index = index;
    });
    // Display all agents initially
    displayAgentsWithHire(agents);
});
}

$('.agent-grid-hired').on('click', '.hire-agent, .unhire-agent', function(e) {
e.preventDefault();
var button = $(this);
var agentId = button.data('agent-id');
// Send a request to the server
$.ajax({
    url: '../new_hire.php',
    type: 'post',
    data: {
        agent_id: agentId
    },
    success: function(response) {
        if (response.message === 'Agent hired successfully.') {
            button.css('background-color', 'green');
            button.html('<i class="fas fa-check"></i>');
            button.prop('disabled', true);
            hiredAgents.push(agentId);
            setTimeout(function() {
                //add .unhire-agent class
                button.css('background-color', '');
                button.css('color', '');
                button.addClass('unhire-agent');
                button.html('<i class="fas fa-times"></i> Unhire');
                button.prop('disabled', false);
            }, 1500);
        } else if (response.message === 'Agent unhired successfully.') {
            button.removeClass('unhire-agent');
            button.css('background-color', 'grey', 'color', 'darkgrey');
            button.html('<i class="fa fa-repeat"></i> Re-Hire?');
            hiredAgents = hiredAgents.filter(function(id) {
                return id !== agentId;
            });
        } else {
            button.css('background-color', 'grey');
            button.html('<i class="fa fa-repeat"></i> Try again');
        }
        //5 seconds until exectution of loadAgents();
        setTimeout(function() {
            loadAgents();
        }, 5000);
    },
    error: function(jqXHR, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
    }
});
});

function displayAgentsWithHire(agents) {
$.ajax({
    url: '../fetch_hired_agents.php',
    type: 'get',
    dataType: 'json',
    success: function(response) {
        hiredAgents = response.map(function(agent) {
        return agent.agent_id;
        });
        displayAgents(agents);
        displayHiredAgents(response);
    },
    error: function(jqXHR, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
    }
});
}

// Function to filter agents based on a search term
function filterAgents(term) {
return agents.filter(function(agent) {
    return agent.agent_firstname && agent.agent_firstname.toLowerCase().includes(term.toLowerCase()) ||
        agent.agent_lastname && agent.agent_lastname.toLowerCase().includes(term.toLowerCase()) ||
        agent.agent_city && agent.agent_city.toLowerCase().includes(term.toLowerCase()) ||
        agent.agent_country && agent.agent_country.toLowerCase().includes(term.toLowerCase()) ||
        agent.agent_exp && agent.agent_exp.toLowerCase().includes(term.toLowerCase()) ||
        agent.agent_about && agent.agent_about.toLowerCase().includes(term.toLowerCase());
});
}

// Function to display agents
function displayAgents(filteredAgents) {
var html = '';

$.each(filteredAgents, function(index, agent) {
    // Determine gender and create avatar URL
    var avatarUrl = 'https://i.pravatar.cc/150?u='+(agent.user_id);

    // Create background image URL
    var bgImageUrl = 'https://source.unsplash.com/featured/300x' + (201 + agent.index) + '?' + agent.label;
    var websiteUrl = agent.agent_website.includes('http') ? agent.agent_website : 'http://' + agent.agent_website;

    // Create agent card
    html += '<div class="agent-card agent-card-subpage">';
    html += '<div style="background-image: url(\'' + bgImageUrl + '\');" class="agent-cover">';
    if (agent.pro) {
        html += '<span class="pro">PRO</span>';
    }
    html += '</div>';
    html += '<div class="agent-content">';
    html += '<img src="' + avatarUrl + '" alt="profile" class="agent-profile" />';
    html += '<h2>' + agent.agent_firstname + ' ' + agent.agent_lastname + '<span>' + agent.agent_exp + '</span></h2>';
    html += '<p>' + agent.agent_about + '</p>';
    // If the agent is hired, show the 'Unhire' state. Otherwise, show the 'Hire' state
    if (hiredAgents && hiredAgents.includes(agent.agent_id)) {
        html += '<a href="#" data-agent-id="' + agent.agent_id + '" class="agent-action hire-agent unhire-agent"><i class="fas fa-times"></i> Unhire</a>';
    } else {
        html += '<a href="#" data-agent-id="' + agent.agent_id + '" class="agent-action hire-agent">Hire</a>';
    }
    html += '<a target="_blank" href="' + websiteUrl + '" class="agent-action more-info" style="padding: 7px;color: #ffffff;font-size: 0.7em;text-transform: uppercase;margin: 10px 0;display: inline-block;opacity: 1;width: 47%;text-align: center;text-decoration: none;font-weight: bold;">Website</a>';
    html += '</div></div>';
});

// Update the agent grid
$('.agent-grid2').html(html);
}

function displayHiredAgents(hiredAgents) {
var html = '';

console.log(hiredAgents);
if (hiredAgents.length < 1) {
    $('.my-hired').slideUp(1000);
} else {
    $('.my-hired').slideDown(1000);
}

$.each(hiredAgents, function(index, agent) {
    var avatarUrl = 'https://i.pravatar.cc/150?u='+(agent.user_id);
    var bgImageUrl = 'https://source.unsplash.com/featured/300x' + (201 + agent.user_id);
    var websiteUrl = agent.agent_website.includes('http') ? agent.agent_website : 'http://' + agent.agent_website;

    html += '<div class="agent-card agent-card-subpage">';
    html += '<div style="background-image: url(\'' + bgImageUrl + '\');" class="agent-cover">';
    if (agent.pro) {
        html += '<span class="pro">PRO</span>';
    }
    html += '</div>';
    html += '<div class="agent-content">';
    html += '<img src="' + avatarUrl + '" alt="profile" class="agent-profile" />';
    html += '<h2>' + agent.agent_firstname + ' ' + agent.agent_lastname + '<span>' + agent.agent_exp + '</span></h2>';
    html += '<span>' + agent.agent_city + ', '+agent.agent_country+'<span>';
    html += '<p>' + agent.agent_about + '</p>';
    html += '<a href="#" data-agent-id="' + agent.agent_id + '" class="agent-action hire-agent unhire-agent"><i class="fas fa-times"></i> Unhire</a>';
    html += '<a target="_blank" href="' +  websiteUrl + '" class="agent-action more-info" style="padding: 7px;color: #ffffff;font-size: 0.7em;text-transform: uppercase;margin: 10px 0;display: inline-block;opacity: 1;width: 47%;text-align: center;text-decoration: none;font-weight: bold;">Website</a>';
    html += '</div></div>';
});

$('.agent-grid-hired').html(html);
}



$(document).ready(function() {
$.get('../getUserId.php', function(data) {
    if (data.error) {
    console.error(data.error);
    } else {
    var userId = data.user_id;
    var firstName = data.first_name;
    var userIdElement = '<span class="user-id" style="text-align: center; margin: 0 auto; width: 100%; font-size: xx-small; display: block; padding-bottom: 10px; color: lightslategrey;">User ID: ' + userId + '</span>';
    $(userIdElement).insertAfter('.footertext');
    var displayName = firstName ? firstName : 'User';
    $('.client-name').text(displayName);
    }
});

// Load all agents
loadAgents();
// Handle search input keyup event
$('.search-input').on('keyup', function() {
    var searchTerm = $(this).val();

    // Filter and display agents
    var filteredAgents = filterAgents(searchTerm);
    displayAgentsWithHire(filteredAgents);
});

$('#logoutButton').click(function() {
    $.get('../logout.php', function() {
        window.location.href = "login.html"; // redirect to the login page or homepage
    });
});

$('#delete-account-button').click(function() {
    if (confirm('Are you sure you want to delete your account? This cannot be undone.')) {
        $.post('../deleteUser.php', function(response) {
            if(response.status == 'success') {
                alert('Your account has been deleted.');
                window.location.href = 'index.html';
            } else {
                alert('An error occurred: ' + response.error);
            }
        });
    }
});
});

const modal = document.querySelector(".modal");
const overlay = document.querySelector(".overlay");
const openModalBtn = document.querySelector(".btn-open");
const closeModalBtn = document.querySelector(".btn-close");

// close modal function
const closeModal = function () {
  modal.classList.add("hidden");
  overlay.classList.add("hidden");
};

// close the modal when the close button and overlay is clicked
closeModalBtn.addEventListener("click", closeModal);
overlay.addEventListener("click", closeModal);

// close modal when the Esc key is pressed
document.addEventListener("keydown", function (e) {
  if (e.key === "Escape" && !modal.classList.contains("hidden")) {
    closeModal();
  }
});

// open modal function
const openModal = function () {
  modal.classList.remove("hidden");
  overlay.classList.remove("hidden");
};
// open modal event
openModalBtn.addEventListener("click", openModal);
