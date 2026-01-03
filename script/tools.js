document.addEventListener("click", function (event) {
    const menu = document.getElementById("userMenu");
    const userInfo = document.getElementById("userInfo");
    const notificationDisplay = document.querySelector(".notification-grid");
    const bellIcon = document.querySelector('.notifications-container');
    const searchResults = document.getElementById('searchResults');
    const searchEnter = document.getElementById('searchInput');

    if(!notificationDisplay.contains(event.target) && !bellIcon.contains(event.target)){
        notificationDisplay.classList.remove("active");
    }
    
    if (!menu.contains(event.target) && !userInfo.contains(event.target)) {
        menu.classList.remove("active");
    }

    if (!searchResults.contains(event.target) && !searchEnter.contains(event.target)) {
        searchResults.classList.remove("active");
    }
});

export function toggleNotification()
{
    const notificationNum = document.querySelector('.notification-number');
    const notificationDisplay = document.querySelector(".notification-grid");
    notificationDisplay.classList.toggle("active");
    notificationNum.style.display = "none";
}

export function toggleMenu()
{
    const menu = document.getElementById("userMenu");
    menu.classList.toggle("active");
}

