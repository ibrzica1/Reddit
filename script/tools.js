document.addEventListener("click", function (event) {
    const menu = document.getElementById("userMenu");
    const userInfo = document.getElementById("userInfo");

    if (!menu.contains(event.target) && !userInfo.contains(event.target)) {
        menu.classList.remove("active");
    }
});

export function toggleMenu()
{
    const menu = document.getElementById("userMenu");
    menu.classList.toggle("active");
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