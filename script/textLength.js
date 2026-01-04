
export function checkTitleLength()
{
    const title = document.getElementById('titleId');
    const letters = document.querySelector(".letters");

    title.addEventListener('keydown', ()=>{
        let maxLetters = 300;
        let used = title.value.length;
        let remaining = maxLetters - used;
        letters.innerHTML = remaining;
    });
        
    title.addEventListener('input', () => {
        const maxLetters = 300;
        if (title.value.length > maxLetters) {
            title.value = title.value.slice(0, maxLetters);
        }
        letters.textContent = maxLetters - title.value.length;
    });
}

export function checkNameLength()
{
    const nameInput = document.getElementById("nameInput");
    const nameLetters = document.querySelector(".name-letters");
    const namePreview = document.querySelector('.prw-name-span');

    nameInput.addEventListener('keydown', ()=>{
        let maxLetters = 21;
        let used = nameInput.value.length;
        let remaining = maxLetters - used;
        nameLetters.innerHTML = remaining;
        namePreview.innerHTML = nameInput.value;
    });

    nameInput.addEventListener('input', ()=>{
        const maxLetters = 21;
        if (nameInput.value.length > maxLetters) {
            nameInput.value = nameInput.value.slice(0, maxLetters);
        }
        nameLetters.textContent = maxLetters - nameInput.value.length;
    });
}

export function checkDescriptionLength()
{
    const descriptionInput = document.getElementById("descriptionInput");
    const descriptionLetters = document.querySelector(".description-letters");
    const descriptionPreview = document.querySelector('.prw-description');

    descriptionInput.addEventListener('keydown', ()=>{
        let used = descriptionInput.value.length;
        descriptionLetters.innerHTML = used;
        descriptionPreview.textContent = descriptionInput.value;
    });

    descriptionInput.addEventListener('input', ()=>{
        const maxLetters = 500;
        if (descriptionInput.value.length > maxLetters) {
            descriptionInput.value = descriptionInput.value.slice(0, maxLetters);
        }
        descriptionLetters.textContent = maxLetters - descriptionInput.value.length;
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