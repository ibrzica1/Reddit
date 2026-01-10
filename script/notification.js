
export function notificationSeen()
{
    const containers = document.querySelectorAll(".single-nott");

    if(containers.length > 0)
    {
        containers.forEach((container) => {
            const nottId = container.dataset.id;
            const seen = container.dataset.seen;
            const type = container.dataset.type;
            const href = container.dataset.href;
            const notification = document.getElementById(`singleNot-${nottId}`);

            if(seen === "false"){
                notification.style.backgroundColor = "rgb(227, 227, 227)";
            }

            notification.addEventListener('click', async() => {

                await fetch('/Reddit/decisionMaker.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `change-seen-nott=${nottId}&type=${type}&href=${href}` 
                });

                switch (type) {
                    case "like-post":
                        window.location.href = `/Reddit/view/community.php?comm_id=${href}&nott_id=${nottId}`;
                        break;

                    case "like-comment":
                        window.location.href = `/Reddit/view/comment.php?post_id=${href}&nott_id=${nottId}`;
                        break;
                    case "comment":
                        window.location.href = `/Reddit/view/comment.php?post_id=${href}&nott_id=${nottId}`;
                        break;

                    case "post":
                        window.location.href = `/Reddit/view/community.php?comm_id=${href}&nott_id=${nottId}`;
                        break;
                }
            });
        })
    }
}