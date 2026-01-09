
export function manageLikes()
{
    document.addEventListener('click',(e)=>{
        const btn = e.target.closest('.up-btn, .down-btn');
        if(!btn){
            return;
        }
        const container = btn.closest('.like-btn');
        const id = container.dataset.id;
        const type = container.dataset.type;
        const action = btn.classList.contains('up-btn')?'like':'dislike';
        const count = document.getElementById(`count-${type}-${id}`);
        const upBtn = document.getElementById(`up-${type}-${id}`);
        const downBtn = document.getElementById(`down-${type}-${id}`);
        
        fetch('/Reddit/decisionMaker.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `${type}-${action}=${id}` 
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if(data.status === "success") {
                let newCount = data.new_count < 0 ? 0 : data.new_count;
                count.textContent = newCount;
                const status = data.like_status; 
            if (status === "liked") {
                container.style.backgroundColor = "rgba(223, 120, 120, 1)";
                upBtn.style.backgroundColor = "rgba(220, 55, 55, 1)";
                downBtn.style.backgroundColor = "rgba(223, 120, 120, 1)";
            } else if (status === "disliked") {
                container.style.backgroundColor = "rgba(112, 148, 220, 1)";
                upBtn.style.backgroundColor = "rgba(112, 148, 220, 1)";
                downBtn.style.backgroundColor = "rgba(66, 117, 220, 1)";
            } else { 
                container.style.backgroundColor = "#dee8fe";
                upBtn.style.backgroundColor = "#dee8fe";
                downBtn.style.backgroundColor = "#dee8fe";
            }
        }})
        .catch(error => console.error('Network error:', error));
    });
}

export function likeStatus()
{
    const containers = document.querySelectorAll('.like-btn');
    containers.forEach((container) => {
        const id = container.dataset.id;
        const type = container.dataset.type;
        const status = container.dataset.status;
        const upBtn = document.getElementById(`up-${type}-${id}`);
        const downBtn = document.getElementById(`down-${type}-${id}`);
        console.log(status);
        if(status === "liked")
        {
            container.style.backgroundColor = "rgba(223, 120, 120, 1)";
            upBtn.style.backgroundColor = "rgba(220, 55, 55, 1)";
            downBtn.style.backgroundColor = "rgba(223, 120, 120, 1)";
        }
        if(status === "disliked")
        {
            container.style.backgroundColor = "rgba(112, 148, 220, 1)";
            upBtn.style.backgroundColor = "rgba(112, 148, 220, 1)";
            downBtn.style.backgroundColor = "rgba(66, 117, 220, 1)";
        }
    })
    
}