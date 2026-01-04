
export function loadPosts()
{
    const container = document.querySelector(".posts-grid");
    const count = parseInt(container.dataset.count);
    const limit = parseInt(container.dataset.limit);
    console.log(count);
    console.log(limit);

    window.addEventListener('DOMContentLoaded', () => {
        const savedScrollPos = localStorage.getItem('scrollPosition');
        if (savedScrollPos) {
            window.scrollTo(0, parseInt(savedScrollPos));
            localStorage.removeItem('scrollPosition');
        }
    });

    if(count > limit ) {
    window.addEventListener('scroll', () => {
        const scrollHeight = document.documentElement.scrollHeight;
        const scrollPos = window.innerHeight + window.scrollY;

        if(scrollPos >= scrollHeight - 50) {
            const urlParams = new URLSearchParams(window.location.search);
            let currentLimit = parseInt(urlParams.get('limit')) || 5;
            let newLimit = currentLimit + 5;

            localStorage.setItem('scrollPosition', window.scrollY);
            window.location.href = `index.php?limit=${newLimit}`;
        }
    });
    };
}

export function toggleReply()
{
    const replys = document.querySelectorAll(".reply-form");

    replys.forEach((reply)=>{
        const commentId = reply.dataset.id;
        const commReplyBtn = document.getElementById(`commentReplyBtn-${commentId}`);
        const replyText =document.getElementById(`replyText-${commentId}`);
        const replyForm = document.getElementById(`replyForm-${commentId}`);
        const replyCancel = document.getElementById(`replyCancel-${commentId}`);

        commReplyBtn.addEventListener('click', () => {
            replyForm.style.display = 'flex';
            replyText.focus();
        });
        replyCancel.addEventListener('click', () => {
            replyForm.style.display = 'none';
        });
    })

    
}

export function deleteCommunity()
{
    const deleteBtn = document.querySelector('.delete-container');

    deleteBtn.addEventListener('click',()=>{
        if(confirm("Are you sure you want do delete this community"))
        {
            deleteBtn.disabled = false;
        }
    });
}

export function toggleNotification()
{
    const notificationNum = document.querySelector('.notification-number');
    const notificationDisplay = document.querySelector(".notification-grid");

    console.log("loaded");
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
    console.log("menu toggled");
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

export function togglePostOptions()
{
    const textOption = document.querySelector('.text-option');
    const imageOption = document.querySelector('.image-option');
    const textContainer = document.querySelector('.text-container');
    const imageContainer = document.querySelector('.image-container');

    textOption.classList.add('active');
    imageContainer.style.display = 'none';

    textOption.addEventListener('click', () => {
    textOption.classList.add('active');
    imageOption.classList.remove('active');
    textContainer.style.display = 'block';
    imageContainer.style.display = 'none';
    });

    imageOption.addEventListener('click', () => {
    imageOption.classList.add('active');
    textOption.classList.remove('active');
    textContainer.style.display = 'none';
    imageContainer.style.display = 'block';
    });
}

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