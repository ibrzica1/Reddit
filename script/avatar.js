
export function changeAvatar()
{

    const formInput = document.querySelector(".form-input");
    const avatarOptions = document.querySelectorAll(".image-wrapper");
    const avatarSelected = document.querySelector(".selected-avatar");

    avatarOptions.forEach(option => {
        const avatarColor = option.getAttribute('data-target');

        option.addEventListener('click',() => {
            avatarSelected.src = `../images/avatars/${avatarColor}.webp`;
            formInput.value = avatarColor;
            changeBanner(avatarColor);
        });
    });
}

export function changeBanner(color)
{
  const banner = document.querySelector(".banner");

  switch(color)
  {
    case "blue":
    {
        banner.style.backgroundColor = '#6b91c4ff';
        break;
    }
    case "green":
    {
        banner.style.backgroundColor = 'rgba(123, 221, 78, 1)';
        break;
    }
    case "greenBlue":
    {
        banner.style.backgroundColor = 'rgba(47, 219, 176, 1)';
        break;
    }
    case "lightBlue":
    {
        banner.style.backgroundColor = 'rgba(52, 215, 224, 1)';
        break;
    }
    case "orange":
    {
        banner.style.backgroundColor = '#e4a956ff';
        break;
    }
    case "pink":
    {
        banner.style.backgroundColor = 'rgb(218, 125, 141)';
        break;
    }
    case "purple":
    {
        banner.style.backgroundColor = 'rgba(194, 141, 209, 1)';
        break;
    }
    case "yellow":
    {
        banner.style.backgroundColor = 'rgba(232, 195, 7, 1)';
        break;
    }
  }
}

export function checkboxesAvatar()
{
    const images = document.querySelectorAll(".image-wrapper");
    let checkboxes = [];

    images.forEach(image => {
        const avatarColor = image.getAttribute('data-target');
        const avatarCheckbox = document.getElementById(avatarColor);

        checkboxes.push(avatarCheckbox);
    });

    images.forEach(image => {
        image.addEventListener('click', ()=>{
            const avatarColor = image.getAttribute('data-target');
            const avatarCheckbox = document.getElementById(avatarColor);

            if(avatarCheckbox.checked)
            {
              checkboxes.forEach(cb => cb.checked = false);
            }
            else
            {
              checkboxes.forEach(cb => cb.checked = false);
              avatarCheckbox.checked = true;
            }
        });
    })
}