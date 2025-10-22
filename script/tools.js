
export function toggleMenu()
{
    const menu = document.getElementById("userMenu");

    if(menu.style.display == "none")
    {
        menu.style.display = "block";
    }
    else
    {
        menu.style.display = "none";
    }

}