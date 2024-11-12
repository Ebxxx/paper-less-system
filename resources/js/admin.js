const sidebar = document.getElementById('sidebar');
const mainContent = document.getElementById('main-content');
const toggleBtn = document.getElementById('toggle-sidebar');

toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
    mainContent.classList.toggle('expanded');
});

// Handle responsive behavior
function checkWindowSize() {
    if (window.innerWidth <= 768) {
        sidebar.classList.add('collapsed');
        mainContent.classList.add('expanded');
    } else {
        sidebar.classList.remove('collapsed');
        mainContent.classList.remove('expanded');
    }
}

// dropdown js
window.addEventListener('resize', checkWindowSize);
checkWindowSize(); // Check on initial load

function toggleDropdown() {
    var menu = document.getElementById("dropdown-menu");
    menu.style.display = (menu.style.display === "block") ? "none" : "block";
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('.dropdown-toggle')) {
        var dropdowns = document.getElementsByClassName("dropdown-menu");
        for (var i = 0; i < dropdowns.length; i++) {
            dropdowns[i].style.display = "none";
        }
    }
}

