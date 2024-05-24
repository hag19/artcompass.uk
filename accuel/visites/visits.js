document.getElementById("N_places").addEventListener("change", total);
document.getElementById("date").addEventListener("change", check);
document.getElementById("transport").addEventListener("change", price);
document.getEmementById("filter").addEventListener("change", filter);
document.getElementById("transport").addEventListener("change", price);
async function filter() {
  const filter = document.getElementById("filter").value;
  const result = await fetch("search.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `filter=${filter}`,
  });
  const response = await result.text();
  const div = document.getElementById("html_result");
  div.innerHTML = response;
}
async  function price() {
  const transport = document.getElementById("transport").value;
  const result = await fetch("price.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `transport=${transport}`,
  });
  const response = await result.text();
  const div = document.getElementById("price_transfer");
  div.innerHTML = response;
}
function createCookie(name, value, timeInSeconds) {
  const date = new Date();
  date.setTime(date.getTime() + timeInSeconds * 1000);
  const expires = "; expires=" + date.toGMTString();
  document.cookie = name + "=" + value + expires + "; path=/";
  console.log(document.cookie);
  console.log(name + "=" + value + expires + "; path=/");
}

async function check() {
  const date = document.getElementById("date").value;
  const name = document.getElementById("name").value;
  try {
    const result = await fetch("order.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `date=${date}&name=${name}`,
    });
    const time = await result.json();
    if (time.length == 0) {
      alert("No visits available on this date");
      return;
    }
    let html = "";
    for (let i = 0; i < time.length; i++) {
      html +=
        '<input type="radio" name="time" value="' +
        time[i].id +
        '"> ' +
        time[i].time +
        "</input>";
      html += "<div class='tourPlaces'>Places available: " + time[i].places + "</div>";
      const places = document.getElementById("N_places");
            places.max = time[i].places;
    }
    const div = document.getElementById("time"); 
    
    div.innerHTML = html;
  } catch (error) {
    console.error("Error fetching data:", error);
  }
}
async function del(i) {
  const result = await fetch("delete.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `i=${i}`,
  });
  const response = await result.text();
  if (response == "success") {
    alert("Order deleted successfully");
  }
}
async function total() {
  const priceText = document.getElementById("price").innerHTML;
  const price = parseFloat(priceText); // Parse price as a float

  const N_placesText = document.getElementById("N_places").value;
  const N_places = parseInt(N_placesText); // Parse N_places as an integer

  if (!isNaN(price) && !isNaN(N_places)) {
    // Check if both price and N_places are valid numbers
    let total = price * N_places;
    const div = document.getElementById("total");
    div.innerHTML = total += "€";
    console.log(total);
  } else {
    console.error("Invalid price or number of places"); // Log an error if either price or N_places is not a valid number
  }
}
function darkMode() {
  if (
    document.getElementsByClassName("indexBlueDark")[0].style.backgroundColor !=
    "rgb(54, 65, 77)"
  ) {
    console.log("Dark mode on");
    createCookie("darkMode", 1, 604800);
    document.body.style.backgroundColor = "#373737";
    const dark1 = document.getElementsByClassName("indexBlueDark");
    for (let i = 0; i < dark1.length; ++i) {
      dark1[i].style.backgroundColor = "#36414d";
    }
    const dark2 = document.getElementsByClassName("indexBlueDarker");
    for (let i = 0; i < dark2.length; ++i) {
      dark2[i].style.backgroundColor = "#252c38";
    }
    const dark3 = document.getElementsByClassName("indexBlackFont");
    for (let i = 0; i < dark3.length; ++i) {
      dark3[i].style.color = "white";
    }
    const dark4 = document.getElementsByClassName("noBoxShadow");
    for (let i = 0; i < dark4.length; ++i) {
      dark4[i].style.boxShadow = "0px 10px 10px #1c1c1c";
    }
    const dark5 = document.getElementsByClassName("odd");
    for (let i = 0; i < dark5.length; ++i) {
      dark5[i].style.backgroundColor = "#333333";
    }
    const dark6 = document.getElementsByClassName("even");
    for (let i = 0; i < dark6.length; ++i) {
      dark6[i].style.backgroundColor = "#303030";
    }
    const dark7 = document.getElementsByClassName("indexWhiteDark");
    for (let i = 0; i < dark7.length; ++i) {
      dark7[i].style.backgroundColor = "#303030";
    }
  } else {
    console.log("Dark mode off");
    document.body.style.backgroundColor = "white";
    const dark1 = document.getElementsByClassName("indexBlueDark");
    for (let i = 0; i < dark1.length; ++i) {
      dark1[i].style.backgroundColor = "#74949F";
    }
    const dark2 = document.getElementsByClassName("indexBlueDarker");
    for (let i = 0; i < dark2.length; ++i) {
      dark2[i].style.backgroundColor = "#526e7f";
    }
    const dark3 = document.getElementsByClassName("indexBlackFont");
    for (let i = 0; i < dark3.length; ++i) {
      dark3[i].style.color = "black";
    }
    const dark4 = document.getElementsByClassName("noBoxShadow");
    for (let i = 0; i < dark4.length; ++i) {
      dark4[i].style.boxShadow = "0px 10px 10px #a1a1a1";
    }
    const dark5 = document.getElementsByClassName("odd");
    for (let i = 0; i < dark5.length; ++i) {
      dark5[i].style.backgroundColor = "#F6F6F6";
    }
    const dark6 = document.getElementsByClassName("even");
    for (let i = 0; i < dark6.length; ++i) {
      dark6[i].style.backgroundColor = "#EDEDED";
    }
    const dark7 = document.getElementsByClassName("indexWhiteDark");
    for (let i = 0; i < dark7.length; ++i) {
      dark7[i].style.backgroundColor = "white";
    }
  }
}

async function search() {
  const searchInput = document.getElementById("search_input");
  const search = searchInput.value;

  if (!search) {
    const res = await fetch("search.php");
    const str = await res.text();
    const figure = document.getElementById("html_result");
    figure.innerHTML = str;
  } else if (search !== "") {
    const res = await fetch("search.php?name=" + search);
    console.log("searching !");
    const str = await res.text();
    const figure = document.getElementById("html_result");
    figure.innerHTML = str;
  }
}
