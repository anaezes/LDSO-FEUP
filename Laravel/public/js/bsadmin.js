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

let album = document.querySelector('#auctionsAlbum');
let showmorebutton = document.querySelector('#showmorebutton');
let i = 0;
let auctions = [];
if (showmorebutton != null)
{
    showmorebutton.addEventListener('click', function(event)
    {
        switch (window.location.pathname)
        {
            case "/myauctions":
                album.innerHTML += myauctionsAlbum();
                break;
            case "/history":
                album.innerHTML += historyAlbum();
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
    ajaxCallGet("api/search?auctionStatus=approved", auctionAlbumHandler);
}

if (window.location.pathname === "/myauctions")
{
    ajaxCallGet("api/search?auctionsOfUser=true", myauctionsAlbumHandler);
}

if (window.location.pathname === "/auctions_im_in")
{
    ajaxCallGet("api/search?userBidOn=true", auctionAlbumHandler);
}

if (window.location.pathname === "/wishlist")
{
    ajaxCallGet("api/search?wishlistOfUser=true", auctionAlbumHandler);
}

if (window.location.pathname === "/history")
{
    ajaxCallGet("api/search?history=true", historyAlbumHandler);
}

function historyAlbumHandler()
{
    auctions = JSON.parse(this.responseText);
    album.innerHTML = historyAlbum();
}

function historyAlbum()
{
    let htmlAuction = `<div class="row">`;
    let max = i + 12;

    for (i; i < max && i < auctions.length; i++)
    {
        let element = auctions[i];
        if (i % 4 === 0 && i !== 0)
        {
            htmlAuction += `</div><div class="row">`;
        }
        htmlAuction += `<div class="col-md-3 auctionItem"  data-id="${element.id}">
        <a href="auction/${element.id}" class="list-group-item-action">
            <div class="card mb-4 box-shadow">
                <div class="col-md-6 img-fluid media-object align-self-center ">
                    <!--<img class="width100" src="../img/book.png" alt="cover image">-->
                    <img class="width100" src="../img/${element.image}" alt="book image">
                </div>
                <div class="card-body">
                    <p class="card-text text-center hidden-p-md-down font-weight-bold" style="font-size: larger"> ${element.title} </p>
                    <p class="card-text text-center hidden-p-md-down">By ${element.author} </p>
                    <div class="text-center align-items-center">
                        <small class="text-success">Sold for ${element.bidMsg} </small>
                    </div>
                </div>
            </div>
        </a>
    </div>`;
    };

    if (i == auctions.length)
        showmorebutton.parentNode.removeChild(showmorebutton);
    htmlAuction += `</div>`;
    return htmlAuction;
}

function myauctionsAlbumHandler()
{
    console.log(this.responseText);
    auctions = JSON.parse(this.responseText);
    album.innerHTML = myauctionsAlbum();
}

function myauctionsAlbum()
{
    console.log(auctions);
    let htmlAuction = `<div class="row">`;
    let max = i + 12;

    for (i; i < max && i < auctions.length; i++)
    {
        let element = auctions[i];
        if (i % 4 === 0 && i !== 0)
        {
            htmlAuction += `</div><div class="row">`;
        }
        htmlAuction += `<div class="col-md-3 auctionItem"  data-id="${element.id}">
        <a href="auction/${element.id}" class="list-group-item-action">
            <div class="card mb-4 box-shadow">
                <div class="col-md-6 img-fluid media-object align-self-center ">
                    <!--<img class="width100" src="../img/book.png" alt="book cover">-->
                    <img class="width100" src="../img/${element.image}" alt="book image">
                </div>
                <div class="card-body">
                    <p class="card-text text-center hidden-p-md-down font-weight-bold" style="font-size: larger"> ${element.title} </p>
                    <p class="card-text text-center hidden-p-md-down">By ${element.author} </p>
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
    htmlAuction += `</div>`;
    if (i == auctions.length)
        showmorebutton.parentNode.removeChild(showmorebutton);
    return htmlAuction;
}

function auctionAlbumHandler()
{
    console.log(this.responseText);
    auctions = JSON.parse(this.responseText);
    album.innerHTML = makeAlbum();
}

function makeAlbum()
{
    console.log(auctions);
    let htmlAuction = `<div class="row">`;
    let max = i + 12;

    for (i; i < max && i < auctions.length; i++)
    {
        let element = auctions[i];
        if (i % 4 === 0 && i !== 0)
        {
            htmlAuction += `</div><div class="row">`;
        }
        htmlAuction += `<div class="col-md-3 auctionItem"  data-id="${element.id}">
        <a href="auction/${element.id}" class="list-group-item-action">
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
    htmlAuction += `</div>`;
    if (i == auctions.length)
    {
        if (showmorebutton != null)
            showmorebutton.parentNode.removeChild(showmorebutton);
    }
    return htmlAuction;
}

function makeSearchAlbum(auctions)
{
    console.log(auctions);
    let htmlAuction = `<div class="row">`;
    let i = 0;
    auctions.forEach(element =>
    {
        if (i % 4 === 0 && i !== 0)
        {
            htmlAuction += `</div><div class="row">`;
        }
        htmlAuction += `<div class="col-md-3 auctionItem"  data-id="${element.id}">
        <a href="auction/${element.id}" class="list-group-item-action">
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
    htmlAuction += `</div>`;
    return htmlAuction;
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
            html_notification += `<a class="dropdown-item" data-id="${element.id}" href="${element.idauction}">
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
    if (notifications.length != 0)
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

let cats = document.querySelectorAll(".category-dropdown");
let navbarSearches = document.querySelectorAll("input[name='category']");
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
if (window.location.href.includes("auction/"))
{
    let editButton = document.querySelector("#edit-auction");
    if (editButton != null)
    {
        editButton.addEventListener("click", function()
        {
            window.location.href = window.location.href + "/edit";
        });
    }

    let timeLeft = document.querySelector("#timeLeft").innerHTML;
    if (timeLeft !== "Auction hasn't been approved yet" && timeLeft !== "Auction has ended!")
    {
        let elements = timeLeft.split(" ");
        let days, hours, minutes, seconds;

        switch (elements.length)
        {
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
        timer.addEventListener('secondsUpdated', function(e)
        {
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
                newTime = "Auction has ended!";

            document.querySelector("#timeLeft").innerHTML = newTime;

            let bidBox = document.querySelector("#bid-box");
            if (newTime === "Auction has ended!")
            {
                bidBox.disabled = true;
                bidBox.innerHTML = "Auction is unbiddable right now";
            }
        });
    }

    window.setInterval(function()
    {
        let auctionID = getAuctionID();
        let requestURL = "/api/bid/?auctionID=" + auctionID;
        ajaxCallGet(requestURL, getBidHandler);
        ajaxCallGet2('/auction',
        {}, null);
    }, 2000);

    let bidBox = document.querySelector("#bid-box");
    if (bidBox != null)
    {
        bidBox.addEventListener("click", function()
        {
            let currVal = document.querySelector("#currentBid").value;
            if (currVal == "")
            {
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

            if (currVal <= maxVal)
            {
                let header = document.querySelector("#bidResultHeader");
                let body = document.querySelector("#bidResultBody");
                header.innerHTML = "Insufficient bidding value";
                body.innerHTML = '<i class="fa fa-times"></i>  Your bid cannot be lower or equal to the current bid.';
                body.className = "alert alert-danger";
                $("#bidResult").modal();
                return;
            }

            let auctionID = getAuctionID();

            let params = {
                "auctionID": auctionID,
                "value": currVal
            };
            ajaxCallPost("/api/bid", params, postBidHandler);
        });
    }

    let wishlistButton = document.querySelector("#wish-box");
    if (wishlistButton != null)
    {
        let id = getAuctionID();
        wishlistButton.addEventListener("click", function()
        {
            ajaxCallPost("/api/wishlist",
            {
                "id": id
            }, function(data)
            {
                if (data.wishlisted)
                    wishlistButton.innerHTML = "Remove from wishlist";
                else
                    wishlistButton.innerHTML = "Add to wishlist";
            });
        });
    }
}

function getAuctionID()
{
    let auctionID = window.location.href.split('/').pop();
    if (auctionID.endsWith('#'))
        auctionID = auctionID.susbstring(0, auctionID.length - 1);
    return auctionID;
}

function getBidHandler()
{
    let answer = JSON.parse(this.responseText);
    let newVal = answer['max'];
    let currentBidValue = document.querySelector("#currentMaxBid").innerHTML = newVal + "€";
}

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
    let album = document.querySelector("#auctionsAlbum");

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
function moderatorAction(modAction, auctionId, auctionModId = -1)
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
            ida: auctionId,
            idm: auctionModId,
            action: modAction
        },

        success: function(result)
        {
            // Fade elements on approve/remove
            if (window.location.pathname === "/moderator")
            {
                if (modAction == "approve_creation" || modAction == "remove_creation")
                {
                    $(`#cr-${auctionId}`).fadeOut();
                }
                else if (modAction == "get_new_description")
                {
                    let description = JSON.parse(result);
                    let action_approve = "moderatorAction('approve_modification'," + auctionId + "," + auctionModId + ")";
                    let action_remove = "moderatorAction('remove_modification'," + auctionId + "," + auctionModId + ")";
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
                    $(`#mr-${auctionId}`).fadeOut();
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
