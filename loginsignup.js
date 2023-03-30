function login() {

  const username = $("#loginU").val();
  const password = $("#loginP").val();

  // check there is input
  if (username == "" || password == "") {
    return;
  }

  const data = { username: username, password: password };

  fetch("login.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
  })
    .then((response) => response.json())
    .then((data) => {

      
      const str = JSON.stringify(data);
      let parsed = JSON.parse(str);
     
      let username = $("#loginU").val();
      // clear input fields
      $("#loginU").val("");
      $("#loginP").val("");

      // checking if there were errors in php
      if (!parsed.success) {
        $("#error").text(data.message);

      } else {
        //update html
        $("#loginSignup").css("display", "none");
        $("#signupBox").css("display", "");
        $("#logout").removeClass("d-none");
        $("#urldisplay").removeClass("d-none");
        $("#error").text("");

        // load urls
        loadLinks();
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

function signup() {
  const username = $("#signupU").val();
  const password = $("#signupP").val();

  //check there is input
  if (username == "" || password == "") { 
    return;
  }

  const data = { username: username, password: password };

  fetch("signup.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
  })
    .then((response) => response.json())
    .then((data) => {
      console.log("Success:", data);
      const str = JSON.stringify(data);
      let parsed = JSON.parse(str);

      //clear input
      $("#signupU").val("");
      $("#signupP").val("");
      if (!parsed.success) {
        $("#error").text("Username Already Exists");
      } else {
        $("#signupBox").css("display", "none");
        $("#login").removeClass("inline");
      }
      
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

function logout() {
  fetch("logout.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((response) => response.json())
    .then((data) => {
      console.log("Success:", data);

      //clear links
      $("#urlTable").html(`<table id="urlTable"><tr><th></th> <th>Short URL Extension</th> <th>Full URL</th> <th></th> <th></th></tr></table>`);

      // reset display
      $("#loginSignup").css("display", "");
      $("#urldisplay").addClass("d-none");
      $("#logout").addClass("d-none");
      $("#login").addClass("inline");



    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

function addURL() {
  
  //check fields fillded
  if ($("#longURLinput").val() == "") {
    $("#error").text("Fields Not Completed");
    return;
  }
  if ($("#shortName").val() == "") {
    $("#error").text("Fields Not Completed");
    return;
  }

  //check url validity
  if (!checkURL($("#longURLinput").val())) {
    $("#error").text("Not Valid URL");
    return;
  }

  
  const data = {
    long: $("#longURLinput").val(),
    short: $("#shortURLinput").val()
  }

  console.log(data);

  fetch("addURL.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },

    body: JSON.stringify(data),
  })
    .then((response) => response.json())
    .then((data) => {
      console.log("Success:", data);


      //clear input fields
      $("#longURLinput").val("");
      $("#shortURLinput").val("");


      // redisplay urls
      loadLinks();


    })
    .catch((error) => {
      console.error("Error:", error);
    });

}

function loadLinks() {

  //clear links
  $("#urlTable").html(`<table id="urlTable"><tr> <th></th> <th>Short URL Extension</th> <th>Full URL</th> <th></th> <th></th></tr></table>`);

  fetch("loadLinks.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    }

  })
    .then((response) => response.json())
    .then((data) => {
     
    
      let currHtml = $("#urlTable").children().eq(0).html();
      
      //urls from data base
      urls = data.urls


      //iterate through database urls and assign vars
      for (let i = 0; i < urls.length; i++) {
        const short = urls[i].short;
        const link = "http://ec2-18-188-67-75.us-east-2.compute.amazonaws.com/~lev/tamid/sendTo.php?loc=" + short;
        const full = urls[i].full;
        const id = urls[i].id;

        // add to the html
        currHtml = currHtml + `<tr id="${id}"> <td> <input type="submit" value="copy full" class="copy"/> </td><td> <a href="${link}">${short}</a></td> <td> ${full} </td> <td> <input type="submit" value="delete" class="delete"/> </td> <td> <input type="submit" value="edit" class="edit"/> </td> </tr>`
        $("#urlTable").html(currHtml);

      }
      //add buttons
      $(".delete").on("click", deleteURL);
      $(".copy").on("click", copyURL);
      $(".edit").on("click", showPopup);
      

    })
    .catch((error) => {
      console.error("Error:", error);
    });
}


function deleteURL() {
  const urlid = $(this).parent().parent().attr("id");

  const data = { urlid: urlid }

  console.log(urlid);

  fetch("deleteURL.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data)

  })
    .then((response) => response.json())
    .then((data) => {
      console.log("Success:", data);
      // redisplay after delete
      loadLinks();
    })
    .catch((error) => {
      console.error("Error:", error);
    });


}

function showPopup() {
  $("#popup").removeClass("d-none");

  $("#enter-btn").on("click", (event) => {
    //pulls up for specific url clicked
    editURL($(this));
  });

}

function closePopup() {
  $("#changeLong").val("");
  $("#changeShort").val("");
  $("#editerror").text("");
  $("#popup").addClass("d-none");

}


function editURL(target) {
  $("#editerror").text("");

  const urlid = $(target).parent().parent().attr("id");
  const full = $("#changeLong").val();
  const short = $("#changeShort").val();

  //check fields filled
  if (short == "" || full == "") {
    $("#editerror").text("Fields not completed");
    return;
  }
  //check url validity
  console.log($("#changeLong").val());
  if (!checkURL($("#changeLong").val())) {
    $("#editerror").text("Not Valid URL");
    return;
  }

  const data = {
    urlid: urlid,
    short: short,
    full: full

  }


  fetch("editURL.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data)

  })
    .then((response) => response.json())
    .then((data) => {
      console.log("Success:", data);
      // update html
      loadLinks();
      closePopup();
    })
    .catch((error) => {
      console.error("Error:", error);
    });

}

function checkURL(str) {
  try {
    return Boolean(new URL(str));
  }
  catch (e) {
    return false;
  }
}

function copyURL() {
  //copies the full link to clipboard
  const short = $(this).parent().parent().children().eq(1).children().eq(0);
  var copyText = "http://ec2-18-188-67-75.us-east-2.compute.amazonaws.com/~lev/tamid/sendTo.php?loc="  + short.text();
  var tempInput = $("<input>");
  $("body").append(tempInput);
  tempInput.val(copyText).select();
  document.execCommand("copy");
  tempInput.remove();
  
  
  showCopied('Copied to clipboard!', 1500);
}


function showCopied(message, duration) {
  //shows notification for user that it was copied
  const popup = document.createElement('div');
  popup.style.position = 'fixed';
  popup.style.bottom = '20px';
  popup.style.left = '50%';
  popup.style.transform = 'translateX(-50%)';
  popup.style.padding = '10px';
  popup.style.backgroundColor = '#333';
  popup.style.color = '#fff';
  popup.style.borderRadius = '5px';
  popup.textContent = message;
  document.body.appendChild(popup);

  setTimeout(() => {
    popup.parentNode.removeChild(popup);
  }, duration);
}

// EVENT LISTENERS 
$("#loginbutton").on("click", login);
$("#signupbutton").on("click", signup);
$("#logoutButton").on("click", logout);
$("#addURL").on("click", addURL);
$("#close-btn").on("click", closePopup);

