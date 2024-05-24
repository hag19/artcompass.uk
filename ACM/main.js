document.addEventListener('DOMContentLoaded', (event) => {
  let images = JSON.parse(document.getElementById('imageData').textContent);
  let currentIndex = 0;
  document.getElementById('nextImgBtn').addEventListener('click', function() {
      currentIndex = (currentIndex + 1) % images.length;
      document.getElementById('displayedImg').src = images[currentIndex];
  });
});

async function like(id, like, name, column) {
  try {
      const result = await fetch("/ACM/blog/add_comment.php", {
          method: "POST",
          headers: {
              "Content-Type": "application/x-www-form-urlencoded",
          },
          body: `id=${id}&like=${like}&name=${name}&column=${column}`,
      });

      const response = await result.text();
      const likesElement = document.getElementById("likes");

      if (response.trim() === "success") {
          likesElement.innerHTML = parseInt(likesElement.innerHTML) + 1;
      } else if (response.trim() === "noice") { // Handle unlike
        if (parseInt(likesElement.innerHTML) > 0) {
          likesElement.innerHTML = parseInt(likesElement.innerHTML) - 1;
        }
      } else {
          console.error("Error:", response);
      }
  } catch (error) {
      console.error("Error:", error);
  }
}

async function search() {
  const searchInput = document.getElementById("search_input");
  const search = searchInput.value;

  if (!search) {
    const res = await fetch("search.php?name=" + search);
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
