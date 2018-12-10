/**
 * JS related to the style on all pages
 */
$(document).ready(function()
{
    $(".sidebar-toggle").on("click", function()
    {
        $(".sidebar").toggleClass("toggled");
        $("#fixed-footer")
            .removeClass("#fixed-footer")
            .addClass("footer");
    });

    var active = $(".sidebar .active");

    if (active.length && active.parent(".collapse").length)
    {
        var parent = active.parent(".collapse");

        parent.prev("a").attr("aria-expanded", true);
        parent.addClass("show");
    }
});

/**
 * Focus username input after opening login modal
 */
$('#myModalLogin').on('shown.bs.modal', function()
{
    $('#username').focus();
})

/**
 * Focus name input after opening register modal
 */
$('#myModalRegister').on('shown.bs.modal', function()
{
    $('#name').focus();
})

/**
 * Error handling
 */
$(window).on("load", function()
{
    $("#myModalError").modal("show");
});

/**
 * Functions for GET and POST AJAX requests
 */
function ajaxCallGet(url, handler)
{
    let xmlhttp = new XMLHttpRequest();

    xmlhttp.open("GET", url, true);
    xmlhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xmlhttp.onload = handler;
    xmlhttp.send();
}

function ajaxCallGet2(url, params, handler)
{
    $.ajax(
    {
        url: url,
        type: 'GET',
        data: params,
        success: handler
    });
}

function ajaxCallPost(url, params, handler)
{
    let token = document.querySelector("#csrfToken").content;
    params._token = token;
    $.ajax(
    {
        url: url,
        type: 'POST',
        data: params,
        success: handler
    });
}

