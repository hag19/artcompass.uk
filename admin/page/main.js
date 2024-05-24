document.getElementById("time").addEventListener("change", check);
async function makeGuide(id) {
    alert("You are about to promote this user to tour guide. Click 'OK' to proceed.");
    await fetch('users.php?message=g_' + id);
}
async function makeAdmin(id) {
    alert("You are about to promote this user to website administrator. Click 'OK' to proceed.");
    await fetch('users.php?message=a_' + id);
}

async function banUser(id) {
    alert("You are about to ban this user from the website. This will prevent them from seeing any account-only content. Click 'OK' to proceed.");
    await fetch('users.php?message=b_' + id);
}

async function deleteAccount(id) {
    alert("You are about to remove this user from the database. This action is irreversible. Click 'OK' to proceed.");
    await fetch('users.php?message=d_' + id);
}


async function demoteGuide(id) {
    alert("You are about to demote this guide, making them a normal user in the process. Click 'OK' to proceed.");
    await fetch('users.php?message=dg_' + id);
}
async function demoteAdmin(id) {
    alert("You are about to demote this administrator, preventing them from accessing the admin page and moderating the website. Click 'OK' to proceed.");
    await fetch('users.php?message=da_' + id);
}
async function unbanUser(id) {
    alert("Are you sure you wish to unban this user from the website?");
    await fetch('users.php?message=ub_' + id);
}

async function check() {
    const date =  document.getElementById("date").value;
    const time = document.getElementById("time").value;
    try {
        const result = await fetch('guid.php', {
            method: 'POST', 
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `date=${date}&time=${time}`
        });
        const names = await result.json(); // Parse JSON response
        if (names == 'no guide available'){
            alert('No guide available on this date');
            return;
        }
        const div = document.getElementById('guide');
        let html = "";
        for(let i = 0; i < names.length; i++) {
            html += '<input type="radio" name="guide" value="' + names[i].username + '">' + names[i].username + '<br>'; // Added the name of the guide next to the radio button
        }
        div.innerHTML = html;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

function loadLog() {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        document.getElementById("log").innerHTML = this.responseText;
    }
    xhttp.open("GET", "../../accuel/connex/log.txt", true);
    xhttp.send();
}