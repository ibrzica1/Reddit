let index = 0

export function imageDisplay(id)
{
    const display = document.getElementById(`imageDisplay-${id}`);
    display.src=`../images/uploaded/${postImages[currentImgIndex].name}`;

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
       const action = btn.classList.contains('left-arrow')? 'left' : 'right';
       const images = JSON.parse(container.dataset.images);
       const display = document.getElementById(`imageDisplay-${id}`);
       
       display.src=`../images/uploaded/${images[index].name}`;
    })
}