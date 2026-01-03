
export function profileSearch()
{
    const searchEnter = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const userId = searchEnter.dataset.user_id;

    searchEnter.addEventListener('input', () => {
    let search = searchEnter.value.trim();
    if(search.length >= 2)
    {
        searchResults.classList.toggle("active");
    }

    fetch("../decisionMaker.php?profile-search=" + search + "&user-id=" + userId)
    .then(res => res.json())
    .then(data => {
        searchResults.innerHTML = "";
        if(data.length === 0)
        {
            const div = document.createElement('div');
            div.innerHTML = "No results...";
            searchResults.appendChild(div);
            div.className = "search-no-result";
        }
        data.forEach(result => {
            const div = document.createElement('div');
            const divImg = document.createElement('div');
            const divInfo = document.createElement('div');
            const img = document.createElement('img');
            const h3 = document.createElement('h3');
            const p = document.createElement('p');
            const span = document.createElement('span');

            div.className = "search-result-container";
            divImg.className = "search-image-container";
            divInfo.className = "search-info-container";

          if(result['type'] === "community"){
            h3.innerHTML = "r/" + result['display_name'];
            p.innerHTML = result['info'];
            fetch("../decisionMaker.php?community-image=" + result['id'])
            .then(res => res.json())
            .then(image => {
                if(!image || !image.name){
                    img.src = "../images/reddit.png";
                }
                else{
                    img.src = "../images/community/" + image['name'];
                }
            });
            div.appendChild(divImg);
            divImg.appendChild(img);
            div.appendChild(divInfo);
            divInfo.appendChild(h3);
            divInfo.appendChild(p);
            searchResults.appendChild(div);

            div.addEventListener("click",()=>{
                window.location.href = "community.php?comm_id=" + result['id'];
            });
            }

          if(result['type'] === "post"){
            h3.innerHTML = "p/" + result['display_name'];
            p.innerHTML = result['info'];
            img.src = "../images/reddit.png";
            
            div.appendChild(divImg);
            divImg.appendChild(img);
            div.appendChild(divInfo);
            divInfo.appendChild(h3);
            divInfo.appendChild(p);
            searchResults.appendChild(div);

            div.addEventListener("click",()=>{
                window.location.href = "community.php?comm_id=" + result['picture'];
            });
            }
        });
        });
    });
}

export function postSearch()
{
    const searchEnter = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const commId = searchEnter.dataset.comm_id;

    searchEnter.addEventListener('input', () => {
        let search = searchEnter.value.trim();
        if(search.length >= 2)
        {
            searchResults.classList.toggle("active");
        }

        fetch("../decisionMaker.php?post-search=" + search + "&comm-id=" + commId)
        .then(res => res.json())
        .then(data => {
        searchResults.innerHTML = "";
        if(data.length === 0)
        {
            const div = document.createElement('div');
            div.innerHTML = "No results...";
            searchResults.appendChild(div);
            div.className = "search-no-result";
        }
        data.forEach(result => {
            const div = document.createElement('div');
            const divImg = document.createElement('div');
            const divInfo = document.createElement('div');
            const img = document.createElement('img');
            const h3 = document.createElement('h3');
            const p = document.createElement('p');
            const span = document.createElement('span');

            div.className = "search-result-container";
            divImg.className = "search-image-container";
            divInfo.className = "search-info-container";

            h3.innerHTML = "p/" + result['title'];
            if (result['text']) {
                p.innerHTML = result['text'];
            }
            img.src = "../images/reddit.png";
            
            div.appendChild(divImg);
            divImg.appendChild(img);
            div.appendChild(divInfo);
            divInfo.appendChild(h3);
            divInfo.appendChild(p);
            searchResults.appendChild(div);

            div.addEventListener("click",()=>{
                window.location.href = "community.php?comm_id=" + result['community_id'];
            });
        });
    });
    });
}

export function generalSearch()
{
    const searchEnter = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');

    searchEnter.addEventListener('input', () => {
        let search = searchEnter.value.trim();
        if(search.length >= 2)
        {
            searchResults.classList.toggle("active");
        }

        fetch("/Reddit/decisionMaker.php?general-search=" + search)
        .then(res => res.json())
        .then(data => {
            searchResults.innerHTML = "";
            data.forEach(result => {
                const div = document.createElement('div');
                const divImg = document.createElement('div');
                const divInfo = document.createElement('div');
                const img = document.createElement('img');
                const h3 = document.createElement('h3');
                const p = document.createElement('p');
                const span = document.createElement('span');

                div.className = "search-result-container";
                divImg.className = "search-image-container";
                divInfo.className = "search-info-container";

                if(result['type'] === "community"){
                    h3.innerHTML = "r/" + result['display_name'];
                    p.innerHTML = result['info'];
                    fetch("/Reddit/decisionMaker.php?community-image=" + result['id'])
                    .then(res => res.json())
                    .then(image => {
                        if(!image || !image.name){
                            img.src = "/Reddit/images/reddit.png";
                        }
                        else{
                            img.src = "/Reddit/images/community/" + image['name'];
                        }
                    });
                    div.appendChild(divImg);
                    divImg.appendChild(img);
                    div.appendChild(divInfo);
                    divInfo.appendChild(h3);
                    divInfo.appendChild(p);
                    searchResults.appendChild(div);

                    div.addEventListener("click",()=>{
                        window.location.href = "/Reddit/view/community.php?comm_id=" + result['id'];
                    });
                }

                if(result['type'] === "post"){
                    h3.innerHTML = "p/" + result['display_name'];
                    p.innerHTML = result['info'];
                    img.src = "/Reddit/images/reddit.png";
                    
                    div.appendChild(divImg);
                    divImg.appendChild(img);
                    div.appendChild(divInfo);
                    divInfo.appendChild(h3);
                    divInfo.appendChild(p);
                    searchResults.appendChild(div);

                    div.addEventListener("click",()=>{
                        window.location.href = "/Reddit/view/community.php?comm_id=" + result['picture'];
                    });
                }
                if(result['type'] === "user"){
                    h3.innerHTML = "u/" + result['display_name'];
                    img.src = "/Reddit/images/avatars/" + result['picture'] + ".webp";

                    div.appendChild(divImg);
                    divImg.appendChild(img);
                    div.appendChild(divInfo);
                    divInfo.appendChild(h3);
                    searchResults.appendChild(div);
                }
            });
        
        });
    });

}