function encodeForAjax(data)
{
    return Object.keys(data).map(function(k)
    {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
}

let album = document.querySelector('#proposalsAlbum');
let showmorebutton = document.querySelector('#showmorebutton');
let i = 0;
let proposals = [];
let teams = [];
if (showmorebutton != null)
{
    showmorebutton.addEventListener('click', function(event)
    {
        switch (window.location.pathname)
        {
            case "/myproposals":
                album.innerHTML += myproposalsAlbum();
                break;
            case "/allproposals":
                album.innerHTML += allproposalsAlbum();
                break;
            case "/history":
                album.innerHTML += historyAlbum();
                break;
            case "/proposalsIWon":
                album.innerHTML += proposalsIWonAlbum();
                break;
            case "/teams":
                album.innerHTML += teamsAlbum();
                break;
            default:
                album.innerHTML += makeAlbum();
        }
        console.log("what");
        event.preventDefault();
    });
}

/**
 * JS for the lists
 */
if (window.location.pathname === "/home")
{
    ajaxCallGet("api/search?proposalStatus=approved", proposalAlbumHandler);
}

if (window.location.pathname === "/myproposals")
{
    ajaxCallGet("api/search?proposalsOfUser=true", myproposalsAlbumHandler);
}

if (window.location.pathname === "/allproposals")
{
    ajaxCallGet("api/search?proposalsAvailableToUser=true", allproposalsAlbumHandler);
}

if (window.location.pathname === "/proposals_im_in")
{
    ajaxCallGet("api/search?userBidOn=true", proposalAlbumHandler);
}

if (window.location.pathname === "/proposalsIWon")
{
    ajaxCallGet("api/search?userBidWinner=true", proposalsIWonAlbumHandler);
}

if (window.location.pathname === "/history")
{
    ajaxCallGet("api/search?history=true", historyAlbumHandler);
}
if (window.location.pathname === "/teams")
{
    ajaxCallGet("api/search?teamsOfUser=true", teamsAlbumHandler);
}

function teamsAlbumHandler()
{
    teams = JSON.parse(this.responseText);
    album.innerHTML += teamsAlbum();
}

function teamsAlbum()
{
    console.log(teams);
    let htmlproposal = `<div class="row">`;
    let max = i + 12;

    if (teams.length == 0){
        htmlproposal += `
            <div class="col-md-12 proposalItem">
                   <p class="text-center font-weight-bold" style="font-size: larger"> It looks like you have no teams yet. Create a team!</p>`;
    }

    for (i; i < max && i < teams.length; i++) {
        let element = teams[i];
        if (i % 4 === 0 && i !== 0)
        {
            htmlproposal += `</div><div class="row">`;
        }
        htmlproposal += `<div class="col-md-3 proposalItem"  data-id="${element.id}">
        <a href="team/${element.id}" class="list-group-item-action">
            <div class="card mb-4 box-shadow">
                <div class="card-body">
                    <p class="card-text text-center hidden-p-sm-down font-weight-bold" style="font-size: larger"> ${element.teamname} </p>
                </div>
            </div>
        </a>
    </div>`;
    };

    htmlproposal += `</div>`;
    if (i == teams.length)
        showmorebutton.parentNode.removeChild(showmorebutton);
    return htmlproposal;
}

function historyAlbumHandler()
{
    console.log(this.responseText);
    proposals = JSON.parse(this.responseText);
    album.innerHTML = historyAlbum();
}

function historyAlbum()
{
    console.log(proposals);
    let htmlproposal = `<div class="row">`;
    let max = i + 12;

    for (i; i < max && i < proposals.length; i++)
    {
        let element = proposals[i];
        if (i % 4 === 0 && i !== 0)
        {
            htmlproposal += `</div><div class="row">`;
        }
        htmlproposal += `<div class="col-md-3 proposalItem"  data-id="${element.id}">
        <a href="proposal/${element.id}" class="list-group-item-action">
            <div class="card mb-4 box-shadow">
                <div class="card-body">
                    <p class="card-text text-center hidden-p-sm-down font-weight-bold" style="font-size: larger"> ${element.title} </p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-success">${element.bidMsg} </small>
                        <small class="text-danger">
                                ${element.time}</small>
                    </div>
                </div>
            </div>
        </a>
    </div>`;
    };
    htmlproposal += `</div>`;
    if (i == proposals.length)
        showmorebutton.parentNode.removeChild(showmorebutton);
    return htmlproposal;
}

function myproposalsAlbumHandler()
{
    console.log(this.responseText);
    proposals = JSON.parse(this.responseText);
    album.innerHTML = myproposalsAlbum();
}

function myproposalsAlbum()
{
    console.log(proposals);
    let htmlproposal = `<div class="row">`;
    let max = i + 12;

    for (i; i < max && i < proposals.length; i++)
    {
        let element = proposals[i];
        if (i % 4 === 0 && i !== 0)
        {
            htmlproposal += `</div><div class="row">`;
        }
        htmlproposal += `<div class="col-md-3 proposalItem"  data-id="${element.id}">
        <a href="proposal/${element.id}" class="list-group-item-action">
            <div class="card mb-4 box-shadow">
                <div class="card-body">
                    <p class="card-text text-center hidden-p-sm-down font-weight-bold" style="font-size: larger"> ${element.title} </p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-success">${element.bidMsg} </small>
                        <small class="text-danger">
                                ${element.time}</small>
                    </div>
                </div>
            </div>
        </a>
    </div>`;
    };
    htmlproposal += `</div>`;
    if (i == proposals.length)
        showmorebutton.parentNode.removeChild(showmorebutton);
    return htmlproposal;
}

function allproposalsAlbumHandler()
{
    console.log(this.responseText);
    proposals = JSON.parse(this.responseText);
    album.innerHTML = allproposalsAlbum();
}

function allproposalsAlbum()
{
    console.log(proposals);
    let htmlproposal = `<div class="row">`;
    let max = i + 12;

    for (i; i < max && i < proposals.length; i++)
    {
        let element = proposals[i];
        if (i % 4 === 0 && i !== 0)
        {
            htmlproposal += `</div><div class="row">`;
        }
        htmlproposal += `<div class="col-md-3 proposalItem"  data-id="${element.id}">
        <a href="proposal/${element.id}" class="list-group-item-action">
            <div class="card mb-4 box-shadow">
                <div class="card-body">
                    <p class="card-text text-center hidden-p-sm-down font-weight-bold" style="font-size: larger"> ${element.title} </p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-success">${element.bidMsg} </small>
                        <small class="text-danger">
                                ${element.time}</small>
                    </div>
                </div>
            </div>
        </a>
    </div>`;
    };
    htmlproposal += `</div>`;
    if (i == proposals.length)
        showmorebutton.parentNode.removeChild(showmorebutton);
    return htmlproposal;
}

function proposalsIWonAlbumHandler(){
    console.log(this.responseText);
    proposals = JSON.parse(this.responseText);
    album.innerHTML = proposalsIWonAlbum();
}

function proposalsIWonAlbum(){
    console.log(proposals);
    let htmlproposal = `<div class="row">`;
    let max = i + 12;

    for (i; i < max && i < proposals.length; i++)
    {
        let element = proposals[i];
        if (i % 4 === 0 && i !== 0)
        {
            htmlproposal += `</div><div class="row">`;
        }
        htmlproposal += `<div class="col-md-3 proposalItem"  data-id="${element.id}">
        <a href="proposal/${element.id}" class="list-group-item-action">
            <div class="card mb-4 box-shadow">
                <div class="card-body">
                    <p class="card-text text-center hidden-p-sm-down font-weight-bold" style="font-size: larger"> ${element.title} </p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-success">${element.bidMsg} </small>
                        <small class="text-danger">
                                ${element.time}</small>
                    </div>
                </div>
            </div>
        </a>
    </div>`;
    };
    htmlproposal += `</div>`;
    if (i == proposals.length)
        showmorebutton.parentNode.removeChild(showmorebutton);
    return htmlproposal;
}

function proposalAlbumHandler()
{
    console.log(this.responseText);
    proposals = JSON.parse(this.responseText);
    album.innerHTML = makeAlbum();
}

function makeAlbum()
{
    console.log(proposals);
    let htmlproposal = `<div class="row">`;
    let max = i + 12;

    for (i; i < max && i < proposals.length; i++)
    {
        let element = proposals[i];
        if (i % 4 === 0 && i !== 0)
        {
            htmlproposal += `</div><div class="row">`;
        }
        htmlproposal += `<div class="col-md-3 proposalItem"  data-id="${element.id}">
        <a href="proposal/${element.id}" class="list-group-item-action">
            <div class="card mb-4 box-shadow">
                <div class="col-md-6 img-fluid media-object align-self-center ">

                    <img class="width100" src="../img/${element.image}" alt="book image">
                </div>
                <div class="card-body">
                    <p class="card-text text-center hidden-p-md-down font-weight-bold" style="font-size: larger"> ${element.title} </p>
                    <p class="card-text text-center hidden-p-md-down">By ${element.author} </p>
                    <div class="d-flex justify-content-between align-items-center">
                        ${element.wishlisted}
                        <small class="text-success">${element.bidMsg} </small>
                        <small class="text-danger">
                                ${element.time}</small>
                    </div>
                </div>

            </div>
            </a>
    </div>`;
    };
    htmlproposal += `</div>`;
    if (i == proposals.length)
    {
        if (showmorebutton != null)
            showmorebutton.parentNode.removeChild(showmorebutton);
    }
    return htmlproposal;
}

function makeSearchAlbum(proposals)
{
    console.log(proposals);
    let htmlproposal = `<div class="row">`;
    let i = 0;
    proposals.forEach(element =>
    {
        if (i % 4 === 0 && i !== 0)
        {
            htmlproposal += `</div><div class="row">`;
        }
        htmlproposal += `<div class="col-md-3 proposalItem"  data-id="${element.id}">
        <a href="proposal/${element.id}" class="list-group-item-action">
            <div class="card mb-4 box-shadow">
                <div class="col-md-6 img-fluid media-object align-self-center ">
                    <!--<img class="width100" src="../img/book.png" alt="the orphan stale">-->
                    <img class="width100" src="../img/${element.image}" alt="book image">
                </div>
                <div class="card-body">
                    <p class="card-text text-center hidden-p-md-down font-weight-bold" style="font-size: larger"> ${element.title} </p>
                    <p class="card-text text-center hidden-p-md-down">By ${element.author} </p>
                    <div class="d-flex justify-content-between align-items-center">
                        <i class="fas fa-star btn btn-sm text-primary"></i>
                        <small class="text-success">${element.maxBid}€ </small>
                        <small class="text-danger">
                                &lt; ${element.time}</small>
                    </div>
                </div>
            </div>
        </a>
    </div>`;
        i++;
    });
    htmlproposal += `</div>`;
    return htmlproposal;
}

/**
 * JS for the notifications
 */
let counter = document.querySelector("#counter");

function notificationsClick()
{
    let params = {};
    counter.innerHTML = "";
    ajaxCallGet2('../api/notifications', params, notificationsHandler);
}


function notificationsHandler(response)
{
    let notifications = JSON.parse(JSON.stringify(response));
    let notification_list = document.querySelector("#not_itens");
    let html_notification = `<h6 class="dropdown-header">New Alerts:</h6>
                          <div class="dropdown-divider"></div>`;
    if (notifications.length == 0)
    {
        html_notification += `<a class="dropdown-item">
                                <div class="dropdown-message"><span class="text-left small">No new notifications.</span></div>
                              </a>`;
    }
    else
    {
        notifications.forEach(function(element)
        {
            let time_sent = element.datesent.substring(10, 16);
            html_notification += `<a class="dropdown-item" data-id="${element.id}" href="/proposal/${element.idproposal}">
                              <span class="text text-left">
                                <strong>${element.title}</strong>
                              </span>
                              <span class="small text-right text-muted">${time_sent}</span>
                              <div class="dropdown-message">
                                <span class="text-left small">${element.information}</span>
                              </div>
                            </a>`;
            let params = {
                "notification_id": element.id
            };
            ajaxCallPost('/notifications/{id}', params, 'success');
        });
    }
    notification_list.innerHTML = html_notification;
}

function getNotCounter(response)
{
    let notifications = JSON.parse(JSON.stringify(response));
    if (notifications.length != 0 && notifications instanceof Array)
    {
        counter.innerHTML = notifications.length;
    }
}

setInterval(function()
{
    let params = {};
    ajaxCallGet2('../api/notifications', params, getNotCounter);
}, 1000);

/**
 * JS for the feedback functionalities
 */
let feedback = document.querySelector("#myfeedback");
let like;
let profile_id;


if (window.location.href.includes('profile'))
{
    profile_id = getProfileID();
    let params = {
        "user": profile_id
    };
    ajaxCallGet2('/users/{id}/comments', params, commentsHandler);
}

function commentsHandler(response)
{
    let comments = JSON.parse(JSON.stringify(response));
    if (comments.length == 0)
    {
        feedback.innerHTML = `<a class="list-group-item list-group-item-action text-muted">
                                <div class="container">
                                    <span> No feedback.</span>
                                </div>
                              </a>`;
    }
    else
    {
        let comments_html;
        comments.forEach(function(element)
        {
            if (element.idparent == null)
            {
                comments_html = "";
                let date_sent = element.dateposted.substring(0, 11);
                comments_html += `<a class="list-group-item list-group-item-action text-muted">
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <span onclick="changeurl('/profile/${element.idsender}')" class="btn btn-outline-secondary">${element.username}</span>
                                         </div>`;
                if (element.liked)
                {
                    comments_html += `<div class="col-lg-1  text-left text-dark lead">
                                            <i class="fa fa-thumbs-up btn btn-success" alt="good feedback"></i>
                                        </div>`;
                }
                else if (element.liked == false)
                {
                    comments_html += `<div class="col-lg-1  text-left text-dark lead">
                                            <i class="fa fa-thumbs-down btn btn-danger" alt="bad feedback"></i>
                                        </div>`;
                }
                else
                {
                    comments_html += `<div class="col-lg-1  text-left text-dark lead">
                                            <i class="fa fa-thumbs-o-upbtn btn-info"></i>
                                        </div>`;
                }
                comments_html += `<div class="col-lg-5  text-left text-dark lead">
                                    <p>${element.comment_text}</p>
                              </div>
                              <div class="col-lg-2  text-left text-dark lead">
                                    <p>${date_sent}</p>
                              </div>`;

                if (user_id == profile_id)
                {
                    comments_html += `<div class="col-lg-2  text-left text-dark lead">
                                        <span id="rb${element.id}" onclick = "showTextArea(${element.id})" class="btn btn-secondary">Reply</span>
                                    </div>
                                    <div class="container"  id = "r${element.id}" style = "display:none">
                                    <div class="row">
                                        <div class="col-sm-1 col-md-10">
                                            <div class="panel panel-default">
                                                <div class="panel-body">
                                                        <textarea class="form-control col-md-auto" id = "textArea${element.id}" name="message" placeholder="Type in your reply" rows="3" style="margin-bottom:10px;"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-secondary col-sm-auto col-lg-auto" onclick="sendReply(${element.id},${element.idreceiver})" id="sendreply${element.id}" type="button">Reply</button>
                                    </div>
                                </div>
                             </div>
                        </a>`;
                }

                feedback.innerHTML += comments_html;
            }
            else
            {
                let idx1 = "#r" + element.idparent;
                let idx2 = "#rb" + element.idparent;
                console.log(idx1);
                let commentid = document.querySelector(idx1);
                let rpbtn = document.querySelector(idx2);
                commentid.innerHTML = `<div class="col-lg-10  text-left text-dark border-success lead">
                                            <div class="row">
                                                <div class="col-sm-10 col-md-10">
                                                     <div>
                                                          <div class="panel-body" style="font-size: 0.8em">
                                                            <span>${element.username} replied:</span>
                                                            <span class = "container">${element.comment_text}</span>
                                                           </div>
                                                     </div>
                                                </div>
                                            </div>
                                       </div>`;
                commentid.style.display = "inline-block";
                rpbtn.style.display = "none";
            }
        });


    }
}

function setLike()
{
    like = true;
    console.log(like);
}

function setUnlike()
{
    like = false;
    console.log(like);
}

function postFeedback(senderID)
{
    let feedback = document.querySelector('#left-feedback').value;
    console.log(feedback);
    if (feedback !== null)
    {
        let params = {
            "id_sender": senderID,
            "text": feedback,
            "id_receiver": getProfileID(),
            "liked": like,
            "id_parent": null
        };
        ajaxCallPost('/users/{id}', params, null);
        window.location.reload();
    }
}

function getProfileID()
{
    return window.location.pathname.substring(9, window.location.pathname.length);
}

function changeurl(newUrl)
{
    window.location = newUrl;
}

function showTextArea(id)
{
    let idx1 = "#r" + id.toString();
    let idx2 = "#rb" + id.toString();
    console.log(idx2);
    let textarea = document.querySelector(idx1);
    let replybtn = document.querySelector(idx2);
    textarea.style.display = "inline-block";
    replybtn.style.display = "none";
}

let id_reply = "";

function sendReply(feedback_id, receiver_id)
{
    let taidx = "#textArea" + feedback_id;
    let text = document.querySelector(taidx).value;
    if (text !== null)
    {
        let params = {
            "id_parent": feedback_id,
            "id_sender": receiver_id,
            "id_receiver": receiver_id,
            "text": text,
            "liked": null
        };
        setID("#r" + feedback_id);
        ajaxCallPost('/users/{id}', params, null);
        window.location.reload();
    }
}

function setID(id)
{
    id_reply = id;
}

/**
 *JS for search-related stuff and APIs
 */
if (window.location.pathname === "/search") //use ajax on advanced search form
{
    let searchForm = document.querySelector("#advSearchSubmit");
    searchForm.onsubmit = function(event)
    {
        event.preventDefault();
        let params = "api/search";

        ajaxCallGet(params, "searchHandler");
    }
}

let cats = document.querySelectorAll(".faculty-dropdown");
let navbarSearches = document.querySelectorAll("input[name='faculty']");
let selectedCat = document.querySelector("#catDropDown");

for (let i = 0; i < cats.length; i++)
{
    cats[i].addEventListener("click", function()
    {
        let cat = cats[i].innerHTML;
        selectedCat.innerHTML = cat;
        for (let j = 0; j < navbarSearches.length; j++)
            navbarSearches[j].value = cat;
    });
}

/**
 * JS for bidding-related stuff and APIs
 */
if (window.location.href.includes("proposal/")) {
    let editButton = document.querySelector("#edit-proposal");
    if (editButton != null) {
        editButton.addEventListener("click", function () {
            window.location.href = window.location.href + "/edit";
        });
    }

    let timeLeft = document.querySelector("#timeLeft").innerHTML;
    if (timeLeft !== "Proposal hasn't been approved yet" && timeLeft !== "Proposal has ended!") {
        let elements = timeLeft.split(" ");
        let days, hours, minutes, seconds;

        switch (elements.length) {
            case 4:
                days = parseInt(elements[0].slice(0, -1));
                hours = parseInt(elements[1].slice(0, -1));
                minutes = parseInt(elements[2].slice(0, -1));
                seconds = parseInt(elements[3].slice(0, -1));
                break;
            case 3:
                days = 0;
                hours = parseInt(elements[0].slice(0, -1));
                minutes = parseInt(elements[1].slice(0, -1));
                seconds = parseInt(elements[2].slice(0, -1));
                break;
            case 2:
                days = 0;
                hours = 0;
                minutes = parseInt(elements[0].slice(0, -1));
                seconds = parseInt(elements[1].slice(0, -1));
                break;
            case 1:
                days = 0;
                hours = 0;
                minutes = 0;
                seconds = parseInt(elements[0].slice(0, -1));
                break;
        }

        let timer = new Timer();
        timer.start(
            {
                countdown: true,
                startValues:
                {
                    days: days,
                    hours: hours,
                    minutes: minutes,
                    seconds: seconds
                }
            });
        timer.addEventListener('secondsUpdated', function (e) {
            let newTime = "";
            if (timer.getTimeValues().days > 0)
                newTime += timer.getTimeValues().days + "d ";
            if (timer.getTimeValues().hours > 0)
                newTime += timer.getTimeValues().hours + "h ";
            if (timer.getTimeValues().minutes > 0)
                newTime += timer.getTimeValues().minutes + "m ";
            if (timer.getTimeValues().seconds > 0)
                newTime += timer.getTimeValues().seconds + "s";
            if (newTime == "")
                newTime = "Proposal has ended!";

            document.querySelector("#timeLeft").innerHTML = newTime;

            let bidBox = document.querySelector("#bid-box");
            if (newTime === "Proposal has ended!") {
                bidBox.disabled = true;
                bidBox.innerHTML = "Proposal is unbiddable right now";
            }
        });
    }

    window.setInterval(function () {
        let proposalID = getproposalID();
        let requestURL = "/api/bid/?proposalID=" + proposalID;
        //ajaxCallGet(requestURL, getBidHandler);
        ajaxCallGet2('/proposal',
            {}, null);
    }, 2000);

    let bidBox = document.querySelector("#bid-box");
    if (bidBox != null) {
        bidBox.addEventListener("click", function () {
            let currVal = document.querySelector("#currentBid").value;
            if (currVal == "") {
                let header = document.querySelector("#bidResultHeader");
                let body = document.querySelector("#bidResultBody");
                header.innerHTML = "Bidding value not set";
                body.innerHTML = "You must choose a value to bid";
                body.className = "alert alert-danger";
                $("#bidResult").modal();
                return;
            }
            currVal = parseFloat(currVal);

            let maxVal = document.querySelector("#currentMaxBid").innerHTML;
            maxVal = parseFloat(maxVal);

            if (currVal <= maxVal) {
                let header = document.querySelector("#bidResultHeader");
                let body = document.querySelector("#bidResultBody");
                header.innerHTML = "Insufficient bidding value";
                body.innerHTML = '<i class="fa fa-times"></i>  Your bid cannot be lower or equal to the current bid.';
                body.className = "alert alert-danger";
                $("#bidResult").modal();
                return;
            }

            let proposalID = getproposalID();

            let params = {
                "proposalID": proposalID,
                "value": currVal
            };
            ajaxCallPost("/api/bid", params, postBidHandler);
        });
    }


}

function getproposalID()
{
    let proposalID = window.location.href.split('/').pop();
    if (proposalID.endsWith('#'))
        proposalID = proposalID.susbstring(0, proposalID.length - 1);
    return proposalID;
}

/*function getBidHandler()
{
    let answer = JSON.parse(this.responseText);
    let newVal = answer['max'];
    let currentBidValue = document.querySelector("#currentMaxBid").innerHTML = newVal + "€";
}*/

function postBidHandler(data)
{
    let header = document.querySelector("#bidResultHeader");
    let body = document.querySelector("#bidResultBody");

    let success = data['success'];
    let message = data['message'];
    if (success)
    {

        header.innerHTML = "Successful bid";
        body.innerHTML = '<i class="fa fa-check" aria-hidden="true"></i>  ' + message;
        body.className = "alert alert-success";
    }
    else
    {
        header.innerHTML = "Unsuccessful bid";
        body.innerHTML = '<i class="fa fa-times"></i>  ' + message;
        body.className = "alert alert-danger";
    }
    $("#bidResult").modal();
}

/**
 * JS for the advanced search page
 */
if (window.location.href.includes("search"))
{
    let advSearch = document.querySelector("#advSearch");
    advSearch.addEventListener('submit', function(event)
    {
        event.preventDefault();
        let data = $("form").serialize();
        console.log(data);
        ajaxCallGet("api/search?" + data, advSearchHandler);
    });
}

function advSearchHandler()
{
    let header = document.querySelector("#responseSentence");
    let album = document.querySelector("#proposalsAlbum");

    let answer = JSON.parse(this.responseText);
    console.log(answer);
    if (answer.length == 0)
    {
        header.innerHTML = "No results were found";
        album.innerHTML = "";
        return;
    }

    let sentence = "";
    if (answer.length == 1)
        sentence = "1 result found:";
    else
        sentence = answer.length + " results found";

    header.innerHTML = sentence;
    htmlAlbum = makeSearchAlbum(answer);
    album.innerHTML = htmlAlbum;
}

/**
 * Contact AJAX form validator and sender with notification alert
 */
if (window.location.pathname === "/contact")
{
    $("#contactForm").click(function(event)
    {
        event.preventDefault();
    });

    function submitContactMessage()
    {
        let openAlertF = "<div class='alert alert-danger alert-dismissible mb-4' id='contactAlert'> <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
        let closeAlert = "</div>";

        if ($('#contactForm')[0].checkValidity())
        {
            let spinningCircle = "<i class='fa fas fa-circle-notch fa-spin' style='font-size:24px'></i>";
            let defaultText = "Send Message";
            let openAlertS = "<div class='alert alert-success alert-dismissible mb-4' id='contactAlert' data-dismiss='alert'> <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";

            $("#contactSubmitButton").html(spinningCircle);
            $.ajax(
            {
                type: "POST",
                url: "/contact",
                data: $("#contactForm").serialize(),
                success: function(data)
                {
                    $("#contactAlert").html(openAlertS + data + closeAlert);
                    $("#contactSubmitButton").html(defaultText);
                    $("#contactForm")[0].reset();
                },
                error: function(data)
                {
                    $("#contactSubmitButton").html(defaultText);
                    $("#contactAlert").html(openAlertF + "Something unexpected hapened. Please contact directly at: admin@bookhub.com" + closeAlert);
                }
            });
        }
        else
        {
            $("#contactAlert").html(openAlertF + "Please fill all above fields correctly to send message." + closeAlert);
        }
    }
}

/**
 * JS for moderation actions
 */
function moderatorAction(modAction, proposalId, proposalModId = -1)
{
    $.ajaxSetup(
    {
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax(
    {
        url: "/api/moderator",
        method: 'post',
        data:
        {
            ida: proposalId,
            idm: proposalModId,
            action: modAction
        },

        success: function(result)
        {
            // Fade elements on approve/remove
            if (window.location.pathname === "/moderator")
            {
                if (modAction == "approve_creation" || modAction == "remove_creation")
                {
                    $(`#cr-${proposalId}`).fadeOut();
                }
                else if (modAction == "get_new_description")
                {
                    let description = JSON.parse(result);
                    let action_approve = "moderatorAction('approve_modification'," + proposalId + "," + proposalModId + ")";
                    let action_remove = "moderatorAction('remove_modification'," + proposalId + "," + proposalModId + ")";
                    //put description text in modal
                    $("#bookTitle").text(description.title);
                    $("#oldDescription").text(description.old);
                    $("#newDescription").text(description.new);
                    //change action of modal buttons
                    $("#approveBtn").attr("onclick", action_approve);
                    $("#removeBtn").attr("onclick", action_remove);

                }
                else
                {
                    $(`#mr-${proposalId}`).fadeOut();
                }
            }
            else
            {
                location.reload();
            }
        },
        error: function(data)
        {
            console.log(data);
            alert("Check the log.")
        }
    });
}

/**
 * JS for Administrator actions
 */
function adminAction(adminAction, id_member = -1, username = "")
{
    $.ajaxSetup(
    {
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax(
    {
        url: "/api/admin",
        method: 'post',
        data:
        {
            id_member: id_member,
            username: username,
            action: adminAction
        },

        success: function(result)
        {
            // Fade elements on approve/remove
            
            if (window.location.pathname === "/admin")
            {
                if (adminAction == "remove_profile" || adminAction == "ignore_del_request")
                {
                    $(`#dr-${id_member}`).fadeOut();
                } else if (adminAction == "visit_profile"){
                    window.open('/profile/'+result);
                }
                else {
                    window.alert(result);
                    console.log(result);
                }
            }
            else
            {
                location.reload();
            }
        },
        error: function(data)
        {
            window.alert("Check the log for error details.");
            console.log("data");
        }
    });
}
