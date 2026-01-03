

document.addEventListener("click", function (event) {
    const menu = document.getElementById("userMenu");
    const userInfo = document.getElementById("userInfo");
    const notificationDisplay = document.querySelector(".notification-grid");
    const bellIcon = document.querySelector('.notifications-container');
    const searchResults = document.getElementById('searchResults');
    const searchEnter = document.getElementById('searchInput');

    if (!notificationDisplay || !bellIcon) return;

    if (
        !notificationDisplay.contains(event.target) &&
        !bellIcon.contains(event.target)
    ) {
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

    if (!notificationDisplay) return;

    notificationDisplay.classList.toggle("active");

    if (notificationNum) {
        notificationNum.style.display = "none";
    }
}

export function toggleMenu()
{
    const menu = document.getElementById("userMenu");
    menu.classList.toggle("active");
}

export function toggleEditForms()
{
    const editBtn = document.querySelectorAll(".edit-btn");

    editBtn.forEach(button => {
      button.addEventListener('click', ()=>{
        const targetFormId = button.getAttribute('data-target');
        const targetForm = document.getElementById(targetFormId);

        if (targetForm) {
                if (targetForm.style.display === "none" || targetForm.style.display === "") {
                    targetForm.style.display = "flex"; 
                    button.textContent = "Cancel"; 
                } else {
                    targetForm.style.display = "none"; 
                    button.textContent = "Edit";
                }
            }
        });
    });
}

export function checkBioLength()
{
    const bio = document.getElementById("bioId");
    const letters = document.querySelector(".letters");

    bio.addEventListener('keydown', ()=>{
        let maxLetters = 235;
        let used = bio.value.length;
        let remaining = maxLetters - used;
        letters.innerHTML = remaining;
    });
    
   bio.addEventListener('input', () => {
        const maxLetters = 235;
        if (bio.value.length > maxLetters) {
            bio.value = bio.value.slice(0, maxLetters);
        }
        letters.textContent = maxLetters - bio.value.length;
    });
}