document.addEventListener("DOMContentLoaded", function() {
  // Select all elements with the class 'pro'
  let proElements = document.querySelectorAll('.pro');
  // Loop over all elements
  for (let i = 0; i < proElements.length; i++) {
    // Generate a random number between 0 and 1
    let randomNumber = Math.random();
    // If the random number is less than 0.5, hide the element
    if (randomNumber < 0.5) {
      proElements[i].style.display = 'none';
    } else {
      // Otherwise, show the element
      proElements[i].style.display = 'block';
    }
  }
});

//get element by class "hire-agent"

  document.getElementById('hire-agent').addEventListener('click', function(event)  {
  event.preventDefault();
  document.getElementById('message').classList.remove('hidden');

  confetti({
      particleCount: 100,
      spread: 70,
      origin: { y: 0.6 }
  });
});
