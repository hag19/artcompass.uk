async function search_users() {
  const searchInput = document.getElementById("search_input");
  const search = searchInput.value;

  if (!search) {
    const res = await fetch("search_users.php");
    const str = await res.text();
    const figure = document.getElementById("html_result");
    figure.innerHTML = str;
  } else if (search !== "") {
    const res = await fetch("search_users.php?name=" + search);
    console.log("searching !");
    const str = await res.text();
    const figure = document.getElementById("html_result");
    figure.innerHTML = str;
  }
}

async function easterEgg() {
  num = document.getElementsByClassName("increment")[0];
  a = num.innerHTML;
  b = Number(a) + 1;
  num.innerHTML = b;
  if(b >= 10) {
    image = document.getElementsByClassName("smart")[0];
    image.src="../img/smarthierry.png";
    nuh_huh = document.getElementsByClassName("nuhHuh")[0];
    nuh_huh.innerHTML="Photographies classifiées. Ne pas divulguer.";
    classified = document.getElementsByClassName("classified")[0];
    classified.style.display="initial";
  }
}