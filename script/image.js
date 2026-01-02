let index = 0

export function stageImages()
{
    const containers = document.querySelectorAll('.image');
    if(containers.length === 0){
        return;
    }
    containers.forEach((container) => {
        const id = container.dataset.id;
        const rightArrow = document.getElementById(`rightArrow-${id}`);
        const images = JSON.parse(container.dataset.images);
        const count = images.length;
        if(count < 2){
            return;
        }
        else{
            rightArrow.style.display = "flex";
        }
    })

}

export function imageScroll()
{
    document.addEventListener('click',(e)=>{
       const btn = e.target.closest('.left-arrow, .right-arrow');
       if(!btn){
        return;
       }
       const container = btn.closest('.image');
       const id = container.dataset.id;
       const leftArrow = document.getElementById(`leftArrow-${id}`);
       const rightArrow = document.getElementById(`rightArrow-${id}`);
       const action = btn.classList.contains('left-arrow')? 'left' : 'right';
       const images = JSON.parse(container.dataset.images);
       const display = document.getElementById(`imageDisplay-${id}`);
       const imageCount = images.length;
       let input = document.getElementById(`index-${id}`);
       let index = parseInt(input.value);

       if(action === "right" && index < imageCount){
            index++;
            display.src=`/Reddit/images/uploaded/${images[index].name}`;
            input.value = index;
       }
       if(action === "left" && index > 0){
            index--;
            display.src=`/Reddit/images/uploaded/${images[index].name}`;
            input.value = index;
       }

       if (index > 0) {
            leftArrow.style.display = "flex";
        } else {
            leftArrow.style.display = "none";
        }

        if (index < imageCount - 1) {
            rightArrow.style.display = "flex";
        } else {
            rightArrow.style.display = "none";
        }
       
    })
}